<?php
/**
 * InventoryManagerAlertController - low stock and expiry alert screens.
 *
 * The screens still show sample data written in their views.
 * page_title / active_page / page_css are read by partials/header.php.
 */
class InventoryManagerAlertController extends Controller
{
    protected string $viewBase = 'InventoryManager';

    public function lowStock(): void
    {
        $this->requireRole('Inventory_Manager');

        $this->render('alert/low_stock', [
            'page_title'  => 'Low Stock Alerts',
            'active_page' => 'low_stock',
            'page_css'    => 'low_stock_alerts.css',
        ]);
    }

    public function expiry(): void
    {
        $this->requireRole('Inventory_Manager');

        $this->render('alert/expiry', [
            'page_title'  => 'Expiry Alerts',
            'active_page' => 'expiry_alerts',
            'page_css'    => 'expiry_alerts.css',
        ]);
    }
}
