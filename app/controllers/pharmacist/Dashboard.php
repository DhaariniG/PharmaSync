<?php

class Dashboard extends Controller
{
    public function __construct()
    {
        // Require Pharmacist authentication for all actions in this controller
        $this->requireRole('Pharmacist');
    }

    /** URL: index.php?url=pharmacist/dashboard/index (or pharmacist/dashboard) */
    public function index(): void
    {
        $this->view('Pharmacist/dashboard');
    }

    /** URL: index.php?url=pharmacist/dashboard/prescriptions */
    public function prescriptions(): void
    {
        $this->view('Pharmacist/prescriptionQueue');
    }

    /** URL: index.php?url=pharmacist/dashboard/medicines */
    public function medicines(): void
    {
        $this->view('Pharmacist/medicineAvailability');
    }

    /** URL: index.php?url=pharmacist/dashboard/sales */
    public function sales(): void
    {
        $this->view('Pharmacist/physicalSale');
    }

    /** URL: index.php?url=pharmacist/dashboard/history */
    public function history(): void
    {
        $this->view('Pharmacist/prescriptionHistory');
    }

    /** URL: index.php?url=pharmacist/dashboard/notifications */
    public function notifications(): void
    {
        $this->view('Pharmacist/notification');
    }

    /** URL: index.php?url=pharmacist/dashboard/settings */
    public function settings(): void
    {
        $this->view('Pharmacist/settings');
    }
}