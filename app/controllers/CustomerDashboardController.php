<?php

class CustomerDashboardController extends Controller
{
    protected string $viewBase = 'customer';

    public function index(): void
    {
        $this->requireRole('Customer');

        

        $medicineModel = new CustomerMedicine();
        $prescriptionModel = new Prescription();
        $orderModel = new Order();

        $this->render('dashboard.index', [
            'user'          => $this->currentUser(),
            'categories'    => CustomerMedicine::categories(),
            'featured'      => $medicineModel->featured(4),
            'recentOrders'  => $orderModel->recentForUser($this->currentUser()['id'] ?? 0, 3),
            'prescriptions' => $prescriptionModel->forUser($this->currentUser()['id'] ?? 0),
            'therapyAlert'  => $medicineModel->activeTherapyAlert(),
        ]);
    }
}
