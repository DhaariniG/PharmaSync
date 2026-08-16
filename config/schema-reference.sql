-- =====================================================================
-- PharmaSync — Customer module reference schema
-- =====================================================================
--
-- This file is NOT executed by the app (DB_ENABLED = false in config.php).
-- It documents the exact shape of the data every mock Model in this module
-- currently returns, so the real tables can be matched column-for-column and
-- app/models/*.php can be swapped to PDO queries without touching the
-- controllers or views.
--
-- ---------------------------------------------------------------------
-- READ THIS BEFORE CREATING ANY OF THESE TABLES
-- ---------------------------------------------------------------------
-- The group already has a shared 24-table schema. Where a table below
-- overlaps with one in the shared schema, THE SHARED SCHEMA WINS — this file
-- is here to show what the customer module needs from it, not to replace it.
--
-- Known conflicts to settle with the group before writing any queries:
--
--   1. STOCK. The `medicines.stock` column below does NOT exist in the shared
--      schema. Real stock is SUM(stock_batches.quantity). Medicine::find()
--      and the stock checks in CustomerCartController must be rewritten to
--      aggregate batches. Do not add a `stock` column to work around this —
--      it will drift out of sync with the inventory manager's module.
--
--   2. BATCH ALLOCATION. The shared schema has
--      online_order_items.batch_id NOT NULL, so placing an order must pick
--      batches (FEFO — first expiry, first out) and split a line across
--      batches when one batch can't cover the quantity. That means
--      order_items below needs a batch_id, and one cart line can become
--      several order_item rows. Still an open group question: where this
--      shared code lives, and whether checkout wraps it in a transaction
--      with SELECT ... FOR UPDATE to stop two customers buying the same
--      batch at once.
--
--   3. USERS vs CUSTOMERS. The shared schema uses a single `users` table with
--      an ENUM role. `customers` below is really "users WHERE role =
--      'customer'". Accounts and passwords belong to the shared login, not to
--      this module.
--
--   4. ORDERS TABLE NAME. The shared schema calls it `online_orders` /
--      `online_order_items`. Names below are the module's local ones.
--
-- Anything marked [NEW] does not exist in the shared schema yet and needs to
-- be raised with the group.
-- =====================================================================


-- ---------------------------------------------------------------------
-- Accounts (owned by the shared login module — shown here for reference)
-- ---------------------------------------------------------------------
CREATE TABLE customers (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    name          VARCHAR(120) NOT NULL,
    email         VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL, -- managed by the shared login, not this module
    phone         VARCHAR(30),
    address       VARCHAR(255),
    dob           DATE NULL,
    health_points INT NOT NULL DEFAULT 0,
    created_at    DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- [NEW] Free-text allergies and conditions shown on the profile page.
-- Currently arrays on the session user (Customer::demoUser()).
CREATE TABLE customer_health_flags (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT NOT NULL,
    type        ENUM('allergy','condition') NOT NULL,
    value       VARCHAR(120) NOT NULL,
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES customers(id)
);

-- [NEW] People a customer orders medicine for: themselves and family.
-- Model: app/models/FamilyMember.php. An order and a prescription each point
-- at one of these, so the customer can tell whose medicine is whose.
CREATE TABLE family_members (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    user_id       INT NOT NULL,
    name          VARCHAR(80) NOT NULL,
    relationship  ENUM('Self','Father','Mother','Spouse','Child','Sibling','Other') NOT NULL DEFAULT 'Other',
    date_of_birth DATE NULL,
    notes         VARCHAR(255),
    created_at    DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES customers(id)
);


-- ---------------------------------------------------------------------
-- Catalogue (owned by the inventory manager module)
-- ---------------------------------------------------------------------
CREATE TABLE categories (
    id   INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

CREATE TABLE medicines (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    name         VARCHAR(150) NOT NULL,
    category_id  INT,
    description  TEXT,
    price        DECIMAL(10,2) NOT NULL,
    -- NO `stock` COLUMN. See conflict #1 at the top of this file:
    -- stock = SUM(stock_batches.quantity) for the medicine.
    requires_rx  TINYINT(1) NOT NULL DEFAULT 0,
    image        VARCHAR(255),
    manufacturer VARCHAR(150),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- [NEW] Extra product photos beyond `medicines.image`.
-- Used by medicine_gallery() on the product page.
CREATE TABLE medicine_images (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    medicine_id INT NOT NULL,
    filename    VARCHAR(255) NOT NULL,
    sort_order  TINYINT NOT NULL DEFAULT 0,
    FOREIGN KEY (medicine_id) REFERENCES medicines(id)
);

-- [NEW] Pharmacist-approved substitutes, used when a medicine is out of
-- stock (the "suggest an alternative" flow).
CREATE TABLE medicine_alternatives (
    medicine_id     INT NOT NULL,
    alternative_id  INT NOT NULL,
    PRIMARY KEY (medicine_id, alternative_id),
    FOREIGN KEY (medicine_id)    REFERENCES medicines(id),
    FOREIGN KEY (alternative_id) REFERENCES medicines(id)
);


-- ---------------------------------------------------------------------
-- Cart (persistent, per the group decision — not session-only)
-- ---------------------------------------------------------------------
CREATE TABLE cart_items (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    user_id       INT NOT NULL,
    medicine_id   INT NOT NULL,
    quantity      INT NOT NULL DEFAULT 1,
    -- Price at the time it went in the cart, so the total can't silently
    -- change under the customer if the shop reprices the item.
    unit_price    DECIMAL(10,2) NOT NULL,
    -- [NEW] "Save for later" keeps the row but takes it out of the cart.
    saved_for_later TINYINT(1) NOT NULL DEFAULT 0,
    added_at      DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_cart_line (user_id, medicine_id, saved_for_later),
    FOREIGN KEY (user_id)     REFERENCES customers(id),
    FOREIGN KEY (medicine_id) REFERENCES medicines(id)
);


-- ---------------------------------------------------------------------
-- Addresses
-- ---------------------------------------------------------------------
CREATE TABLE addresses (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    user_id    INT NOT NULL,
    label      VARCHAR(50),
    line1      VARCHAR(255) NOT NULL,
    city       VARCHAR(100) NOT NULL,
    postcode   VARCHAR(20),
    phone      VARCHAR(30),
    is_default TINYINT(1) NOT NULL DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES customers(id)
);


-- ---------------------------------------------------------------------
-- Prescriptions
-- ---------------------------------------------------------------------
CREATE TABLE prescriptions (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    user_id       INT NOT NULL,
    -- Who the medicine was prescribed to. An order placed from this
    -- prescription inherits this patient.
    patient_id    INT NULL,
    -- Filename only. Files live in /storage/prescriptions (outside the web
    -- root) and are served via /prescription/file/{id} with an ownership check.
    file_path     VARCHAR(255) NOT NULL,
    -- The module uses all five of these. 'needs_alternative' and 'prepared'
    -- are missing from the shared schema's ENUM and must be added.
    status        ENUM('pending','needs_alternative','prepared','approved','rejected')
                  NOT NULL DEFAULT 'pending',
    reviewed_by   INT NULL, -- pharmacist user id (Pharmacist module)
    notes         VARCHAR(500),
    pharmacy_notes VARCHAR(500),
    urgent        TINYINT(1) NOT NULL DEFAULT 0,
    clinic        VARCHAR(150),
    prescribed_date DATE NULL,
    requested_medicine_id INT NULL,
    requested_quantity    INT NOT NULL DEFAULT 1,
    -- Set when status = 'needs_alternative': the substitute the pharmacist
    -- suggested, and what the customer decided about it.
    alternative_id       INT NULL,
    alternative_decision ENUM('pending','approved','waiting') NULL,
    uploaded_at   DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id)   REFERENCES customers(id),
    FOREIGN KEY (patient_id) REFERENCES family_members(id),
    FOREIGN KEY (requested_medicine_id) REFERENCES medicines(id),
    FOREIGN KEY (alternative_id)        REFERENCES medicines(id)
);

-- [NEW] What the pharmacist prepared against a prescription. The customer
-- confirms these and they go into the cart.
CREATE TABLE prescription_items (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    prescription_id INT NOT NULL,
    medicine_id     INT NOT NULL,
    quantity        INT NOT NULL DEFAULT 1,
    FOREIGN KEY (prescription_id) REFERENCES prescriptions(id),
    FOREIGN KEY (medicine_id)     REFERENCES medicines(id)
);


-- ---------------------------------------------------------------------
-- Orders  (shared schema calls this online_orders)
-- ---------------------------------------------------------------------
CREATE TABLE orders (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    user_id          INT NOT NULL,
    -- Who the order is for. patient_id links to the family profile;
    -- patient_label is a snapshot of how it read at the time, so an old order
    -- still displays correctly if the family member is renamed or removed.
    -- Same reasoning as delivery_address below.
    patient_id       INT NULL,
    patient_label    VARCHAR(120),
    prescription_id  INT NULL, -- NULL = over-the-counter order
    status           ENUM('pending','processing','confirmed','ready_for_pickup',
                          'delivered','collected','cancelled') NOT NULL DEFAULT 'pending',

    -- Fulfilment. The delivery fee is only charged on 'delivery'; store
    -- pickup is free. This is why the cart page shows no fee — the customer
    -- hasn't chosen yet at that point.
    delivery_method  ENUM('delivery','pickup') NOT NULL DEFAULT 'delivery',
    -- Snapshot of the chosen address, not just address_id, so the order still
    -- shows where it went if the address is later edited or deleted.
    delivery_address VARCHAR(255) NULL,
    address_id       INT NULL,
    address_notes    VARCHAR(255),
    delivery_person_id INT NULL, -- assigned by the Delivery module
    pickup_date      DATE NULL,
    pickup_time      TIME NULL,

    payment_method   ENUM('cod','card','bank_transfer') NOT NULL DEFAULT 'cod',

    subtotal         DECIMAL(10,2) NOT NULL,
    discount         DECIMAL(10,2) NOT NULL DEFAULT 0,
    delivery_fee     DECIMAL(10,2) NOT NULL DEFAULT 0,
    tax              DECIMAL(10,2) NOT NULL DEFAULT 0,
    total            DECIMAL(10,2) NOT NULL,
    promo_code       VARCHAR(30) NULL,

    placed_at        DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id)         REFERENCES customers(id),
    FOREIGN KEY (patient_id)      REFERENCES family_members(id),
    FOREIGN KEY (address_id)      REFERENCES addresses(id),
    FOREIGN KEY (prescription_id) REFERENCES prescriptions(id)
);

CREATE TABLE order_items (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    order_id    INT NOT NULL,
    medicine_id INT NOT NULL,
    -- See conflict #2: the shared schema makes this NOT NULL, so checkout has
    -- to allocate batches (FEFO). One cart line may produce several rows here
    -- when a single batch can't cover the quantity.
    batch_id    INT NULL,
    quantity    INT NOT NULL,
    unit_price  DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id)    REFERENCES orders(id),
    FOREIGN KEY (medicine_id) REFERENCES medicines(id)
);

-- [NEW] The order timeline shown on the tracking page. Currently a hardcoded
-- `tracking` array in Order::create(). Rows are appended by whichever module
-- moves the order along (pharmacist, delivery).
CREATE TABLE order_tracking (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    order_id    INT NOT NULL,
    label       VARCHAR(60) NOT NULL, -- 'Order Received', 'Ready for Pickup', ...
    note        VARCHAR(255),
    occurred_at DATETIME NULL,        -- NULL = step not reached yet
    sort_order  TINYINT NOT NULL DEFAULT 0,
    FOREIGN KEY (order_id) REFERENCES orders(id)
);

CREATE TABLE payments (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    order_id   INT NOT NULL,
    method     ENUM('cod','card','bank_transfer') NOT NULL,
    status     ENUM('pending','paid','failed') NOT NULL DEFAULT 'pending',
    amount     DECIMAL(10,2) NOT NULL,
    paid_at    DATETIME NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id)
);


-- ---------------------------------------------------------------------
-- Notifications
-- ---------------------------------------------------------------------
-- [NEW] Model: app/models/Notification.php.
CREATE TABLE notifications (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT NOT NULL,
    type        VARCHAR(40) NOT NULL,  -- order, prescription, stock, promo
    title       VARCHAR(150) NOT NULL,
    body        VARCHAR(500),
    link        VARCHAR(255),
    is_read     TINYINT(1) NOT NULL DEFAULT 0,
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES customers(id)
);


-- ---------------------------------------------------------------------
-- Promotions
-- ---------------------------------------------------------------------
-- [NEW] Currently the Cart::PROMOS constant (HEALTH30 only). Still an open
-- group question whether promo codes stay in scope at all.
CREATE TABLE promo_codes (
    code         VARCHAR(30) PRIMARY KEY,
    discount_pct DECIMAL(5,2) NOT NULL, -- 30.00 = 30% off the subtotal
    active       TINYINT(1) NOT NULL DEFAULT 1,
    valid_from   DATE NULL,
    valid_until  DATE NULL
);
