<?php
/**
 * DeliveryPartnerDashboardController - starter controller for the Delivery Partner module.
 *
 * Keep the two lines in index() as they are: requireRole() is what stops
 * another role from opening your pages, and $viewBase is what points
 * render() at app/views/deliveryPartner/.
 *
 * Add more controllers beside this one as DeliveryPartner<Feature>Controller.php and
 * register them in config/routes/deliveryPartner.php.
 */
class DeliveryPartnerDashboardController extends Controller
{
    protected string $viewBase = 'deliveryPartner';

    public function index(): void
    {
        $this->requireRole('Delivery_Partner');

        $this->render('dashboard/index', [
            'user' => $this->currentUser(),
        ]);
    }
}
