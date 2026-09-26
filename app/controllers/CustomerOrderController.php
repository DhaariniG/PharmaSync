<?php

class CustomerOrderController extends Controller
{
    protected string $viewBase = 'customer';

    public function confirmation($id): void
    {
        $this->requireRole('Customer');

        $order = (new Order())->find((int) $id);
        if (!$order || $order['user_id'] !== ($this->currentUser()['id'] ?? null)) {
            $this->redirect('/customer/orders');
            return;
        }

        $this->render('order.confirmation', [
            'order'    => $order,
            'popular'  => (new CustomerMedicine())->featured(3),
        ]);
    }

    public function myOrders(): void
    {
        $this->requireRole('Customer');

        $user = $this->currentUser();
        $orders = (new Order())->forUser($user['id'] ?? 0);

        $active = array_values(array_filter($orders, fn($o) => !in_array($o['status'], ['delivered', 'cancelled'], true)));
        $past   = array_values(array_filter($orders, fn($o) => in_array($o['status'], ['delivered', 'cancelled'], true)));

        // Prescriptions are not orders, but the customer should see here
        // that some still need them (or the pharmacist) before they can buy.
        $this->render('order.my-orders', [
            'active'       => $active,
            'past'         => $past,
            'rxInProgress' => (new Prescription())->inProgressFor((int) ($user['id'] ?? 0)),
            'patients'     => new FamilyMember(),
            'userId'       => (int) ($user['id'] ?? 0),
        ]);
    }

    public function show($id): void
    {
        $this->requireRole('Customer');

        $order = (new Order())->find((int) $id);
        if (!$order || $order['user_id'] !== ($this->currentUser()['id'] ?? null)) {
            $this->redirect('/customer/orders');
            return;
        }

        // "Change delivery time" needs the open windows. The order's own
        // window stays selectable, as "your current time".
        $schedule = [];
        $currentSlot = null;
        if (Order::canChange($order) && ($order['delivery_method'] ?? '') === 'delivery') {
            $schedule = (new DeliverySlot())->schedule(null, $this->leadHoursFor($order));
            if (!empty($order['delivery_slot']['date'])) {
                $currentSlot = $order['delivery_slot']['date'] . '|' . $order['delivery_slot']['start'];
            }
        }

        $this->render('order.show', [
            'order'            => $order,
            'deliverySchedule' => $schedule,
            'currentSlot'      => $currentSlot,
        ]);
    }

    /** Cancel an order nobody has started on yet. */
    public function cancel($id): void
    {
        $this->verifyCsrf();
        $this->requireRole('Customer');

        $orders = new Order();
        $order = $orders->find((int) $id);
        if (!$order || $order['user_id'] !== ($this->currentUser()['id'] ?? null)) {
            $this->redirect('/customer/orders');
            return;
        }

        $cancelled = $orders->cancel((int) $id);
        if (!$cancelled && $order['status'] === 'cancelled') {
            $this->flash('error', 'This order is already cancelled.');
        } elseif (!$cancelled) {
            $this->flash('error', 'This order is already being prepared, so it can no longer be cancelled here. Please call the pharmacy.');
        } elseif (Order::paymentStatus($cancelled) === 'refund_due') {
            $this->flash('success', 'Order #' . $cancelled['id'] . ' cancelled. Your card payment of ' . money($cancelled['total']) . ' will be refunded.');
        } else {
            $this->flash('success', 'Order #' . $cancelled['id'] . ' cancelled.');
        }
        $this->redirect('/customer/orders/' . (int) $id);
    }

    /** Move a pending home delivery to another free window. */
    public function reschedule($id): void
    {
        $this->verifyCsrf();
        $this->requireRole('Customer');

        $orders = new Order();
        $order = $orders->find((int) $id);
        if (!$order || $order['user_id'] !== ($this->currentUser()['id'] ?? null)) {
            $this->redirect('/customer/orders');
            return;
        }

        $posted = (string) $this->input('delivery_slot', '');
        $current = !empty($order['delivery_slot']['date'])
            ? $order['delivery_slot']['date'] . '|' . $order['delivery_slot']['start']
            : null;

        if ($posted === $current) {
            $this->redirect('/customer/orders/' . (int) $id);   // nothing changed
            return;
        }

        $slot = (new DeliverySlot())->findBookable($posted, null, $this->leadHoursFor($order));
        if (!$slot) {
            $this->flash('error', 'That delivery time is no longer available. Please choose another.');
        } elseif (!$orders->reschedule((int) $id, $slot)) {
            $this->flash('error', $order['status'] === 'cancelled'
                ? 'This order is cancelled.'
                : 'This order is already being prepared, so its delivery time can no longer be changed here.');
        } else {
            $this->flash('success', 'Delivery time changed to ' . DeliverySlot::describe($slot) . '.');
        }
        $this->redirect('/customer/orders/' . (int) $id);
    }

    /** Prescription orders need the longer lead time. */
    private function leadHoursFor(array $order): int
    {
        return !empty($order['prescription_id']) ? DeliverySlot::LEAD_HOURS_RX : DeliverySlot::LEAD_HOURS;
    }

    // Put a past order's items back in the cart to buy again.
    public function reorder($id): void
    {
        $this->verifyCsrf();
        $this->requireRole('Customer');

        $order = (new Order())->find((int) $id);
        if (!$order || $order['user_id'] !== ($this->currentUser()['id'] ?? null)) {
            $this->redirect('/customer/orders');
            return;
        }

        $medicineModel = new CustomerMedicine();
        $cart = new Cart();
        $userId = $this->currentUser()['id'] ?? null;
        $added = 0;
        $notes = [];

        // Same limits as adding by hand: stock, the per-order limit, and for
        // prescription medicine what an approved prescription still allows.
        // Reordering never re-uses a prescription beyond what it covers.
        foreach ($order['items'] as $item) {
            $medicine = $medicineModel->find((int) $item['medicine_id']);
            if (!$medicine) {
                $notes[] = $item['name'] . ' is no longer sold.';
                continue;
            }
            $result = $cart->addWithinLimit($medicine, (int) $item['quantity'], $userId);
            if ($result['added'] > 0) {
                $added++;
            }
            if ($result['note'] !== null) {
                $notes[] = $result['note'];
            }
        }

        // Carry the order's prescription forward to checkout if it still
        // covers something, so the customer doesn't have to pick it again.
        $carriedRx = false;
        if (!empty($order['prescription_id'])) {
            $prescriptions = new Prescription();
            $prescription = $prescriptions->find((int) $order['prescription_id']);
            if ($prescription && !$prescriptions->isUsedUp($prescription) && $prescriptions->coverage($prescription)) {
                $_SESSION['reorder_prescription_id'] = $prescription['id'];
                $carriedRx = true;
            }
        }

        if ($added > 0) {
            $message = 'Added ' . $added . ' item(s) from this order to your cart.';
            if ($carriedRx) {
                $message .= ' The prescription used for this order will be pre-selected at checkout.';
            }
            if ($notes) {
                $message .= ' Note: ' . implode(' ', $notes);
            }
            $this->flash('success', $message);
        } else {
            $this->flash('error', 'Nothing from this order could be added. ' . implode(' ', $notes));
        }

        $this->redirect('/customer/cart');
    }
}
