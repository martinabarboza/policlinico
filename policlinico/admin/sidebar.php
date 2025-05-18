<aside class="admin-sidebar">
    <div class="admin-logo">
        <a href="../index.php">
            <img src="../assets/logo.svg" alt="<?php echo SITE_NAME; ?>">
        </a>
    </div>
    <ul class="admin-menu">
        <li class="admin-menu-item">
            <a href="index.php" class="admin-menu-link <?php echo basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>
        
        <?php if ($_SESSION['user_role'] === ROLE_ADMIN || $_SESSION['user_role'] === ROLE_VET): ?>
            <li class="admin-menu-item">
                <a href="registros_medicos.php" class="admin-menu-link <?php echo basename($_SERVER['PHP_SELF']) === 'registros_medicos.php' ? 'active' : ''; ?>">
                    <i class="fas fa-file-medical"></i>
                    <span>Registros Médicos</span>
                </a>
            </li>
        <?php endif; ?>
        
        <li class="admin-menu-item">
            <a href="citas.php" class="admin-menu-link <?php echo basename($_SERVER['PHP_SELF']) === 'citas.php' ? 'active' : ''; ?>">
                <i class="fas fa-calendar-alt"></i>
                <span>Citas</span>
            </a>
        </li>
        
        <li class="admin-menu-item">
            <a href="mascotas.php" class="admin-menu-link <?php echo basename($_SERVER['PHP_SELF']) === 'mascotas.php' ? 'active' : ''; ?>">
                <i class="fas fa-paw"></i>
                <span>Mascotas</span>
            </a>
        </li>
        
        <li class="admin-menu-item">
            <a href="propietarios.php" class="admin-menu-link <?php echo basename($_SERVER['PHP_SELF']) === 'propietarios.php' ? 'active' : ''; ?>">
                <i class="fas fa-user"></i>
                <span>Propietarios</span>
            </a>
        </li>
        
        <?php if ($_SESSION['user_role'] === ROLE_ADMIN): ?>
            <li class="admin-menu-item">
                <a href="personal.php" class="admin-menu-link <?php echo basename($_SERVER['PHP_SELF']) === 'personal.php' ? 'active' : ''; ?>">
                    <i class="fas fa-user-md"></i>
                    <span>Personal</span>
                </a>
            </li>
            
            <li class="admin-menu-item">
                <a href="servicios_admin.php" class="admin-menu-link <?php echo basename($_SERVER['PHP_SELF']) === 'servicios_admin.php' ? 'active' : ''; ?>">
                    <i class="fas fa-briefcase-medical"></i>
                    <span>Servicios</span>
                </a>
            </li>
            
            <li class="admin-menu-item">
                <a href="testimonios_admin.php" class="admin-menu-link <?php echo basename($_SERVER['PHP_SELF']) === 'testimonios_admin.php' ? 'active' : ''; ?>">
                    <i class="fas fa-comment"></i>
                    <span>Testimonios</span>
                </a>
            </li>
        <?php endif; ?>
        
        <li class="admin-menu-item">
            <a href="mi_perfil.php" class="admin-menu-link <?php echo basename($_SERVER['PHP_SELF']) === 'mi_perfil.php' ? 'active' : ''; ?>">
                <i class="fas fa-user-circle"></i>
                <span>Mi Perfil</span>
            </a>
        </li>
        
        <?php if ($_SESSION['user_role'] === ROLE_ADMIN): ?>
            <li class="admin-menu-item">
                <a href="configuracion.php" class="admin-menu-link <?php echo basename($_SERVER['PHP_SELF']) === 'configuracion.php' ? 'active' : ''; ?>">
                    <i class="fas fa-cog"></i>
                    <span>Configuración</span>
                </a>
            </li>
        <?php endif; ?>
        
        <li class="admin-menu-item">
            <a href="../logout.php" class="admin-menu-link">
                <i class="fas fa-sign-out-alt"></i>
                <span>Cerrar Sesión</span>
            </a>
        </li>
    </ul>
</aside>