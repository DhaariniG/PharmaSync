<?php

class CustomerCheckoutController extends Controller
{
    public function index(): void
    {
        $this->requireAuth('Please sign in to complete your order.');

        $cart = new Cart();
        if ($cart->isEmpty()) {
            $this->redirect('/cart');
            return;
        }

        $prescriptionModel = new Prescription();
        $user = $this->currentUser();
        $totals = $cart->totals('delivery');

        $allRx = $prescriptionModel->forUser($user['id'] ?? 0);

        $approvedRx = array_values(array_filter(
            $allRx,
            fn($p) => $p['status'] === 'approved'
        ));

        // Prescriptions still under review — used to warn (not block) at checkout.
        $pendingRx = array_values(array_filter(
            $allRx,
            fn($p) => in_array($p['status'], ['pending', 'needs_alternative', 'prepared'], true)
        ));

        // If we came from "Reorder", pre-select the prescription that order
        // used. Read it once, then clear it.
        $carriedRxId = $_SESSION['reorder_prescription_id'] ?? null;
        unset($_SESSION['reorder_prescription_id']);
        $preselectedPrescriptionId = null;
        if ($carriedRxId) {
            foreach ($approvedRx as $rx) {
                if ($rx['id'] === $carriedRxId) {
                    $preselectedPrescriptionId = $carriedRxId;
                    break;
                }
            }
        }

        $this->render('checkout.index', [
            'items'                      => $cart->items(),
            'subtotal'                   => $totals['subtotal'],
            'discount'                   => $totals['discount'],
            'tax'                        => $totals['tax'],
            'delivery'                   => $totals['delivery'],
            'orderTotal'                 => $totals['total'],
            'hasRxItem'                  => $cart->hasRxItem(),
            'addresses'                  => (new Address())->forUser($user['id'] ?? 0),
            'approvedRx'                 => $approvedRx,
            'pendingRx'                  => $pendingRx,
            'preselectedPrescriptionId'  => $preselectedPrescriptionId,
            'patients'                   => new FamilyMember(),
            'familyMembers'              => (new FamilyMember())->forUser($user['id'] ?? 0),
            'user'                       => $user,
            'error'                      => $this->flash('error'),
        ]);
    }

    public function placeOrder(): void
    {
        $this->verifyCsrf();
        $this->requireAuth('Please sign in to complete your order.');

        $cart = new Cart();
        if ($cart->isEmpty()) {
            $this->redirect('/cart');
            return;
        }

        $user = $this->currentUser();

        // A prescription may only be used by the account that uploaded it, and
        // only once a pharmacist has approved it. Never trust the posted id:
        // look it up and check the owner and the status.
        $prescriptionId = null;
        if ($cart->hasRxItem()) {
            $rx = (new Prescription())->find((int) $this->input('prescription_id', 0));

            if (!$rx || $rx['user_id'] !== $user['id']) {
                $this->flash('error', 'Please select one of your own approved prescriptions.');
                $this->redirect('/checkout');
                return;
            }
            if ($rx['status'] !== 'approved') {
                $this->flash('error', 'That prescription has not been approved by a pharmacist yet.');
                $this->redirect('/checkout');
                return;
            }
            $prescriptionId = $rx['id'];
        }

        // Who the order is for. For a prescription order this is fixed by the
        // prescription itself — the medicine was prescribed to that person, so
        // the customer can't point it at someone else. For an over-the-counter
        // order they choose, and we check the choice is one of their own
        // family profiles rather than trusting the posted id.
        $familyMembers = new FamilyMember();
        $patient = null;

        if ($prescriptionId !== null) {
            $patient = $familyMembers->find($user['id'], (int) ($rx['patient_id'] ?? 0));
        } else {
            $patient = $familyMembers->find($user['id'], (int) $this->input('patient_id', 0));
            if (!$patient) {
                $this->flash('error', 'Please choose who this order is for.');
                $this->redirect('/checkout');
                return;
            }
        }

        $items = [];
        foreach ($cart->items() as $line) {
            $items[] = [
                'medicine_id' => $line['medicine']['id'],
                'name'        => $line['medicine']['name'],
                'quantity'    => $line['quantity'],
                'unit_price'  => $line['medicine']['price'],
            ];
        }

        $method = $this->input('delivery_method', 'delivery') === 'pickup' ? 'pickup' : 'delivery';

        // Same calculation the cart and checkout screens showed, so the promo
        // discount carries through to what the customer is actually charged.
        $totals = $cart->totals($method);

        // For a delivery, keep a copy of the address the customer picked. We
        // store the text rather than just the id, so an old order still shows
        // where it went even if that address is edited or removed later.
        $address = null;
        $addressNotes = '';
        if ($method === 'delivery') {
            $chosenAddress = (new Address())->find($user['id'], (int) $this->input('address_id', 0));
            if (!$chosenAddress) {
                $this->flash('error', 'Please choose a delivery address.');
                $this->redirect('/checkout');
                return;
            }
            $address = trim($chosenAddress['line1'] . ', ' . $chosenAddress['city'], ', ');
            $addressNotes = trim((string) $this->input('address_notes', ''));
        }

        // For pickup, check the chosen slot against the ones we actually offer
        // (don't trust the posted value) and split it into date + time.
        $pickup = null;
        if ($method === 'pickup') {
            $chosen  = (string) $this->input('pickup_slot', '');
            $allowed = array_column(pickup_slots(), 'value');
            if (!in_array($chosen, $allowed, true)) {
                $this->flash('error', 'Please choose a valid pickup date and time.');
                $this->redirect('/checkout');
                return;
            }
            [$pdate, $ptime] = explode(' ', $chosen);
            $pickup = ['date' => $pdate, 'time' => $ptime];
        }

        // Only accept a payment method we actually offer.
        $payment = (string) $this->input('payment_method', 'cod');
        if (!in_array($payment, ['card', 'bank_transfer', 'cod'], true)) {
            $payment = 'cod';
        }

        $order = (new Order())->create([
            'user_id'         => $user['id'],
            'payment_method'  => $payment,
            'prescription_id' => $prescriptionId,
            'patient_id'      => $patient['id'] ?? null,
            'patient_label'   => $familyMembers->label($user['id'], $patient['id'] ?? null),
            'delivery_method' => $method,
            'address'         => $address,
            'address_notes'   => $addressNotes,
            'pickup'          => $pickup,
            'items'           => $items,
            'subtotal'        => $totals['subtotal'],
            'discount'        => $totals['discount'],
            'delivery_fee'    => $totals['delivery'],
            'tax'             => $totals['tax'],
            'total'           => $totals['total'],
        ]);

        $cart->clear();

        // Best-effort confirmation email — checkout still succeeds if mail
        // isn't configured yet (see app/core/Mailer.php).
        Mailer::sendOrderConfirmation($user, $order);

        $this->redirect('/order/confirmation/' . $order['id']);
    }
}
