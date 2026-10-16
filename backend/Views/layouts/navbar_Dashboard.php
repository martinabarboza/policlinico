<?php $usuario = Auth::user(); ?>
<!-- Empieza Navbar -->
<div id="content" class="contenido-principal">

    <nav class="navbar navbar-expand bg-light navbar-light sticky-top px-4 py-0">
        <a href="index.php" class="navbar-brand d-flex d-lg-none me-4">
            <h2 class="text-light mb-0"><i class="fa fa-horse"></i></h2>
        </a>
        <a href="#" class="boton-menu-lateral flex-shrink-0 bg-light text-secondary">
            <i class="fa fa-ellipsis-v"></i>
        </a>
        <!-- tipoBusqueda decide el centro; busqueda se conserva como parámetro GET. -->
        <form class="d-none d-md-flex ms-4 align-items-center gap-2" action="/buscar-centro" method="get">
            <label class="visually-hidden" for="tipo-busqueda-navbar">Buscar en</label>
            <select class="form-select border-0" id="tipo-busqueda-navbar" name="tipoBusqueda" aria-label="Tipo de búsqueda">
                <option value="paciente" selected>Paciente</option>
                <option value="tutor">Tutor</option>
            </select>
            <label class="visually-hidden" for="texto-busqueda-navbar">Texto a buscar</label>
            <input class="form-control border-0"
                id="texto-busqueda-navbar"
                type="search"
                name="busqueda"
                placeholder="Nombre, chip, documento o teléfono..."
                minlength="1"
                maxlength="100"
                required>
            <button class="btn btn-primary" type="submit" aria-label="Buscar">
                <i class="fa fa-search" aria-hidden="true"></i>
            </button>
        </form>

        <div class="navbar-nav align-items-center ms-auto">
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fa fa-envelope me-lg-2"></i>
                    <span class="d-none d-lg-inline-flex">Mensajes</span>
                </a>
                <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                    <!--
                    <a href="#" class="dropdown-item">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <div class="ms-2">
                                <h6 class="fw-normal mb-0">Sofía te ha mandado un mensaje</h6>
                                <small>hace 15 minutos</small>
                            </div>
                        </div>
                    </a>
                    -->
                    <hr class="dropdown-divider">
                    <a href="centro-mensajes" class="dropdown-item text-center">Ver todos los mensajes</a>
                </div>
            </div>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fa fa-bell me-lg-2"></i>
                    <span class="d-none d-lg-inline-flex">Notificaciones</span>
                </a>
                <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                    <!-- 
                        <a href="#" class="dropdown-item">
                            <h6 class="fw-normal mb-0">Perfil actualizado</h6>
                            <small>hace 15 minutos</small>
                        </a>

                        <hr class="dropdown-divider">
                        <a href="#" class="dropdown-item">
                            <h6 class="fw-normal mb-0">Nuevo usuario agregado</h6>
                            <small>hace 2 días</small>
                        </a>
                        <hr class="dropdown-divider">
                        <a href="#" class="dropdown-item">
                            <h6 class="fw-normal mb-0">Contraseña cambiada</h6>
                            <small>hace 1 mes</small>
                        </a>
                        <hr class="dropdown-divider">
                    -->
                    <a href="centro-notificaciones" class="dropdown-item text-center">Ver todas las notificaciones</a>
                </div>
            </div>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <span class="d-none d-lg-inline-flex"><?= $usuario['nombre'] ?></span>
                </a>
                <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                    <a href="perfil" class="dropdown-item">Mi Perfil</a>
                    <a href="cerrar-sesion" class="dropdown-item">Cerrar Sesión</a>
                </div>
            </div>
        </div>
    </nav>
    <!-- Fin Navbar -->