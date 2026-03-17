<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Definir ruta base absoluta para evitar errores de inclusión
define('BASE_PATH', __DIR__);

// Incluir configuración y funciones con rutas absolutas
require_once BASE_PATH . '/includes/config.php';
require_once BASE_PATH . '/includes/functions.php';

// Conectar a la base de datos
$conn = conectarDB();

// Obtener servicios destacados
$sql = "SELECT * FROM servicios WHERE activo = 1 ORDER BY id LIMIT 4";
$resultado = $conn->query($sql);
$servicios = [];
if ($resultado && $resultado->num_rows > 0) {
    while ($servicio = $resultado->fetch_assoc()) {
        $servicios[] = $servicio;
    }
}

// Obtener personal destacado (veterinarios)
$sql = "SELECT p.*, u.nombre_completo, u.email 
        FROM personal p 
        INNER JOIN usuarios u ON p.usuario_id = u.id 
        WHERE p.activo = 1 AND u.rol = 'vet' 
        ORDER BY RAND() 
        LIMIT 3";
$resultado = $conn->query($sql);
$personal = [];
if ($resultado && $resultado->num_rows > 0) {
    while ($miembro = $resultado->fetch_assoc()) {
        $personal[] = $miembro;
    }
}

// Obtener testimonios aprobados
$testimonios = obtenerTestimoniosAprobados($conn, 3);

// Cerrar conexión
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Policlínico Veterinario - Centro de Atención Integral para Mascotas</title>
    <meta name="description" content="El Policlínico Veterinario ofrece servicios médicos de alta calidad para su mascota, incluyendo consultas, vacunaciones, cirugías y atención de emergencias. Médicos veterinarios con experiencia en Salto, Uruguay.">
    
    <!-- Open Graph Tags -->
    <meta property="og:title" content="Policlínico Veterinario - Salto, Uruguay">
    <meta property="og:description" content="Centro veterinario integral con servicios de consulta, vacunación, cirugía y emergencias. Atención profesional para el bienestar de su mascota.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo $config['site_url']; ?>">
    <meta property="og:image" content="<?php echo $config['site_url']; ?>/assets/logo.png">
    
    <!-- CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!--  <link rel="stylesheet" href="/policlinico/css/normalize.css"> -->
    <link rel="stylesheet" href="/policlinico/css/styles.css">
    
    <!-- Favicon -->
    <link rel="icon" href="assets/favicon.ico">
</head>
<body>
    <!-- Header -->
    <?php include 'includes/header.php'; ?>
    
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1>Cuidado veterinario de excelencia para su mascota</h1>
                <p>El Policlínico Veterinario es un centro de referencia para la atención integral de animales, asociado a la Universidad de la República - CENUR Litoral Norte.</p>
                <div class="hero-buttons">
                    <a href="citas.php" class="btn btn-primary">Agendar Cita</a>
                    <a href="servicios.php" class="btn btn-secondary">Servicios</a>
                </div>
            </div>
            <div class="hero-image">
                <img src="assets/hero-image.jpg" alt="Veterinario examinando a un perro">
            </div>
        </div>
    </section>
    
    <!-- Servicios Destacados -->
    <section class="services section-padding bg-light" id="servicios">
        <div class="container">
            <div class="section-header">
                <h2>Servicios</h2>
                <p>Ofrecemos una amplia variedad de servicios médicos para garantizar la salud y el bienestar de su mascota.</p>
            </div>
            
            <div class="services-grid">
                <?php if (empty($servicios)): ?>
                    <p class="empty-message">No hay servicios disponibles en este momento.</p>
                <?php else: ?>
                    <?php foreach ($servicios as $servicio): ?>
                        <div class="service-card">
                            <div class="service-icon">
                                <i class="fas <?php echo $servicio['icono']; ?>"></i>
                            </div>
                            <h3><?php echo $servicio['nombre']; ?></h3>
                            <p><?php echo truncarTexto($servicio['descripcion'], 120); ?></p>
                            <?php if (!is_null($servicio['precio'])): ?>
                                <div class="service-price">
                                    <?php echo formatearPrecio($servicio['precio']); ?>
                                </div>
                            <?php endif; ?>
                            <a href="servicios.php?id=<?php echo $servicio['id']; ?>" class="btn btn-sm">Más Información</a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <div class="section-footer">
                <a href="servicios.php" class="btn btn-primary">Ver Todos los Servicios</a>
            </div>
        </div>
    </section>
    
<!-- SOBRE NOSOTROS -->
<section class="about section-padding" id="nosotros">

