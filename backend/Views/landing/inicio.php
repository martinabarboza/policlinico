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
            $titulo = htmlspecialchars((string) ($carta['titulo'] ?? 'Servicio'), ENT_QUOTES, 'UTF-8');
            $descripcion = htmlspecialchars((string) ($carta['descripcion'] ?? ''), ENT_QUOTES, 'UTF-8');
            $imagen = trim((string) ($carta['imagen'] ?? ''));
            $link = trim((string) ($carta['link'] ?? ''));

            echo '
                <div class="tarjeta-servicio">
                    ' . ($imagen !== ''
                        ? '<img src="' . htmlspecialchars($imagen, ENT_QUOTES, 'UTF-8') . '" class="card-img-top" alt="' . $titulo . '">'
                        : '<div class="tarjeta-servicio__imagen-vacia" aria-hidden="true"><i class="bi bi-heart-pulse"></i></div>') . '

                    <div class="card-body">
                        <span class="categoria-servicio">
                            Servicio
                        </span>

                        <h5 class="card-title">
                            ' . $titulo . '
                        </h5>

                        <p class="card-text">
                            ' . $descripcion . '
                        </p>

                        <a href="' . htmlspecialchars($link !== '' ? $link : '/login', ENT_QUOTES, 'UTF-8') . '"
                            class="btn btn-primary">
                            Más información
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
    <?php include __DIR__ . '/../layouts/navbar_Landing.php'; ?>
    <!--HERO-->
    <section class="portada p-2">
        <div class=" container-fluid w-100 px-4 py-5">
            <div class="row flex-lg-row-reverse align-items-center g-5 py-5">
                <div class="col-12 col-lg-6">
                    <img src="<?= ASSETS_URL ?>/imgs/policlinico.png" class="d-block mx-lg-auto img-fluid rounded rounded-3 imagen-policlinica" alt="Policlinico Veterinario CENUR" loading="lazy">
                </div>
                <div class="col-lg-6">
                    <h1 class="display-5 portada-titulo lh-1 mb-3">Gestión clínica e investigación veterinaria</h1>
                    <p class="lead portada-parrafo texto-justificado">Optimizando la administración de pacientes, profesionales veterinarios y datos clínicos de la Policlínica CENUR para mejorar la atención y potenciar la investigación epidemiológica.</p>
                </div>
            </div>
        </div>
    </section>
    <!--ACCESOS DIRECTOS-->
    <section class="py-5">

        <!-- Título de la sección -->
        <div class="d-flex justify-content-center px-4">

            <ul class="lista-titulos-seccion list-unstyled">

                <li>
                    <h2 class="m-0 titulo-seccion texto-justificado">
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

                    <a href="<?= htmlspecialchars(url('dashboard'), ENT_QUOTES, 'UTF-8') ?>" class="tarjeta-acceso">

                        <!-- Número del acceso -->
                        <div class="tarjeta-acceso__numero">
                            01
                        </div>

                        <!-- Información -->
                        <div class="tarjeta-acceso__contenido">

                            <span class="tarjeta-acceso__categoria">
                                Dashboard
                            </span>

                            <h3>
                                Agenda diaria
                            </h3>

                            <p>
                                Consultar la agenda por fecha, pacientes en espera y hospitalizaciones activas.
                            </p>

                        </div>

                        <!-- Indicador de acceso -->
                        <span class="tarjeta-acceso__flecha">
                            ↗
                        </span>

                    </a>

                </div>


                <!-- =================================================
                 TURNOS
                 ================================================= -->
                <div class="col-12 col-sm-6 col-lg-4">

                    <a href="<?= htmlspecialchars(url('consultas-diarias'), ENT_QUOTES, 'UTF-8') ?>" class="tarjeta-acceso">

                        <div class="tarjeta-acceso__numero">
                            02
                        </div>

                        <div class="tarjeta-acceso__contenido">

                            <span class="tarjeta-acceso__categoria">
                                Consultas
                            </span>

                            <h3>
                                Consultas del día
                            </h3>

                            <p>
                                Ver todas las consultas registradas para hoy y acceder a su detalle.
                            </p>

                        </div>

                        <span class="tarjeta-acceso__flecha">
                            ↗
                        </span>

                    </a>

                </div>


                <!-- =================================================
                 HISTORIAS CLÍNICAS
                 ================================================= -->
                <div class="col-12 col-sm-6 col-lg-4">

                    <a href="<?= htmlspecialchars(url('consultas-incompletas'), ENT_QUOTES, 'UTF-8') ?>" class="tarjeta-acceso">

                        <div class="tarjeta-acceso__numero">
                            03
                        </div>

                        <div class="tarjeta-acceso__contenido">

                            <span class="tarjeta-acceso__categoria">
                                Seguimiento
                            </span>

                            <h3>
                                Consultas incompletas
                            </h3>

                            <p>
                                Revisar consultas pendientes o en curso y continuar su seguimiento.
                            </p>

                        </div>

                        <span class="tarjeta-acceso__flecha">
                            ↗
                        </span>

                    </a>

                </div>


                <!-- =================================================
                 PROFESIONALES
                 ================================================= -->
                <div class="col-12 col-sm-6 col-lg-4">

                    <a href="<?= htmlspecialchars(url('proximas-consultas'), ENT_QUOTES, 'UTF-8') ?>" class="tarjeta-acceso">

                        <div class="tarjeta-acceso__numero">
                            04
                        </div>

                        <div class="tarjeta-acceso__contenido">

                            <span class="tarjeta-acceso__categoria">
                                Agenda
                            </span>

                            <h3>
                                Próximas consultas
                            </h3>

                            <p>
                                Consultar las próximas consultas aún no finalizadas ni canceladas.
                            </p>

                        </div>

                        <span class="tarjeta-acceso__flecha">
                            ↗
                        </span>

                    </a>

                </div>


                <!-- =================================================
                 CONSULTORIOS
                 ================================================= -->
                <div class="col-12 col-sm-6 col-lg-4">

                    <a href="<?= htmlspecialchars(url('centro-consultas'), ENT_QUOTES, 'UTF-8') ?>" class="tarjeta-acceso">

                        <div class="tarjeta-acceso__numero">
                            05
                        </div>

                        <div class="tarjeta-acceso__contenido">

                            <span class="tarjeta-acceso__categoria">
                                Gestión clínica
                            </span>

                            <h3>
                                Consultorio
                            </h3>

                            <p>
                                Buscar pacientes y consultas, revisar detalles y registrar nuevas consultas.
                            </p>

                        </div>

                        <span class="tarjeta-acceso__flecha">
                            ↗
                        </span>

                    </a>

                </div>


                <!-- =================================================
                 REPORTES
                 ================================================= -->
                <div class="col-12 col-sm-6 col-lg-4">

                    <a href="<?= htmlspecialchars(url('centro-tutores'), ENT_QUOTES, 'UTF-8') ?>" class="tarjeta-acceso">

                        <div class="tarjeta-acceso__numero">
                            06
                        </div>

                        <div class="tarjeta-acceso__contenido">

                            <span class="tarjeta-acceso__categoria">
                                Gestión
                            </span>

                            <h3>
                                Tutores
                            </h3>

                            <p>
                                Buscar y registrar tutores con sus datos de contacto.
                            </p>

                        </div>

                        <span class="tarjeta-acceso__flecha">
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
            <ul class="lista-titulos-seccion list-unstyled">
                <li>
                    <h2 class=" m-0 titulo-seccion texto-justificado">Nuestros servicios</h2>
                </li>
                <li class=" d-flex justify-content-center">
                    <div class="separador-con-huella my-4">
                        <img src="<?= ASSETS_URL ?>/svgs/paw-print.svg" alt="">
                    </div>
                </li>
            </ul>
        </div>
        <?php if (empty($servicios)): ?>
            <div class="servicios-vacios mx-auto" role="status">
                <span class="servicios-vacios__icono"><i class="bi bi-heart-pulse"></i></span>
                <h3>Estamos preparando esta información</h3>
                <p class="mb-0">Los servicios estarán disponibles próximamente. Mientras tanto, podés ingresar al sistema para más información.</p>
            </div>
        <?php else: ?>
            <!--CELULAR 1CARTA-->
            <div class="d-block d-md-none p-sm-1">
                <div id="carouselMobile" class="carousel slide w-100" data-bs-ride="carousel" data-bs-interval="5000">
                    <div class="carousel-inner interior-movil">
                        <?php mostrarServicios($servicios, 1); ?>
                    </div>
                    <?php if (count($servicios) > 1): ?>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselMobile" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                            <span class="visually-hidden">Anterior</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselMobile" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                            <span class="visually-hidden">Siguiente</span>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
            <!--TABLET 2CARTAS-->
            <div class="d-none d-md-block d-lg-none p-5">
                <div id="carouselTablet" class="carousel slide w-100" data-bs-ride="carousel" data-bs-interval="5000">
                    <div class="carousel-inner interior-tablet">
                        <?php mostrarServicios($servicios, 2); ?>
                    </div>
                    <?php if (count($servicios) > 2): ?>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselTablet" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                            <span class="visually-hidden">Anterior</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselTablet" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                            <span class="visually-hidden">Siguiente</span>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
            <!--PC 3CARTAS-->
            <div class="d-none d-lg-block p-5">
                <div id="carouselDesktop" class="carousel slide w-100" data-bs-ride="carousel" data-bs-interval="5000">
                    <div class="carousel-inner interior-escritorio">
                        <?php mostrarServicios($servicios, 3); ?>
                    </div>
                    <?php if (count($servicios) > 3): ?>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselDesktop" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                            <span class="visually-hidden">Anterior</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselDesktop" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                            <span class="visually-hidden">Siguiente</span>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
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
                <div class="franja-horario bg-success p-3 text-white d-flex flex-column flex-md-row align-items-center shadow position-relative">

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
                    <img src="<?= ASSETS_URL ?>/imgs/golden.png" alt="Golden Retriever" class="imagen-perro position-absolute end-0 d-none d-md-inline-block">

                </div>

            </div>
            <!-- MOBIL -->
            <div class="horario-movil d-lg-none">

                <div class="horario-movil-titulo">
                    <h2>Horario de Atención</h2>
                </div>

                <div class="horario-movil-franja">

                    <div class="horario-movil-reloj">
                        <img src="<?= ASSETS_URL ?>/svgs/reloj.svg"
                            alt="Reloj"
                            width="48"
                            height="48">
                    </div>

                    <div class="horario-movil-info">
                        <h3>Horario de atención</h3>
                        <p>Lunes a Viernes</p>
                        <span>10:00 a 17:00 horas</span>
                    </div>

                    <div class="horario-movil-telefono">
                        <a href="tel:098230818">
                            <i class="bi bi-telephone-fill"></i>
                            <span>098 230 818</span>
                        </a>
                    </div>

                </div>

            </div>
        </div>
    </section>
</body>

</html>