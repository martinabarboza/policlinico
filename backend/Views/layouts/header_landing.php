<!DOCTYPE html>
<html lang="es">
<!-- librerias siempre en el header para que siempre carguen junto con la pagina -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Policlinico | Pagina de Inicio</title>
    <!-- Llamados a LIBS -->
    <link rel="stylesheet" href="<?= ASSETS_URL ?>/libs/bootstrap-5.3.8-dist/css/bootstrap.min.css">
    <script src="<?= ASSETS_URL ?>/libs/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
    <!-- CSS -->
    <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/landing.css">
    <!-- CSS-DINAMICOS -->
    <link id="dinamico-css" rel="stylesheet" href="<?= ASSETS_URL ?>/css/inicio_landing.css">

</head>
<header class="container-fluid p-0">
    <div class="row g-0">

        <div class="col-6 bg-success d-flex align-items-center px-5 py-4">

            <img
                src="<?= ASSETS_URL ?>/imgs/petcraft-logo.png"
                alt="PetCraft"
                class="img-fluid me-4"
                style="max-height:80px;">

            <div class="text-white">
                <h2 class=" d-none d-sm-block fw-bold mb-1">PetCraft</h2>
                <h5 class="d-none d-sm-block mb-0">Sistema de Gestión Veterinaria</h5>
            </div>

        </div>

        <div class="col-6 bg-primary d-flex justify-content-end align-items-center px-5 py-4">
            <img
                src="<?= ASSETS_URL ?>/imgs/policlinico-logo-blanco.png"
                alt="Policlínico Veterinario"
                class="img-fluid"
                style="max-height:65px;">
        </div>
    </div>
</header>