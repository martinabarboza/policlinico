<footer class="site-footer">
    <div class="footer-main">
        <div class="container">
            <div class="footer-columns">
                <div class="footer-column">
                    <div class="footer-logo">
                        <img src="assets/logo.png" alt="<?php echo SITE_NAME; ?>">
                    </div>
                    <p>Centro veterinario especializado en el cuidado y tratamiento de pequeños y grandes animales. Servicio de la Universidad de la República para la comunidad de Salto y alrededores.</p>
                    <div class="footer-social">
                        <a href="<?php echo config('social_facebook'); ?>" target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook-f"></i></a>
                        <a href="<?php echo config('social_instagram'); ?>" target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a>
                        <a href="<?php echo config('social_twitter'); ?>" target="_blank" rel="noopener noreferrer"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
                <div class="footer-column">
                    <h3>Enlaces Rápidos</h3>
                    <ul class="footer-links">
                        <li><a href="index.php">Inicio</a></li>
                        <li><a href="servicios.php">Servicios</a></li>
                        <li><a href="equipo.php">Equipo</a></li>
                        <li><a href="citas.php">Solicitar Cita</a></li>
                        <li><a href="contacto.php">Contacto</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Servicios</h3>
                    <ul class="footer-links">
                        <?php
                        // Obtener servicios activos (limitar a 5)
                        $conn = conectarDB();
                        $servicios = obtenerServicios($conn);
                        $conn->close();
                        
                        $count = 0;
                        foreach ($servicios as $servicio) {
                            if ($count < 5) {
                                echo '<li><a href="servicios.php?id=' . $servicio['id'] . '">' . $servicio['nombre'] . '</a></li>';
                                $count++;
                            }
                        }
                        ?>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Contáctenos</h3>
                    <ul class="footer-contact">
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            <span><?php echo config('site_address'); ?></span>
                        </li>
                        <li>
                            <i class="fas fa-phone"></i>
                            <span><?php echo config('site_phone'); ?></span>
                        </li>
                        <li>
                            <i class="fas fa-envelope"></i>
                            <span><?php echo config('site_email'); ?></span>
                        </li>
                        <li>
                            <i class="fas fa-clock"></i>
                            <span>Lunes a Viernes: 8:00 - 18:00</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-affiliations">
        <div class="container">
            <div class="affiliation-logos">
                <a href="https://udelar.edu.uy/portal" target="_blank" rel="noopener noreferrer">
                    <img src="assets/universidad-republica.png" alt="Universidad de la República">
                </a>
                <a href="https://www.litoralnorte.udelar.edu.uy" target="_blank" rel="noopener noreferrer">
                    <img src="assets/logos/cenur-litoral-norte.svg" alt="CENUR Litoral Norte">
                </a>
                <a href="https://www.fvet.edu.uy" target="_blank" rel="noopener noreferrer">
                    <img src="assets/logos/fvet.png" alt="Facultad de Veterinaria">
                </a>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <div class="copyright">
                <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. Todos los derechos reservados.</p>
            </div>
            <div class="footer-nav">
                <ul>
                    <li><a href="terminos.php">Términos y Condiciones</a></li>
                    <li><a href="privacidad.php">Política de Privacidad</a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>

<!-- JavaScript para menú móvil -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.querySelector('.menu-toggle');
    const navMenu = document.querySelector('.nav-menu');
    
    if (menuToggle && navMenu) {
        menuToggle.addEventListener('click', function() {
            navMenu.classList.toggle('active');
            menuToggle.classList.toggle('active');
        });
    }
    
    // Cerrar menú al hacer clic en enlaces
    const navLinks = document.querySelectorAll('.nav-menu a');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                navMenu.classList.remove('active');
                menuToggle.classList.remove('active');
            }
        });
    });
    
    // Gestión de submenús en móvil
    const hasDropdown = document.querySelectorAll('.user-menu');
    hasDropdown.forEach(item => {
        const link = item.querySelector('a');
        if (link) {
            link.addEventListener('click', function(e) {
                if (window.innerWidth <= 768) {
                    e.preventDefault();
                    item.classList.toggle('show-dropdown');
                }
            });
        }
    });
});
</script>