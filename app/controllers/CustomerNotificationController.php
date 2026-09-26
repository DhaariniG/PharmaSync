<?php

class CustomerNotificationController extends Controller
{
    protected string $viewBase = 'customer';

    public function index(): void
    {
        $this->requireRole('Customer');

        $type = $this->input('type', 'all');
        $model = new Notification();

        $this->render('notification.index', [
            'notifications' => $model->byType($type),
            'activeType'    => $type,
            'hiddenCount'   => $model->hiddenCount(),
        ]);
    }

    public function markAllRead(): void
    {
        $this->verifyCsrf();
        $this->requireRole('Customer');
        (new Notification())->markAllRead();
        $this->redirect('/customer/notifications');
    }

    public function markAllUnread(): void
    {
        $this->verifyCsrf();
        $this->requireRole('Customer');
        (new Notification())->markAllUnread();
        $this->redirect('/customer/notifications');
    }

    // Flip one notification between read and unread.
    public function toggleRead(): void
    {
        $this->verifyCsrf();
        $this->requireRole('Customer');
        (new Notification())->toggleRead((int) $this->input('id', 0));

        // safeReferer() already includes BASE_URL, so redirect with it directly.
        header('Location: ' . $this->safeReferer('/customer/notifications'));
        exit;
    }

    public function clearAll(): void
    {
        $this->verifyCsrf();
        $this->requireRole('Customer');
        (new Notification())->clearAll();
        $this->redirect('/customer/notifications');
    }
}
