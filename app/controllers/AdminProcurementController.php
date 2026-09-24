<?php

class AdminProcurementController extends Controller
{
    protected string $viewBase = 'admin';

    public function create(): void
    {
        $this->requireRole('Admin');
        $this->render('procurement/create', ['user' => $this->currentUser()]);
    }

}
