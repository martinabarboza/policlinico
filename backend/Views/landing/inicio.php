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
    <section class="portada p-2" id="inicio">
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
    <section class="pb-5" id="servicios">
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
    <section class="landing-about" id="sobre-nosotros">
        <div class="container">
            <div class="landing-about__content">
                <p class="landing-section-label">CENUR Litoral Norte · Udelar</p>
                <h2>Sobre nosotros</h2>
                <p class="landing-about__intro">
                    El Policlínico Veterinario del CENUR Litoral Norte es un servicio universitario en Salto
                    que integra la atención veterinaria con la formación práctica y el vínculo con la comunidad.
                </p>
                <div class="landing-about__details">
                    <p>
                        En el marco de las funciones universitarias de enseñanza, investigación y extensión,
                        el trabajo del Policlínico también acerca a la comunidad actividades educativas sobre
                        bienestar animal, tenencia responsable y prevención de zoonosis.
                    </p>
                    <a class="landing-section-link"
                       href="https://www.litoralnorte.udelar.edu.uy/institucional/cenur-litoral-norte/que-es-el-cenur"
                       target="_blank" rel="noopener noreferrer">
                        Conocer el CENUR Litoral Norte
                        <i class="bi bi-box-arrow-up-right" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="landing-contact" id="contacto">
        <div class="container">
            <div class="landing-contact__card">
                <div class="landing-contact__copy">
                    <p class="landing-section-label">Contacto e información</p>
                    <h2>Consultá por canales oficiales</h2>
                    <p>
                        Para confirmar horarios, disponibilidad, requisitos de atención o cómo coordinar una consulta,
                        comunicate directamente con el CENUR Litoral Norte o consultá la información institucional
                        del Policlínico.
                    </p>
                    <p class="landing-contact__note">
                        Esta página es informativa y no realiza reservas de turnos.
                    </p>
                </div>
                <div class="landing-contact__actions">
                    <a class="landing-contact__button landing-contact__button--primary"
                       href="https://www.litoralnorte.udelar.edu.uy/contactos"
                       target="_blank" rel="noopener noreferrer">
                        Contacto del CENUR
                        <i class="bi bi-box-arrow-up-right" aria-hidden="true"></i>
                    </a>
                    <a class="landing-contact__button landing-contact__button--secondary"
                       href="https://www.litoralnorte.udelar.edu.uy/"
                       target="_blank" rel="noopener noreferrer">
                        Sitio oficial del CENUR
                        <i class="bi bi-box-arrow-up-right" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>
</body>

</html>