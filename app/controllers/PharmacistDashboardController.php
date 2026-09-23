<?php
/**
 * PharmacistDashboardController - the Pharmacist home screen.
 *
 * The figures on the dashboard are still sample values written in the view
 * (static mock-up), so there is no model call here yet.
 */
class PharmacistDashboardController extends Controller
{
    // render() looks for views inside app/views/pharmacist/
    protected string $viewBase = 'pharmacist';

    /** GET /pharmacist/dashboard */
    public function index(): void
    {
        // Only a signed-in Pharmacist may open this page.
        $this->requireRole('Pharmacist');

        $this->render('dashboard.index', [
            'page_title'  => 'Dashboard',
            'active_page' => 'dashboard',
            'page_css'    => 'dashboard.css',
        ]);
    }
}
