<?php
/**
 * InventoryManagerStockMovementController - the stock movements history screen.
 *
 * The screens still show sample data written in their views.
 * page_title / active_page / page_css are read by partials/header.php.
 */
class InventoryManagerStockMovementController extends Controller
{
    protected string $viewBase = 'InventoryManager';

    public function index(): void
    {
        $this->requireRole('Inventory_Manager');

        $this->render('stock_movement/index', [
            'page_title'  => 'Stock Movements',
            'active_page' => 'stock_movements',
            'page_css'    => 'stock_movements.css',
        ]);
    }
}
