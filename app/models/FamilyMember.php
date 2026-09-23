<?php

/**
 * People an account holder can order medicine for: themselves and any family
 * members they add. A prescription is uploaded for one of these, so the
 * patient name shown on screen comes from a real record rather than a guess.
 *
 * FULL CRUD lives here (customer module, Mithun):
 *   create()  - add a family member
 *   forUser() / find()  - read
 *   update()  - edit name, relationship, date of birth, notes
 *   delete()  - remove a member and their health flags
 *
 * Each member carries their OWN known allergies and chronic conditions,
 * stored as "health flags": addFlag() / deleteFlag(). The account holder is
 * simply the member whose relationship is 'Self', so the Health Profile card
 * on the profile page and a member's own card use the same storage.
 *
 * Both paths are written: while DB_ENABLED is false the data lives in the
 * session, and the moment it is true the same methods run SQL against
 * family_members and family_member_health_flags
 * (database/003_add_customer_profile_tables.sql). Controllers and views do
 * not change either way.
 */
class FamilyMember extends Model
{
    use UsesCustomerDatabase;   // MySQL when CUSTOMER_DB_ENABLED is on

    protected string $table = 'family_members';

    /** Health flags are a child table, so they need their own name. */
    protected string $flagTable = 'family_member_health_flags';

    public const RELATIONSHIPS = ['Self', 'Father', 'Mother', 'Spouse', 'Child', 'Sibling', 'Other'];

    /** A health flag is one of these. 'allergy' and 'condition' match the ENUM. */
    public const FLAG_TYPES = ['allergy', 'condition'];

    /* ==================================================================
     * Sample data (used only while DB_ENABLED is false)
     * ================================================================== */

    private static function seed(int $userId): array
    {
        // Only the demo customer (user 1) gets the sample family. Anyone who
        // registered gets just their own 'Self' record, built from their
        // account, so a new account never shows Nadeesha's family.
        if ($userId !== DEMO_CUSTOMER_ID) {
            $me = Session::user() ?? [];
            return [[
                'id' => 1, 'user_id' => $userId, 'name' => (string) ($me['name'] ?? 'Me'),
                'relationship' => 'Self', 'date_of_birth' => (string) ($me['dob'] ?? ''),
                'notes' => '', 'flags' => [],
            ]];
        }

        return [
            [
                'id' => 1, 'user_id' => $userId, 'name' => 'Nadeesha Perera',
                'relationship' => 'Self', 'date_of_birth' => '1990-05-14', 'notes' => '',
                'flags' => [
                    ['id' => 1, 'type' => 'allergy',   'value' => 'Penicillin'],
                    ['id' => 2, 'type' => 'allergy',   'value' => 'Peanuts'],
                    ['id' => 3, 'type' => 'condition', 'value' => 'Hypertension'],
                ],
            ],
            [
                'id' => 2, 'user_id' => $userId, 'name' => 'Kamal Wijesinghe',
                'relationship' => 'Father', 'date_of_birth' => '1958-02-20',
                'notes' => 'Takes Metformin for diabetes.',
                'flags' => [
                    ['id' => 4, 'type' => 'condition', 'value' => 'Type 2 Diabetes'],
                    ['id' => 5, 'type' => 'allergy',   'value' => 'Sulfa drugs'],
                ],
            ],
        ];
    }

    private function &store(int $userId): array
    {
        if (!isset($_SESSION['family_members']) || !is_array($_SESSION['family_members'])) {
            $_SESSION['family_members'] = self::seed($userId);
        }
        return $_SESSION['family_members'];
    }

    /**
     * Fill in any key an older session might be missing, so a browser that
     * was open before this feature was added does not throw undefined-index
     * notices. Saves the group a SEED_VERSION bump.
     */
    private function normalise(array $member): array
    {
        $member['notes'] = (string) ($member['notes'] ?? '');
        $member['date_of_birth'] = (string) ($member['date_of_birth'] ?? '');
        $member['flags'] = is_array($member['flags'] ?? null) ? $member['flags'] : [];
        return $member;
    }

