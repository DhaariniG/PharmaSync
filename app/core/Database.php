<?php
/**
 * Database - single shared PDO connection.
 *
 * One connection per request, no matter how many models are used.
 * Never write `new PDO(...)` anywhere else in the project.
 */
class Database
{
    private static ?PDO $instance = null;

    private function __construct() {}
    private function __clone() {}

    public static function conn(): PDO
    {
        if (self::$instance === null) {
            $dsn = 'mysql:host=' . DB_HOST
                 . ';dbname='    . DB_NAME
                 . ';charset='   . DB_CHARSET;

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_EMULATE_PREPARES   => false,   // real prepared statements
                PDO::ATTR_STRINGIFY_FETCHES  => false,
            ];

            try {
                self::$instance = new PDO($dsn, DB_USER, DB_PASS, $options);
            } catch (PDOException $e) {
                if (APP_ENV === 'dev') {
                    die('Database connection failed: ' . $e->getMessage());
                }
                error_log('DB connection failed: ' . $e->getMessage());
                http_response_code(500);
                die('The system is temporarily unavailable. Please try again later.');
            }
        }

        return self::$instance;
    }
}
