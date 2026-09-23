-- ============================================================
-- PharmaSync - migration 003: customer CRUD tables
-- Owner: Mithun (customer module)
--
-- The tables behind the customer module's CRUD:
--   family_members              people you order medicine for
--   family_member_health_flags  each member's allergies / conditions
--   addresses                   saved delivery addresses
--
-- IMPORT ON ITS OWN (interim):
--   phpMyAdmin > Import > this file. It creates the pharmasync
--   database and the two account tables the CRUD tables point at
--   (users, customer_profiles) if they are not there yet, plus the
--   demo accounts. Then set CUSTOMER_DB_ENABLED to true in
--   config/config.php. Everything else keeps running on sample data.
--
-- OR AS PART OF THE FULL SCHEMA (later):
--   001_schema.sql, 001a_seed_demo_users.sql, 002_..., then this.
--   users/customer_profiles already exist, so those parts are
--   skipped and nothing is duplicated.
--
-- Safe to run again: it rebuilds the three CRUD tables with fresh
-- demo rows. It never drops users or customer_profiles.
-- ============================================================

CREATE DATABASE IF NOT EXISTS pharmasync
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE pharmasync;

-- ------------------------------------------------------------
-- Accounts the CRUD tables belong to.
-- Same definitions as 001_schema.sql. IF NOT EXISTS means the
-- group's schema always wins when it has already been imported.
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    user_id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name       VARCHAR(150)    NOT NULL,
    email           VARCHAR(150)    NOT NULL UNIQUE,
    phone           VARCHAR(20)     NOT NULL,
    address         VARCHAR(255)    NULL,
    password_hash   VARCHAR(255)    NOT NULL,
    role            ENUM('Customer','Pharmacist','Admin','Inventory_Manager','Delivery_Partner') NOT NULL,
    last_login      DATETIME        NULL,
    status          ENUM('Active','Inactive','Suspended') NOT NULL DEFAULT 'Active',
    created_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_users_role (role),
    INDEX idx_users_status (status)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS customer_profiles (
    user_id             INT UNSIGNED PRIMARY KEY,
    date_of_birth       DATE            NULL,
    allergies           TEXT            NULL,
    medical_conditions  TEXT            NULL,
    CONSTRAINT fk_customer_profiles_user
        FOREIGN KEY (user_id) REFERENCES users(user_id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Demo accounts - same as 001a_seed_demo_users.sql. Password: Demo@1234
-- INSERT IGNORE skips any that already exist.
INSERT IGNORE INTO users (user_id, full_name, email, phone, address, password_hash, role, status) VALUES
    (1, 'Nadeesha Perera',     'customer@pharmasync.com',   '0771234567', '12 Galle Road, Colombo 03', '$2y$10$scuPT.BT4687rP9bNweFsOZ7uAVIFu0dRHmTh4uETwkkY.grU0Kri', 'Customer',          'Active'),
    (2, 'Sarah Jenkins',       'pharmacist@pharmasync.com', '0777654321', '45 Pharmacy Way, Kandy',    '$2y$10$scuPT.BT4687rP9bNweFsOZ7uAVIFu0dRHmTh4uETwkkY.grU0Kri', 'Pharmacist',        'Active'),
    (3, 'System Admin',        'admin@pharmasync.com',      '0771112233', 'Colombo, Sri Lanka',        '$2y$10$scuPT.BT4687rP9bNweFsOZ7uAVIFu0dRHmTh4uETwkkY.grU0Kri', 'Admin',             'Active'),
    (4, 'Tharindu Jayasuriya', 'inventory@pharmasync.com',  '0772223344', NULL,                        '$2y$10$scuPT.BT4687rP9bNweFsOZ7uAVIFu0dRHmTh4uETwkkY.grU0Kri', 'Inventory_Manager', 'Active'),
    (5, 'Kasun Bandara',       'delivery@pharmasync.com',   '0773334455', NULL,                        '$2y$10$scuPT.BT4687rP9bNweFsOZ7uAVIFu0dRHmTh4uETwkkY.grU0Kri', 'Delivery_Partner',  'Active');

INSERT IGNORE INTO customer_profiles (user_id, date_of_birth) VALUES
    (1, '1990-05-14');

-- ------------------------------------------------------------
-- Rebuild the CRUD tables (children first, because of the
-- foreign keys).
-- ------------------------------------------------------------
DROP TABLE IF EXISTS family_member_health_flags;
DROP TABLE IF EXISTS family_members;
DROP TABLE IF EXISTS addresses;

-- ------------------------------------------------------------
-- FAMILY_MEMBERS
-- People an account holder orders medicine for. One row per
-- account is 'Self' - the account holder's own patient record,
-- which the app never deletes. For a new account the app creates
-- it automatically the first time the profile is opened.
-- ------------------------------------------------------------
CREATE TABLE family_members (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id       INT UNSIGNED NOT NULL,
    name          VARCHAR(80)  NOT NULL,
    relationship  ENUM('Self','Father','Mother','Spouse','Child','Sibling','Other')
                      NOT NULL DEFAULT 'Other',
    date_of_birth DATE         NULL,
    notes         VARCHAR(255) NULL,
    created_at    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
                      ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_family_members_user
        FOREIGN KEY (user_id) REFERENCES users(user_id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_family_members_user (user_id)
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- FAMILY_MEMBER_HEALTH_FLAGS
-- One allergy or chronic condition for ONE family member, so the
-- pharmacist can check a prescription against the person it was
-- written for. Deleting a member deletes their flags (CASCADE).
-- The unique key stops the same flag being added twice.
-- ------------------------------------------------------------
CREATE TABLE family_member_health_flags (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    member_id  INT UNSIGNED NOT NULL,
    type       ENUM('allergy','condition') NOT NULL,
    value      VARCHAR(120) NOT NULL,
    created_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_health_flags_member
        FOREIGN KEY (member_id) REFERENCES family_members(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE KEY uq_member_flag (member_id, type, value),
    INDEX idx_health_flags_member (member_id)
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- ADDRESSES
-- Saved delivery addresses. is_default marks the one checkout
-- pre-selects; the app keeps exactly one default per customer
-- and promotes another if the default is deleted. Orders store
-- their address as text, so deleting a row here never changes
-- order history.
-- ------------------------------------------------------------
CREATE TABLE addresses (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id    INT UNSIGNED NOT NULL,
    label      VARCHAR(50)  NOT NULL DEFAULT 'Other',
    line1      VARCHAR(255) NOT NULL,
    city       VARCHAR(100) NOT NULL,
    postcode   VARCHAR(20)  NULL,
    phone      VARCHAR(30)  NULL,
    is_default TINYINT(1)   NOT NULL DEFAULT 0,
    created_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_addresses_user
        FOREIGN KEY (user_id) REFERENCES users(user_id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_addresses_user (user_id, is_default)
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Demo rows for the demo customer (user_id 1).
-- ------------------------------------------------------------
INSERT INTO family_members (id, user_id, name, relationship, date_of_birth, notes) VALUES
    (1, 1, 'Nadeesha Perera',  'Self',   '1990-05-14', ''),
    (2, 1, 'Kamal Wijesinghe', 'Father', '1958-02-20', 'Takes Metformin for diabetes.');

INSERT INTO family_member_health_flags (member_id, type, value) VALUES
    (1, 'allergy',   'Penicillin'),
    (1, 'allergy',   'Peanuts'),
    (1, 'condition', 'Hypertension'),
    (2, 'condition', 'Type 2 Diabetes'),
    (2, 'allergy',   'Sulfa drugs');

INSERT INTO addresses (user_id, label, line1, city, postcode, phone, is_default) VALUES
    (1, 'Home', '12 Galle Road, Colombo 03',       'Colombo',      '00300', '+94 77 123 4567', 1),
    (1, 'Work', 'No 10, Kandy Road, Kiribathgoda', 'Kiribathgoda', '11600', '+94 77 123 4567', 0);
