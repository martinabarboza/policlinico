<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Policlínico Veterinario - Sistema de Gestión</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon_io/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon_io/favicon-16x16.png">
    <link rel="apple-touch-icon" href="/favicon_io/apple-touch-icon.png">
    <link rel="manifest" href="/favicon_io/site.webmanifest">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="<?= ASSETS_URL ?>/libs/bootstrap-5.3.8-dist/css/bootstrap.min.css">

    <!-- Iconos -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Libs -->
    <?php foreach ($libs as $lib): ?>
        <script src="<?= ASSETS_URL ?>/libs/<?= htmlspecialchars($lib, ENT_QUOTES, 'UTF-8') ?>"></script>
    <?php endforeach; ?>
    <!-- Estilos CSS -->
    <?php foreach ($hojasEstilo as $hojaEstilo): ?>
        <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/<?= htmlspecialchars($hojaEstilo, ENT_QUOTES, 'UTF-8') ?>">
    <?php endforeach; ?>
    <!-- Scripts JS -->
    <?php foreach ($scriptsJS as $scriptJS): ?>
        <script src="<?= ASSETS_URL ?>/js/<?= htmlspecialchars($scriptJS, ENT_QUOTES, 'UTF-8') ?>"></script>
    <?php endforeach; ?>
</head>

<body>
    <div class="container-fluid position-relative bg-white d-flex p-0">
        <!-- Cargador de Página -->
        <div id="spinner" class="visible bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <!--Fin Cargador de Página -->