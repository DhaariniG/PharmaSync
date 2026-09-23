<?php
/**
 * PharmacistDashboardController - starter controller for the Pharmacist module.
 *
 * Keep the two lines in index() as they are: requireRole() is what stops
 * another role from opening your pages, and $viewBase is what points
 * render() at app/views/pharmacist/.
 *
 * Add more controllers beside this one as Pharmacist<Feature>Controller.php and
 * register them in config/routes/pharmacist.php.
 */
class PharmacistDashboardController extends Controller
{
    protected string $viewBase = 'pharmacist';

    public function index(): void
    {
        $this->requireRole('Pharmacist');

        $this->render('dashboard/index', [
            'user' => $this->currentUser(),
        ]);
    }
}
