<?php

class PharmacistMedicineController extends Controller
{
    protected string $viewBase = 'pharmacist';

    /** GET /pharmacist/medicines */
    public function index(): void
    {
        $this->requireRole('Pharmacist');

        $this->render('medicine.index', [
            'page_title'      => 'Medicine Availability',
            'active_page'     => 'medicines',
            'page_css'        => 'medicineAvailability.css',
            'container_class' => 'dashboard-container',
        ]);
    }

    /** GET /pharmacist/medicines/alternatives OR /pharmacist/alternative-review */
    public function alternatives(): void
    {
        $this->requireRole('Pharmacist');

        $this->render('medicine.alternate', [
            'page_title'      => 'Alternative Medicine Review',
            'active_page'     => 'prescriptions',
            'page_css'        => 'alternateMedicine.css',
            'container_class' => 'dashboard-container',
        ]);
    }

    /** Alias method in case routes call alternate() */
    public function alternate(): void
    {
        $this->alternatives();
    }
}