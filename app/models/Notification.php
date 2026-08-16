<?php

// Notifications live in the session, seeded with sample alerts.
class Notification extends Model
{
    private static function seed(): array
    {
        return [
            ['id' => 1, 'type' => 'orders', 'icon' => 'truck', 'color' => 'primary', 'title' => 'Order Shipped: #9002', 'body' => 'Your order for Omega-3 Fish Oil has been dispatched and is on its way. Estimated arrival: tomorrow, 2:00 PM.', 'time' => '2 mins ago', 'read' => false, 'actions' => [['label' => 'Track Shipment', 'href' => '/orders/9002', 'style' => 'primary'], ['label' => 'View Order', 'href' => '/orders/9002', 'style' => 'outline']]],
            ['id' => 2, 'type' => 'prescriptions', 'icon' => 'triangle-alert', 'color' => 'danger', 'title' => 'Refill Required Soon', 'body' => 'Your prescription for Metformin 500mg has only 3 doses remaining. Refill now to avoid any interruption.', 'time' => '1 hour ago', 'read' => false, 'actions' => [['label' => 'Request Refill', 'href' => '/catalog?category=6', 'style' => 'danger']]],
            ['id' => 3, 'type' => 'health-tips', 'icon' => 'leaf', 'color' => 'primary', 'title' => 'Seasonal Wellness Tip', 'body' => 'Pollen counts are high this week. Learn how to manage seasonal allergies effectively with our pharmacist-approved guide.', 'time' => '4 hours ago', 'read' => true, 'actions' => []],
            ['id' => 4, 'type' => 'promos', 'icon' => 'tag', 'color' => 'banner', 'title' => 'Summer Wellness Sale: 20% OFF', 'body' => 'Stock up on vitamins and sunscreen! Use code SUMMER20 at checkout for an exclusive discount on wellness products.', 'time' => '8 hours ago', 'read' => true, 'actions' => [['label' => 'Shop Now', 'href' => '/catalog?category=3', 'style' => 'light']]],
            ['id' => 5, 'type' => 'prescriptions', 'icon' => 'circle-check', 'color' => 'muted', 'title' => 'Prescription Approved', 'body' => 'Your new prescription has been verified and added to your profile. You can now place an order for delivery.', 'time' => 'Yesterday', 'read' => true, 'actions' => []],
            ['id' => 6, 'type' => 'prescriptions', 'icon' => 'shuffle', 'color' => 'danger', 'title' => 'Alternative Suggested for Prescription #502', 'body' => 'Amoxicillin 500mg is out of stock. Your pharmacist has suggested an approved alternative — review it and approve, or continue waiting for restock.', 'time' => '3 hours ago', 'read' => false, 'actions' => [['label' => 'Review & Approve', 'href' => '/prescription/status/502', 'style' => 'primary']]],
            ['id' => 7, 'type' => 'prescriptions', 'icon' => 'package-open', 'color' => 'primary', 'title' => 'Prescription Order Prepared', 'body' => 'Your prescription order for Metformin 500mg is ready. Confirm it to add the items to your cart.', 'time' => 'Yesterday', 'read' => false, 'actions' => [['label' => 'Review & Confirm', 'href' => '/prescription/status/503', 'style' => 'primary']]],
        ];
    }

    private function &store(): array
    {
        if (!isset($_SESSION['notifications']) || !is_array($_SESSION['notifications'])) {
            $_SESSION['notifications'] = self::seed();
        }
        return $_SESSION['notifications'];
    }

    public function all(): array
    {
        return $this->store();
    }

    public function byType(string $type): array
    {
        if ($type === 'all') {
            return $this->all();
        }
        return array_values(array_filter($this->store(), fn($n) => $n['type'] === $type));
    }

    public function unreadCount(): int
    {
        return count(array_filter($this->store(), fn($n) => !$n['read']));
    }

    public function markAllRead(): void
    {
        $store = &$this->store();
        foreach ($store as &$n) {
            $n['read'] = true;
        }
    }

    public function markAllUnread(): void
    {
        $store = &$this->store();
        foreach ($store as &$n) {
            $n['read'] = false;
        }
    }

    // Flip one notification between read and unread.
    public function toggleRead(int $id): void
    {
        $store = &$this->store();
        foreach ($store as &$n) {
            if ((int) $n['id'] === $id) {
                $n['read'] = !$n['read'];
                return;
            }
        }
    }

    public function clearAll(): void
    {
        $_SESSION['notifications'] = [];
    }
}
