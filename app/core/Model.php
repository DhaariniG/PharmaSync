<?php
/**
 * Model - base class for every model in the project.
 *
 * Rules for everyone:
 *   - ALL SQL lives in models. Never in a controller, never in a view.
 *   - ALWAYS use the helpers below or your own prepared statement.
 *     Never concatenate a variable into a query string.
 *
 * While DB_ENABLED is false, db() returns null and each model serves its
 * own sample data from the session. The shape of that sample data must
 * match the columns in database/001_schema.sql, so that switching the flag
 * on later changes the model and nothing else.
 *
 * Rows come back as ASSOCIATIVE ARRAYS ($row['name']), not objects. Every
 * view in the project is written that way - do not change it.
 */
class Model
{
    /** Child classes may set this, e.g. protected string $table = 'medicines'; */
    protected string $table = '';

    /** PDO connection, or null while the project still runs on sample data. */
    protected function db(): ?PDO
    {
        return DB_ENABLED ? Database::getConnection() : null;
    }

    /** True when this model should read from MySQL rather than the session. */
    protected function hasDb(): bool
    {
        return DB_ENABLED;
    }

    /* ==================================================================
     * Query helpers - only usable once DB_ENABLED is true
     * ================================================================== */

    /*
     * These are deliberately named fetchAll / fetchOne / insertRow / ... and
     * not all / one / insert / update. Models in this project already use the
     * short names for their own business methods (Medicine::all(),
     * Prescription::update()), and PHP refuses to let a child class redeclare
     * a parent method with a different signature. Do not rename them back.
     */

    /** Run a query and return every row. */
    protected function fetchAll(string $sql, array $params = []): array
    {
        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /** Run a query and return the first row, or null. */
    protected function fetchOne(string $sql, array $params = []): ?array
    {
        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }

    /** Run a query and return the first column of the first row. */
    protected function fetchValue(string $sql, array $params = [])
    {
        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);
        $value = $stmt->fetchColumn();
        return $value === false ? null : $value;
    }

    /** Run an INSERT, UPDATE or DELETE and return the rows affected. */
    protected function exec(string $sql, array $params = []): int
    {
        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    /**
     * Insert one row from an array of column => value and return its id.
     *
     *   $id = $this->insertRow(['name' => $name, 'price' => $price]);
     */
    protected function insertRow(array $data, ?string $table = null): int
    {
        $table   = $table ?? $this->table;
        $columns = array_keys($data);
        $holders = array_map(fn($c) => ':' . $c, $columns);

        $sql = 'INSERT INTO ' . $table
             . ' (' . implode(', ', $columns) . ')'
             . ' VALUES (' . implode(', ', $holders) . ')';

        $this->exec($sql, $data);

        return (int) $this->db()->lastInsertId();
    }

    /** Update one row by primary key. Returns the rows affected. */
    protected function updateRow(int $id, array $data, ?string $table = null, string $key = 'id'): int
    {
        $table = $table ?? $this->table;
        $sets  = [];

        foreach (array_keys($data) as $column) {
            $sets[] = $column . ' = :' . $column;
        }

        $sql = 'UPDATE ' . $table
             . ' SET ' . implode(', ', $sets)
             . ' WHERE ' . $key . ' = :__id';

        $data['__id'] = $id;

        return $this->exec($sql, $data);
    }

    /* ==================================================================
     * Sample data helpers - delete once DB_ENABLED is true
     * ================================================================== */

    /**
     * Read a session key, seeding it the first time.
     *
     *   $orders = $this->seeded('orders', fn() => $this->sampleOrders());
     *
     * Stale sample data is cleared by the SEED_VERSION check in
     * public/index.php, so remember to list your keys in
     * SEEDED_SESSION_KEYS and to bump SEED_VERSION when a shape changes.
     */
    protected function seeded(string $key, callable $seed): array
    {
        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = $seed();
        }
        return $_SESSION[$key];
    }

    /** Overwrite a session key. */
    protected function sessionPut(string $key, array $value): void
    {
        $_SESSION[$key] = $value;
    }
}
