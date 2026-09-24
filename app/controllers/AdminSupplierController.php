<?php

class AdminSupplierController extends Controller
{
    protected string $viewBase = 'admin';

    public function index(): void
    {
        $this->requireRole('Admin');
        $this->render('suppliers/index', ['user' => $this->currentUser()]);
    }

    public function create(): void
    {
        $this->requireRole('Admin');
        $this->render('suppliers/create', ['user' => $this->currentUser()]);
    }

    public function detail(): void
    {
        $this->requireRole('Admin');
        $this->render('suppliers/detail', ['user' => $this->currentUser()]);
    }

}
