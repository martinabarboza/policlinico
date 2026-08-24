<?php

/**
 * Conexión única a la base de datos.
 * con mysqli
 */

class Database
{
    private static ?mysqli $conn = null;
 
    public static function getConnection(): mysqli
    {
        if (self::$conn === null) {
 
            $config = require __DIR__ . '/../Db/config.php';
 
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
 
            try {
                self::$conn = new mysqli(
                    $config['DB_HOST'],
                    $config['DB_USER'],
                    $config['DB_PASS'],
                    $config['DB_NAME']
                );
                self::$conn->set_charset('utf8mb4');
            } catch (mysqli_sql_exception $e) {
                error_log(sprintf(
                    'Error de conexión a la base de datos [host=%s, db=%s]: %s',
                    $config['DB_HOST'],
                    $config['DB_NAME'],
                    $e->getMessage()
                ));
                throw new Exception('No se pudo conectar a la base de datos.');
            }
        }
 
        return self::$conn;
    }
 
    /**
     * Devuelve el SECRET usado para firmar tokens/cookies.
     */
    public static function getSecret(): string
    {
        static $secret = null;
 
        if ($secret === null) {
            $config = require __DIR__ . '/../Db/config.php';
            $secret = $config['SECRET'];
        }
 
        return $secret;
    }
}
