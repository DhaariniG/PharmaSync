<?php
/**
 * CustomerDashboardController - starter controller for the Customer module.
 *
 * Keep the two lines in index() as they are: requireRole() is what stops
 * another role from opening your pages, and $viewBase is what points
 * render() at app/views/customer/.
 *
 * Add more controllers beside this one as Customer<Feature>Controller.php and
 * register them in config/routes/customer.php.
 */
class CustomerDashboardController extends Controller
{
    protected string $viewBase = 'customer';

    public function index(): void
    {
        $this->requireRole('Customer');

        $this->render('dashboard/index', [
            'user' => $this->currentUser(),
        ]);
    }
}
