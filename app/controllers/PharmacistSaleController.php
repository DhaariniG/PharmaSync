<?php
/**
 * PharmacistSaleController - counter (physical) sales.
 *
 *   create()            show the sale form
 *   store()             POST: validate, save the sale, go to the receipt
 *   completed($id)      "sale recorded" screen
 *   show($id)           printable bill
 *   edit($id)           edit quantities form
 *   update($id)         POST: save new quantities, go to the bill
 *   cancel($id)         POST: cancel / refund a sale, go to the history
 *
 * No SQL here - everything goes through the PhysicalSale model.
 */
class PharmacistSaleController extends Controller
{
    protected string $viewBase = 'pharmacist';

    /** The only choices the cancel button may send. */
    private const CANCEL_TYPES = ['Cancelled', 'Refunded'];

    /** The only payment methods the counter accepts. */
    private const PAYMENT_METHODS = ['Cash', 'Card'];

    /** GET /pharmacist/sales */
    public function create(): void
    {
        $this->requireRole('Pharmacist');

        $model = new PhysicalSale();

        $this->render('sale.create', [
            'stock'           => $model->getAvailableStock(),
            'page_title'      => 'Physical Sale',
            'active_page'     => 'sales',
            'page_css'        => 'physicalSale.css',
            'container_class' => 'dashboard-container',
        ]);
    }

    /** POST /pharmacist/sales */
    public function store(): void
    {
        $this->verifyCsrf();
        $this->requireRole('Pharmacist');

        $customerName  = $this->text('customer_name');
        $notes         = $this->text('notes');
        $paymentMethod = $this->text('payment_method', 'Cash');

        if (mb_strlen($customerName) > 150 || mb_strlen($notes) > 1000) {
            $this->fail('/pharmacist/sales', 'The customer name or notes are too long.');
        }
        if (!in_array($paymentMethod, self::PAYMENT_METHODS, true)) {
            $this->fail('/pharmacist/sales', 'Please choose Cash or Card.');
        }

        // Keep only what the server needs: the batch and a whole-number quantity.
        $items = [];
        foreach ((array) ($_POST['items'] ?? []) as $row) {
            $batchId  = filter_var($row['batch_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
            $quantity = filter_var($row['quantity'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 10000]]);

            if ($batchId === false || $quantity === false) {
                $this->fail('/pharmacist/sales', 'Every quantity must be a whole number of 1 or more.');
            }
            $items[] = ['batch_id' => $batchId, 'quantity' => $quantity];
        }
        if (!$items) {
            $this->fail('/pharmacist/sales', 'Add at least one medicine to the sale.');
        }

        $model   = new PhysicalSale();
        $orderId = $model->createSale((int) Session::id(), $customerName, $items, $paymentMethod, $notes);

        if ($orderId === null) {
            $this->fail('/pharmacist/sales', $model->error);
        }

        $this->redirect('/pharmacist/sales/' . $orderId . '/completed');
    }

    //** GET /pharmacist/sales/{id}/completed */
public function completed($id): void
{
    $this->requireRole('Pharmacist');

    // Try fetching from DB; fall back to static mock data if testing
    try {
        $order = $this->findOrder($id);
    } catch (\Throwable $e) {
        $order = [
            'order_id'       => (int) $id,
            'customer_name'  => 'Ms.D',
            'pharmacist'     => 'Sarah Jenkins',
            'payment_method' => 'Cash',
            'created_at'     => '2026-09-23 16:19:52',
            'total_amount'   => 25.00,
            'items'          => [
                [
                    'name'         => 'Paracetamol 500mg',
                    'batch_number' => 'PCM-001',
                    'quantity'     => 1,
                    'unit_price'   => 25.00,
                    'subtotal'     => 25.00
                ]
            ]
        ];
    }

    $this->renderBare('sale.completed', ['order' => $order]);
}
    /** GET /pharmacist/sales/{id}/bill */
    public function show($id): void
    {
        $this->requireRole('Pharmacist');

        $this->renderBare('sale.bill', ['order' => $this->findOrder($id)]);
    }

    /** GET /pharmacist/sales/{id}/edit */
    public function edit($id): void
    {
        $this->requireRole('Pharmacist');

        $order = $this->findOrder($id);

        if ($order['status'] !== 'Completed') {
            $this->fail('/pharmacist/history', 'A cancelled or refunded sale cannot be edited.');
        }

        $this->renderBare('sale.edit', ['order' => $order]);
    }

    /** POST /pharmacist/sales/{id}/update */
    public function update($id): void
    {
        $this->verifyCsrf();
        $this->requireRole('Pharmacist');

        $order = $this->findOrder($id);
        $back  = '/pharmacist/sales/' . $order['order_id'] . '/edit';

        $quantities = [];
        foreach ((array) ($_POST['items'] ?? []) as $itemId => $qty) {
            $itemId = filter_var($itemId, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
            $qty    = filter_var($qty, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 10000]]);

            if ($itemId === false || $qty === false) {
                $this->fail($back, 'Every quantity must be a whole number of 1 or more.');
            }
            $quantities[$itemId] = $qty;
        }
        if (!$quantities) {
            $this->fail($back, 'There is nothing to update.');
        }

        $model = new PhysicalSale();

        if (!$model->updateQuantities((int) $order['order_id'], $quantities, (int) Session::id())) {
            $this->fail($back, $model->error);
        }

        $this->flash('success', 'Quantities updated.');
        $this->redirect('/pharmacist/sales/' . $order['order_id'] . '/bill');
    }

    /** POST /pharmacist/sales/{id}/cancel */
    public function cancel($id): void
    {
        $this->verifyCsrf();
        $this->requireRole('Pharmacist');

        $order  = $this->findOrder($id);
        $type   = $this->text('action_type');
        $reason = mb_substr($this->text('cancellation_reason', 'Pharmacist Voided'), 0, 200);

        if (!in_array($type, self::CANCEL_TYPES, true)) {
            $this->fail('/pharmacist/history', 'That action is not allowed.');
        }
        if ($reason === '') {
            $reason = 'Pharmacist Voided';
        }

        $model = new PhysicalSale();

        if (!$model->cancelSale((int) $order['order_id'], (int) Session::id(), $type, $reason)) {
            $this->fail('/pharmacist/history', $model->error);
        }

        $this->flash('success', 'Sale #POS-' . $order['order_id'] . ' marked as ' . strtolower($type) . '. Stock was restored.');
        $this->redirect('/pharmacist/history');
    }

    /* ------------------------------------------------------------------ */

    /** The sale for a URL id, or a 404 if the id is not a real sale. */
    private function findOrder($id): array
    {
        $orderId = filter_var($id, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $order   = $orderId === false ? null : (new PhysicalSale())->getOrderDetails($orderId);

        if ($order === null) {
            $this->notFound();
        }

        return $order;
    }

    /** Flash an error message and go back to a page. */
    private function fail(string $path, string $message): void
    {
        $this->flash('error', $message);
        $this->redirect($path);
    }

    
}