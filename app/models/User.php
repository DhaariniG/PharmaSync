<?php
/**
 * User - accounts for every role. Owner: authentication.
 *
 * Backed by the `users` and `customer_profiles` tables in
 * database/001_schema.sql.
 *
 * While DB_ENABLED is false (the rest of the project still runs on sample
 * data), accounts are kept in a small JSON file instead: storage/users.json.
 * A file, not the session, because logging out clears the session - a user
 * who registers and logs out must still be able to log back in.
 *
 * The file is created on first use with one demo account per role (see
 * demoAccounts() below). It is git-ignored, so everyone gets their own copy.
 * Delete the file to reset the accounts.
 *
 * Every method returns rows in the same shape as the `users` table:
 *   user_id, full_name, email, phone, address, password_hash, role,
 *   status, last_login, created_at, date_of_birth
 */
class User extends Model
{
    protected string $table = 'users';

    /** Password for every demo account, in both the file and the SQL seed. */
    public const DEMO_PASSWORD = 'Demo@1234';

    /* ==================================================================
     * Lookups
     * ================================================================== */

    public function findByEmail(string $email): ?array
    {
        $email = strtolower(trim($email));

        if ($this->hasDb()) {
            return $this->fetchOne(
                'SELECT u.*, cp.date_of_birth
                   FROM users u
                   LEFT JOIN customer_profiles cp ON cp.user_id = u.user_id
                  WHERE u.email = :email
                  LIMIT 1',
                ['email' => $email]
            );
        }

        foreach ($this->fileUsers() as $user) {
            if (strtolower($user['email']) === $email) {
                return $user;
            }
        }
        return null;
    }

    public function findById(int $id): ?array
    {
        if ($this->hasDb()) {
            return $this->fetchOne(
                'SELECT u.*, cp.date_of_birth
                   FROM users u
                   LEFT JOIN customer_profiles cp ON cp.user_id = u.user_id
                  WHERE u.user_id = :id
                  LIMIT 1',
                ['id' => $id]
            );
        }

        foreach ($this->fileUsers() as $user) {
            if ((int) $user['user_id'] === $id) {
                return $user;
            }
        }
        return null;
    }

    public function emailExists(string $email): bool
    {
        return $this->findByEmail($email) !== null;
    }

    /* ==================================================================
     * Writes
     * ================================================================== */

    /**
     * Create a Customer account plus its customer_profiles row.
     * Returns the new user_id, or null if the insert failed.
     */
    public function registerCustomer(array $data): ?int
    {
        $row = [
            'full_name'     => $data['full_name'],
            'email'         => strtolower(trim($data['email'])),
            'phone'         => $data['phone'],
            'address'       => ($data['address'] ?? '') !== '' ? $data['address'] : null,
            'password_hash' => password_hash($data['password'], PASSWORD_BCRYPT),
            'role'          => 'Customer',
            'status'        => 'Active',
        ];
        $dob = ($data['date_of_birth'] ?? '') !== '' ? $data['date_of_birth'] : null;

        if ($this->hasDb()) {
            $db = $this->db();
            try {
                $db->beginTransaction();

                $userId = $this->insertRow($row);

                $this->exec(
                    'INSERT INTO customer_profiles (user_id, date_of_birth)
                     VALUES (:user_id, :dob)',
                    ['user_id' => $userId, 'dob' => $dob]
                );

                $db->commit();
                return $userId;
            } catch (Throwable $e) {
                if ($db->inTransaction()) {
                    $db->rollBack();
                }
                error_log('registerCustomer failed: ' . $e->getMessage());
                return null;
            }
        }

        $newId = null;
        $this->withFileUsers(function (array &$users) use ($row, $dob, &$newId) {
            $newId = 1 + max(array_map(fn($u) => (int) $u['user_id'], $users) ?: [0]);
            $users[] = $row + [
                'user_id'       => $newId,
                'last_login'    => null,
                'created_at'    => date('Y-m-d H:i:s'),
                'date_of_birth' => $dob,
            ];
        });

        return $newId;
    }

    public function recordLogin(int $userId): void
    {
        if ($this->hasDb()) {
            $this->exec('UPDATE users SET last_login = NOW() WHERE user_id = :id', ['id' => $userId]);
            return;
        }

        $this->withFileUsers(function (array &$users) use ($userId) {
            foreach ($users as &$user) {
                if ((int) $user['user_id'] === $userId) {
                    $user['last_login'] = date('Y-m-d H:i:s');
                }
            }
        });
    }

    /* ==================================================================
     * Session shape
     * ================================================================== */

