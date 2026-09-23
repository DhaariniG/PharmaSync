<?php
/**
 * Database - one shared PDO connection per request.
 *
 * Never write `new PDO(...)` anywhere else in the project. Models reach the
 * connection through Model::db(), which returns null until DB_ENABLED is
 * switched on in config/config.php.
 *
 * Rows are fetched as associative arrays. Every view in the project reads
 * $row['column'], so do not change the fetch mode.
 */
class Database
{
    private static ?PDO $connection = null;

    private function __construct() {}
    private function __clone() {}

    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            $dsn = 'mysql:host=' . DB_HOST
                 . ';dbname='    . DB_NAME
                 . ';charset='   . DB_CHARSET;

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,   // real prepared statements
                PDO::ATTR_STRINGIFY_FETCHES  => false,
            ];

            try {
                self::$connection = new PDO($dsn, DB_USER, DB_PASS, $options);
            } catch (PDOException $e) {
                if (APP_ENV === 'dev') {
                    die('Database connection failed: ' . $e->getMessage());
                }

                error_log('DB connection failed: ' . $e->getMessage());
                http_response_code(500);
                die('The system is temporarily unavailable. Please try again later.');
            }
        }

        return self::$connection;
    }

    /** Short alias, so Database::conn() also works. */
    public static function conn(): PDO
    {
        return self::getConnection();
    }
}
