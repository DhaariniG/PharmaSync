<?php
/**
 * InventoryManagerReportController - the reports screen.
 *
 * The screens still show sample data written in their views.
 * page_title / active_page / page_css are read by partials/header.php.
 */
class InventoryManagerReportController extends Controller
{
    protected string $viewBase = 'InventoryManager';

    public function index(): void
    {
        $this->requireRole('Inventory_Manager');

        $this->render('report/index', [
            'page_title'  => 'Reports',
            'active_page' => 'reports',
            'page_css'    => 'reports.css',
        ]);
    }
}
