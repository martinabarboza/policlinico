<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poliniclinico</title>
</head>

<body>
    <?php
    
    $config = require __DIR__ . '/../config/config.php';

    try {

        $dsn = "mysql:host={$config['DB_HOST']};dbname={$config['DB_NAME']};charset=utf8";
        $pdo = new PDO($dsn, $config['DB_USER'], $config['DB_PASS']);

        echo "✅ ¡CONEXIÓN EXITOSA! Docker y PHP están hablando correctamente.\n";
    } catch (PDOException $e) {
        echo "❌ ERROR DE CONEXIÓN: " . $e->getMessage() . "\n";
    }
    ?>
</body>

</html>