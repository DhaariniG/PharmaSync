<?php

/**
 * Saved delivery addresses.
 *
 * Full CRUD: create(), forUser()/find(), update(), delete(), plus
 * setDefault() for the one address the checkout page pre-selects.
 *
 * Session-backed while DB_ENABLED is false; the same methods run SQL against
 * the `addresses` table (database/003_add_customer_profile_tables.sql) once
 * it is true.
 *
 * Deleting an address does NOT touch past orders: an order stores the
 * delivery address as text at the moment it was placed, so order history
 * stays correct even after the address is removed.
 */
class Address extends Model
{
    use UsesCustomerDatabase;   // MySQL when CUSTOMER_DB_ENABLED is on

    protected string $table = 'addresses';

    public const LABELS = ['Home', 'Work', 'Other'];

    private static function seed(int $userId): array
    {
        // Sample addresses belong to the demo customer only. A newly
        // registered account starts with none and adds its own.
        if ($userId !== DEMO_CUSTOMER_ID) {
            return [];
        }

        return [
            ['id' => 1, 'user_id' => $userId, 'label' => 'Home', 'line1' => '12 Galle Road, Colombo 03', 'city' => 'Colombo',      'postcode' => '00300', 'phone' => '+94 77 123 4567', 'is_default' => true],
            ['id' => 2, 'user_id' => $userId, 'label' => 'Work', 'line1' => 'No 10, Kandy Road, Kiribathgoda', 'city' => 'Kiribathgoda', 'postcode' => '11600', 'phone' => '+94 77 123 4567', 'is_default' => false],
        ];
    }

    private function &store(int $userId): array
    {
        if (!isset($_SESSION['addresses']) || !is_array($_SESSION['addresses'])) {
            $_SESSION['addresses'] = self::seed($userId);
        }
        return $_SESSION['addresses'];
    }

    /** Fill in keys an older session may not have, so no re-seed is needed. */
    private function normalise(array $address): array
    {
        $address['label']      = (string) ($address['label'] ?? 'Other');
        $address['city']        = (string) ($address['city'] ?? '');
        $address['postcode']   = (string) ($address['postcode'] ?? '');
        $address['phone']      = (string) ($address['phone'] ?? '');
        $address['is_default'] = !empty($address['is_default']);
        return $address;
    }

    /* ==================================================================
     * READ
     * ================================================================== */

    public function forUser(int $userId): array
    {
        if ($this->hasDb()) {
            $rows = $this->fetchAll(
                "SELECT id, user_id, label, line1, city, postcode, phone, is_default
                   FROM {$this->table}
                  WHERE user_id = :user_id
               ORDER BY is_default DESC, id",
                ['user_id' => $userId]
            );

            foreach ($rows as $i => $row) {
                $rows[$i] = $this->normalise($row);
            }

            return $rows;
        }

        $rows = array_values(array_filter($this->store($userId), fn($a) => (int) $a['user_id'] === $userId));

        foreach ($rows as $i => $row) {
            $rows[$i] = $this->normalise($row);
        }

        usort($rows, fn($a, $b) => ($b['is_default'] <=> $a['is_default']) ?: ($a['id'] <=> $b['id']));

        return $rows;
    }

    public function find(int $userId, int $id): ?array
    {
        foreach ($this->forUser($userId) as $a) {
            if ((int) $a['id'] === $id) {
                return $a;
            }
        }
        return null;
    }

    /* ==================================================================
     * CREATE
     * ================================================================== */

    public function create(int $userId, array $data): ?array
    {
        $fields = self::clean($data);

        if ($fields['line1'] === '' || $fields['city'] === '') {
            return null;
        }

        // The very first address is the default, whatever the form said.
        $makeDefault = !empty($data['is_default']) || count($this->forUser($userId)) === 0;

        if ($this->hasDb()) {
            $id = $this->insertRow($fields + [
                'user_id'    => $userId,
                'is_default' => 0,
            ]);

            if ($makeDefault) {
                $this->setDefault($userId, $id);
            }

            return $this->find($userId, $id);
        }

        $store = &$this->store($userId);
        $ids   = array_column($store, 'id');

        $address = $fields + [
            'id'         => ($ids ? max($ids) : 0) + 1,
            'user_id'    => $userId,
            'is_default' => false,
        ];

        $store[] = $address;

        if ($makeDefault) {
            $this->setDefault($userId, (int) $address['id']);
        }

        return $this->find($userId, (int) $address['id']);
    }

