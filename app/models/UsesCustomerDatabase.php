<?php
/**
 * UsesCustomerDatabase - lets a model use MySQL on its own.
 *
 * The rest of the project stays on sample data until DB_ENABLED is true.
 * CUSTOMER_DB_ENABLED (config/config.php) switches on MySQL for just the
 * customer CRUD and the accounts it belongs to:
 *
 *   User          users, customer_profiles
 *   FamilyMember  family_members, family_member_health_flags
 *   Address       addresses
 *
 * Tables: database/003_add_customer_profile_tables.sql (imports on its own).
 *
 * It overrides Model::db() and Model::hasDb(), so every query helper in
 * Model (fetchAll, insertRow, ...) works unchanged. Once the whole project
 * is on MySQL (DB_ENABLED true) this trait changes nothing.
 */
trait UsesCustomerDatabase
{
    protected function hasDb(): bool
    {
        return DB_ENABLED || CUSTOMER_DB_ENABLED;
    }

    protected function db(): ?PDO
    {
        return $this->hasDb() ? Database::getConnection() : null;
    }
}
