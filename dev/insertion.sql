INSERT INTO users (full_name, email, phone, address, password_hash, role, status)
VALUES (
    'System Admin',
    'admin@pharmasync.com',
    '0771234567',
    'Colombo, Sri Lanka',
    '$2y$10$nUH3IJURcRsXBja..NUmy.Kv9QcnFYNvYGRiDfKEXRrbEiIk6oRoW', 
    'Admin',
    'Active'
);

START TRANSACTION;

-- 1. Insert into core users table
INSERT INTO users (full_name, email, phone, address, password_hash, role, status)
VALUES (
    'Sarah Jenkins',
    'pharmacist@pharmasync.com',
    '0777654321',
    '45 Pharmacy Way, Kandy',
    '$2y$10$HjI9tw8/mtpy6iux8Cr/EuhhSA5uAhfON7NUDO4RrFcMSLFOMgBjS', -- Paste your generated hash string here
    'Pharmacist',
    'Active'
);

-- 2. Insert into pharmacist_profiles (uses the auto-generated user_id from above)
INSERT INTO pharmacist_profiles (user_id, license_number)
VALUES (
    LAST_INSERT_ID(),
    'PHARM-2026-0089' -- Required unique license number
);

COMMIT;