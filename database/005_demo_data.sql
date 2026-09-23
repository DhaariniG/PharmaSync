-- ============================================================
-- PharmaSync - migration 005: demo data for the Inventory Manager
-- and Pharmacist screens
-- Owner: Zaidh (integration)
--
-- Fills medicines, suppliers and stock_batches with realistic demo
-- rows, so the Inventory Manager list/CRUD, the low-stock and
-- expiry-alert screens, and the Pharmacist counter-sale screen all
-- have something real to show instead of an empty table.
--
-- The mix is deliberate, not random:
--   - 8 medicines across every existing medicine_categories row
--     (looked up by category_name below, never hardcoded, so this
--     still works if categories are renumbered)
--   - a mix of prescription and non-prescription medicines
--   - 3 suppliers
--   - stock_batches mostly healthy, but Amoxicillin and Cetirizine
--     are deliberately left BELOW their reorder_level, and the
--     Amoxicillin and Metformin batches expire within 30 days of
--     today (CURDATE()) - both alert screens have something to show.
--
-- Resets demo data, safe to re-run: it only ever touches medicines,
-- suppliers and stock_batches (never users, family_members,
-- addresses, prescriptions or anything else), and it deletes its own
-- previous rows in those three tables before re-inserting them.
-- physical_order_items, prescription_items, purchase_order_items and
-- stock_changes must be empty for this to run - a real sale, order
-- or prescription against this demo stock would block the delete.
-- ============================================================

USE pharmasync;

DELETE FROM stock_batches;
DELETE FROM medicines;
DELETE FROM suppliers;

ALTER TABLE stock_batches AUTO_INCREMENT = 1;
ALTER TABLE medicines      AUTO_INCREMENT = 1;
ALTER TABLE suppliers      AUTO_INCREMENT = 1;

-- ------------------------------------------------------------
-- SUPPLIERS
-- ------------------------------------------------------------
INSERT INTO suppliers (name, email, phone, address, status) VALUES
    ('MedSupply Lanka (Pvt) Ltd',        'orders@medsupplylanka.lk',  '+94 11 234 5001', '221 Negombo Road, Colombo 14',  'Active'),
    ('Ceylon Pharma Distributors',       'sales@ceylonpharma.lk',     '+94 11 234 5002', '78 Union Place, Colombo 02',    'Active'),
    ('Colombo Wholesale Pharmaceuticals','info@cwpharma.lk',         '+94 11 234 5003', '15 Nawam Mawatha, Colombo 02',  'Active');

-- ------------------------------------------------------------
-- MEDICINES
-- One per category id, looked up by name, plus two extra
-- (Antibiotic and Pain Relief get a second medicine each) to reach
-- eight. requires_prescription: 1 for the four that need one.
-- ------------------------------------------------------------
INSERT INTO medicines (category_id, name, generic_name, description, unit_price, requires_prescription, reorder_level, status) VALUES
    ((SELECT category_id FROM medicine_categories WHERE category_name = 'Pain Relief'),
        'Paracetamol 500mg', 'Paracetamol', 'General pain relief and fever reducer.', 25.00, 0, 50, 'Active'),
    ((SELECT category_id FROM medicine_categories WHERE category_name = 'Pain Relief'),
        'Ibuprofen 400mg', 'Ibuprofen', 'Anti-inflammatory pain relief.', 35.00, 0, 40, 'Active'),
    ((SELECT category_id FROM medicine_categories WHERE category_name = 'Antibiotic'),
        'Amoxicillin 500mg', 'Amoxicillin', 'Broad-spectrum antibiotic capsule.', 45.00, 1, 30, 'Active'),
    ((SELECT category_id FROM medicine_categories WHERE category_name = 'Antibiotic'),
        'Azithromycin 250mg', 'Azithromycin', 'Antibiotic for respiratory and skin infections.', 120.00, 1, 20, 'Active'),
    ((SELECT category_id FROM medicine_categories WHERE category_name = 'Antihistamine'),
        'Cetirizine 10mg', 'Cetirizine', 'Once-daily allergy relief tablet.', 18.00, 0, 25, 'Active'),
    ((SELECT category_id FROM medicine_categories WHERE category_name = 'Diabetes'),
        'Metformin 500mg', 'Metformin', 'First-line oral medicine for type 2 diabetes.', 15.00, 1, 40, 'Active'),
    ((SELECT category_id FROM medicine_categories WHERE category_name = 'Respiratory'),
        'Salbutamol Inhaler 100mcg', 'Salbutamol', 'Reliever inhaler for asthma and wheezing.', 450.00, 1, 15, 'Active'),
    ((SELECT category_id FROM medicine_categories WHERE category_name = 'Supplements'),
        'Vitamin C 1000mg', 'Ascorbic acid', 'Immune support supplement tablet.', 12.00, 0, 60, 'Active');