<div class="container">

    <!-- HEADER -->
    <div class="section-header center">
        <h2>Sobre el Policlínico</h2>
        <p class="subtitle">
        Atención clínica, formación académica e investigación veterinaria en un mismo espacio.
        </p>
    </div>


    <!-- BLOQUE 1 -->
    <div class="about-grid">

        <div class="about-img">
            <img src="assets/veterinaria1.png" alt="Inauguracion del policlinico">
        </div>

        <div class="about-text">
            <h3>Nuestra Historia</h3>

            <p>
            El Policlínico Veterinario es un espacio dedicado a la atención médica de animales
            y al desarrollo de investigación clínica veterinaria. Su enfoque principal se basa
            en la medicina veterinaria basada en evidencia, priorizando la calidad del registro
            clínico y el seguimiento de cada paciente.
            </p>

            <p>
            A diferencia de las clínicas comerciales, el policlínico también cumple una función
            educativa, permitiendo que estudiantes y profesionales participen en el proceso de
            diagnóstico, tratamiento y análisis clínico.
            </p>

        </div>

    </div>


    <!-- BLOQUE 2 -->
    <div class="about-grid reverse">

        <div class="about-text">

            <h3>Enfoque</h3>

            <p>
            El objetivo principal del policlínico es generar información clínica confiable que
            permita mejorar los tratamientos veterinarios y analizar la evolución de los
            pacientes a lo largo del tiempo.
            </p>

            <p>
            A través del uso de tecnología, registros clínicos estructurados y análisis de
            datos veterinarios, buscamos contribuir al desarrollo del conocimiento científico
            dentro del área de la medicina veterinaria.
            </p>

        </div>

        <div class="about-img">
            <img src="assets/veterinaria2.jpg" alt="Equipo veterinario">
        </div>

    </div>


    <!-- MISIÓN VISIÓN VALORES -->
    <div class="cards-grid">

        <div class="info-card">
            <div class="icon"><i class="fas fa-bullseye"></i></div>
            <h3>Misión</h3>

            <p>
            Brindar atención veterinaria de calidad mientras formamos profesionales
            comprometidos con la medicina basada en evidencia y el registro clínico responsable.
            </p>

        </div>


        <div class="info-card">
            <div class="icon"><i class="fas fa-eye"></i></div>
            <h3>Visión</h3>

            <p>
            Convertirnos en un referente en el uso de tecnología aplicada a la medicina
            veterinaria, impulsando sistemas que permitan mejorar la atención médica,
            la investigación clínica y el análisis de datos veterinarios.
            </p>

        </div>


        <div class="info-card">
            <div class="icon"><i class="fas fa-star"></i></div>
            <h3>Valores</h3>

            <p>
            Nos guiamos por el rigor clínico, la formación académica, la investigación
            y la innovación tecnológica, asegurando registros precisos y un seguimiento
            responsable de cada paciente.
            </p>

        </div>

    </div>


    <!-- ESTADÍSTICAS -->
    <div class="stats">

        <div class="stat">
            <h3>500+</h3>
            <p>Pacientes atendidos</p>
        </div>

        <div class="stat">
            <h3>10+</h3>
            <p>Profesionales</p>
        </div>

        <div class="stat">
            <h3>100+</h3>
            <p>Registros clínicos analizados</p>
        </div>

        <div class="stat">
            <h3>5+</h3>
            <p>Proyectos de investigación</p>
        </div>

    </div>

</div>
</section>
    <!-- Equipo -->
    <section class="team section-padding bg-light" id="equipo">
        <div class="container">
            <div class="section-header">
                <h2>Nuestro Equipo</h2>
                <p>Contamos con profesionales altamente calificados y comprometidos con la salud y el bienestar de su mascota.</p>
            </div>
            
            <div class="team-grid">
                <?php if (empty($personal)): ?>
                    <p class="empty-message">No hay personal disponible en este momento.</p>
                <?php else: ?>
                    <?php foreach ($personal as $miembro): ?>
                        <div class="team-card">
                            <div class="team-image">
                                <?php if ($miembro['foto']): ?>
                                    <img src="<?php echo $miembro['foto']; ?>" alt="<?php echo $miembro['nombre_completo']; ?>">
                                <?php else: ?>
                                    <img src="assets/images/default-profile.jpg" alt="<?php echo $miembro['nombre_completo']; ?>">
                                <?php endif; ?>
                            </div>
                            <div class="team-info">
                                <h3><?php echo $miembro['nombre_completo']; ?></h3>
                                <p class="team-role"><?php echo $miembro['especialidad']; ?></p>
                                <?php if ($miembro['bio']): ?>
                                    <p class="team-bio"><?php echo truncarTexto($miembro['bio'], 100); ?></p>
                                <?php endif; ?>
                                <div class="team-contact">
                                    <a href="mailto:<?php echo $miembro['email']; ?>"><i class="fas fa-envelope"></i></a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <div class="section-footer">
                <a href="equipo.php" class="btn btn-primary">Conocer a Todo el Equipo</a>
            </div>
        </div>
    </section> 
           
        </div>
    </section>
    
    <!-- Contacto y Ubicación -->
    <section class="contact section-padding bg-light" id="contacto">
        <div class="container">
            <div class="section-header">
                <h2>Contacto y Ubicación</h2>
                <p>Estamos a su disposición para resolver cualquier consulta</p>
            </div>
            
            <div class="contact-grid">
                <div class="contact-info">
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <h3>Dirección</h3>
                        <p>Campus Universitario CENUR Litoral Norte<br>
                        Rivera 1350, Salto, Uruguay</p>
                    </div>
                    
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <h3>Teléfono</h3>
                        <p><a href="tel:+59847334816">+598 4733 4816</a></p>
                    </div>
                    
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <h3>Email</h3>
                        <p><a href="mailto:policlinico@fvet.edu.uy">policlinico@fvet.edu.uy</a></p>
                    </div>
                    
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h3>Horario de Atención</h3>
                        <p>Lunes a Viernes: 8:00 - 18:00<br>
                        Sábados: 8:00 - 12:00</p>
                    </div>
                </div>
                
                <div class="map-container">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3393.3432945681757!2d-57.96760482376904!3d-31.383731974220403!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95addd5dffbc9823%3A0x17a79a5eb06bbb34!2sCENUR%20Litoral%20Norte%20-%20Sede%20Salto!5e0!3m2!1ses!2suy!4v1684178352284!5m2!1ses!2suy" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
            
            <div class="section-footer">
                <a href="contacto.php" class="btn btn-primary">Contáctenos</a>
            </div>
        </div>
    </section>
    
    <!-- Instituciones Asociadas -->
    <section class="partners section-padding">
        <div class="container">
            <div class="instituciones">
                <h2>Instituciones Asociadas</h2>
            </div>
            
            <div class="partners-logos">
                <?php include 'includes/organization_logos.php'; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
    
    <!-- JavaScript -->
    <script src="js/main.js">    </script>
<script>
</script>

</body>
</html>