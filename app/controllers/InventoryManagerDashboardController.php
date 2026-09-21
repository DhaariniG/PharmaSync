<?php
/**
 * InventoryManagerDashboardController - the Inventory Manager home screen.
 *
 * The figures on the dashboard are still sample values written in the view.
 * Real numbers from the database can be added here later.
 */
class InventoryManagerDashboardController extends Controller
{
    // render() looks for views inside app/views/InventoryManager/
    protected string $viewBase = 'InventoryManager';

    public function index(): void
    {
        // Only a signed-in Inventory Manager may open this page.
        $this->requireRole('Inventory_Manager');

        // These three values are used by partials/header.php:
        //   page_title  -> browser tab + topbar heading
        //   active_page -> which sidebar link is highlighted
        //   page_css    -> this screen's own stylesheet
        $this->render('dashboard/index', [
            'user'        => $this->currentUser(),
            'page_title'  => 'Dashboard',
            'active_page' => 'dashboard',
            'page_css'    => 'dashboard.css',
        ]);
    }
}
