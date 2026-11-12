        <!-- Navegación lateral compartida; la opción de administración depende del rol. -->
        <div class="menu-lateral pe-4 pb-3">
            <nav class="navbar">
                <!-- bg-light navbar-light -->
                <a href="<?= htmlspecialchars(url('dashboard'), ENT_QUOTES, 'UTF-8') ?>" class="navbar-brand mx-4 mt-4 mb-4">
                    <h3 class="text-light"><i class="fa fa-paw me-2"></i>PetCraft</h3>
                </a>
                <div class="d-flex align-items-center ms-4 mb-4">
                    <div class="position-relative">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-light text-secondary" style="width: 40px; height: 40px;">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
                    </div>
                    <div class="ms-3 text-light">
                        <h6 class="mb-0"><?= htmlspecialchars((string) $usuario['nombre'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></h6>
                        <span><?= htmlspecialchars((string) $usuario['rol'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></span>
                    </div>
                </div>
                <div class="navbar-nav w-100">
                    <a href="<?= htmlspecialchars(url('dashboard'), ENT_QUOTES, 'UTF-8') ?>" class="<?php if (str_contains($_SERVER['REQUEST_URI'], 'dashboard')) {
                                                                                                        echo 'nav-item nav-link active';
                                                                                                    } else {
                                                                                                        echo 'nav-item nav-link';
                                                                                                    } ?>"><i class="fa fa-home me-2"></i>Inicio</a>
                    <a href="<?= htmlspecialchars(url('centro-consultas'), ENT_QUOTES, 'UTF-8') ?>" class="<?php if (str_contains($_SERVER['REQUEST_URI'], 'centro-consultas')) {
                                                                                                                echo 'nav-item nav-link active';
                                                                                                            } else {
                                                                                                                echo 'nav-item nav-link';
                                                                                                            } ?>"><i class="fa fa-search me-2"></i>Consultorio</a>
                    <a href="<?= htmlspecialchars(url('centro-tutores'), ENT_QUOTES, 'UTF-8') ?>" class="<?php if (str_contains($_SERVER['REQUEST_URI'], 'centro-tutores')) {
                                                                                                                echo 'nav-item nav-link active';
                                                                                                            } else {
                                                                                                                echo 'nav-item nav-link';
                                                                                                            } ?>"><i class="fa fa-users me-2"></i>Tutores</a>
                    <a href="<?= htmlspecialchars(url('agenda'), ENT_QUOTES, 'UTF-8') ?>" class="<?php if (str_contains($_SERVER['REQUEST_URI'], 'agenda')) {
                                                                                                        echo 'nav-item nav-link active';
                                                                                                    } else {
                                                                                                        echo 'nav-item nav-link';
                                                                                                    } ?>"><i class="fa fa-calendar-alt me-2"></i>Agenda</a>
                    <a href="<?= htmlspecialchars(url('hospitalizacion'), ENT_QUOTES, 'UTF-8') ?>" class="<?php if (str_contains($_SERVER['REQUEST_URI'], 'hospitalizacion')) {
                                                                                                                echo 'nav-item nav-link active';
                                                                                                            } else {
                                                                                                                echo 'nav-item nav-link';
                                                                                                            } ?>"><i class="fa fa-hospital-alt me-2"></i>Hospitalización</a>


                    <?php if (Auth::rol() === 'Director'): ?>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-user-cog me-2"></i>
                            Administración
                        </a>
                        <div class="dropdown-menu border-0">
                            <a href="<?= htmlspecialchars(url('servicios'), ENT_QUOTES, 'UTF-8') ?>" class="dropdown-item">
                                Servicios
                            </a>
                            <a href="<?= htmlspecialchars(url('usuarios'), ENT_QUOTES, 'UTF-8') ?>" class="dropdown-item">
                                Usuarios
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </nav>
        </div>
        <!-- Fin Sidebar -->