    /* ==================================================================
     * UPDATE
     * ================================================================== */

    public function update(int $userId, int $id, array $data): ?array
    {
        $existing = $this->find($userId, $id);
        if (!$existing) {
            return null;
        }

        $fields = self::clean($data + $existing);

        if ($fields['line1'] === '' || $fields['city'] === '') {
            return null;
        }

        if ($this->hasDb()) {
            $this->exec(
                "UPDATE {$this->table}
                    SET label = :label, line1 = :line1, city = :city,
                        postcode = :postcode, phone = :phone
                  WHERE id = :id AND user_id = :user_id",
                $fields + ['id' => $id, 'user_id' => $userId]
            );
        } else {
            $store = &$this->store($userId);
            foreach ($store as &$a) {
                if ((int) $a['id'] === $id && (int) $a['user_id'] === $userId) {
                    $a = array_merge($this->normalise($a), $fields);
                    break;
                }
            }
            unset($a);
        }

        if (!empty($data['is_default'])) {
            $this->setDefault($userId, $id);
        }

        return $this->find($userId, $id);
    }

    /** Make one address the default and clear the flag on all the others. */
    public function setDefault(int $userId, int $id): bool
    {
        if (!$this->find($userId, $id)) {
            return false;
        }

        if ($this->hasDb()) {
            $this->exec(
                "UPDATE {$this->table} SET is_default = 0 WHERE user_id = :user_id",
                ['user_id' => $userId]
            );

            return $this->exec(
                "UPDATE {$this->table} SET is_default = 1 WHERE id = :id AND user_id = :user_id",
                ['id' => $id, 'user_id' => $userId]
            ) > 0;
        }

        $store = &$this->store($userId);
        foreach ($store as &$a) {
            if ((int) $a['user_id'] === $userId) {
                $a['is_default'] = ((int) $a['id'] === $id);
            }
        }
        unset($a);

        return true;
    }

    /* ==================================================================
     * DELETE
     * ================================================================== */

    /**
     * Remove an address. If it was the default and others remain, the next
     * one is promoted, so the checkout page always has something selected.
     */
    public function delete(int $userId, int $id): bool
    {
        $address = $this->find($userId, $id);
        if (!$address) {
            return false;
        }

        if ($this->hasDb()) {
            $removed = $this->exec(
                "DELETE FROM {$this->table} WHERE id = :id AND user_id = :user_id",
                ['id' => $id, 'user_id' => $userId]
            ) > 0;
        } else {
            $removed = false;
            $store = &$this->store($userId);

            foreach ($store as $i => $a) {
                if ((int) $a['id'] === $id && (int) $a['user_id'] === $userId) {
                    unset($store[$i]);
                    $store   = array_values($store);
                    $removed = true;
                    break;
                }
            }
        }

        if ($removed && $address['is_default']) {
            $remaining = $this->forUser($userId);
            if ($remaining) {
                $this->setDefault($userId, (int) $remaining[0]['id']);
            }
        }

        return $removed;
    }

    /* ------------------------------------------------------------------ */

    /** Trim and cap every editable field. Keys match the table columns. */
    private static function clean(array $data): array
    {
        $label = (string) ($data['label'] ?? 'Other');

        return [
            'label'    => in_array($label, self::LABELS, true) ? $label : 'Other',
            'line1'    => mb_substr(trim((string) ($data['line1'] ?? '')), 0, 255),
            'city'     => mb_substr(trim((string) ($data['city'] ?? '')), 0, 100),
            'postcode' => mb_substr(trim((string) ($data['postcode'] ?? '')), 0, 20),
            'phone'    => mb_substr(trim((string) ($data['phone'] ?? '')), 0, 30),
        ];
    }
}
