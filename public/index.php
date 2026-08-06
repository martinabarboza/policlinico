<?php require __DIR__ . '../../backend/Controllers/InicioController.php'; ?>
<!DOCTYPE html>
<html lang="es">

<head class="head-landing">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poliniclinico</title>
    <!-- Librerias -->
    <link rel="stylesheet" href="<?= '/activos/libs/bootstrap-5.3.8-dist/css/bootstrap.min.css' ?>">
    <script src= "<?= '/activos/libs/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js'?>"></script>
    <!-- CSS -->
     <link rel="stylesheet" href="<?= '/activos/css/' ?>">
</head>

<body>
    <?php $InicioController->index();  ?>
</body>

</html> 