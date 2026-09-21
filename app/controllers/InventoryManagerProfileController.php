<?php
/**
 * InventoryManagerProfileController - the Inventory Manager's own profile screen.
 *
 * The screens still show sample data written in their views.
 * page_title / active_page / page_css are read by partials/header.php.
 */
class InventoryManagerProfileController extends Controller
{
    protected string $viewBase = 'InventoryManager';

    public function index(): void
    {
        $this->requireRole('Inventory_Manager');

        $this->render('profile/index', [
            'page_title'  => 'Profile',
            'active_page' => 'profile',
            'page_css'    => 'profile.css',
        ]);
    }
}
