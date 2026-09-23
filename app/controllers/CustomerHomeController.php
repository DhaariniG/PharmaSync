<?php
/**
 * CustomerHomeController - the public landing page.
 *
 * This is what someone sees when they type the site address. It is open to
 * guests. HomeController (the site root, '/') hands guests to index() here;
 * signed-in users go straight to their own dashboard instead.
 * Also reachable at /customer/home.
 */
class CustomerHomeController extends Controller
{
    use CustomerGuestAccess;

    protected string $viewBase = 'customer';

    public function index(): void
    {
        $this->allowGuest();

        $medicineModel = new CustomerMedicine();

        $this->renderBare('home.index', [
            'user'       => Session::user(),
            'categories' => CustomerMedicine::categories(),
            'featured'   => $medicineModel->featured(4),
            'cartCount'  => (new Cart())->count(),
            'flash'      => Session::takeFlash(),
        ]);
    }
}
