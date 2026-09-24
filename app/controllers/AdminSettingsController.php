<?php

class AdminSettingsController extends Controller
{
    protected string $viewBase = 'admin';

    public function index(): void
    {
        $this->requireRole('Admin');
        $this->render('settings/index', ['user' => $this->currentUser()]);
    }

}
