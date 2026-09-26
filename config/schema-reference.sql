-- PharmaSync — Customer module reference schema
--
-- This is NOT executed by the app (DB_ENABLED = false in config.php).
-- It documents the shape of data every mock Model currently returns,
-- so whoever builds the real database can match column names/types and
-- the app/Models/*.php files can be swapped to real PDO queries with
-- minimal changes to Controllers/Views.

CREATE TABLE customers (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    name          VARCHAR(120) NOT NULL,
    email         VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    phone         VARCHAR(30),
    address       VARCHAR(255),
    created_at    DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
    id   INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

CREATE TABLE medicines (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(150) NOT NULL,
    category_id     INT,
    description     TEXT,
    price           DECIMAL(10,2) NOT NULL,
    stock           INT NOT NULL DEFAULT 0,
    requires_rx     TINYINT(1) NOT NULL DEFAULT 0, -- prescription required?
    image           VARCHAR(255),
    manufacturer    VARCHAR(150),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE cart_items (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT NOT NULL,
    medicine_id INT NOT NULL,
    quantity    INT NOT NULL DEFAULT 1,
    FOREIGN KEY (user_id) REFERENCES customers(id),
    FOREIGN KEY (medicine_id) REFERENCES medicines(id)
);

CREATE TABLE addresses (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    user_id    INT NOT NULL,
    label      VARCHAR(50),
    line1      VARCHAR(255) NOT NULL,
    city       VARCHAR(100) NOT NULL,
    postcode   VARCHAR(20),
    phone      VARCHAR(30),
    FOREIGN KEY (user_id) REFERENCES customers(id)
);

CREATE TABLE prescriptions (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    user_id      INT NOT NULL,
    file_path    VARCHAR(255) NOT NULL, -- filename only; files stored in /storage/prescriptions (outside web root), served via /prescription/file/{id} with an ownership check
    status       ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
    reviewed_by  INT NULL, -- pharmacist user id (from Pharmacist module)
    notes        VARCHAR(255),
    uploaded_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES customers(id)
);

CREATE TABLE orders (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    user_id          INT NOT NULL,
    address_id       INT,
    prescription_id  INT NULL,
    status           ENUM('pending','processing','confirmed','delivered','cancelled') NOT NULL DEFAULT 'pending',
    payment_method   ENUM('cod','card') NOT NULL DEFAULT 'cod',
    subtotal         DECIMAL(10,2) NOT NULL,
    delivery_fee     DECIMAL(10,2) NOT NULL DEFAULT 0,
    total            DECIMAL(10,2) NOT NULL,
    placed_at        DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES customers(id),
    FOREIGN KEY (address_id) REFERENCES addresses(id),
    FOREIGN KEY (prescription_id) REFERENCES prescriptions(id)
);

CREATE TABLE order_items (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    order_id    INT NOT NULL,
    medicine_id INT NOT NULL,
    quantity    INT NOT NULL,
    unit_price  DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (medicine_id) REFERENCES medicines(id)
);

CREATE TABLE payments (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    order_id   INT NOT NULL,
    method     ENUM('cod','card') NOT NULL,
    status     ENUM('pending','paid','failed') NOT NULL DEFAULT 'pending',
    paid_at    DATETIME NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id)
);
