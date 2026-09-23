<?php
/**
 * CustomerGuestAccess - pages a visitor can use without logging in.
 *
 * Guests may browse the shop: landing page, catalog, search, product pages,
 * alternatives and the cart. Everything personal (checkout, prescriptions,
 * orders, profile, notifications, settings, dashboard) still calls
 * requireRole('Customer'), which sends a guest to the login page and brings
 * them back to that same page afterwards.
 *
 * The cart is kept in the session, and Session::login() keeps session data
 * when it regenerates the id, so a guest's cart is still there after login.
 *
 * Use it in a customer controller:
 *   use CustomerGuestAccess;
 *   ...
 *   $this->allowGuest();   // instead of $this->requireRole('Customer')
 */
trait CustomerGuestAccess
{
    /**
     * Let guests and customers through. A signed-in staff member (pharmacist,
     * admin, ...) is sent to their own dashboard instead of the shop.
     */
    protected function allowGuest(): void
    {
        if (Session::isLoggedIn() && Session::role() !== 'Customer') {
            $this->redirectToDashboard();
        }
    }

    protected function isGuest(): bool
    {
        return !Session::isLoggedIn();
    }
}
