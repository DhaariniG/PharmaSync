<?php
/**
 * PharmacistOrderController - order screens and confirmation mock-ups.
 */
class PharmacistOrderController extends Controller
{
    protected string $viewBase = 'pharmacist';

    /** GET /pharmacist/orders/confirmation */
    public function confirmation(): void
    {
        $this->requireRole('Pharmacist');

        $this->renderBare('order.confirmation', [
            'page_title'      => 'Order Confirmation',
            'active_page'     => 'prescriptions',
            'page_css'        => 'orderConfirmation.css',
            'container_class' => 'dashboard-container',
        ]);
    }

    /** GET /pharmacist/orders/details */
    public function details(): void
    {
        $this->requireRole('Pharmacist');

        $this->render('order.details', [
            'page_title'  => 'Order Details',
            'active_page' => 'prescriptions',
            'page_css'    => 'orderDetails.css',
        ]);
    }

    /** GET /pharmacist/orders/process (draws its own top bar) */
    public function process(): void
    {
        $this->requireRole('Pharmacist');

        $this->render('order.process', [
            'page_title'  => 'Process Order',
            'active_page' => 'prescriptions',
            'page_css'    => 'physicalSale.css',
            'show_topbar' => false,
        ]);
    }
}