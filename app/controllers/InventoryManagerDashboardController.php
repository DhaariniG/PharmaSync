<?php
/**
 * InventoryManagerDashboardController - starter controller for the Inventory Manager module.
 *
 * Keep the two lines in index() as they are: requireRole() is what stops
 * another role from opening your pages, and $viewBase is what points
 * render() at app/views/InventoryManager/.
 *
 * Add more controllers beside this one as InventoryManager<Feature>Controller.php and
 * register them in config/routes/inventoryManager.php.
 */
class InventoryManagerDashboardController extends Controller
{
    protected string $viewBase = 'InventoryManager';

    public function index(): void
    {
        $this->requireRole('Inventory_Manager');

        $this->render('dashboard/index', [
            'user' => $this->currentUser(),
        ]);
    }
}
