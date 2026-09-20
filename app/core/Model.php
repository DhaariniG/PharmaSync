<?php
/**
 * Model - base class for every model in the project.
 *
 * Rules for everyone:
 *   - ALL SQL lives in models. Never in a controller, never in a view.
 *   - ALWAYS use the helpers below or a prepared statement.
 *     Never concatenate a variable into a query string.
 */
abstract class Model
{
    protected PDO $db;

    /** Child classes set this, e.g. protected string $table = 'medicines'; */
    protected string $table = '';

    public function __construct()
    {
        $this->db = Database::conn();
    }

    /* ------------------------------------------------------------------
     * Query helpers
     * ------------------------------------------------------------------ */

    /** Run a query, return all rows as objects. */
    protected function all(string $sql, array $params = []): array
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /** Run a query, return the first row or null. */
    protected function one(string $sql, array $params = []): ?object
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }

    /** Run a query, return a single scalar value. */
    protected function scalar(string $sql, array $params = [])
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    /** Run an INSERT/UPDATE/DELETE, return affected row count. */
    protected function run(string $sql, array $params = []): int
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    /** Insert a row from an associative array, return the new id. */
    protected function insert(string $table, array $data): int
    {
        $cols         = array_keys($data);
        $placeholders = array_map(fn($c) => ':' . $c, $cols);

        $sql = 'INSERT INTO ' . $table
             . ' (' . implode(', ', $cols) . ')'
             . ' VALUES (' . implode(', ', $placeholders) . ')';

        $this->run($sql, $data);
        return (int) $this->db->lastInsertId();
    }

    /** Update a row by primary key, return affected row count. */
    protected function update(string $table, string $pkColumn, $pkValue, array $data): int
    {
        $sets = [];
        foreach (array_keys($data) as $col) {
            $sets[] = "$col = :$col";
        }

        $sql = 'UPDATE ' . $table
             . ' SET ' . implode(', ', $sets)
             . " WHERE $pkColumn = :__pk";

        $data['__pk'] = $pkValue;
        return $this->run($sql, $data);
    }

    /* ------------------------------------------------------------------
     * Transactions
     * ------------------------------------------------------------------ */

    protected function begin(): void    { $this->db->beginTransaction(); }
    protected function commit(): void   { $this->db->commit(); }
    protected function rollback(): void
    {
        if ($this->db->inTransaction()) {
            $this->db->rollBack();
        }
    }

    /**
     * Run a callback inside a transaction. Rolls back on any exception.
     *
     *   $this->transaction(function () {
     *       // ...inserts and updates...
     *       return $orderId;
     *   });
     */
    protected function transaction(callable $work)
    {
        $this->begin();
        try {
            $result = $work();
            $this->commit();
            return $result;
        } catch (Throwable $e) {
            $this->rollback();
            throw $e;
        }
    }

    /* ------------------------------------------------------------------
     * Shared stock helper
     * ------------------------------------------------------------------
     * `medicines` has no stock column - stock is the sum of its usable
     * batches. Every module must calculate it the same way, so it lives
     * here and nowhere else.
     */

    /** Total sellable quantity of one medicine. */
    public function stockOf(int $medicineId): int
    {
        return (int) $this->scalar(
            "SELECT COALESCE(SUM(quantity), 0)
               FROM stock_batches
              WHERE medicine_id = ?
                AND status      = 'Available'
                AND expiry_date > CURDATE()",
            [$medicineId]
        );
    }

    /**
     * SQL fragment for stock, to embed as a sub-select in bigger queries.
     * Usage:
     *   "SELECT m.*, " . $this->stockSubquery('m.medicine_id') . " AS stock FROM medicines m"
     */
    protected function stockSubquery(string $medicineIdColumn): string
    {
        return "(SELECT COALESCE(SUM(sb.quantity), 0)
                   FROM stock_batches sb
                  WHERE sb.medicine_id = $medicineIdColumn
                    AND sb.status      = 'Available'
                    AND sb.expiry_date > CURDATE())";
    }
}
