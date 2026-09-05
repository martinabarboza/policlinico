<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<?php include __DIR__ . '/../layouts/header_landing.php'; ?>
<?php
function mostrarServicios($cartas, $cantidad)
{
    $grupos = array_chunk($cartas, $cantidad);

    foreach ($grupos as $index => $grupo) {
        $active = $index === 0 ? 'active' : '';

        echo '
        <div class="carousel-item ' . $active . '">
            <div class="d-flex justify-content-center align-items-stretch gap-4 px-5">';

        foreach ($grupo as $carta) {
            echo '
                <div class="servicio-card">
                    <img src="' . htmlspecialchars($carta['imagen'], ENT_QUOTES, 'UTF-8') . '"
                        class="card-img-top"
                        alt="' . htmlspecialchars($carta['titulo']) . '">

                    <div class="card-body">
                        <span class="servicio-categoria">
                            Servicio
                        </span>

                        <h5 class="card-title">
                            ' . htmlspecialchars($carta['titulo']) . '
                        </h5>

                        <p class="card-text">
                            ' . htmlspecialchars($carta['descripcion']) . '
                        </p>

                        <a href="' . htmlspecialchars($carta['link'], ENT_QUOTES, 'UTF-8') . '"
                            class="btn btn-primary">
                            Acceder al portal
                            <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>';
        }

        echo '
            </div>
        </div>';
    }
}

?>

