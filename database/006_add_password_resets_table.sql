-- ============================================================
-- PharmaSync - migration 006: password reset tokens
-- Owner: Zaidh (integration)
--
-- Adds the password_resets table used by
-- AuthenticationController::forgot() / resetForm() / resetPassword()
-- and app/models/User.php (createPasswordResetToken,
-- findPasswordResetToken, updatePasswordByEmail).
--
-- One row per outstanding reset request. Requesting a new one, or
-- successfully resetting, deletes the old rows for that email first,
-- so at most one valid token exists per address at a time. token is
-- UNIQUE so the reset link can look a request up by the token alone.
-- expires_at is checked in the query, not enforced by MySQL.
--
-- Safe to run again: CREATE TABLE IF NOT EXISTS never touches an
-- existing table or its rows.
-- ============================================================

USE pharmasync;

CREATE TABLE IF NOT EXISTS password_resets (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email      VARCHAR(150) NOT NULL,
    token      VARCHAR(255) NOT NULL UNIQUE,
    expires_at DATETIME     NOT NULL,
    created_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_password_resets_email (email)
) ENGINE=InnoDB;
