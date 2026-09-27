<?php

class CustomerCartController extends Controller
{
    use CustomerGuestAccess;   // browsing works without logging in

    protected string $viewBase = 'customer';

    public function index(): void
    {
        $this->allowGuest();

        $cart = new Cart();
        $medicineModel = new CustomerMedicine();

        // No delivery fee on this page: the customer picks home delivery or
        // store pickup at checkout, so the fee is only known from there on.
        $totals = $cart->totals('none');

        // Each line knows the most it may hold, for the stepper's max.
        $userId = $this->currentUser()['id'] ?? null;
        $items = $cart->items();
        foreach ($items as &$line) {
            $line['limit'] = $cart->limitFor($line['medicine'], $userId)['max'];
        }
        unset($line);

        $promoCode = $_SESSION['promo_code'] ?? null;

        $this->render('cart.index', [
            'promoProblem'   => $promoCode ? $cart->promoProblem($promoCode) : null,
            'items'          => $items,
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
        $this->allowGuest();

        $this->verifyCsrf();
        (new Cart())->saveForLater((int) $this->input('medicine_id'));
        $this->redirect('/customer/cart');
    }

    public function moveToCart(): void
    {
        $this->allowGuest();

        $this->verifyCsrf();

        // Same limits as "Add to cart": stock, per-order limit, prescription.
        $cart = new Cart();
        $medicine = (new CustomerMedicine())->find((int) $this->input('medicine_id'));
        if ($medicine) {
            $limit = $cart->limitFor($medicine, $this->currentUser()['id'] ?? null);
            if ($cart->quantityOf((int) $medicine['id']) < $limit['max']) {
                $cart->moveToCart((int) $medicine['id']);
            } else {
                $this->flash('error', $limit['reason']);
            }
        }
        $this->redirect('/customer/cart');
    }

    public function applyPromo(): void
    {
        $this->allowGuest();

        $this->verifyCsrf();

        $code = strtoupper(trim((string) $this->input('promo_code', '')));

        if ($code === '') {
            unset($_SESSION['promo_code']);
            $this->redirect('/customer/cart');
            return;
        }

        // Rules for each code live in Cart::PROMOS (expiry, minimum spend,
        // once per customer, over-the-counter items only).
        $problem = (new Cart())->promoProblem($code);
        if ($problem === null) {
            $_SESSION['promo_code'] = $code;
            $this->flash('success', 'Promo code "' . $code . '" applied — '
                . (int) round(Cart::PROMOS[$code]['rate'] * 100) . '% off over-the-counter items.');
        } else {
            unset($_SESSION['promo_code']);
            $this->flash('promo_error', $problem);
        }

        $this->redirect('/customer/cart');
    }

    public function add(): void
    {
        $this->allowGuest();

        $this->verifyCsrf();

        $medicineId = (int) $this->input('medicine_id');
        $qty = max(1, (int) $this->input('quantity', 1));

        $medicineModel = new CustomerMedicine();
        $medicine = $medicineModel->find($medicineId);

        if (!$medicine) {
            $this->flash('error', 'That medicine could not be found.');
        } elseif (!empty($medicine['requires_rx'])) {
            // Prescription medicine only reaches the cart from an approved
            // prescription (prescription page), never from a plain "Add".
            $this->flash('error', $medicine['name'] . ' needs a prescription. Upload one, and once our pharmacist approves it you can add it from the prescription page.');
        } else {
            // Never more than stock or the per-order limit - counting what
            // is already in the cart, not just this click (BUG-04).
            $result = (new Cart())->addWithinLimit($medicine, $qty, $this->currentUser()['id'] ?? null);
            if ($result['added'] === 0) {
                $this->flash('error', $result['note']);
            } elseif ($result['note'] !== null) {
                $this->flash('success', 'Added ' . $result['added'] . ' ' . CustomerMedicine::unitName($medicine, $result['added'])
                    . ' of ' . $medicine['name'] . '. ' . $result['note']);
            } else {
                $this->flash('success', $medicine['name'] . ' added to cart.');
            }
        }

        if ($this->input('buy_now')) {
            $this->redirect('/customer/checkout');
            return;
        }

        // Go back to whichever page the "Add to cart" form was submitted from,
        // but only if it's a same-host internal path (prevents open redirects).
        header('Location: ' . $this->safeReferer('/customer/cart'));
        exit;
    }

    public function update(): void
    {
        $this->allowGuest();

        $this->verifyCsrf();

        $medicineId = (int) $this->input('medicine_id');
        $qty = (int) $this->input('quantity', 1);

        $medicine = (new CustomerMedicine())->find($medicineId);
        if (!$medicine) {
            $this->redirect('/customer/cart');
            return;
        }

        if ($qty <= 0) {
            (new Cart())->remove($medicineId);
        } else {
            $result = (new Cart())->setWithinLimit($medicine, $qty, $this->currentUser()['id'] ?? null);
            if ($result['note'] !== null) {
                $this->flash('error', $result['note']);
            }
        }
        $this->redirect('/customer/cart');
    }

    public function remove(): void
    {
        $this->allowGuest();

        $this->verifyCsrf();

        $medicineId = (int) $this->input('medicine_id');
        (new Cart())->remove($medicineId);
        $this->flash('success', 'Item removed from cart.');
        $this->redirect('/customer/cart');
    }
}
