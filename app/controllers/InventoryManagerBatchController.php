<?php
/**
 * InventoryManagerBatchController - list of stock batches and the add-batch form.
 *
 * The screens still show sample data written in their views.
 * page_title / active_page / page_css are read by partials/header.php.
 */
class InventoryManagerBatchController extends Controller
{
    protected string $viewBase = 'InventoryManager';

    public function index(): void
    {
        $this->requireRole('Inventory_Manager');

        $this->render('batch/index', [
            'page_title'  => 'Stock & Batches',
            'active_page' => 'batches',
            'page_css'    => 'batches_list.css',
        ]);
    }

    public function create(): void
    {
        $this->requireRole('Inventory_Manager');

        $this->render('batch/create', [
            'page_title'  => 'Stock & Batches',
            'active_page' => 'batches',
            'page_css'    => 'batches_add.css',
        ]);
    }
}
