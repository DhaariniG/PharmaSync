<?php

// Cart lives in the session for now (would become a cart_items table later).
class Cart extends Model
{
    // promo code => discount fraction (0.30 = 30% off)
    public const PROMOS = [
        'HEALTH30' => 0.30,
    ];

    // Discount amount in Rs. for the applied promo code, if any.
    public function discount(float $subtotal): float
    {
        $code = $_SESSION['promo_code'] ?? null;
        if ($code && isset(self::PROMOS[$code])) {
            return round($subtotal * self::PROMOS[$code], 2);
        }
        return 0.0;
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
        $medicineModel = new Medicine();
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

    public function moveToCart(int $medicineId): void
    {
        $saved = &$this->savedStore();
        $saved = array_values(array_diff($saved, [$medicineId]));
        $this->add($medicineId, 1);
    }

    public function savedItems(): array
    {
        $medicineModel = new Medicine();
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
