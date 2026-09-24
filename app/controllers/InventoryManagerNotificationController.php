<?php
/**
 * InventoryManagerNotificationController - static sample notifications.
 *
 * Same pattern as the Customer and Pharmacist modules: the bell in the
 * topbar links to a dedicated page rather than opening a dropdown. Data
 * is static for now - each row is what a real low-stock, expiry or
 * purchase-order alert would say once this list reads from the database.
 */
class InventoryManagerNotificationController extends Controller
{
    protected string $viewBase = 'InventoryManager';

    /** GET /InventoryManager/notifications */
    public function index(): void
    {
        $this->requireRole('Inventory_Manager');

        $this->render('notifications/index', [
            'notifications' => self::sampleNotifications(),
            'page_title'    => 'Notifications',
            'active_page'   => 'notifications',
            'page_css'      => 'notifications.css',
        ]);
    }

    /** The topbar badge count must match this list, so both read it from here. */
    public static function sampleNotifications(): array
    {
        return [
            [
                'icon'        => 'package-open',
                'type'        => 'low-stock',
                'title'       => 'Low Stock Alert',
                'description' => 'Amoxicillin 500mg is below its reorder level.',
                'time'        => '15 mins ago',
                'link'        => '/InventoryManager/low-stock',
            ],
            [
                'icon'        => 'package-open',
                'type'        => 'low-stock',
                'title'       => 'Low Stock Alert',
                'description' => 'Cetirizine 10mg is below its reorder level.',
                'time'        => '1 hour ago',
                'link'        => '/InventoryManager/low-stock',
            ],
            [
                'icon'        => 'calendar',
                'type'        => 'expiry',
                'title'       => 'Expiry Alert',
                'description' => 'Metformin 500mg batch expires in 10 days.',
                'time'        => '2 hours ago',
                'link'        => '/InventoryManager/expiry-alerts',
            ],
            [
                'icon'        => 'calendar',
                'type'        => 'expiry',
                'title'       => 'Expiry Alert',
                'description' => 'Amoxicillin 500mg batch expires in 20 days.',
                'time'        => '3 hours ago',
                'link'        => '/InventoryManager/expiry-alerts',
            ],
            [
                'icon'        => 'circle-check',
                'type'        => 'purchase-order',
                'title'       => 'Purchase Order Approved',
                'description' => 'Your purchase order has been approved and is ready for delivery.',
                'time'        => 'Yesterday',
                'link'        => '/InventoryManager/purchase-orders',
            ],
        ];
    }
}
