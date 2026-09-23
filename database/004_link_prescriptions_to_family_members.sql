-- ============================================================
-- PharmaSync - migration 004: link prescriptions to family members
-- Owner: Zaidh (integration)
--
-- Adds prescriptions.family_member_id, a nullable link to the
-- family_members table (see 003_add_customer_profile_tables.sql).
-- NULL means the prescription is for the account holder themself,
-- not for one of the family members on their account - the same
-- meaning "no family member" already has everywhere else in the
-- customer module.
--
-- ON DELETE SET NULL: if a family member is later removed, the
-- prescription is kept - it just falls back to meaning "the account
-- holder" instead of being deleted or blocked by the foreign key.
--
-- Safe to run again: the column and its foreign key are only added
-- if they are not already there.
-- ============================================================

USE pharmasync;

ALTER TABLE prescriptions
    ADD COLUMN IF NOT EXISTS family_member_id INT UNSIGNED NULL AFTER customer_id;

-- MariaDB has no "ADD CONSTRAINT IF NOT EXISTS", so guard it with a
-- quick existence check instead.
SET @fk_exists = (
    SELECT COUNT(*)
      FROM information_schema.TABLE_CONSTRAINTS
     WHERE CONSTRAINT_SCHEMA = DATABASE()
       AND TABLE_NAME = 'prescriptions'
       AND CONSTRAINT_NAME = 'fk_prescriptions_family_member'
);

SET @sql = IF(@fk_exists = 0,
    'ALTER TABLE prescriptions
        ADD CONSTRAINT fk_prescriptions_family_member
            FOREIGN KEY (family_member_id) REFERENCES family_members(id)
            ON DELETE SET NULL ON UPDATE CASCADE',
    'SELECT 1'
);

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

CREATE INDEX IF NOT EXISTS idx_prescriptions_family_member ON prescriptions (family_member_id);
