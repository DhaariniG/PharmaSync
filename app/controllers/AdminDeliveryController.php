<?php

class AdminDeliveryController extends Controller
{
    protected string $viewBase = 'admin';

    public function index(): void
    {
        $this->requireRole('Admin');
        $this->render('deliveries/index', ['user' => $this->currentUser()]);
    }

    public function create(): void
    {
        $this->requireRole('Admin');
        $this->render('deliveries/create', ['user' => $this->currentUser()]);
    }

    public function detail(): void
    {
        $this->requireRole('Admin');
        $this->render('deliveries/detail', ['user' => $this->currentUser()]);
    }

    public function optimize(): void
    {
        $this->requireRole('Admin');
        $this->render('deliveries/optimize', ['user' => $this->currentUser()]);
    }

}
