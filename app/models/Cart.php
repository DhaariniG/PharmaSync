<?php

// Cart lives in the session for now (would become a cart_items table later).
class Cart extends Model
{
    /**
     * Promo codes and their rules (business settings - change freely):
     *   rate          discount fraction (0.30 = 30% off)
     *   min_subtotal  over-the-counter spend needed to use it (Rs.)
     *   expires       last day it works
     *   once          one use per customer account
     * A promo only discounts over-the-counter items. Prescription medicines
     * are never discounted, so a promo can't push anyone to buy more of them.
     */
    public const PROMOS = [
        'HEALTH30' => ['rate' => 0.30, 'min_subtotal' => 1000.00, 'expires' => '2026-12-31', 'once' => true],
    ];

    /** Subtotal of the over-the-counter lines only - what a promo applies to. */
    public function otcSubtotal(): float
    {
        $sum = 0.0;
        foreach ($this->items() as $line) {
            if (empty($line['medicine']['requires_rx'])) {
                $sum += $line['subtotal'];
            }
        }
        return $sum;
    }

    /**
     * Why $code can't be used on this cart right now, or null if it can.
     * The once-per-customer rule needs a signed-in customer; guests are
     * checked again at checkout, which always needs a login.
     */
    public function promoProblem(string $code): ?string
    {
        $promo = self::PROMOS[$code] ?? null;
        if ($promo === null) {
            return 'That promo code isn\'t valid.';
        }
        if (date('Y-m-d') > $promo['expires']) {
            return $code . ' expired on ' . date('j M Y', strtotime($promo['expires'])) . '.';
        }
        if ($this->otcSubtotal() < $promo['min_subtotal']) {
            return $code . ' needs at least ' . money($promo['min_subtotal'])
                . ' of over-the-counter items (prescription medicines don\'t count).';
        }
        $userId = $_SESSION['user']['id'] ?? null;
        if ($promo['once'] && $userId) {
            $used = (new Order())->orderUsingPromo((int) $userId, $code);
            if ($used) {
                return $code . ' can be used once per customer, and you used it on order #' . $used['id'] . '.';
            }
        }
        return null;
    }

    /** The applied promo code, only while it is still valid for this cart. */
    public function activePromo(): ?string
    {
        $code = $_SESSION['promo_code'] ?? null;
        return ($code && $this->promoProblem($code) === null) ? $code : null;
    }

    // Discount amount in Rs. for the applied promo code, if it still applies.
    public function discount(float $subtotal): float
    {
        $code = $this->activePromo();
        if ($code === null) {
            return 0.0;
        }
        return round($this->otcSubtotal() * self::PROMOS[$code]['rate'], 2);
    }

