<?php

/**
 * CustomerSettings - one customer's choices on the Settings page.
 * Owner: customer module. Table: database/007_add_customer_settings_table.sql
 *
 *   notify_orders          show order notifications
 *   notify_prescriptions   show prescription notifications
 *   notify_refills         show refill reminders
 *   notify_offers          show promotions and health tips
 *   password_changed_at    when the password was last changed from Settings
 *
 * Saved in MySQL when the customer tables are on and 007 has been imported,
 * so choices survive signing out. If the table isn't there yet, it falls
 * back to the session instead of breaking the page.
 */
class CustomerSettings extends Model
{
    use UsesCustomerDatabase;

    /** What a new account starts with: everything useful on, offers off. */
    public const DEFAULTS = [
        'notify_orders'        => 1,
        'notify_prescriptions' => 1,
        'notify_refills'       => 1,
        'notify_offers'        => 0,
        'password_changed_at'  => null,
    ];

    /** The four notification switches, with their Settings page wording. */
    public const NOTIFICATION_TYPES = [
        'notify_orders'        => ['Order updates', 'Packing, dispatch and delivery alerts'],
        'notify_prescriptions' => ['Prescription updates', 'Approvals, prepared items and suggested alternatives'],
        'notify_refills'       => ['Refill reminders', 'When a repeat medicine is running low'],
        'notify_offers'        => ['Offers and health tips', 'Occasional promotions and seasonal advice'],
    ];

    public function get(int $userId): array
    {
        $row = null;
        if ($this->tableReady()) {
            $row = $this->fetchOne(
                'SELECT notify_orders, notify_prescriptions, notify_refills, notify_offers, password_changed_at
                   FROM customer_settings WHERE user_id = :id',
                ['id' => $userId]
            );
        } else {
            $row = $_SESSION['customer_settings'][$userId] ?? null;
        }

        $settings = array_merge(self::DEFAULTS, $row ?? []);
        foreach (array_keys(self::NOTIFICATION_TYPES) as $key) {
            $settings[$key] = (int) $settings[$key];
        }
        return $settings;
    }

    /** Save some settings. Keys not in DEFAULTS are ignored. */
    public function save(int $userId, array $changes): void
    {
        $settings = array_merge($this->get($userId), array_intersect_key($changes, self::DEFAULTS));

        if ($this->tableReady()) {
            $this->exec(
                'INSERT INTO customer_settings
                    (user_id, notify_orders, notify_prescriptions, notify_refills, notify_offers, password_changed_at)
                 VALUES (:id, :o, :p, :r, :f, :pw)
                 ON DUPLICATE KEY UPDATE
                    notify_orders = VALUES(notify_orders), notify_prescriptions = VALUES(notify_prescriptions),
                    notify_refills = VALUES(notify_refills), notify_offers = VALUES(notify_offers),
                    password_changed_at = VALUES(password_changed_at)',
                [
                    'id' => $userId,
                    'o'  => $settings['notify_orders'],
                    'p'  => $settings['notify_prescriptions'],
                    'r'  => $settings['notify_refills'],
                    'f'  => $settings['notify_offers'],
                    'pw' => $settings['password_changed_at'],
                ]
            );
            return;
        }

        $_SESSION['customer_settings'][$userId] = $settings;
    }

    /** True when the settings table can be used (DB on and 007 imported). */
    private function tableReady(): bool
    {
        static $ready = null;
        if ($ready !== null) {
            return $ready;
        }
        if (!$this->hasDb()) {
            return $ready = false;
        }
        try {
            return $ready = $this->fetchValue("SHOW TABLES LIKE 'customer_settings'") !== null;
        } catch (Throwable $e) {
            return $ready = false;
        }
    }
}
