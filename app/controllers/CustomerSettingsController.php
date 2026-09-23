<?php

class CustomerSettingsController extends Controller
{
    protected string $viewBase = 'customer';

    public function index(): void
    {
        $this->requireRole('Customer');

        $this->render('settings.index', [
            'user' => $this->currentUser(),
            'tab'  => $this->input('tab', 'security'),
        ]);
    }
}
