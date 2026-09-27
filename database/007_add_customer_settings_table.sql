-- =====================================================================
-- 007 - Customer settings (customer module)
-- =====================================================================
-- One row per customer account: which notification types they want to
-- see, and when they last changed their password from Settings.
-- Import after 001-006. Safe to run twice.
--
-- Until this is imported, CustomerSettings falls back to the session, so
-- the settings page still works - choices just reset on sign-out.
-- =====================================================================

CREATE DATABASE IF NOT EXISTS pharmasync
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE pharmasync;

CREATE TABLE IF NOT EXISTS customer_settings (
    user_id               INT UNSIGNED NOT NULL,
    notify_orders         TINYINT(1)   NOT NULL DEFAULT 1,
    notify_prescriptions  TINYINT(1)   NOT NULL DEFAULT 1,
    notify_refills        TINYINT(1)   NOT NULL DEFAULT 1,
    notify_offers         TINYINT(1)   NOT NULL DEFAULT 0,
    password_changed_at   DATETIME     NULL,
    updated_at            DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id),
    CONSTRAINT fk_customer_settings_user
        FOREIGN KEY (user_id) REFERENCES users (user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
