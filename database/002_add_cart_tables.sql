-- ============================================================
-- PharmaSync - migration 002
-- Adds the shopping cart, which the original schema is missing.
--
-- Run this AFTER the main schema file.
-- ============================================================

USE pharmasync;

-- ------------------------------------------------------------
-- CARTS
-- One open cart per customer. The cart survives logout, which a
-- session-only cart would not.
-- ------------------------------------------------------------
DROP TABLE IF EXISTS cart_items;
DROP TABLE IF EXISTS carts;

CREATE TABLE carts (
    cart_id     INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    customer_id INT UNSIGNED NOT NULL UNIQUE,
    created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
                    ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_carts_customer
        FOREIGN KEY (customer_id) REFERENCES users(user_id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- CART_ITEMS
--
-- Deliberately NO batch_id and NO price snapshot here.
-- A cart is only an intention to buy. The batch is chosen and the
-- price is frozen at checkout, when online_order_items is written.
-- Holding a batch in the cart would lock stock that may never be sold.
-- ------------------------------------------------------------
CREATE TABLE cart_items (
    cart_item_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    cart_id      INT UNSIGNED NOT NULL,
    medicine_id  INT UNSIGNED NOT NULL,
    quantity     INT UNSIGNED NOT NULL DEFAULT 1,
    added_at     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_cart_items_cart
        FOREIGN KEY (cart_id) REFERENCES carts(cart_id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_cart_items_medicine
        FOREIGN KEY (medicine_id) REFERENCES medicines(medicine_id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT chk_cart_items_quantity
        CHECK (quantity > 0),
    -- Adding the same medicine twice increases the quantity
    -- instead of creating a second row.
    UNIQUE KEY uq_cart_medicine (cart_id, medicine_id)
) ENGINE=InnoDB;
