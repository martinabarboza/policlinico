<?php
$config = require __DIR__ . '/config.php'; // devuelve el array que contiene data sensible $config;

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {

    $host = $config['DB_HOST'];
    $username = $config['DB_USER'];
    $password = $config['DB_PASS'];
    $dbname = $config['DB_NAME'];
    $conn = new mysqli($host, $username, $password, $dbname);
    $conn->set_charset("utf8mb4");
    $conn_ok = true;

} catch (mysqli_sql_exception $e) { 
    error_log("Error de conexión: " . $e->getMessage()); $conn_ok = false;

}

//"Estado del servidor: " . $conn->stat()
//"Host info: " . $conn->host_info
//"Versión del servidor: " . $conn->server_info
//Cierra la conección $conn->close();
//Para cuando hago delete,update o insert: $conn->begin_transaction(); $conn->rollback();[En un catch (Exception $e)]
