<?php
/** Authenticated routes for the Delivery Partner UI prototype. */
class DeliveryPartnerPageController extends Controller
{
    protected string $viewBase = 'deliveryPartner';

    public function show(string $page): void
    {
        $this->requireRole('Delivery_Partner');
        $allowed = ['support', 'profile', 'new-delivery', 'privacy', 'terms', 'earnings-report', 'settings', 'withdraw', 'payment-history', 'order-detail', 'audit-log', 'earnings', 'delivery-history', 'edit-profile', 'upload-document', 'add-payout-method', 'deliveries'];
        if (!in_array($page, $allowed, true)) {
            http_response_code(404);
            $this->renderShared('errors/404');
            return;
        }
        $this->render($page . '/index', ['user' => $this->currentUser()]);
    }
}
