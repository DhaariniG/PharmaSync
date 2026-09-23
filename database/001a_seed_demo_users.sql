-- ============================================================
-- PharmaSync - demo accounts
--
-- One account per role. Every password is: Demo@1234
--
-- Run AFTER 001_schema.sql and BEFORE 003_add_customer_profile_tables.sql
-- (003's demo family members and addresses belong to user_id 1, so that
-- user must exist first). The file name sorts between 001 and 002 on
-- purpose.
--
-- These are the same accounts app/models/User.php writes to
-- storage/users.json while DB_ENABLED is false, so logins do not change
-- when the database is switched on.
--
-- Delete this file before the final submission if the group imports
-- real accounts instead.
-- ============================================================

USE pharmasync;

INSERT INTO users (user_id, full_name, email, phone, address, password_hash, role, status) VALUES
    (1, 'Nadeesha Perera',     'customer@pharmasync.com',   '0771234567', '12 Galle Road, Colombo 03', '$2y$10$scuPT.BT4687rP9bNweFsOZ7uAVIFu0dRHmTh4uETwkkY.grU0Kri', 'Customer',          'Active'),
    (2, 'Sarah Jenkins',       'pharmacist@pharmasync.com', '0777654321', '45 Pharmacy Way, Kandy',    '$2y$10$scuPT.BT4687rP9bNweFsOZ7uAVIFu0dRHmTh4uETwkkY.grU0Kri', 'Pharmacist',        'Active'),
    (3, 'System Admin',        'admin@pharmasync.com',      '0771112233', 'Colombo, Sri Lanka',        '$2y$10$scuPT.BT4687rP9bNweFsOZ7uAVIFu0dRHmTh4uETwkkY.grU0Kri', 'Admin',             'Active'),
    (4, 'Tharindu Jayasuriya', 'inventory@pharmasync.com',  '0772223344', NULL,                        '$2y$10$scuPT.BT4687rP9bNweFsOZ7uAVIFu0dRHmTh4uETwkkY.grU0Kri', 'Inventory_Manager', 'Active'),
    (5, 'Kasun Bandara',       'delivery@pharmasync.com',   '0773334455', NULL,                        '$2y$10$scuPT.BT4687rP9bNweFsOZ7uAVIFu0dRHmTh4uETwkkY.grU0Kri', 'Delivery_Partner',  'Active');

INSERT INTO customer_profiles (user_id, date_of_birth) VALUES
    (1, '1990-05-14');

INSERT INTO pharmacist_profiles (user_id, license_number) VALUES
    (2, 'PHARM-2026-0089');

INSERT INTO delivery_partner_profiles (user_id, vehicle_type, vehicle_number, availability_status) VALUES
    (5, 'Motorcycle', 'WP BAX-1234', 'Available');
