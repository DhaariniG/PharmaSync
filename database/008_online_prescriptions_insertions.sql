-- ============================================================
-- PharmaSync - migration 008: prescription quantity/frequency and
-- sample Pharmacist data
-- Owner: Pharmacist module
--
-- Gives the Pharmacist queue, history and counter-sale screens real
-- rows to show:
--   - two new columns on physical_order_items: prescribed_quantity
--     (how many the prescription asked for) and frequency (how often
--     to take it, e.g. 'TDS (3x daily)'). Both nullable, so existing
--     sales are unaffected.
--   - 3 test customers (user_id 10, 11, 12), password Demo@1234 like
--     every demo account, each with a customer_profiles row
--   - 2 family members for those customers
--   - 6 prescriptions: 4 Pending (the Pharmacist queue) and 2 already
--     reviewed (the Pharmacist history)
--   - 2 online orders made from the reviewed prescriptions, each with
--     a payment
--   - 1 counter sale (physical order #4) with 2 items and a payment
--   - renames the demo pharmacist (user_id 2) to 'Dhaariniii'
--
-- Import after 001-006. Uses fixed IDs, and medicine_id 1 / 5 and
-- batch_id 1 / 6 from 005_demo_data.sql. Sale #4 uses those two
-- medicines' unit_price from 005 (25.00 and 18.00).
--
-- Safe to run again: the ADD COLUMN lines use IF NOT EXISTS, and
-- STEP 0 deletes only this file's own rows (by their fixed IDs) before
-- inserting them again. The test users and their profiles are only
-- deleted when the ID and the email both match, so a real account that
-- happens to have user_id 10-12 is never touched. Deleting users 10-12
-- also removes anything MySQL cascades from them (their cart, saved
-- addresses and notifications).
-- ============================================================

USE pharmasync;

-- =========================================================
-- STEP 1: Schema Updates (Add frequency and prescribed quantity)
-- =========================================================
ALTER TABLE physical_order_items
ADD COLUMN IF NOT EXISTS prescribed_quantity INT(10) UNSIGNED DEFAULT NULL AFTER quantity;

ALTER TABLE physical_order_items
ADD COLUMN IF NOT EXISTS frequency VARCHAR(50) DEFAULT NULL AFTER prescribed_quantity;


-- =========================================================
-- STEP 0: Remove this file's own rows, so it can be imported again
-- Children first, parents last, so no foreign key blocks a delete.
-- =========================================================
DELETE FROM payments             WHERE payment_id IN (301, 302, 401);
DELETE FROM physical_order_items WHERE item_id IN (41, 42);
DELETE FROM physical_orders      WHERE order_id = 4;
DELETE FROM online_orders        WHERE order_id IN (201, 202);
DELETE FROM prescriptions        WHERE prescription_id BETWEEN 101 AND 106;
DELETE FROM family_members       WHERE id IN (10, 11);
DELETE FROM customer_profiles    WHERE user_id IN (
    SELECT user_id FROM users
     WHERE (user_id, email) IN ((10, 'dilani.silva@gmail.com'),
                                (11, 'kavinda.perera@gmail.com'),
                                (12, 'anusha.f@gmail.com')));
DELETE FROM users
 WHERE (user_id, email) IN ((10, 'dilani.silva@gmail.com'),
                            (11, 'kavinda.perera@gmail.com'),
                            (12, 'anusha.f@gmail.com'));


-- =========================================================
-- STEP 2: Insert Test Customers
-- =========================================================
INSERT INTO `users` (`user_id`, `full_name`, `email`, `phone`, `address`, `password_hash`, `role`, `status`) VALUES
(10, 'Dilani Silva', 'dilani.silva@gmail.com', '0719876543', '15 Galle Road, Colombo 03', '$2y$10$scuPT.BT4687rP9bNweFsOZ7uAVIFu0dRHmTh4uETwkkY.grU0Kri', 'Customer', 'Active'),
(11, 'Kavinda Perera', 'kavinda.perera@gmail.com', '0774567890', '42 Peradeniya Road, Kandy', '$2y$10$scuPT.BT4687rP9bNweFsOZ7uAVIFu0dRHmTh4uETwkkY.grU0Kri', 'Customer', 'Active'),
(12, 'Anusha Fernando', 'anusha.f@gmail.com', '0751239876', '88 Main Street, Negombo', '$2y$10$scuPT.BT4687rP9bNweFsOZ7uAVIFu0dRHmTh4uETwkkY.grU0Kri', 'Customer', 'Active');

INSERT INTO `customer_profiles` (`user_id`, `date_of_birth`, `allergies`, `medical_conditions`) VALUES
(10, '1992-08-14', 'Penicillin, Latex', 'Hypertension'),
(11, '1988-03-22', 'Sulfa drugs', 'Asthma'),
(12, '1995-11-05', 'None', 'None');


-- =========================================================
-- STEP 3: Insert Family Members
-- =========================================================
INSERT INTO `family_members` (`id`, `user_id`, `name`, `relationship`, `date_of_birth`, `notes`) VALUES
(10, 10, 'Saman Silva', 'Father', '1960-05-10', 'Requires daily Metformin'),
(11, 11, 'Malkanthi Perera', 'Mother', '1965-09-18', 'Asthma patient');


-- =========================================================
-- STEP 4: Insert Prescriptions (Queue & History data)
-- =========================================================
INSERT INTO `prescriptions` (`prescription_id`, `customer_id`, `family_member_id`, `pharmacist_id`, `image_path`, `pharmacist_notes`, `status`, `uploaded_at`, `reviewed_at`) VALUES
-- Pending Queue (4 Items)
(101, 10, NULL, NULL, '/uploads/prescriptions/rx_101.jpg', NULL, 'Pending', '2026-09-26 09:15:00', NULL),
(102, 10, 10,   NULL, '/uploads/prescriptions/rx_102.jpg', NULL, 'Pending', '2026-09-26 10:30:00', NULL),
(103, 11, NULL, NULL, '/uploads/prescriptions/rx_103.jpg', NULL, 'Pending', '2026-09-26 11:45:00', NULL),
(104, 12, NULL, NULL, '/uploads/prescriptions/rx_104.jpg', NULL, 'Pending', '2026-09-26 13:10:00', NULL),

-- History Items (2 Online Orders)
(105, 1,  1,    2,    '/uploads/prescriptions/rx_105.jpg', 'Dispensed as per instructions', 'Approved', '2026-09-25 14:20:00', '2026-09-25 14:35:00'),
(106, 11, 11,   2,    '/uploads/prescriptions/rx_106.jpg', 'Includes inhaler refill', 'Prepared', '2026-09-25 16:00:00', '2026-09-25 16:15:00');


-- =========================================================
-- STEP 5: Insert Online Orders & Payments (For History)
-- =========================================================
INSERT INTO `online_orders` (`order_id`, `customer_id`, `prescription_id`, `order_reference`, `delivery_method`, `delivery_address`, `status`, `subtotal`, `delivery_fee`, `tax`, `total_amount`, `created_at`) VALUES
(201, 1,  105, 'ORD-2026-105', 'Delivery', '12 Galle Road, Colombo 03', 'Dispatched', 1200.00, 250.00, 0.00, 1450.00, '2026-09-25 14:40:00'),
(202, 11, 106, 'ORD-2026-106', 'Pickup',   NULL,                        'Ready_for_pickup', 850.00, 0.00, 0.00, 850.00, '2026-09-25 16:20:00');

INSERT INTO `payments` (`payment_id`, `online_order_id`, `physical_order_id`, `payment_method`, `transaction_reference`, `collected_by`, `amount`, `payment_status`, `payment_date`) VALUES
(301, 201, NULL, 'Online_Gateway', 'PAY-CARD-99812', NULL, 1450.00, 'Successful', '2026-09-25 14:42:00'),
(302, 202, NULL, 'Cash',           'PAY-CASH-1002',  2,    850.00,  'Successful', '2026-09-25 16:20:00');


-- =========================================================
-- STEP 6: Insert Physical Order ID #4 (Fixing Details 404)
-- =========================================================
INSERT INTO `physical_orders` (`order_id`, `pharmacist_id`, `customer_id`, `customer_name`, `total_amount`, `notes`, `sale_date`) VALUES
(4, 2, 1, 'Nadeesha Perera', 340.00, 'Walk-in prescription sale', '2026-09-26 14:30:00');

INSERT INTO `physical_order_items` (`item_id`, `order_id`, `medicine_id`, `batch_id`, `quantity`, `prescribed_quantity`, `frequency`, `unit_price`, `subtotal`) VALUES
(41, 4, 1, 1, 10, 10, 'TDS (3x daily)', 25.00, 250.00),
(42, 4, 5, 6, 5,  5,  'OD (Night)',    18.00, 90.00);

INSERT INTO `payments` (`payment_id`, `online_order_id`, `physical_order_id`, `payment_method`, `transaction_reference`, `collected_by`, `amount`, `payment_status`, `payment_date`) VALUES
(401, NULL, 4, 'Cash', 'POS-CASH-004', 2, 340.00, 'Successful', '2026-09-26 14:30:00');


-- =========================================================
-- STEP 7: Changing name of the Pharmacist
-- =========================================================
UPDATE `users`
SET `full_name` = 'Dhaariniii'
WHERE `user_id` = 2;
