<?php

class AdminReportController extends Controller
{
    protected string $viewBase = 'admin';

    public function index(): void
    {
        $this->requireRole('Admin');
        $this->render('reports/index', ['user' => $this->currentUser()]);
    }

}
