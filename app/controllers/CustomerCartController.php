<?php

class CustomerCartController extends Controller
{
    public function index(): void
    {
        $cart = new Cart();
        $medicineModel = new Medicine();

        // No delivery fee on this page: the customer picks home delivery or
        // store pickup at checkout, so the fee is only known from there on.
        $totals = $cart->totals('none');

        $this->render('cart.index', [
            'items'          => $cart->items(),
            'subtotal'       => $totals['subtotal'],
            'tax'            => $totals['tax'],
            'discount'       => $totals['discount'],
            'cartTotal'      => $totals['total'],
            'promoCode'      => $_SESSION['promo_code'] ?? null,
            'hasRxItem'      => $cart->hasRxItem(),
            'savedItems'     => $cart->savedItems(),
            'recentlyViewed' => $medicineModel->recentlyViewed(),
            'promoError'     => $this->flash('promo_error'),
        ]);
    }

    public function saveForLater(): void
    {
        $this->verifyCsrf();
        (new Cart())->saveForLater((int) $this->input('medicine_id'));
        $this->redirect('/cart');
    }

    public function moveToCart(): void
    {
        $this->verifyCsrf();
        (new Cart())->moveToCart((int) $this->input('medicine_id'));
        $this->redirect('/cart');
    }

    public function applyPromo(): void
    {
        $this->verifyCsrf();

        $code = strtoupper(trim((string) $this->input('promo_code', '')));

        if ($code === '') {
            unset($_SESSION['promo_code']);
            $this->redirect('/cart');
            return;
        }

        // Single demo promo for now — HEALTH30 gives 30% off the subtotal.
        // Codes live in Cart::PROMOS so this is trivial to extend later.
        if (isset(Cart::PROMOS[$code])) {
            $_SESSION['promo_code'] = $code;
            $this->flash('success', 'Promo code "' . $code . '" applied — ' . (int) round(Cart::PROMOS[$code] * 100) . '% off your items.');
        } else {
            unset($_SESSION['promo_code']);
            $this->flash('promo_error', 'That promo code isn\'t valid. Try HEALTH30.');
        }

        $this->redirect('/cart');
    }

    public function add(): void
    {
        $this->verifyCsrf();

        $medicineId = (int) $this->input('medicine_id');
        $qty = max(1, (int) $this->input('quantity', 1));

        $medicineModel = new Medicine();
        $medicine = $medicineModel->find($medicineId);

        if (!$medicine) {
            $this->flash('error', 'That medicine could not be found.');
        } elseif ($medicine['stock'] < 1) {
            $this->flash('error', $medicine['name'] . ' is out of stock.');
        } else {
            // Never let the cart hold more than the shop has (BUG-04).
            $qty = min($qty, (int) $medicine['stock']);
            (new Cart())->add($medicineId, $qty);
            $this->flash('success', $medicine['name'] . ' added to cart.');
        }

        if ($this->input('buy_now')) {
            $this->redirect('/checkout');
            return;
        }

        // Go back to whichever page the "Add to cart" form was submitted from,
        // but only if it's a same-host internal path (prevents open redirects).
        header('Location: ' . $this->safeReferer('/cart'));
        exit;
    }

    public function update(): void
    {
        $this->verifyCsrf();

        $medicineId = (int) $this->input('medicine_id');
        $qty = (int) $this->input('quantity', 1);

        $medicine = (new Medicine())->find($medicineId);
        if ($medicine && $qty > $medicine['stock']) {
            $qty = (int) $medicine['stock'];
            $this->flash('error', 'Only ' . $qty . ' of ' . $medicine['name'] . ' left in stock.');
        }

        (new Cart())->update($medicineId, $qty);
        $this->redirect('/cart');
    }

    public function remove(): void
    {
        $this->verifyCsrf();

        $medicineId = (int) $this->input('medicine_id');
        (new Cart())->remove($medicineId);
        $this->flash('success', 'Item removed from cart.');
        $this->redirect('/cart');
    }
}
