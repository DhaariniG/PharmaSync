<?php
/**
 * HomeController - the site root.
 *
 * It does not have a page of its own: it sends a signed-in user to their own
 * dashboard, and everyone else to the login page.
 */
class HomeController extends Controller
{
    public function index(): void
    {
        if (Session::isLoggedIn()) {
            $this->redirectToDashboard();
        }

        $this->redirect('/' . AUTH_SLUG . '/login');
    }
}
