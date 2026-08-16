<?php

class CustomerSettingsController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();

        $this->render('settings.index', [
            'user' => $this->currentUser(),
            'tab'  => $this->input('tab', 'security'),
        ]);
    }
}
