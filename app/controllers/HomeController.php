<?php
/**
 * HomeController - the site root.
 *
 * Signed-in users go to their own dashboard. Everyone else sees the public
 * landing page, which belongs to the customer module (guests are potential
 * customers). Staff log in from the link in the landing page footer.
 */
class HomeController extends Controller
{
    public function index(): void
    {
        if (Session::isLoggedIn()) {
            $this->redirectToDashboard();
        }

        (new CustomerHomeController())->index();
    }
}
