<?php
/**
 * Medicine - the `medicines` table (joined to `medicine_categories`).
 *
 * All SQL for medicines lives here. Values always go in as ? or :name
 * parameters, never glued into the SQL string.
 */
class Medicine extends Model
{
    protected string $table = 'medicines';

    /**
     * This module already has a real, populated database, so it always uses
     * MySQL. The base class returns null while DB_ENABLED is false (that flag
     * is shared by the whole team), so we skip that check for this model only.
     */
    protected function db(): ?PDO
    {
        return Database::getConnection();
    }

    /**
     * Every medicine, with its category name and its total stock.
     * Stock = the sum of quantity over that medicine's Available batches.
     * LEFT JOIN means a medicine with no batches still appears (stock 0).
     */
    public function all(?int $limit = null, int $offset = 0): array
    {
        // LIMIT/OFFSET are cast to int, so gluing them into the SQL is safe.
        $paging = $limit === null ? '' : ' LIMIT ' . max(0, $limit) . ' OFFSET ' . max(0, $offset);

        return $this->fetchAll(
            "SELECT m.*, c.category_name,
                    COALESCE(SUM(b.quantity), 0) AS stock
               FROM medicines m
               JOIN medicine_categories c ON c.category_id = m.category_id
          LEFT JOIN stock_batches b ON b.medicine_id = m.medicine_id
                                   AND b.status = 'Available'
           GROUP BY m.medicine_id
           ORDER BY m.name" . $paging
        );
    }

    /** Total number of medicines (same rows all() would return, unpaged). */
    public function countAll(): int
    {
        return (int) $this->fetchValue(
            'SELECT COUNT(*)
               FROM medicines m
               JOIN medicine_categories c ON c.category_id = m.category_id'
        );
    }

    /** One medicine by id, or null if there is no such row. */
    public function find(int $id): ?array
    {
        return $this->fetchOne(
            'SELECT * FROM medicines WHERE medicine_id = ?',
            [$id]
        );
    }

    /** id + name of every category, for the Category dropdown. */
    public function categories(): array
    {
        return $this->fetchAll(
            'SELECT category_id, category_name
               FROM medicine_categories
           ORDER BY category_name'
        );
    }

    /** True if this category id exists (so a forged id is rejected). */
    public function categoryExists(int $categoryId): bool
    {
        return $this->fetchValue(
            'SELECT COUNT(*) FROM medicine_categories WHERE category_id = ?',
            [$categoryId]
        ) > 0;
    }

    /** Insert a new medicine. $data keys are real column names. Returns the new id. */
    public function create(array $data): int
    {
        return $this->insertRow($data);
    }

    /** Update one medicine. The primary key column is medicine_id, not id. */
    public function update(int $id, array $data): int
    {
        return $this->updateRow($id, $data, null, 'medicine_id');
    }

    /**
     * Delete one medicine. Throws PDOException if other tables (batches,
     * orders...) still point at it - the controller catches that.
     */
    public function delete(int $id): int
    {
        return $this->exec('DELETE FROM medicines WHERE medicine_id = ?', [$id]);
    }
}
