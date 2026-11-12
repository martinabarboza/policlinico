<?php

/** Mantiene una única conexión mysqli y centraliza su configuración. */
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
                // DATETIME representa hora local en este sistema; alinear NOW() y CURDATE().
                $offsetHorario = (new DateTimeImmutable('now'))->format('P');
                self::$conn->query("SET time_zone = '{$offsetHorario}'");
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
 
    /** Devuelve y conserva en memoria el secreto usado para firmar tokens. */
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
