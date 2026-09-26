<?php

class AdminInventoryController extends Controller
{
    protected string $viewBase = 'admin';

    public function index(): void
    {
        $this->requireRole('Admin');
        $this->render('inventory/index', ['user' => $this->currentUser()]);
    }

    public function add(): void
    {
        $this->requireRole('Admin');
        $this->render('inventory/add', ['user' => $this->currentUser()]);
    }

    public function detail(): void
    {
        $this->requireRole('Admin');
        $this->render('inventory/detail', ['user' => $this->currentUser()]);
    }

    public function audit(): void
    {
        $this->requireRole('Admin');
        $this->render('inventory/audit', ['user' => $this->currentUser()]);
    }

}
