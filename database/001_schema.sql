CREATE DATABASE IF NOT EXISTS pharmasync
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE pharmasync;

SET FOREIGN_KEY_CHECKS = 0;

-- 1. USERS (merged Customers/Pharmacists/Admins/Inventory_Managers/Delivery_Partners)
DROP TABLE IF EXISTS users;
CREATE TABLE users (
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

-- 2. CUSTOMER_PROFILES (extends Users where role = 'Customer')
DROP TABLE IF EXISTS customer_profiles;
CREATE TABLE customer_profiles (
    user_id             INT UNSIGNED PRIMARY KEY,
    date_of_birth       DATE            NULL,
    allergies           TEXT            NULL,
    medical_conditions  TEXT            NULL,
    CONSTRAINT fk_customer_profiles_user
        FOREIGN KEY (user_id) REFERENCES users(user_id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- 3. PHARMACIST_PROFILES (extends Users where role = 'Pharmacist')
DROP TABLE IF EXISTS pharmacist_profiles;
CREATE TABLE pharmacist_profiles (
    user_id         INT UNSIGNED PRIMARY KEY,
    license_number  VARCHAR(50)     NOT NULL UNIQUE,
    CONSTRAINT fk_pharmacist_profiles_user
        FOREIGN KEY (user_id) REFERENCES users(user_id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- 4. DELIVERY_PARTNER_PROFILES (extends Users where role = 'Delivery_Partner')
DROP TABLE IF EXISTS delivery_partner_profiles;
CREATE TABLE delivery_partner_profiles (
    user_id             INT UNSIGNED PRIMARY KEY,
    vehicle_type        ENUM('Motorcycle','Three_wheeler','Car','Van','Other') NOT NULL,
    vehicle_number      VARCHAR(20)     NOT NULL,
    availability_status ENUM('Available','Unavailable','On_delivery') NOT NULL DEFAULT 'Unavailable',
    CONSTRAINT fk_delivery_partner_profiles_user
        FOREIGN KEY (user_id) REFERENCES users(user_id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Admins and Inventory_Managers have no extra attributes,
-- so they rely on the Users table alone (role = 'Admin' / 'Inventory_Manager').

-- 5. MEDICINE_CATEGORIES
DROP TABLE IF EXISTS medicine_categories;
CREATE TABLE medicine_categories (
    category_id     INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_name   VARCHAR(100)    NOT NULL UNIQUE,
    description     TEXT            NULL
) ENGINE=InnoDB;

-- 6. MEDICINES
DROP TABLE IF EXISTS medicines;
CREATE TABLE medicines (
    medicine_id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id             INT UNSIGNED    NOT NULL,
    name                    VARCHAR(150)    NOT NULL,
    generic_name            VARCHAR(150)    NULL,
    description             TEXT            NULL,
    unit_price              DECIMAL(10,2)   NOT NULL,
    requires_prescription   BOOLEAN         NOT NULL DEFAULT FALSE,
    reorder_level           INT UNSIGNED    NOT NULL DEFAULT 0,
    image                   VARCHAR(255)    NULL,
    status                  ENUM('Active','Inactive','Discontinued') NOT NULL DEFAULT 'Active',
    created_at              DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_medicines_category
        FOREIGN KEY (category_id) REFERENCES medicine_categories(category_id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_medicines_status (status),
    INDEX idx_medicines_name (name)
) ENGINE=InnoDB;

-- 7. DRUG_INTERACTIONS
DROP TABLE IF EXISTS drug_interactions;
CREATE TABLE drug_interactions (
    interaction_id  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    medicine1_id    INT UNSIGNED    NOT NULL,
    medicine2_id    INT UNSIGNED    NOT NULL,
    severity        ENUM('Low','Moderate','High','Critical') NOT NULL,
    description     TEXT            NULL,
    CONSTRAINT fk_interactions_medicine1
        FOREIGN KEY (medicine1_id) REFERENCES medicines(medicine_id)
        ON DELETE CASCADE ON UPDATE RESTRICT,
    CONSTRAINT fk_interactions_medicine2
        FOREIGN KEY (medicine2_id) REFERENCES medicines(medicine_id)
        ON DELETE CASCADE ON UPDATE RESTRICT,
    CONSTRAINT chk_interactions_distinct_medicines
        CHECK (medicine1_id <> medicine2_id),
    UNIQUE KEY uq_interaction_pair (medicine1_id, medicine2_id)
) ENGINE=InnoDB;

-- 8. SUPPLIERS
DROP TABLE IF EXISTS suppliers;
CREATE TABLE suppliers (
    supplier_id     INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(150)    NOT NULL,
    email           VARCHAR(150)    NULL,
    phone           VARCHAR(20)     NOT NULL,
    address         VARCHAR(255)    NULL,
    status          ENUM('Active','Inactive') NOT NULL DEFAULT 'Active',
    created_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 9. PURCHASE_ORDERS
DROP TABLE IF EXISTS purchase_orders;
CREATE TABLE purchase_orders (
    purchase_id     INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    supplier_id     INT UNSIGNED    NOT NULL,
    created_by      INT UNSIGNED    NOT NULL,
    total_amount    DECIMAL(12,2)   NOT NULL DEFAULT 0.00, -- denormalized: SUM(purchase_order_items.subtotal)
    status          ENUM('Pending','Received','Cancelled') NOT NULL DEFAULT 'Pending',
    created_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_purchase_orders_supplier
        FOREIGN KEY (supplier_id) REFERENCES suppliers(supplier_id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_purchase_orders_created_by
        FOREIGN KEY (created_by) REFERENCES users(user_id)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

-- 10. PURCHASE_ORDER_ITEMS
DROP TABLE IF EXISTS purchase_order_items;
CREATE TABLE purchase_order_items (
    item_id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    purchase_id         INT UNSIGNED    NOT NULL,
    medicine_id         INT UNSIGNED    NOT NULL,
    quantity_ordered    INT UNSIGNED    NOT NULL,
    unit_price          DECIMAL(10,2)   NOT NULL,
    subtotal            DECIMAL(12,2)   NOT NULL, -- denormalized: quantity_ordered * unit_price
    CONSTRAINT fk_poi_purchase
        FOREIGN KEY (purchase_id) REFERENCES purchase_orders(purchase_id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_poi_medicine
        FOREIGN KEY (medicine_id) REFERENCES medicines(medicine_id)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

-- 11. STOCK_BATCHES
DROP TABLE IF EXISTS stock_batches;
CREATE TABLE stock_batches (
    batch_id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    medicine_id         INT UNSIGNED    NOT NULL,
    supplier_id         INT UNSIGNED    NOT NULL,
    batch_number        VARCHAR(50)     NOT NULL,
    quantity            INT UNSIGNED    NOT NULL DEFAULT 0,
    initial_quantity    INT UNSIGNED    NOT NULL,
    manufacture_date    DATE            NULL,
    expiry_date         DATE            NOT NULL,
    purchase_price      DECIMAL(10,2)   NOT NULL,
    received_date       DATE            NOT NULL,
    status              ENUM('Available','Expired','Depleted') NOT NULL DEFAULT 'Available',
    CONSTRAINT fk_batches_medicine
        FOREIGN KEY (medicine_id) REFERENCES medicines(medicine_id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_batches_supplier
        FOREIGN KEY (supplier_id) REFERENCES suppliers(supplier_id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_batches_expiry (expiry_date),   -- supports FEFO allocation queries
    INDEX idx_batches_status (status)
) ENGINE=InnoDB;

-- 12. STOCK_CHANGES 
DROP TABLE IF EXISTS stock_changes;
CREATE TABLE stock_changes (
    change_id       INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    batch_id        INT UNSIGNED    NOT NULL,
    user_id         INT UNSIGNED    NOT NULL,
    change_type     ENUM('Purchase','Sale','Adjustment','Expired','Return','Other') NOT NULL,
    quantity        INT             NOT NULL, -- signed: positive = stock in, negative = stock out
    reference_id    INT UNSIGNED    NULL,     -- polymorphic: order_id / purchase_id depending on reference_type
    reference_type  ENUM('Online_Order','Physical_Order','Purchase_Order') NULL,
    notes           TEXT            NULL,
    created_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_stock_changes_batch
        FOREIGN KEY (batch_id) REFERENCES stock_batches(batch_id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_stock_changes_user
        FOREIGN KEY (user_id) REFERENCES users(user_id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_stock_changes_reference (reference_type, reference_id)
) ENGINE=InnoDB;


-- 13. PRESCRIPTIONS
DROP TABLE IF EXISTS prescriptions;
CREATE TABLE prescriptions (
    prescription_id     INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    customer_id         INT UNSIGNED    NOT NULL,
    pharmacist_id       INT UNSIGNED    NULL,
    image_path          VARCHAR(255)    NOT NULL,
    pharmacist_notes    TEXT            NULL,
    status              ENUM('Pending','Approved','Prepared','Rejected') NOT NULL DEFAULT 'Pending',
    uploaded_at         DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    reviewed_at         DATETIME        NULL,
    customer_confirmed_at DATETIME      NULL, -- stamped when customer confirms prepared items into their cart/order
    CONSTRAINT fk_prescriptions_customer
        FOREIGN KEY (customer_id) REFERENCES users(user_id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_prescriptions_pharmacist
        FOREIGN KEY (pharmacist_id) REFERENCES users(user_id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    INDEX idx_prescriptions_status (status)
) ENGINE=InnoDB;

-- 14. PRESCRIPTION_ITEMS
DROP TABLE IF EXISTS prescription_items;
CREATE TABLE prescription_items (
    prescription_item_id   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    prescription_id        INT UNSIGNED    NOT NULL,
    medicine_id             INT UNSIGNED    NOT NULL,
    prescribed_quantity     INT UNSIGNED    NOT NULL,
    prescribed_dosage       VARCHAR(100)    NULL,
    instructions             TEXT            NULL,
    fulfillment_status      ENUM('Pending','Available','Substitute_suggested','Substitute_approved','Awaiting_restock')
                             NOT NULL DEFAULT 'Pending',
    substitute_medicine_id  INT UNSIGNED    NULL, -- medicine the pharmacist suggests in place of medicine_id, if out of stock
    substitute_decision     ENUM('Pending','Approved','Wait_for_restock') NULL, -- customer's response to the suggested substitute
    CONSTRAINT fk_prescription_items_prescription
        FOREIGN KEY (prescription_id) REFERENCES prescriptions(prescription_id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_prescription_items_medicine
        FOREIGN KEY (medicine_id) REFERENCES medicines(medicine_id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_prescription_items_substitute
        FOREIGN KEY (substitute_medicine_id) REFERENCES medicines(medicine_id)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;


-- 15. ONLINE_ORDERS
DROP TABLE IF EXISTS online_orders;
CREATE TABLE online_orders (
    order_id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    customer_id         INT UNSIGNED    NOT NULL,
    prescription_id     INT UNSIGNED    NULL,
    order_reference     VARCHAR(50)     NOT NULL UNIQUE,
    delivery_method     ENUM('Pickup','Delivery') NOT NULL,
    delivery_address    VARCHAR(255)    NULL, -- intentionally separate from users.address (delivery-time override)
    status              ENUM('Pending_review','Approved','Preparing','Ready_for_pickup','Dispatched','Delivered','Rejected') NOT NULL DEFAULT 'Pending_review',
    subtotal            DECIMAL(12,2)   NOT NULL DEFAULT 0.00, -- denormalized: SUM(online_order_items.subtotal)
    delivery_fee        DECIMAL(10,2)   NOT NULL DEFAULT 0.00,
    tax                 DECIMAL(10,2)   NOT NULL DEFAULT 0.00,
    total_amount        DECIMAL(12,2)   NOT NULL DEFAULT 0.00, -- denormalized: subtotal + delivery_fee + tax
    notes               TEXT            NULL,
    created_at          DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_online_orders_customer
        FOREIGN KEY (customer_id) REFERENCES users(user_id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_online_orders_prescription
        FOREIGN KEY (prescription_id) REFERENCES prescriptions(prescription_id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    INDEX idx_online_orders_status (status)
) ENGINE=InnoDB;

-- 16. ONLINE_ORDER_ITEMS
DROP TABLE IF EXISTS online_order_items;
CREATE TABLE online_order_items (
    item_id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id        INT UNSIGNED    NOT NULL,
    medicine_id     INT UNSIGNED    NOT NULL,
    batch_id        INT UNSIGNED    NOT NULL,
    quantity        INT UNSIGNED    NOT NULL,
    unit_price      DECIMAL(10,2)   NOT NULL, -- price snapshot at time of sale
    subtotal        DECIMAL(12,2)   NOT NULL, -- denormalized: quantity * unit_price
    CONSTRAINT fk_ooi_order
        FOREIGN KEY (order_id) REFERENCES online_orders(order_id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_ooi_medicine
        FOREIGN KEY (medicine_id) REFERENCES medicines(medicine_id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_ooi_batch
        FOREIGN KEY (batch_id) REFERENCES stock_batches(batch_id)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

-- 17. PHYSICAL_ORDERS
DROP TABLE IF EXISTS physical_orders;
CREATE TABLE physical_orders (
    order_id        INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pharmacist_id   INT UNSIGNED    NOT NULL,
    customer_id     INT UNSIGNED    NULL,
    customer_name   VARCHAR(150)    NULL, -- used only when customer_id IS NULL (walk-in sale)
    total_amount    DECIMAL(12,2)   NOT NULL DEFAULT 0.00,
    notes           TEXT            NULL,
    sale_date       DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_physical_orders_pharmacist
        FOREIGN KEY (pharmacist_id) REFERENCES users(user_id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_physical_orders_customer
        FOREIGN KEY (customer_id) REFERENCES users(user_id)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

-- 18. PHYSICAL_ORDER_ITEMS
DROP TABLE IF EXISTS physical_order_items;
CREATE TABLE physical_order_items (
    item_id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id        INT UNSIGNED    NOT NULL,
    medicine_id     INT UNSIGNED    NOT NULL,
    batch_id        INT UNSIGNED    NOT NULL,
    quantity        INT UNSIGNED    NOT NULL,
    unit_price      DECIMAL(10,2)   NOT NULL,
    subtotal        DECIMAL(12,2)   NOT NULL,
    CONSTRAINT fk_poi2_order
        FOREIGN KEY (order_id) REFERENCES physical_orders(order_id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_poi2_medicine
        FOREIGN KEY (medicine_id) REFERENCES medicines(medicine_id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_poi2_batch
        FOREIGN KEY (batch_id) REFERENCES stock_batches(batch_id)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

-- 19. PAYMENTS (CHECK: exactly one of online_order_id / physical_order_id set)
DROP TABLE IF EXISTS payments;
CREATE TABLE payments (
    payment_id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    online_order_id         INT UNSIGNED    NULL,
    physical_order_id       INT UNSIGNED    NULL,
    payment_method          ENUM('Cash','Card','Online_Gateway') NOT NULL,
    transaction_reference   VARCHAR(100)    NULL,
    collected_by            INT UNSIGNED    NULL,
    amount                  DECIMAL(12,2)   NOT NULL,
    payment_status          ENUM('Pending','Successful','Failed','Refunded') NOT NULL DEFAULT 'Pending',
    payment_date            DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_payments_online_order
        FOREIGN KEY (online_order_id) REFERENCES online_orders(order_id)
        ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_payments_physical_order
        FOREIGN KEY (physical_order_id) REFERENCES physical_orders(order_id)
        ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_payments_collected_by
        FOREIGN KEY (collected_by) REFERENCES users(user_id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT chk_payments_exactly_one_order
        CHECK (
            (online_order_id IS NOT NULL AND physical_order_id IS NULL)
            OR
            (online_order_id IS NULL AND physical_order_id IS NOT NULL)
        )
) ENGINE=InnoDB;

-- 20. DELIVERIES
DROP TABLE IF EXISTS deliveries;
CREATE TABLE deliveries (
    delivery_id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id            INT UNSIGNED    NOT NULL,
    delivery_agent_id   INT UNSIGNED    NOT NULL,
    assigned_by         INT UNSIGNED    NOT NULL,
    contact_number      VARCHAR(20)     NOT NULL, -- intentionally separate from users.phone (recipient may differ)
    status              ENUM('Pending','Assigned','Accepted','In_transit','Delivered','Failed') NOT NULL DEFAULT 'Pending',
    notes               TEXT            NULL,
    issue_description   TEXT            NULL,
    is_payment_collected BOOLEAN        NOT NULL DEFAULT FALSE,
    payment_amount      DECIMAL(12,2)   NULL,
    assigned_at         DATETIME        NULL,
    delivered_at        DATETIME        NULL,
    created_at          DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_deliveries_order
        FOREIGN KEY (order_id) REFERENCES online_orders(order_id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_deliveries_agent
        FOREIGN KEY (delivery_agent_id) REFERENCES users(user_id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_deliveries_assigned_by
        FOREIGN KEY (assigned_by) REFERENCES users(user_id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_deliveries_status (status)
) ENGINE=InnoDB;

-- 21. DEMAND_FORECASTS
DROP TABLE IF EXISTS demand_forecasts;
CREATE TABLE demand_forecasts (
    forecast_id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    medicine_id             INT UNSIGNED    NOT NULL,
    forecasted_quantity     INT UNSIGNED    NOT NULL,
    period_start            DATE            NOT NULL,
    period_end              DATE            NOT NULL,
    generated_date          DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_forecasts_medicine
        FOREIGN KEY (medicine_id) REFERENCES medicines(medicine_id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- 22. NOTIFICATIONS
DROP TABLE IF EXISTS notifications;
CREATE TABLE notifications (
    notification_id     INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id             INT UNSIGNED    NOT NULL,
    medicine_id         INT UNSIGNED    NULL,
    title               VARCHAR(150)    NOT NULL,
    message             TEXT            NOT NULL,
    is_read             BOOLEAN         NOT NULL DEFAULT FALSE,
    created_at          DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_notifications_user
        FOREIGN KEY (user_id) REFERENCES users(user_id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_notifications_medicine
        FOREIGN KEY (medicine_id) REFERENCES medicines(medicine_id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    INDEX idx_notifications_user_unread (user_id, is_read)
) ENGINE=InnoDB;

-- 23. AUDIT_LOG
DROP TABLE IF EXISTS audit_log;
CREATE TABLE audit_log (
    log_id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id         INT UNSIGNED    NOT NULL,
    action          ENUM('Create','Update','Delete','Login','Logout','Approve','Reject','Assign','Generate_report','Other') NOT NULL,
    module          ENUM('Authentication','Inventory','Prescription','Order','Delivery','Supplier','Report','User_management') NOT NULL,
    table_affected  VARCHAR(100)    NULL,
    record_id       INT UNSIGNED    NULL,
    ip_address      VARCHAR(45)     NULL,
    description     TEXT            NULL,
    created_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_audit_log_user
        FOREIGN KEY (user_id) REFERENCES users(user_id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_audit_log_module (module),
    INDEX idx_audit_log_created_at (created_at)
) ENGINE=InnoDB;

-- 24. SYSTEM_SETTINGS
DROP TABLE IF EXISTS system_settings;
CREATE TABLE system_settings (
    setting_id      INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    setting_key     VARCHAR(100)    NOT NULL UNIQUE,
    setting_value   TEXT            NULL,
    description     TEXT            NULL
) ENGINE=InnoDB;

SET FOREIGN_KEY_CHECKS = 1;