<body>
    <?php include __DIR__ . '/../layouts/navbar_landing.php'; ?>
    <!--HERO-->
    <section class="hero p-2">
        <div class=" container-fluid w-100 px-4 py-5">
            <div class="row flex-lg-row-reverse align-items-center g-5 py-5">
                <div class="col-12 col-lg-6">
                    <img src="<?= ASSETS_URL ?>/imgs/policlinico.png" class="d-block mx-lg-auto img-fluid rounded rounded-3 img-policlinico" alt="Policlinico Veterinario CENUR" loading="lazy">
                </div>
                <div class="col-lg-6">
                    <h1 class="display-5 hero-titulo lh-1 mb-3">Gestión clínica e investigación veterinaria</h1>
                    <p class="lead hero-parrafo text-justify">Optimizando la administración de pacientes, profesionales veterinarios y datos clínicos de la Policlínica CENUR para mejorar la atención y potenciar la investigación epidemiológica.</p>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                        <button type="button" class="btn btn-primary btn-lg px-4 me-md-2 hero-btn">INGRESAR AL PANEL DE CONTROL</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--ACCESOS DIRECTOS-->
    <section class="py-5">

        <!-- Título de la sección -->
        <div class="d-flex justify-content-center px-4">

            <ul class="ul-h2-container list-unstyled">

                <li>
                    <h2 class="m-0 section1-h2 text-justify">
                        Soluciones operativas y accesos rápidos
                    </h2>
                </li>

                <!-- Separador con huella -->
                <li class="d-flex justify-content-center">

                    <div class="separador-con-huella my-4">
                        <img
                            src="<?= ASSETS_URL ?>/svgs/paw-print.svg"
                            alt="">
                    </div>

                </li>

            </ul>

        </div>


        <!-- Contenedor de los accesos -->
        <div class="container">

            <div class="row g-4 justify-content-center">


                <!-- =================================================
                 PACIENTES
                 ================================================= -->
                <div class="col-12 col-sm-6 col-lg-4">

                    <a href="/pacientes" class="shortcut-card">

                        <!-- Número del acceso -->
                        <div class="shortcut-number">
                            01
                        </div>

                        <!-- Información -->
                        <div class="shortcut-content">

                            <span class="shortcut-category">
                                Gestión
                            </span>

                            <h3>
                                Pacientes
                            </h3>

                            <p>
                                Registrar, buscar y consultar pacientes.
                            </p>

                        </div>

                        <!-- Indicador de acceso -->
                        <span class="shortcut-arrow">
                            ↗
                        </span>

                    </a>

                </div>


                <!-- =================================================
                 TURNOS
                 ================================================= -->
                <div class="col-12 col-sm-6 col-lg-4">

                    <a href="/turnos" class="shortcut-card">

                        <div class="shortcut-number">
                            02
                        </div>

                        <div class="shortcut-content">

                            <span class="shortcut-category">
                                Agenda
                            </span>

                            <h3>
                                Turnos
                            </h3>

                            <p>
                                Gestionar consultas y horarios.
                            </p>

                        </div>

                        <span class="shortcut-arrow">
                            ↗
                        </span>

                    </a>

                </div>


                <!-- =================================================
                 HISTORIAS CLÍNICAS
                 ================================================= -->
                <div class="col-12 col-sm-6 col-lg-4">

                    <a href="/historias-clinicas" class="shortcut-card">

                        <div class="shortcut-number">
                            03
                        </div>

                        <div class="shortcut-content">

                            <span class="shortcut-category">
                                Registros
                            </span>

                            <h3>
                                Historias clínicas
                            </h3>

                            <p>
                                Consultar y administrar historias clínicas.
                            </p>

                        </div>

                        <span class="shortcut-arrow">
                            ↗
                        </span>

                    </a>

                </div>


                <!-- =================================================
                 PROFESIONALES
                 ================================================= -->
                <div class="col-12 col-sm-6 col-lg-4">

                    <a href="/profesionales" class="shortcut-card">

                        <div class="shortcut-number">
                            04
                        </div>

                        <div class="shortcut-content">

                            <span class="shortcut-category">
                                Personal
                            </span>

                            <h3>
                                Profesionales
                            </h3>

                            <p>
                                Gestionar médicos y especialistas.
                            </p>

                        </div>

                        <span class="shortcut-arrow">
                            ↗
                        </span>

                    </a>

                </div>


                <!-- =================================================
                 CONSULTORIOS
                 ================================================= -->
                <div class="col-12 col-sm-6 col-lg-4">

                    <a href="/consultorios" class="shortcut-card">

                        <div class="shortcut-number">
                            05
                        </div>

                        <div class="shortcut-content">

                            <span class="shortcut-category">
                                Organización
                            </span>

                            <h3>
                                Consultorios
                            </h3>

                            <p>
                                Consultar disponibilidad y asignaciones.
                            </p>

                        </div>

                        <span class="shortcut-arrow">
                            ↗
                        </span>

                    </a>

                </div>


                <!-- =================================================
                 REPORTES
                 ================================================= -->
                <div class="col-12 col-sm-6 col-lg-4">

                    <a href="/reportes" class="shortcut-card">

                        <div class="shortcut-number">
                            06
                        </div>

                        <div class="shortcut-content">

                            <span class="shortcut-category">
                                Información
                            </span>

                            <h3>
                                Reportes
                            </h3>

                            <p>
                                Consultar estadísticas e información clínica.
                            </p>

                        </div>

                        <span class="shortcut-arrow">
                            ↗
                        </span>

                    </a>

                </div>


            </div>

        </div>

    </section>
    <!--ACCESOS DIRECTOS-->
    <section class=" pb-5">
        <div class="d-flex justify-content-center px-4">
            <ul class="ul-h2-container list-unstyled">
                <li>
                    <h2 class=" m-0 section1-h2 text-justify">Nuestros servicios</h2>
                </li>
                <li class=" d-flex justify-content-center">
                    <div class="separador-con-huella my-4">
                        <img src="<?= ASSETS_URL ?>/svgs/paw-print.svg" alt="">
                    </div>
                </li>
            </ul>
        </div>
        <!--CELULAR 1CARTA-->
        <div class="d-block d-md-none p-sm-1">
            <div class="d-flex justify-content-center w-100">
                <div id="carouselMobile"
                    class="carousel slide w-100"
                    data-bs-ride="carousel"
                    data-bs-interval="5000">

                    <div class="carousel-inner inner-movil">
                        <?php
                        $InicioController = new InicioController();
                        $cartas = $InicioController->obtenerServicios();
                        mostrarServicios($cartas, 1);
                        ?>
                    </div>

                    <button class="carousel-control-prev"
                        type="button"
                        data-bs-target="#carouselMobile"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>

                    <button class="carousel-control-next"
                        type="button"
                        data-bs-target="#carouselMobile"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                        <span class="visually-hidden">Siguiente</span>
                    </button>

                </div>
            </div>
        </div>
        <!--TABLET 2CARTAS-->
        <div class="d-none d-md-block d-lg-none p-5">
            <div class="d-flex justify-content-center w-100">
                <div id="carouselTablet"
                    class="carousel slide w-100"
                    data-bs-ride="carousel"
                    data-bs-interval="5000">

                    <div class="carousel-inner inner-tablet">
                        <?php
                        $InicioController = new InicioController();
                        $cartas = $InicioController->obtenerServicios();
                        mostrarServicios($cartas, 2);
                        ?>
                    </div>

                    <button class="carousel-control-prev"
                        type="button"
                        data-bs-target="#carouselTablet"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>

                    <button class="carousel-control-next"
                        type="button"
                        data-bs-target="#carouselTablet"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                        <span class="visually-hidden">Siguiente</span>
                    </button>

                </div>
            </div>
        </div>
        <!--PC 3CARTAS-->
        <div class="d-none d-lg-block p-5">
            <div class="d-flex justify-content-center w-100">
                <div id="carouselDesktop"
                    class="carousel slide w-100"
                    data-bs-ride="carousel"
                    data-bs-interval="3000">

                    <div class="carousel-inner inner-desktop">
                        <?php
                        $InicioController = new InicioController();
                        $cartas = $InicioController->obtenerServicios();
                        mostrarServicios($cartas, 3);
                        ?>
                    </div>

                    <button class="carousel-control-prev"
                        type="button"
                        data-bs-target="#carouselDesktop"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>

                    <button class="carousel-control-next"
                        type="button"
                        data-bs-target="#carouselDesktop"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                        <span class="visually-hidden">Siguiente</span>
                    </button>

                </div>
            </div>
        </div>
    </section>
    <!--HORA ATENCION-->
    <section>
        <div class=" container">
            <!-- PC y TABLET -->
            <div class="p-5 d-none d-lg-block">

                <!-- Etiqueta superior (Solapada hacia abajo) -->
                <div class="bg-primary titulo-banner-verde text-white d-sm-flex d-md-inline-block d-lg-inline-block rounded-top-3 px-3 pt-2 pb-3 ms-lg-3 ms-md-3">
                    <h2 class="h6 fw-bold mb-0 text-center">Horario de Atención</h2>
                </div>

                <!-- Banner Principal Verde -->
                <div class="banner-horario bg-success p-3 text-white d-flex flex-column flex-md-row align-items-center shadow position-relative">

                    <!-- Icono Reloj -->
                    <div class="px-3">
                        <img src="<?= ASSETS_URL ?>/svgs/reloj.svg" alt="Reloj" width="48" height="48">
                    </div>

                    <!-- Texto Informativo -->
                    <div>
                        <h3 class="h6 fw-bold mb-1">Horario de atención - Lunes a Viernes</h3>
                        <p class="mb-0 small fw-medium">10:00 a 17:00 horas</p>
                    </div>

                    <!-- Sección Derecha: Botón + Perro -->
                    <div class="ms-auto d-flex align-items-center pe-lg-5 pe-md-5 me-lg-4 me-md-4">
                        <!-- Botón de Teléfono -->
                        <a href="tel:098230818" class="btn btn-light text-success fw-bold rounded-3 px-3 py-2 me-3 d-flex align-items-center gap-2 shadow-sm">
                            <i class="bi bi-telephone-fill"></i>
                            <span>098 230 818</span>
                        </a>
                    </div>

                    <!-- Imagen del Perro (sobresale) -->
                    <img src="<?= ASSETS_URL ?>/imgs/golden.png" alt="Golden Retriever" class="img-golden position-absolute end-0 d-none d-md-inline-block">

                </div>

            </div>
            <!-- MOBIL -->
            <div class="horario-mobile d-lg-none">

                <div class="horario-mobile-titulo">
                    <h2>Horario de Atención</h2>
                </div>

                <div class="horario-mobile-banner">

                    <div class="horario-mobile-reloj">
                        <img src="<?= ASSETS_URL ?>/svgs/reloj.svg"
                            alt="Reloj"
                            width="48"
                            height="48">
                    </div>

                    <div class="horario-mobile-info">
                        <h3>Horario de atención</h3>
                        <p>Lunes a Viernes</p>
                        <span>10:00 a 17:00 horas</span>
                    </div>

                    <div class="horario-mobile-telefono">
                        <a href="tel:098230818">
                            <i class="bi bi-telephone-fill"></i>
                            <span>098 230 818</span>
                        </a>
                    </div>

                </div>

            </div>
        </div>
    </section>
    <?php include __DIR__ . '/../layouts/footer_landing.php'; ?>
</body>

</html>