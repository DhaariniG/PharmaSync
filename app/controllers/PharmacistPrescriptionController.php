<?php
/**
 * PharmacistPrescriptionController - the prescription queue.
 *
 * review() is a static mock-up page (hard-coded demo data in the view).
 */
class PharmacistPrescriptionController extends Controller
{
    protected string $viewBase = 'pharmacist';

    /** GET /pharmacist/prescriptions */
   public function index(): void
{
    $this->requireRole('Pharmacist');

    $this->render('prescription.queue', [
        'queue'           => (new PharmacistPrescription())->getPendingQueue(),
        'page_title'      => 'Prescription Queue',
        'active_page'     => 'prescriptions',
        'page_css'        => 'prescriptionQueue.css', // <--- Matches Queue CSS
        'container_class' => 'dashboard-container',
    ]);
}

public function review(): void
{
    $this->requireRole('Pharmacist');

    $this->render('prescription.review', [
        'prescriptionId'  => $_GET['id'] ?? null,
        'page_title'      => 'Review Prescription',
        'active_page'     => 'prescriptions',
        'page_css'        => 'prescriptionReview.css', // <--- Matches Review CSS
        'container_class' => 'dashboard-container',
    ]);
}
}