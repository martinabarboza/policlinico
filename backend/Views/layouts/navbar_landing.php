<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">

        <button class="navbar-toggler ms-auto   " type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 gap-3">
                <li class="nav-item activo">
                    <a class="nav-link active" aria-current="page" href="#">INICIO</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">SERVICIOS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">SOBRE NOSOTROS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">CONTACTO</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item btn li-ingresar rounded rounded-2 d-flex  w-auto align-items-center gap-2">
                    <img src="<?= ASSETS_URL ?>/svgs/user-svgrepo-com.svg" alt="">
                    <a class="btn-ingresar" aria-current="page" href="<?= url('login') ?>">INGRESAR AL PORTAL</a>
                </li>
            </ul>
        </div>
    </div>
</nav>