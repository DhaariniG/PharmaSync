<?php
/**
 * HomeController - the site root.
 *
**/
class HomeController extends Controller
{
    public function index(): void
    {
        if (Session::isLoggedIn()) {
            $this->redirectToDashboard();
        }

        require_once APP_PATH . '/views/index.php';
    }
}
