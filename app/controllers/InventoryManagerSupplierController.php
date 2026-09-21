<?php
/**
 * InventoryManagerSupplierController - list of suppliers and the add-supplier form.
 *
 * The screens still show sample data written in their views.
 * page_title / active_page / page_css are read by partials/header.php.
 */
class InventoryManagerSupplierController extends Controller
{
    protected string $viewBase = 'InventoryManager';

    public function index(): void
    {
        $this->requireRole('Inventory_Manager');

        $this->render('supplier/index', [
            'page_title'  => 'Suppliers',
            'active_page' => 'suppliers',
            'page_css'    => 'suppliers_list.css',
        ]);
    }

    public function create(): void
    {
        $this->requireRole('Inventory_Manager');

        $this->render('supplier/create', [
            'page_title'  => 'Suppliers',
            'active_page' => 'suppliers',
            'page_css'    => 'suppliers_add.css',
        ]);
    }
}
