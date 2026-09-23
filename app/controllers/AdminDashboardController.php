<?php
/**
 * AdminDashboardController - starter controller for the Admin module.
 *
 * Keep the two lines in index() as they are: requireRole() is what stops
 * another role from opening your pages, and $viewBase is what points
 * render() at app/views/admin/.
 *
 * Add more controllers beside this one as Admin<Feature>Controller.php and
 * register them in config/routes/admin.php.
 */
class AdminDashboardController extends Controller
{
    protected string $viewBase = 'admin';

    public function index(): void
    {
        $this->requireRole('Admin');

        $this->render('dashboard/index', [
            'user' => $this->currentUser(),
        ]);
    }
}
