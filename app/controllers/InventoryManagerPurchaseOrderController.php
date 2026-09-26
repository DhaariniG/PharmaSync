<?php
/**
 * InventoryManagerPurchaseOrderController - list of purchase orders and the create-order form.
 *
 * The screens still show sample data written in their views.
 * page_title / active_page / page_css are read by partials/header.php.
 */
class InventoryManagerPurchaseOrderController extends Controller
{
    protected string $viewBase = 'InventoryManager';

    public function index(): void
    {
        $this->requireRole('Inventory_Manager');

        $this->render('purchase_order/index', [
            'page_title'  => 'Purchase Orders',
            'active_page' => 'purchase_orders',
            'page_css'    => 'purchase_orders_list.css',
        ]);
    }

    public function create(): void
    {
        $this->requireRole('Inventory_Manager');

        $this->render('purchase_order/create', [
            'page_title'  => 'Purchase Orders',
            'active_page' => 'purchase_orders',
            'page_css'    => 'purchase_orders_add.css',
        ]);
    }
}
