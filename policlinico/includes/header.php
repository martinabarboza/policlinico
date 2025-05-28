<header class="site-header">
    <div class="top-bar">
        <div class="container">
            <div class="contact-info">
                <span><i class="fas fa-phone"></i> <?php echo config('site_phone'); ?></span>
                <span><i class="fas fa-envelope"></i> <?php echo config('site_email'); ?></span>
                <span><i class="fas fa-map-marker-alt"></i> <?php echo config('site_address'); ?></span>
            </div>
            <div class="social-icons">
                <a href="<?php echo config('social_facebook'); ?>" target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook-f"></i></a>
                <a href="<?php echo config('social_instagram'); ?>" target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a>
                <a href="<?php echo config('social_twitter'); ?>" target="_blank" rel="noopener noreferrer"><i class="fab fa-twitter"></i></a>
            </div>
        </div>
    </div>
    <div class="main-header">
        <div class="container">
            <div class="logo">
                <a href="index.php">
                    <img src="assets/logo.png" alt="Logo Policlínico Veterinario">
                </a>
            </div>
            <nav class="main-nav">
                <button class="menu-toggle" aria-label="Abrir menú">
                    <i class="fas fa-bars"></i>
                </button>
                <ul class="nav-menu">
                    <li><a href="index.php">Inicio</a></li>
                    <li><a href="servicios.php">Servicios</a></li>
                    <li><a href="equipo.php">Equipo</a></li>
                    <li><a href="citas.php">Solicitar Cita</a></li>
                    <li><a href="contacto.php">Contacto</a></li>
                    <?php if (estaLogueado()): ?>
                        <li class="user-menu">
                            <a href="#"><i class="fas fa-user"></i> <?php echo $_SESSION['user_username']; ?> <i class="fas fa-caret-down"></i></a>
                            <ul class="dropdown">
                                <li><a href="admin/index.php"><i class="fas fa-tachometer-alt"></i> Panel</a></li>
                                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li><a href="login.php" class="login-btn"><i class="fas fa-user"></i> Iniciar Sesión</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
</header>