<?php

class PharmacistNotificationController extends Controller
{
    protected string $viewBase = 'pharmacist';

    /** GET /pharmacist/notifications */
    public function index(): void
    {
        $this->requireRole('Pharmacist');

        $this->render('notifications.index', [
            'page_title'      => 'Notifications',
            'active_page'     => 'notifications',
            'page_css'        => 'prescriptionQueue.css',
            'container_class' => 'dashboard-container',
        ]);
    }
}