    // Every money figure for the current cart, worked out in one place so the
    // cart page, the checkout page and the saved order can never disagree.
    // Tax is charged on the discounted amount.
    //
    // $deliveryMethod: 'delivery' adds the delivery fee. 'pickup' and 'none'
    // do not. The cart page passes 'none' because the customer hasn't chosen
    // between home delivery and store pickup yet — that happens at checkout.
    public function totals(string $deliveryMethod = 'none'): array
    {
        $subtotal = $this->subtotal();
        $discount = $this->discount($subtotal);
        $afterDiscount = max(0, $subtotal - $discount);

        $delivery = ($deliveryMethod === 'delivery' && !$this->isEmpty()) ? DELIVERY_FEE : 0.00;
        $tax = round($afterDiscount * TAX_RATE, 2);

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'delivery' => $delivery,
            'tax'      => $tax,
            'total'    => round($afterDiscount + $delivery + $tax, 2),
        ];
    }

    /* ==================================================================
     * How much of one medicine the cart may hold
     * ================================================================== */

    /** Packs of this medicine in the cart now. */
    public function quantityOf(int $medicineId): int
    {
        return (int) ($this->store()[$medicineId] ?? 0);
    }

    /**
     * The most packs of $medicine this cart may hold, and the reason to show
     * when a request goes over it. The smallest of:
     *   - what is in stock
     *   - the per-order limit (over-the-counter)
     *   - what the customer's approved prescriptions still allow
     *     (prescription medicines; guests have none, so 0)
     */
    public function limitFor(array $medicine, ?int $userId): array
    {
        $unit = CustomerMedicine::unitName($medicine, 2);
        $max = (int) $medicine['stock'];
        $reason = $max < 1
            ? $medicine['name'] . ' is out of stock.'
            : 'Only ' . $max . ' ' . $unit . ' of ' . $medicine['name'] . ' left in stock.';

        if (!empty($medicine['requires_rx'])) {
            $allowed = $userId ? (new Prescription())->allowanceFor($userId, (int) $medicine['id']) : 0;
            if ($allowed < $max) {
                $max = $allowed;
                $reason = $allowed < 1
                    ? $medicine['name'] . ' needs a prescription approved by our pharmacist. Add it from your prescription page.'
                    : 'Your approved prescription covers ' . $allowed . ' ' . $unit . ' of ' . $medicine['name'] . '.';
            }
        } else {
            $perOrder = CustomerMedicine::maxPerOrder($medicine);
            if ($perOrder < $max) {
                $max = $perOrder;
                $reason = 'You can buy up to ' . $perOrder . ' ' . $unit . ' of ' . $medicine['name'] . ' in one order.';
            }
        }

        return ['max' => max(0, $max), 'reason' => $reason];
    }

    /**
     * Set this medicine's quantity, but never above limitFor(). Returns the
     * quantity actually set and the reason when it had to be lowered.
     */
    public function setWithinLimit(array $medicine, int $qty, ?int $userId): array
    {
        $limit = $this->limitFor($medicine, $userId);
        $set = min(max(0, $qty), $limit['max']);
        $this->update((int) $medicine['id'], $set);
        return ['quantity' => $set, 'note' => $set < $qty ? $limit['reason'] : null];
    }

    /**
     * Add $qty on top of what is there, within the limit. Never lowers what
     * is already in the cart (checkout flags that instead). Returns packs added.
     */
    public function addWithinLimit(array $medicine, int $qty, ?int $userId): array
    {
        $limit = $this->limitFor($medicine, $userId);
        $room = max(0, $limit['max'] - $this->quantityOf((int) $medicine['id']));
        $add = min(max(0, $qty), $room);
        if ($add > 0) {
            $this->add((int) $medicine['id'], $add);
        }
        return ['added' => $add, 'note' => $add < $qty ? $limit['reason'] : null];
    }

    /**
     * Every line that breaks a limit right now (stock can change, and a
     * prescription can be used up by another order). Checkout refuses
     * to place the order until the list is empty. [medicine name => reason]
     */
    public function problems(?int $userId): array
    {
        $problems = [];
        foreach ($this->items() as $line) {
            $limit = $this->limitFor($line['medicine'], $userId);
            if ($line['quantity'] > $limit['max']) {
                $problems[$line['medicine']['name']] = $limit['reason'];
            }
        }
        return $problems;
    }

    /** Prescription lines in the cart: [medicine_id => packs]. */
    public function rxLines(): array
    {
        $lines = [];
        foreach ($this->items() as $line) {
            if (!empty($line['medicine']['requires_rx'])) {
                $lines[(int) $line['medicine']['id']] = (int) $line['quantity'];
            }
        }
        return $lines;
    }

    private function &store(): array
    {
        if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
            $_SESSION['cart'] = []; // [medicine_id => quantity]
        }
        return $_SESSION['cart'];
    }

    public function add(int $medicineId, int $qty = 1): void
    {
        $cart = &$this->store();
        $cart[$medicineId] = ($cart[$medicineId] ?? 0) + $qty;
        if ($cart[$medicineId] < 1) {
            unset($cart[$medicineId]);
        }
    }

    public function update(int $medicineId, int $qty): void
    {
        $cart = &$this->store();
        if ($qty <= 0) {
            unset($cart[$medicineId]);
        } else {
            $cart[$medicineId] = $qty;
        }
    }

    public function remove(int $medicineId): void
    {
        $cart = &$this->store();
        unset($cart[$medicineId]);
    }

    public function clear(): void
    {
        $_SESSION['cart'] = [];
        unset($_SESSION['promo_code']);
    }

    // Cart lines with the full medicine and line subtotal attached.
    public function items(): array
    {
        $cart = $this->store();
        $medicineModel = new CustomerMedicine();
        $lines = [];

        foreach ($cart as $medicineId => $qty) {
            $medicine = $medicineModel->find((int) $medicineId);
            if (!$medicine) {
                continue;
            }
            $lines[] = [
                'medicine'  => $medicine,
                'quantity'  => $qty,
                'subtotal'  => $medicine['price'] * $qty,
            ];
        }

        return $lines;
    }

    public function count(): int
    {
        return array_sum($this->store());
    }

    public function subtotal(): float
    {
        return array_sum(array_column($this->items(), 'subtotal'));
    }

    public function hasRxItem(): bool
    {
        foreach ($this->items() as $line) {
            if (!empty($line['medicine']['requires_rx'])) {
                return true;
            }
        }
        return false;
    }

    public function isEmpty(): bool
    {
        return count($this->store()) === 0;
    }

    private function &savedStore(): array
    {
        if (!isset($_SESSION['saved_for_later']) || !is_array($_SESSION['saved_for_later'])) {
            $_SESSION['saved_for_later'] = [];
        }
        return $_SESSION['saved_for_later'];
    }

    public function saveForLater(int $medicineId): void
    {
        $cart = &$this->store();
        unset($cart[$medicineId]);

        $saved = &$this->savedStore();
        if (!in_array($medicineId, $saved, true)) {
            $saved[] = $medicineId;
        }
    }

    /** Out of "saved for later" and back into the cart (the caller checks the limit). */
    public function moveToCart(int $medicineId): void
    {
        $saved = &$this->savedStore();
        $saved = array_values(array_diff($saved, [$medicineId]));
        $this->add($medicineId, 1);
    }

    public function savedItems(): array
    {
        $medicineModel = new CustomerMedicine();
        $items = [];
        foreach ($this->savedStore() as $id) {
            $m = $medicineModel->find((int) $id);
            if ($m) {
                $items[] = $m;
            }
        }
        return $items;
    }
}
