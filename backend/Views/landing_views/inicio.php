<?php include __DIR__ . '/../layouts/header_landing.php'; ?>
<?php
function mostrarCartas($cartas, $cantidad)
{
    $grupos = array_chunk($cartas, $cantidad);

    foreach ($grupos as $index => $grupo) {

        if ($index === 0) {
            $active = 'active';
        } else {
            $active = '';
        }

        echo '
            <div class="carousel-item ' . $active . '">

                <div class="d-flex justify-content-center">';

        foreach ($grupo as $carta) {

            echo '
                        <div class="card mx-2 shadown shadow-sm" style="width: 18rem;">

                            <img src="' . $carta['imagen'] . '"
                                class="card-img-top"
                                alt="' . htmlspecialchars($carta['titulo']) . '">

                            <div class="card-body">

                                <h5 class="card-title">
                                    ' . htmlspecialchars($carta['titulo']) . '
                                </h5>

                                <p class="card-text text-justify">
                                    ' . htmlspecialchars($carta['descripcion']) . '
                                </p>

                                <a href="' . $carta['link'] . '"
                                class="btn btn-primary">
                                    Acceder al portal
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
    <section>
        <div class="d-flex justify-content-center mt-4 px-4">
            <ul class="ul-h2-container list-unstyled">
                <li>
                    <h2 class=" m-0 section1-h2 text-justify">Soluciones operativas y accesos rápidos</h2>
                </li>
                <li class=" d-flex justify-content-center">
                    <div class="separador-con-huella my-4">
                        <img src="<?= ASSETS_URL ?>/svgs/paw-print.svg" alt="">
                    </div>
                </li>
            </ul>
        </div>
        <!-- MOVIL -->
        <div class="d-block d-md-none p-5">
            <div class="d-flex justify-content-center w-100">
                <div id="carouselMobile"
                    class="carousel slide"
                    data-bs-ride="carousel"
                    data-bs-interval="5000">

                    <div class="carousel-inner inner-movil">
                        <?php
                        $InicioController = new InicioController();
                        $cartas = $InicioController->obtenerServicios();
                        mostrarCartas($cartas, 1);
                        ?>
                    </div>

                    <!-- Anterior -->
                    <button class="carousel-control-prev"
                        type="button"
                        data-bs-target="#carouselMobile"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>

                    <!-- Siguiente -->
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
        <!-- TABLET -->
        <div class="d-none d-md-block d-lg-none p-5">
            <div class="d-flex justify-content-center">
                <div id="carouselMobile"
                    class="carousel slide"
                    data-bs-ride="carousel"
                    data-bs-interval="5000">

                    <div class="carousel-inner inner-movil">
                        <?php
                        $InicioController = new InicioController();
                        $cartas = $InicioController->obtenerServicios();
                        mostrarCartas($cartas, 2);
                        ?>
                    </div>

                    <!-- Anterior -->
                    <button class="carousel-control-prev"
                        type="button"
                        data-bs-target="#carouselMobile"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>

                    <!-- Siguiente -->
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
        <!-- PC -->
        <div class="d-none d-lg-block p-5">
            <div class="d-flex justify-content-center">
                <div id="carouselMobile"
                    class="carousel slide"
                    data-bs-ride="carousel"
                    data-bs-interval="5000">

                    <div class="carousel-inner inner-movil gap-5 d-flex">
                        <?php
                        $InicioController = new InicioController();
                        $cartas = $InicioController->obtenerServicios();
                        mostrarCartas($cartas, 3);
                        ?>
                    </div>

                    <!-- Anterior -->
                    <button class="carousel-control-prev"
                        type="button"
                        data-bs-target="#carouselMobile"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>

                    <!-- Siguiente -->
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
    </section>
    <section>
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
        <!-- MOVIL -->
        <div class="d-block d-md-none p-5">
            <div class="d-flex justify-content-center">
                <div id="carouselMobile"
                    class="carousel slide"
                    data-bs-ride="carousel"
                    data-bs-interval="5000">

                    <div class="carousel-inner inner-movil">
                        <?php
                        $InicioController = new InicioController();
                        $cartas = $InicioController->obtenerServicios();
                        mostrarCartas($cartas, 1);
                        ?>
                    </div>

                    <!-- Anterior -->
                    <button class="carousel-control-prev"
                        type="button"
                        data-bs-target="#carouselMobile"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>

                    <!-- Siguiente -->
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
        <!-- TABLET -->
        <div class="d-none d-md-block d-lg-none p-5">
            <div class="d-flex justify-content-center">
                <div id="carouselMobile"
                    class="carousel slide"
                    data-bs-ride="carousel"
                    data-bs-interval="5000">

                    <div class="carousel-inner inner-movil">
                        <?php
                        $InicioController = new InicioController();
                        $cartas = $InicioController->obtenerServicios();
                        mostrarCartas($cartas, 2);
                        ?>
                    </div>

                    <!-- Anterior -->
                    <button class="carousel-control-prev"
                        type="button"
                        data-bs-target="#carouselMobile"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>

                    <!-- Siguiente -->
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
        <!-- PC -->
        <div class="d-none d-lg-block p-5">
            <div class="d-flex justify-content-center">
                <div id="carouselMobile"
                    class="carousel slide"
                    data-bs-ride="carousel"
                    data-bs-interval="5000">

                    <div class="carousel-inner inner-movil gap-5 d-flex">
                        <?php
                        $InicioController = new InicioController();
                        $cartas = $InicioController->obtenerServicios();
                        mostrarCartas($cartas, 3);
                        ?>
                    </div>

                    <!-- Anterior -->
                    <button class="carousel-control-prev"
                        type="button"
                        data-bs-target="#carouselMobile"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>

                    <!-- Siguiente -->
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
    </section>
    <section>
        <div class="p-5 ">

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
    </section>
    <?php include __DIR__ . '/../layouts/footer_landing.php'; ?>
</body>

</html>