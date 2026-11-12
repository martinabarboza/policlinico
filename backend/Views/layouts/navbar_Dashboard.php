<?php $usuario = Auth::user(); ?>
<!-- Navegación superior compartida: búsqueda global, alertas y menú de cuenta. -->
<!-- Empieza Navbar -->
<div id="content" class="contenido-principal">

    <nav class="navbar navbar-expand bg-light navbar-light sticky-top px-4 py-0">
        <a href="<?= htmlspecialchars(url('dashboard'), ENT_QUOTES, 'UTF-8') ?>" class="navbar-brand d-flex d-lg-none me-4">
            <h2 class="text-light mb-0"><i class="fa fa-horse"></i></h2>
        </a>
        <a href="#" class="boton-menu-lateral flex-shrink-0 bg-light text-secondary">
            <i class="fa fa-ellipsis-v"></i>
        </a>
        <!-- tipoBusqueda decide el centro; busqueda se conserva como parámetro GET. -->
        <form class="d-none d-md-flex ms-4 align-items-center gap-2 form-navbar-busqueda" action="/buscar-centro" method="get">
            <label class="visually-hidden" for="tipo-busqueda-navbar">Buscar en</label>
            <select class="form-select border-0" id="tipo-busqueda-navbar" name="tipoBusqueda" aria-label="Tipo de búsqueda">
                <option value="paciente" data-placeholder="Nombre o chip del paciente..." selected>Paciente</option>
                <option value="tutor" data-placeholder="Nombre, documento o teléfono del tutor...">Tutor</option>
            </select>
            <label class="visually-hidden" for="texto-busqueda-navbar">Texto a buscar</label>
            <input class="form-control border-0"
                id="texto-busqueda-navbar"
                type="search"
                name="busqueda"
                placeholder="Nombre o chip del paciente..."
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
                    <i class="fa fa-bell me-lg-2" aria-hidden="true"></i>
                    <span class="d-none d-lg-inline-flex">Notificaciones</span>
                    <?php if (!empty($alertasNotificaciones)): ?>
                        <span class="badge rounded-pill bg-danger"><?= count($alertasNotificaciones) ?></span>
                    <?php endif; ?>
                </a>
                <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                    <?php if (!empty($alertasNotificaciones)): ?>
                        <?php foreach ($alertasNotificaciones as $alerta): ?>
                            <a href="<?= htmlspecialchars(url('notificaciones'), ENT_QUOTES, 'UTF-8') ?>" class="dropdown-item">
                                <span class="fw-semibold">Consulta atrasada: <?= htmlspecialchars($alerta['nombre_paciente'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></span>
                                <small class="d-block text-muted">
                                    <?= htmlspecialchars((new DateTimeImmutable($alerta['fecha_hora']))->format('d/m/Y H:i'), ENT_QUOTES, 'UTF-8') ?>
                                    · <?= htmlspecialchars($alerta['estado'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
                                </small>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <span class="dropdown-item-text text-muted">No tienes alertas pendientes</span>
                    <?php endif; ?>
                    <a href="<?= htmlspecialchars(url('notificaciones'), ENT_QUOTES, 'UTF-8') ?>" class="dropdown-item text-center">Ver todas las alertas</a>
                </div>
            </div>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <span class="d-none d-lg-inline-flex"><?= htmlspecialchars((string) $usuario['nombre'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></span>
                </a>
                <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                    <a href="<?= htmlspecialchars(url('perfil'), ENT_QUOTES, 'UTF-8') ?>" class="dropdown-item">Mi Perfil</a>
                    <a href="<?= htmlspecialchars(url('cerrar-sesion'), ENT_QUOTES, 'UTF-8') ?>" class="dropdown-item">Cerrar Sesión</a>
                </div>
            </div>
        </div>
    </nav>
    <!-- Fin Navbar -->