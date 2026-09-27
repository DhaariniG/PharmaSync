<?php

/**
 * Checkout. placeOrder() is the last line of defence: whatever the pages
 * showed, it re-checks every rule before an order is saved -
 *   1. stock and per-order limits           (Cart::problems)
 *   2. prescription items covered by ONE of the customer's approved
 *      prescriptions, within what it still allows (Prescription::covering)
 *   3. who the order is for
 *   4. allergy warnings seen and accepted   (CustomerMedicine::allergyMatches)
 *   5. promo code still valid               (Cart::promoProblem)
 *   6. delivery window still free, or a real pickup time
 */
class CustomerCheckoutController extends Controller
{
    protected string $viewBase = 'customer';

    public function index(): void
    {
        $this->requireRole('Customer');

        $cart = new Cart();
        if ($cart->isEmpty()) {
            $this->redirect('/customer/cart');
            return;
        }

        $prescriptionModel = new Prescription();
        $user = $this->currentUser();
        $userId = (int) ($user['id'] ?? 0);
        $totals = $cart->totals('delivery');
        $hasRxItem = $cart->hasRxItem();

        $allRx = $prescriptionModel->forUser($userId);

        // Only prescriptions that cover every prescription item in the cart
        // are offered - anything else would be refused at "Place Order".
        $rxLines = $cart->rxLines();
        $approvedRx = $hasRxItem ? $prescriptionModel->covering($userId, $rxLines) : [];

        // Prescriptions still under review — used to warn (not block) at checkout.
        $pendingRx = array_values(array_filter(
            $allRx,
            fn($p) => in_array($p['status'], ['pending', 'needs_alternative', 'prepared'], true)
        ));

        // Pre-select the prescription the reorder came from, or the only
        // one that fits. Read the reorder hint once, then clear it.
        $carriedRxId = $_SESSION['reorder_prescription_id'] ?? null;
        unset($_SESSION['reorder_prescription_id']);
        $preselectedPrescriptionId = null;
        foreach ($approvedRx as $rx) {
            if ($rx['id'] === $carriedRxId) {
                $preselectedPrescriptionId = $carriedRxId;
            }
        }
        if ($preselectedPrescriptionId === null && count($approvedRx) === 1) {
            $preselectedPrescriptionId = $approvedRx[0]['id'];
        }

        // Home delivery windows. Prescription orders need a longer lead time.
        $leadHours = $hasRxItem ? DeliverySlot::LEAD_HOURS_RX : DeliverySlot::LEAD_HOURS;
        $slotModel = new DeliverySlot();
        $deliverySchedule = $slotModel->schedule(null, $leadHours);

        $familyMembers = (new FamilyMember())->forUser($userId);

        // The applied promo code, if it stopped being valid (cart changed,
        // already used...), so the page can say why the discount went.
        $promoCode = $_SESSION['promo_code'] ?? null;
        $promoProblem = $promoCode ? $cart->promoProblem($promoCode) : null;

        $this->render('checkout.index', [
            'deliverySchedule'           => $deliverySchedule,
            'selectedSlot'               => $slotModel->firstAvailable($deliverySchedule),
            'leadHours'                  => $leadHours,
            'items'                      => $cart->items(),
            'cartProblems'               => $cart->problems($userId),
            'subtotal'                   => $totals['subtotal'],
            'discount'                   => $totals['discount'],
            'tax'                        => $totals['tax'],
            'delivery'                   => $totals['delivery'],
            'orderTotal'                 => $totals['total'],
            'promoProblem'               => $promoProblem,
            'hasRxItem'                  => $hasRxItem,
            'hasOtcItem'                 => count($rxLines) < count($cart->items()),
            'rxShortfall'                => $hasRxItem && empty($approvedRx) ? $this->rxShortfall($userId, $rxLines) : [],
            'addresses'                  => (new Address())->forUser($userId),
            'approvedRx'                 => $approvedRx,
            'pendingRx'                  => $pendingRx,
            'preselectedPrescriptionId'  => $preselectedPrescriptionId,
            'allergyWarnings'            => $this->allergyWarningsByMember($cart, $familyMembers),
            'patients'                   => new FamilyMember(),
            'familyMembers'              => $familyMembers,
            'user'                       => $user,
            'error'                      => $this->flash('error'),
        ]);
    }

