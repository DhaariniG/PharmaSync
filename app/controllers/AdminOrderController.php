<?php

class AdminOrderController extends Controller
{
    protected string $viewBase = 'admin';

    public function index(): void
    {
        $this->requireRole('Admin');
        $this->render('orders/index', ['user' => $this->currentUser()]);
    }

    public function create(): void
    {
        $this->requireRole('Admin');
        $this->render('orders/create', ['user' => $this->currentUser()]);
    }

    public function detail(): void
    {
        $this->requireRole('Admin');
        $this->render('orders/detail', ['user' => $this->currentUser()]);
    }

    public function urgent(): void
    {
        $this->requireRole('Admin');
        $this->render('orders/urgent', ['user' => $this->currentUser()]);
    }

}