    /**
     * Turn a users row into the array Session::login() stores.
     *
     * 'id', 'role' and 'name' are the three keys every module relies on.
     * The rest is what the customer pages already read off the signed-in
     * user. Never put password_hash in the session.
     */
    public static function toSessionUser(array $row): array
    {
        $user = [
            'id'      => (int) $row['user_id'],
            'role'    => $row['role'],
            'name'    => $row['full_name'],
            'email'   => $row['email'],
            'phone'   => $row['phone'] ?? '',
            'address' => $row['address'] ?? '',
            'dob'     => $row['date_of_birth'] ?? null,
        ];

        // Demo-only display values (health points etc.). The DB has no
        // columns for these yet, so real accounts simply don't get them.
        foreach ($row['extras'] ?? [] as $key => $value) {
            $user[$key] ??= $value;
        }

        return $user;
    }

    /* ==================================================================
     * File store - delete once DB_ENABLED is true
     * ================================================================== */

    private function fileUsers(): array
    {
        if (!is_file(USERS_STORE_FILE)) {
            $this->withFileUsers(fn() => null);   // creates it with the demo accounts
        }

        $json  = (string) @file_get_contents(USERS_STORE_FILE);
        $users = json_decode($json, true);

        return is_array($users) ? $users : [];
    }

    /**
     * Read-modify-write the file under an exclusive lock, so two requests
     * registering at the same moment cannot overwrite each other.
     */
    private function withFileUsers(callable $change): void
    {
        $dir = dirname(USERS_STORE_FILE);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $fh = fopen(USERS_STORE_FILE, 'c+');
        if ($fh === false) {
            throw new RuntimeException('Cannot open ' . USERS_STORE_FILE . ' - is storage/ writable?');
        }

        try {
            flock($fh, LOCK_EX);

            $users = json_decode((string) stream_get_contents($fh), true);
            if (!is_array($users) || $users === []) {
                $users = self::demoAccounts();
            }

            $change($users);

            ftruncate($fh, 0);
            rewind($fh);
            fwrite($fh, json_encode(array_values($users), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            fflush($fh);
            flock($fh, LOCK_UN);
        } finally {
            fclose($fh);
        }
    }

    /**
     * One account per role, all with DEMO_PASSWORD. user_id 1 is the demo
     * customer, because the customer sample data (orders, prescriptions,
     * family members) belongs to user 1. Same rows as
     * database/001a_seed_demo_users.sql.
     */
    private static function demoAccounts(): array
    {
        $hash = password_hash(self::DEMO_PASSWORD, PASSWORD_BCRYPT);
        $now  = date('Y-m-d H:i:s');

        $base = [
            'password_hash' => $hash,
            'status'        => 'Active',
            'last_login'    => null,
            'created_at'    => $now,
            'date_of_birth' => null,
        ];

        return [
            ['user_id' => 1, 'full_name' => 'Nadeesha Perera',     'email' => 'customer@pharmasync.com',   'phone' => '0771234567', 'address' => '12 Galle Road, Colombo 03', 'role' => 'Customer',
             'date_of_birth' => '1990-05-14',
             'extras' => ['health_points' => 2450, 'profile_complete' => 85]] + $base,
            ['user_id' => 2, 'full_name' => 'Sarah Jenkins',       'email' => 'pharmacist@pharmasync.com', 'phone' => '0777654321', 'address' => '45 Pharmacy Way, Kandy',    'role' => 'Pharmacist'] + $base,
            ['user_id' => 3, 'full_name' => 'System Admin',        'email' => 'admin@pharmasync.com',      'phone' => '0771112233', 'address' => 'Colombo, Sri Lanka',        'role' => 'Admin'] + $base,
            ['user_id' => 4, 'full_name' => 'Tharindu Jayasuriya', 'email' => 'inventory@pharmasync.com',  'phone' => '0772223344', 'address' => null,                        'role' => 'Inventory_Manager'] + $base,
            ['user_id' => 5, 'full_name' => 'Kasun Bandara',       'email' => 'delivery@pharmasync.com',   'phone' => '0773334455', 'address' => null,                        'role' => 'Delivery_Partner'] + $base,
        ];
    }

   /* ==================================================================
     * Password Resets
     * ================================================================== */

    public function createPasswordResetToken(string $email, string $token, string $expiresAt): bool
    {
        // 1. Delete previous tokens for this email first
        $this->exec("DELETE FROM password_resets WHERE email = :email", ['email' => $email]);

        // 2. Insert new token using Model's insertRow helper
        $id = $this->insertRow([
            'email'      => $email,
            'token'      => $token,
            'expires_at' => $expiresAt,
        ], 'password_resets');

        return $id > 0;
    }

    public function findPasswordResetToken(string $token): ?array
    {
        return $this->fetchOne(
            "SELECT * FROM password_resets WHERE token = :token AND expires_at > NOW() LIMIT 1",
            ['token' => $token]
        );
    }

    public function updatePasswordByEmail(string $email, string $newPasswordHash): bool
    {
        // Update user's password hash
        $this->exec(
            "UPDATE users SET password_hash = :hash WHERE email = :email",
            ['hash' => $newPasswordHash, 'email' => $email]
        );

        // Clear used reset tokens
        $this->exec("DELETE FROM password_resets WHERE email = :email", ['email' => $email]);

        return true;
    }
}