-- ------------------------------------------------------------
-- STOCK_BATCHES
-- Healthy stock for most medicines. Amoxicillin and Cetirizine are
-- deliberately below their reorder_level. Amoxicillin's batch and
-- one of Metformin's two batches expire within 30 days of today.
-- ------------------------------------------------------------
INSERT INTO stock_batches (medicine_id, supplier_id, batch_number, quantity, initial_quantity, manufacture_date, expiry_date, purchase_price, received_date, status) VALUES
    -- Paracetamol 500mg - healthy stock, far from expiry
    ((SELECT medicine_id FROM medicines WHERE name = 'Paracetamol 500mg'),
        (SELECT supplier_id FROM suppliers WHERE name = 'MedSupply Lanka (Pvt) Ltd'),
        'PCM-2026-01', 250, 250, DATE_SUB(CURDATE(), INTERVAL 60 DAY), DATE_ADD(CURDATE(), INTERVAL 300 DAY), 15.00, DATE_SUB(CURDATE(), INTERVAL 60 DAY), 'Available'),
    ((SELECT medicine_id FROM medicines WHERE name = 'Paracetamol 500mg'),
        (SELECT supplier_id FROM suppliers WHERE name = 'MedSupply Lanka (Pvt) Ltd'),
        'PCM-2026-02', 100, 100, DATE_SUB(CURDATE(), INTERVAL 10 DAY), DATE_ADD(CURDATE(), INTERVAL 200 DAY), 15.00, DATE_SUB(CURDATE(), INTERVAL 10 DAY), 'Available'),

    -- Ibuprofen 400mg - healthy stock
    ((SELECT medicine_id FROM medicines WHERE name = 'Ibuprofen 400mg'),
        (SELECT supplier_id FROM suppliers WHERE name = 'Ceylon Pharma Distributors'),
        'IBU-2026-01', 180, 180, DATE_SUB(CURDATE(), INTERVAL 45 DAY), DATE_ADD(CURDATE(), INTERVAL 250 DAY), 20.00, DATE_SUB(CURDATE(), INTERVAL 45 DAY), 'Available'),

    -- Amoxicillin 500mg - BELOW reorder_level (12 < 30) AND expires within 30 days
    ((SELECT medicine_id FROM medicines WHERE name = 'Amoxicillin 500mg'),
        (SELECT supplier_id FROM suppliers WHERE name = 'Ceylon Pharma Distributors'),
        'AMX-2026-01', 12, 60, DATE_SUB(CURDATE(), INTERVAL 150 DAY), DATE_ADD(CURDATE(), INTERVAL 20 DAY), 28.00, DATE_SUB(CURDATE(), INTERVAL 150 DAY), 'Available'),

    -- Azithromycin 250mg - healthy stock
    ((SELECT medicine_id FROM medicines WHERE name = 'Azithromycin 250mg'),
        (SELECT supplier_id FROM suppliers WHERE name = 'Colombo Wholesale Pharmaceuticals'),
        'AZI-2026-01', 150, 150, DATE_SUB(CURDATE(), INTERVAL 30 DAY), DATE_ADD(CURDATE(), INTERVAL 150 DAY), 80.00, DATE_SUB(CURDATE(), INTERVAL 30 DAY), 'Available'),

    -- Cetirizine 10mg - BELOW reorder_level (8 < 25)
    ((SELECT medicine_id FROM medicines WHERE name = 'Cetirizine 10mg'),
        (SELECT supplier_id FROM suppliers WHERE name = 'MedSupply Lanka (Pvt) Ltd'),
        'CTZ-2026-01', 8, 40, DATE_SUB(CURDATE(), INTERVAL 90 DAY), DATE_ADD(CURDATE(), INTERVAL 400 DAY), 10.00, DATE_SUB(CURDATE(), INTERVAL 90 DAY), 'Available'),

    -- Metformin 500mg - healthy total stock, but one batch expires within 30 days
    ((SELECT medicine_id FROM medicines WHERE name = 'Metformin 500mg'),
        (SELECT supplier_id FROM suppliers WHERE name = 'Colombo Wholesale Pharmaceuticals'),
        'MET-2026-01', 200, 200, DATE_SUB(CURDATE(), INTERVAL 100 DAY), DATE_ADD(CURDATE(), INTERVAL 10 DAY), 8.00, DATE_SUB(CURDATE(), INTERVAL 100 DAY), 'Available'),
    ((SELECT medicine_id FROM medicines WHERE name = 'Metformin 500mg'),
        (SELECT supplier_id FROM suppliers WHERE name = 'Colombo Wholesale Pharmaceuticals'),
        'MET-2026-02', 50, 50, DATE_SUB(CURDATE(), INTERVAL 20 DAY), DATE_ADD(CURDATE(), INTERVAL 300 DAY), 8.00, DATE_SUB(CURDATE(), INTERVAL 20 DAY), 'Available'),

    -- Salbutamol Inhaler 100mcg - healthy stock
    ((SELECT medicine_id FROM medicines WHERE name = 'Salbutamol Inhaler 100mcg'),
        (SELECT supplier_id FROM suppliers WHERE name = 'MedSupply Lanka (Pvt) Ltd'),
        'SAL-2026-01', 60, 60, DATE_SUB(CURDATE(), INTERVAL 40 DAY), DATE_ADD(CURDATE(), INTERVAL 180 DAY), 350.00, DATE_SUB(CURDATE(), INTERVAL 40 DAY), 'Available'),

    -- Vitamin C 1000mg - healthy stock, plenty for a demo counter sale
    ((SELECT medicine_id FROM medicines WHERE name = 'Vitamin C 1000mg'),
        (SELECT supplier_id FROM suppliers WHERE name = 'Ceylon Pharma Distributors'),
        'VTC-2026-01', 500, 500, DATE_SUB(CURDATE(), INTERVAL 20 DAY), DATE_ADD(CURDATE(), INTERVAL 365 DAY), 8.00, DATE_SUB(CURDATE(), INTERVAL 20 DAY), 'Available');
