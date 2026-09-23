<?php
/**
 * User - the `users` table. Shared by every module.
 *
 * This is the one model everybody touches, because every role signs in
 * through it. It is also the worked example for how a model in this project
 * is written, so read it before writing your first one.
 *
 * Two things to copy:
 *
 *   1. Every method checks hasDb() and falls back to sample data, so the
 *      project runs before MySQL exists. Delete the fallback once the
 *      schema is imported and DB_ENABLED is true.
 *   2. Values are never concatenated into SQL. They go in as ? parameters.
 */
class User extends Model
{
    protected string $table = 'users';

    /** One user by id, or null. */
    public function find(int $id): ?array
    {
        if (!$this->hasDb()) {
            $user = AuthenticationController::demoUser();
            return ($user['id'] ?? null) === $id ? $user : null;
        }

        return $this->fetchOne(
            'SELECT user_id AS id,
                    full_name AS name,
                    email,
                    phone,
                    address,
                    role,
                    status,
                    last_login,
                    created_at
            FROM users
            WHERE user_id = ?',
            [$id]
        );
    }

    /**
 * Return all users for the Admin accounts page.
 */
public function all(): array
{
    if (!$this->hasDb()) {
        return [];
    }

    return $this->fetchAll(
        'SELECT user_id AS id,
                full_name AS name,
                email,
                phone,
                address,
                role,
                last_login,
                status,
                created_at,
                updated_at
           FROM users
       ORDER BY created_at DESC'
    );
}

    /**
     * One user by email address, for login.
     *
     * This deliberately DOES select password_hash - the login controller
     * needs it for password_verify(). Never pass the row straight into
     * Session::login() without unsetting it first.
     */
    public function findByEmail(string $email): ?array
    {
        if (!$this->hasDb()) {
            return null;
        }

        return $this->fetchOne(
            'SELECT user_id AS id, full_name AS name, email, phone, address,
                    role, status, password_hash
               FROM users
              WHERE email = ?',
            [$email]
        );
    }

    /** Everyone with one role, e.g. allWithRole('Pharmacist'). */
    public function allWithRole(string $role): array
    {
        if (!$this->hasDb()) {
            return [];
        }

        return $this->fetchAll(
            'SELECT user_id AS id, full_name AS name, email, phone, role, status
               FROM users
              WHERE role = ?
           ORDER BY full_name',
            [$role]
        );
    }

    /**
     * Create a user. $plainPassword is hashed here so no caller is ever
     * tempted to store it as typed.
     *
     *   $id = (new User())->create([
     *       'full_name' => $name,
     *       'email'     => $email,
     *       'phone'     => $phone,
     *       'role'      => 'Customer',
     *   ], $password);
     *
     * Keys must be real column names from database/001_schema.sql.
     */
    public function create(array $data, string $plainPassword): int
    {
        $data['password_hash'] = password_hash($plainPassword, PASSWORD_DEFAULT);

        return $this->insertRow($data);
    }

    /**
 * Update an existing user.
 */
    public function update(int $id, array $data): void
        {
            if (!$this->hasDb()) {
                return;
            }

            $this->exec(
                'UPDATE users
                    SET full_name = ?,
                        email = ?,
                        phone = ?,
                        address = ?,
                        role = ?,
                        status = ?
                WHERE user_id = ?',
                [
                    $data['full_name'],
                    $data['email'],
                    $data['phone'],
                    $data['address'],
                    $data['role'],
                    $data['status'],
                    $id
                ]
            );
        }

    public function delete(int $id): void
    {
        if (!$this->hasDb()) {
            return;
        }

        $this->exec(
            'DELETE FROM users WHERE user_id = ?',
            [$id]
        );
    }    

    /** Record a successful sign-in. */
    public function touchLastLogin(int $id): void
    {
        if (!$this->hasDb()) {
            return;
        }

        $this->exec('UPDATE users SET last_login = NOW() WHERE user_id = ?', [$id]);
    }

    /** Check a typed password against the stored hash. */
    public function passwordMatches(array $user, string $plainPassword): bool
    {
        return isset($user['password_hash'])
            && password_verify($plainPassword, $user['password_hash']);
    }
}