    /** Next free flag id across every member of this account. */
    private function nextFlagId(int $userId): int
    {
        $max = 0;
        foreach ($this->store($userId) as $m) {
            foreach (($m['flags'] ?? []) as $f) {
                $max = max($max, (int) $f['id']);
            }
        }
        return $max + 1;
    }

    /* ==================================================================
     * READ
     * ================================================================== */

    /** Every member on this account, the account holder first. */
    public function forUser(int $userId): array
    {
        if ($this->hasDb()) {
            $members = $this->fetchAll(
                "SELECT id, user_id, name, relationship, date_of_birth, notes
                   FROM {$this->table}
                  WHERE user_id = :user_id
               ORDER BY (relationship = 'Self') DESC, name",
                ['user_id' => $userId]
            );

            // Every account has a 'Self' record. A newly registered account
            // gets it here, the first time anything asks for its members.
            if (!in_array('Self', array_column($members, 'relationship'), true)
                && $this->createAccountHolder($userId)) {
                return $this->forUser($userId);
            }

            foreach ($members as $i => $m) {
                $members[$i] = $this->normalise($m);
                $members[$i]['flags'] = $this->fetchAll(
                    "SELECT id, type, value
                       FROM {$this->flagTable}
                      WHERE member_id = :member_id
                   ORDER BY type, value",
                    ['member_id' => (int) $m['id']]
                );
            }

            return $members;
        }

        $members = array_filter(
            $this->store($userId),
            fn($m) => (int) $m['user_id'] === $userId
        );

        // Account holder first, then everyone else by name.
        usort($members, function ($a, $b) {
            $selfA = $a['relationship'] === 'Self' ? 0 : 1;
            $selfB = $b['relationship'] === 'Self' ? 0 : 1;
            return $selfA === $selfB ? strcasecmp($a['name'], $b['name']) : $selfA <=> $selfB;
        });

        $members = array_values($members);
        foreach ($members as $i => $m) {
            $members[$i] = $this->normalise($m);
        }

        return $members;
    }

    /**
     * Look one up, but only within this account. Used to check that a patient
     * really belongs to the person making the request.
     */
    public function find(int $userId, int $id): ?array
    {
        foreach ($this->forUser($userId) as $m) {
            if ((int) $m['id'] === $id) {
                return $m;
            }
        }
        return null;
    }

    /** The account holder's own record, i.e. the member marked 'Self'. */
    public function accountHolder(int $userId): ?array
    {
        foreach ($this->forUser($userId) as $m) {
            if ($m['relationship'] === 'Self') {
                return $m;
            }
        }
        return null;
    }

    /** "Father — Kamal Wijesinghe", or just the name when it is the account holder. */
    public function label(int $userId, ?int $id): string
    {
        if (!$id) {
            return 'Not specified';
        }
        $m = $this->find($userId, (int) $id);
        if (!$m) {
            return 'Unknown';
        }
        return $m['relationship'] === 'Self'
            ? $m['name']
            : $m['relationship'] . ' — ' . $m['name'];
    }

    /* ==================================================================
     * CREATE
     * ================================================================== */

    /**
     * Insert the 'Self' row for an account from its users row (DB only).
     * Returns false if the account does not exist.
     */
    private function createAccountHolder(int $userId): bool
    {
        $account = $this->fetchOne(
            'SELECT u.full_name, cp.date_of_birth
               FROM users u
               LEFT JOIN customer_profiles cp ON cp.user_id = u.user_id
              WHERE u.user_id = :id',
            ['id' => $userId]
        );

        if (!$account) {
            return false;
        }

        $this->insertRow([
            'user_id'       => $userId,
            'name'          => mb_substr($account['full_name'], 0, 80),
            'relationship'  => 'Self',
            'date_of_birth' => $account['date_of_birth'],
            'notes'         => '',
        ]);

        return true;
    }

