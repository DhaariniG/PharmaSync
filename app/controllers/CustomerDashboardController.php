<?php

class CustomerDashboardController extends Controller
{
    public function index(): void
    {
        

        $medicineModel = new Medicine();
        $prescriptionModel = new Prescription();
        $orderModel = new Order();

        $this->render('dashboard.index', [
            'user'          => $this->currentUser(),
            'categories'    => Medicine::categories(),
            'featured'      => $medicineModel->featured(4),
            'recentOrders'  => $orderModel->recentForUser($this->currentUser()['id'] ?? 0, 3),
            'prescriptions' => $prescriptionModel->forUser($this->currentUser()['id'] ?? 0),
            'therapyAlert'  => $medicineModel->activeTherapyAlert(),
        ]);
    }
}
