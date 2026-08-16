<?php

class CustomerOrderController extends Controller
{
    public function confirmation($id): void
    {
        $this->requireAuth();

        $order = (new Order())->find((int) $id);
        if (!$order || $order['user_id'] !== ($this->currentUser()['id'] ?? null)) {
            $this->redirect('/orders');
            return;
        }

        $this->render('order.confirmation', [
            'order'    => $order,
            'popular'  => (new Medicine())->featured(3),
        ]);
    }

    public function myOrders(): void
    {
        $this->requireAuth();

        $user = $this->currentUser();
        $orders = (new Order())->forUser($user['id'] ?? 0);

        $active = array_values(array_filter($orders, fn($o) => !in_array($o['status'], ['delivered', 'cancelled'], true)));
        $past   = array_values(array_filter($orders, fn($o) => in_array($o['status'], ['delivered', 'cancelled'], true)));

        $this->render('order.my-orders', [
            'active' => $active,
            'past'   => $past,
        ]);
    }

    public function show($id): void
    {
        $this->requireAuth();

        $order = (new Order())->find((int) $id);
        if (!$order || $order['user_id'] !== ($this->currentUser()['id'] ?? null)) {
            $this->redirect('/orders');
            return;
        }

        $this->render('order.show', ['order' => $order]);
    }

    // Put a past order's items back in the cart to buy again.
    public function reorder($id): void
    {
        $this->verifyCsrf();
        $this->requireAuth();

        $order = (new Order())->find((int) $id);
        if (!$order || $order['user_id'] !== ($this->currentUser()['id'] ?? null)) {
            $this->redirect('/orders');
            return;
        }

        $medicineModel = new Medicine();
        $cart = new Cart();
        $added = 0;
        $skipped = 0;

        foreach ($order['items'] as $item) {
            $medicine = $medicineModel->find((int) $item['medicine_id']);
            if (!$medicine || $medicine['stock'] <= 0) {
                $skipped++;
                continue;
            }
            $cart->add((int) $item['medicine_id'], (int) $item['quantity']);
            $added++;
        }

        // Carry the order's prescription forward to checkout if it's still
        // approved, so the customer doesn't have to pick it again.
        $carriedRx = false;
        if (!empty($order['prescription_id'])) {
            $prescription = (new Prescription())->find((int) $order['prescription_id']);
            if ($prescription && $prescription['status'] === 'approved') {
                $_SESSION['reorder_prescription_id'] = $prescription['id'];
                $carriedRx = true;
            }
        }

        if ($added > 0) {
            $message = $skipped > 0
                ? "Added {$added} item(s) to your cart. {$skipped} item(s) are currently unavailable and were skipped."
                : "Added {$added} item(s) from this order to your cart.";
            if ($carriedRx) {
                $message .= ' The approved prescription used for this order will be pre-selected at checkout.';
            }
            $this->flash('success', $message);
        } else {
            $this->flash('error', 'None of the items from this order are currently available.');
        }

        $this->redirect('/cart');
    }
}