    public function placeOrder(): void
    {
        $this->verifyCsrf();
        $this->requireRole('Customer');

        $cart = new Cart();
        if ($cart->isEmpty()) {
            $this->redirect('/customer/cart');
            return;
        }

        $user = $this->currentUser();
        $userId = (int) $user['id'];

        // 1. Stock and per-order limits, checked again now.
        $problems = $cart->problems($userId);
        if ($problems) {
            $this->back('Please fix your cart first: ' . implode(' ', $problems));
            return;
        }

        // 2. Prescription items. The chosen prescription must be the
        //    customer's own, approved, and still allow every prescription
        //    line in the cart (medicine AND quantity). Never trust the id.
        $prescriptionId = null;
        $rx = null;
        if ($cart->hasRxItem()) {
            $chosenId = (int) $this->input('prescription_id', 0);
            foreach ((new Prescription())->covering($userId, $cart->rxLines()) as $candidate) {
                if ($candidate['id'] === $chosenId) {
                    $rx = $candidate;
                }
            }
            if ($rx === null) {
                $this->back('Please choose an approved prescription that covers the prescription items in your cart.');
                return;
            }
            $prescriptionId = $rx['id'];
        }

        // 3. Who the order is for. For a prescription order this is fixed by
        //    the prescription itself — the medicine was prescribed to that
        //    person, so the customer can't point it at someone else. For an
        //    over-the-counter order they choose, and we check the choice is
        //    one of their own family profiles rather than trusting the id.
        $familyMembers = new FamilyMember();
        if ($rx !== null) {
            $patient = $familyMembers->find($userId, (int) ($rx['patient_id'] ?? 0));
        } else {
            $patient = $familyMembers->find($userId, (int) $this->input('patient_id', 0));
            if (!$patient) {
                $this->back('Please choose who this order is for.');
                return;
            }
        }

        // 4. Allergies on that person's profile that an item may trigger.
        //    The customer must tick that they have seen them; the list is
        //    saved on the order for the pharmacist.
        $allergyAlerts = $patient ? $this->allergyWarnings($cart, $patient) : [];
        if ($allergyAlerts && !$this->input('allergy_ack')) {
            $this->back('Please read the allergy warning and tick the box to confirm before placing the order.');
            return;
        }

        // 5. A promo that stopped being valid is dropped. If that changes
        //    the discount the customer was shown, send them back to see the
        //    new total - never charge a different amount without saying so.
        $promoCode = $_SESSION['promo_code'] ?? null;
        $promoError = $promoCode ? $cart->promoProblem($promoCode) : null;
        if ($promoError !== null) {
            unset($_SESSION['promo_code']);
        }
        $discountNow = $cart->totals('none')['discount'];
        $discountShown = (float) $this->input('expected_discount', $discountNow);
        if (abs($discountNow - $discountShown) > 0.005) {
            $this->back('Your promo discount changed' . ($promoError !== null ? ': ' . $promoError : '.') . ' Please check the new total before ordering.');
            return;
        }

        // Each line keeps a copy of the pack size (strip of 10 tablets...),
        // so an old order still says what "quantity 2" meant at the time.
        $items = [];
        foreach ($cart->items() as $line) {
            $items[] = [
                'medicine_id' => $line['medicine']['id'],
                'name'        => $line['medicine']['name'],
                'quantity'    => $line['quantity'],
                'unit_price'  => $line['medicine']['price'],
            ] + CustomerMedicine::packSnapshot($line['medicine']);
        }

        $method = $this->input('delivery_method', 'delivery') === 'pickup' ? 'pickup' : 'delivery';

        // Same calculation the cart and checkout screens showed, so the promo
        // discount carries through to what the customer is actually charged.
        $totals = $cart->totals($method);

        // 6a. Home delivery: a copy of the address (text, not just the id, so
        //     an old order still shows where it went) and a free window.
        $address = null;
        $addressNotes = '';
        $deliverySlot = null;
        if ($method === 'delivery') {
            $chosenAddress = (new Address())->find($userId, (int) $this->input('address_id', 0));
            if (!$chosenAddress) {
                $this->back('Please choose a delivery address.');
                return;
            }
            $address = trim($chosenAddress['line1'] . ', ' . $chosenAddress['city'], ', ');
            $addressNotes = trim((string) $this->input('address_notes', ''));

            // Rebuilt from the schedule, never trusted from the form: it must
            // still be free and far enough away for this kind of order.
            $leadHours = $prescriptionId ? DeliverySlot::LEAD_HOURS_RX : DeliverySlot::LEAD_HOURS;
            $postedSlot = (string) $this->input('delivery_slot', '');
            $slot = (new DeliverySlot())->findBookable($postedSlot, null, $leadHours);
            if (!$slot) {
                $this->back($postedSlot === ''
                    ? 'Please choose a delivery date and time.'
                    : 'That delivery time is no longer available. Please choose another date or time.');
                return;
            }
            $deliverySlot = ['date' => $slot['date'], 'start' => $slot['start'], 'end' => $slot['end']];
        }

        // 6b. Pickup: check the chosen time against the ones we actually
        //     offer and split it into date + time.
        $pickup = null;
        if ($method === 'pickup') {
            $chosen  = (string) $this->input('pickup_slot', '');
            $allowed = array_column(pickup_slots(), 'value');
            if (!in_array($chosen, $allowed, true)) {
                $this->back('Please choose a valid pickup date and time.');
                return;
            }
            [$pdate, $ptime] = explode(' ', $chosen);
            $pickup = ['date' => $pdate, 'time' => $ptime];
        }

        // Only accept a payment method we actually offer: card, or cash on
        // delivery / at the counter. Bank transfer was removed - it needs
        // someone at the pharmacy to check each slip, which doesn't exist
        // yet. Anything else is sent back rather than quietly changed.
        // Card details are checked in the browser, never sent to or stored by us.
        $payment = (string) $this->input('payment_method', '');
        if (!in_array($payment, ['card', 'cod'], true)) {
            $this->back('Please choose how you want to pay: card, or cash.');
            return;
        }

        $order = (new Order())->create([
            'user_id'         => $userId,
            'payment_method'  => $payment,
            'prescription_id' => $prescriptionId,
            'patient_id'      => $patient['id'] ?? null,
            'patient_label'   => $familyMembers->label($userId, $patient['id'] ?? null),
            'delivery_method' => $method,
            'address'         => $address,
            'address_notes'   => $addressNotes,
            'pickup'          => $pickup,
            'delivery_slot'   => $deliverySlot,
            'items'           => $items,
            'promo_code'      => $cart->activePromo(),
            'allergy_alerts'  => $allergyAlerts,
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

        $this->redirect('/customer/order/confirmation/' . $order['id']);
    }

    /* ------------------------------------------------------------------ */

    /** Back to checkout with an error message. */
    private function back(string $message): void
    {
        $this->flash('error', $message);
        $this->redirect('/customer/checkout');
    }

    /** "Amoxicillin 500mg — may affect: Penicillin" for one person. */
    private function allergyWarnings(Cart $cart, array $member): array
    {
        $allergies = array_column(FamilyMember::allergiesOf($member), 'value');
        $warnings = [];
        foreach ($cart->items() as $line) {
            $hits = CustomerMedicine::allergyMatches($line['medicine'], $allergies);
            if ($hits) {
                $warnings[] = $line['medicine']['name'] . ' — listed allergy: ' . implode(', ', $hits);
            }
        }
        return $warnings;
    }

    /** Allergy warnings for every family member: [member_id => [warning, ...]]. */
    private function allergyWarningsByMember(Cart $cart, array $members): array
    {
        $map = [];
        foreach ($members as $member) {
            $warnings = $this->allergyWarnings($cart, $member);
            if ($warnings) {
                $map[(int) $member['id']] = $warnings;
            }
        }
        return $map;
    }

    /**
     * When no single prescription covers the cart: per prescription item,
     * what is in the cart and what the customer's prescriptions allow.
     */
    private function rxShortfall(int $userId, array $rxLines): array
    {
        $medicines = new CustomerMedicine();
        $prescriptions = new Prescription();
        $rows = [];
        foreach ($rxLines as $medicineId => $qty) {
            $m = $medicines->find((int) $medicineId);
            if ($m) {
                $rows[] = [
                    'medicine' => $m,
                    'in_cart'  => $qty,
                    'allowed'  => $prescriptions->allowanceFor($userId, (int) $medicineId),
                ];
            }
        }
        return $rows;
    }
}
