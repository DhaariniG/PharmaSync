<?php

class CustomerNotificationController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();

        $type = $this->input('type', 'all');
        $model = new Notification();

        $this->render('notification.index', [
            'notifications' => $model->byType($type),
            'activeType'    => $type,
        ]);
    }

    public function markAllRead(): void
    {
        $this->verifyCsrf();
        $this->requireAuth();
        (new Notification())->markAllRead();
        $this->redirect('/notifications');
    }

    public function markAllUnread(): void
    {
        $this->verifyCsrf();
        $this->requireAuth();
        (new Notification())->markAllUnread();
        $this->redirect('/notifications');
    }

    // Flip one notification between read and unread.
    public function toggleRead(): void
    {
        $this->verifyCsrf();
        $this->requireAuth();
        (new Notification())->toggleRead((int) $this->input('id', 0));

        // safeReferer() already includes BASE_URL, so redirect with it directly.
        header('Location: ' . $this->safeReferer('/notifications'));
        exit;
    }

    public function clearAll(): void
    {
        $this->verifyCsrf();
        $this->requireAuth();
        (new Notification())->clearAll();
        $this->redirect('/notifications');
    }
}