    public function create(int $userId, array $data): array
    {
        $relationship = self::cleanRelationship($data['relationship'] ?? '');

        // Only one account holder. A second 'Self' becomes 'Other'.
        if ($relationship === 'Self' && $this->accountHolder($userId)) {
            $relationship = 'Other';
        }

        $name  = mb_substr(trim((string) ($data['name'] ?? '')), 0, 80);
        $dob   = self::cleanDate($data['date_of_birth'] ?? '');
        $notes = mb_substr(trim((string) ($data['notes'] ?? '')), 0, 255);

        if ($this->hasDb()) {
            $id = $this->insertRow([
                'user_id'       => $userId,
                'name'          => $name,
                'relationship'  => $relationship,
                'date_of_birth' => $dob !== '' ? $dob : null,
                'notes'         => $notes,
            ]);

            return $this->find($userId, $id) ?? [
                'id'            => $id,
                'user_id'       => $userId,
                'name'          => $name,
                'relationship'  => $relationship,
                'date_of_birth' => $dob,
                'notes'         => $notes,
                'flags'         => [],
            ];
        }

        $store = &$this->store($userId);
        $ids   = array_column($store, 'id');

        $member = [
            'id'            => ($ids ? max($ids) : 0) + 1,
            'user_id'       => $userId,
            'name'          => $name,
            'relationship'  => $relationship,
            'date_of_birth' => $dob,
            'notes'         => $notes,
            'flags'         => [],
        ];

        $store[] = $member;
        return $member;
    }

    /* ==================================================================
     * UPDATE
     * ================================================================== */

    /**
     * Edit one member. Returns the updated record, or null if the id does
     * not belong to this account.
     *
     * The account holder stays the account holder: 'Self' can neither be
     * given away nor taken by another member.
     */
    public function update(int $userId, int $id, array $data): ?array
    {
        $existing = $this->find($userId, $id);
        if (!$existing) {
            return null;
        }

        $relationship = self::cleanRelationship($data['relationship'] ?? $existing['relationship']);

        if ($existing['relationship'] === 'Self') {
            $relationship = 'Self';                 // cannot be demoted
        } elseif ($relationship === 'Self') {
            $relationship = $existing['relationship'];   // cannot be promoted
        }

        $changes = [
            'name'          => mb_substr(trim((string) ($data['name'] ?? $existing['name'])), 0, 80),
            'relationship'  => $relationship,
            'date_of_birth' => self::cleanDate($data['date_of_birth'] ?? $existing['date_of_birth']),
            'notes'         => mb_substr(trim((string) ($data['notes'] ?? $existing['notes'])), 0, 255),
        ];

        if ($changes['name'] === '') {
            return null;
        }

        if ($this->hasDb()) {
            $this->exec(
                "UPDATE {$this->table}
                    SET name = :name,
                        relationship = :relationship,
                        date_of_birth = :date_of_birth,
                        notes = :notes
                  WHERE id = :id AND user_id = :user_id",
                [
                    'name'          => $changes['name'],
                    'relationship'  => $changes['relationship'],
                    'date_of_birth' => $changes['date_of_birth'] !== '' ? $changes['date_of_birth'] : null,
                    'notes'         => $changes['notes'],
                    'id'            => $id,
                    'user_id'       => $userId,
                ]
            );

            return $this->find($userId, $id);
        }

        $store = &$this->store($userId);
        foreach ($store as &$m) {
            if ((int) $m['id'] === $id && (int) $m['user_id'] === $userId) {
                $m = array_merge($this->normalise($m), $changes);
                return $m;
            }
        }
        unset($m);

        return null;
    }

    /* ==================================================================
     * DELETE
     * ================================================================== */

    /**
     * Remove a member and every health flag attached to them.
     * The account holder ('Self') is never deletable - the caller should
     * check isDeletable() first and explain why.
     */
    public function delete(int $userId, int $id): bool
    {
        $member = $this->find($userId, $id);
        if (!$member || !self::isDeletable($member)) {
            return false;
        }

        if ($this->hasDb()) {
            // Flags go first in case the FK is created without ON DELETE CASCADE.
            $this->exec(
                "DELETE FROM {$this->flagTable} WHERE member_id = :member_id",
                ['member_id' => $id]
            );

            return $this->exec(
                "DELETE FROM {$this->table} WHERE id = :id AND user_id = :user_id",
                ['id' => $id, 'user_id' => $userId]
            ) > 0;
        }

        $store = &$this->store($userId);
        foreach ($store as $i => $m) {
            if ((int) $m['id'] === $id && (int) $m['user_id'] === $userId) {
                unset($store[$i]);
                $store = array_values($store);
                return true;
            }
        }

        return false;
    }

