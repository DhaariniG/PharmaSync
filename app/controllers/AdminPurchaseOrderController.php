<?php

class AdminPurchaseOrderController extends Controller
{
    protected string $viewBase = 'admin';

    public function index(): void
    {
        $this->requireRole('Admin');
        $this->render('purchase-orders/index', ['user' => $this->currentUser()]);
    }

}