    /** The account holder's own record must always exist. */
    public static function isDeletable(array $member): bool
    {
        return ($member['relationship'] ?? '') !== 'Self';
    }

    /* ==================================================================
     * Health flags - allergies and chronic conditions, per member
     * ================================================================== */

    /** One member's flags of a given type, e.g. flagsOf($member, 'allergy'). */
    public static function flagsOf(array $member, string $type): array
    {
        return array_values(array_filter(
            $member['flags'] ?? [],
            fn($f) => ($f['type'] ?? '') === $type
        ));
    }

    public static function allergiesOf(array $member): array
    {
        return self::flagsOf($member, 'allergy');
    }

    public static function conditionsOf(array $member): array
    {
        return self::flagsOf($member, 'condition');
    }

    /**
     * Record a known allergy or chronic condition against one member.
     * Returns the new flag, or null if the member is not on this account,
     * the value is empty, or the same flag is already recorded.
     */
    public function addFlag(int $userId, int $memberId, string $type, string $value): ?array
    {
        $type  = in_array($type, self::FLAG_TYPES, true) ? $type : 'allergy';
        $value = mb_substr(trim($value), 0, 120);

        if ($value === '') {
            return null;
        }

        $member = $this->find($userId, $memberId);
        if (!$member) {
            return null;
        }

        // No duplicates, case-insensitively.
        foreach (self::flagsOf($member, $type) as $existing) {
            if (mb_strtolower($existing['value']) === mb_strtolower($value)) {
                return null;
            }
        }

        if ($this->hasDb()) {
            $id = $this->insertRow([
                'member_id' => $memberId,
                'type'      => $type,
                'value'     => $value,
            ], $this->flagTable);

            return ['id' => $id, 'type' => $type, 'value' => $value];
        }

        $flag  = ['id' => $this->nextFlagId($userId), 'type' => $type, 'value' => $value];
        $store = &$this->store($userId);

        foreach ($store as &$m) {
            if ((int) $m['id'] === $memberId && (int) $m['user_id'] === $userId) {
                $m = $this->normalise($m);
                $m['flags'][] = $flag;
                return $flag;
            }
        }
        unset($m);

        return null;
    }

    /** Remove one allergy or condition from one member. */
    public function deleteFlag(int $userId, int $memberId, int $flagId): bool
    {
        if (!$this->find($userId, $memberId)) {
            return false;
        }

        if ($this->hasDb()) {
            return $this->exec(
                "DELETE FROM {$this->flagTable} WHERE id = :id AND member_id = :member_id",
                ['id' => $flagId, 'member_id' => $memberId]
            ) > 0;
        }

        $store = &$this->store($userId);

        foreach ($store as &$m) {
            if ((int) $m['id'] === $memberId && (int) $m['user_id'] === $userId) {
                $m = $this->normalise($m);
                foreach ($m['flags'] as $i => $f) {
                    if ((int) $f['id'] === $flagId) {
                        unset($m['flags'][$i]);
                        $m['flags'] = array_values($m['flags']);
                        return true;
                    }
                }
            }
        }
        unset($m);

        return false;
    }

    /* ==================================================================
     * Small validators, kept here so the controller stays thin
     * ================================================================== */

    public static function cleanRelationship(string $value): string
    {
        return in_array($value, self::RELATIONSHIPS, true) ? $value : 'Other';
    }

    /** 'YYYY-MM-DD' if it is a real, non-future date; '' otherwise. */
    public static function cleanDate($value): string
    {
        $value = trim((string) $value);
        if ($value === '') {
            return '';
        }

        $date = DateTime::createFromFormat('Y-m-d', $value);
        if (!$date || $date->format('Y-m-d') !== $value) {
            return '';
        }

        return $date->format('Y-m-d') > date('Y-m-d') ? '' : $value;
    }
}
