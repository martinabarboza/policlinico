<?php
// Incluir configuración y funciones
include 'includes/config.php';
include 'includes/functions.php';

// Conectar a la base de datos
$conn = conectarDB();

// Verificar si se solicita un servicio específico
$servicio_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$servicio_detalle = null;

if ($servicio_id > 0) {
    // Obtener información del servicio específico
    $servicio_detalle = obtenerServicioPorId($conn, $servicio_id);
}

// Obtener todos los servicios
$servicios = obtenerServicios($conn);

// Cerrar conexión
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $servicio_detalle ? $servicio_detalle['nombre'] : 'Servicios'; ?> - Policlínico Veterinario</title>
    <meta name="description" content="<?php echo $servicio_detalle ? truncarTexto($servicio_detalle['descripcion'], 160) : 'Descubra nuestra amplia gama de servicios veterinarios para el cuidado y bienestar de su mascota. Ofrecemos consultas, vacunaciones, cirugías y más con profesionales experimentados.'; ?>">
    
    <!-- CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/styles.css">
    
    <!-- Favicon -->
    <link rel="icon" href="assets/favicon.ico">
    
</head>
<body>
    <!-- Header -->
    <?php include 'includes/header.php'; ?>
    
    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <div class="container">
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <?php if ($servicio_detalle): ?>
                    <li><a href="servicios.php">Servicios</a></li>
                    <li><?php echo $servicio_detalle['nombre']; ?></li>
                <?php else: ?>
                    <li>Servicios</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    
    <!-- Contenido Principal -->
    <div class="container">
        <?php if ($servicio_detalle): ?>
            <!-- Detalle de Servicio -->
            <section class="service-detail-section section-padding">
                <div class="service-detail">
                    <div class="service-image">
                        <?php 
                        // Determinar qué imagen mostrar según el servicio
                        $imagen_src = 'assets/images/servicios/default.jpg';
                        
                        switch (strtolower($servicio_detalle['nombre'])) {
                            case 'consulta general':
                                $imagen_src = 'assets/images/servicios/consulta.jpg';
                                break;
                            case 'vacunación':
                                $imagen_src = 'assets/images/servicios/vacunacion.jpg';
                                break;
                            case 'cirugía menor':
                                $imagen_src = 'assets/images/servicios/cirugia.jpg';
                                break;
                            case 'análisis de laboratorio':
                                $imagen_src = 'assets/images/servicios/laboratorio.jpg';
                                break;
                            case 'limpieza dental':
                                $imagen_src = 'assets/images/servicios/dental.jpg';
                                break;
                        }
                        ?>
                        <img src="<?php echo $imagen_src; ?>" alt="<?php echo $servicio_detalle['nombre']; ?>">
                    </div>
                    
                    <div class="service-info">
                        <h1><?php echo $servicio_detalle['nombre']; ?></h1>
                        
                        <div class="service-meta">
                            <?php if ($servicio_detalle['duracion']): ?>
                                <div class="service-meta-item">
                                    <i class="fas fa-clock"></i>
                                    <span>Duración aproximada: <?php echo $servicio_detalle['duracion']; ?> minutos</span>
                                </div>
                            <?php endif; ?>
                            
                            <div class="service-meta-item">
                                <i class="fas fa-tag"></i>
                                <span>Servicio Veterinario</span>
                            </div>
                        </div>
                        
                        <div class="service-description">
                            <?php echo nl2br($servicio_detalle['descripcion']); ?>
                        </div>
                        
                        <?php if (!is_null($servicio_detalle['precio'])): ?>
                            <div class="service-price-detail">
                                <?php echo formatearPrecio($servicio_detalle['precio']); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="service-actions">
                            <a href="citas.php?servicio_id=<?php echo $servicio_detalle['id']; ?>" class="btn btn-primary">Solicitar Cita</a>
                            <a href="contacto.php" class="btn btn-outline">Consultar</a>
                        </div>
                    </div>
                </div>
                
                <div class="other-services">
                    <h2>Otros Servicios</h2>
                    
                    <div class="services-grid">
                        <?php 
                        $count = 0;
                        foreach ($servicios as $servicio):
                            // Mostrar solo servicios diferentes al actual y limitar a 3
                            if ($servicio['id'] != $servicio_detalle['id'] && $count < 3):
                                $count++;
                        ?>
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
                        <?php 
                            endif;
                        endforeach; 
                        ?>
                    </div>
                </div>
            </section>
        <?php else: ?>
            <!-- Lista de Servicios -->
            <section class="services-section section-padding">
                <div class="section-header">
                    <h1>Servicios</h1>
                    <p>En el Policlínico Veterinario ofrecemos una amplia gama de servicios para garantizar la salud y el bienestar de su mascota.</p>
                </div>
                
                <?php if (empty($servicios)): ?>
                    <p class="empty-message">No hay servicios disponibles en este momento.</p>
                <?php else: ?>
                    <div class="services-grid">
                        <?php foreach ($servicios as $servicio): ?>
                            <div class="service-card" id="<?php echo strtolower(str_replace(' ', '-', $servicio['nombre'])); ?>">
                                <div class="service-icon">
                                    <i class="fas <?php echo $servicio['icono']; ?>"></i>
                                </div>
                                <h3><?php echo $servicio['nombre']; ?></h3>
                                <p><?php echo truncarTexto($servicio['descripcion'], 150); ?></p>
                                <?php if (!is_null($servicio['precio'])): ?>
                                    <div class="service-price">
                                        <?php echo formatearPrecio($servicio['precio']); ?>
                                    </div>
                                <?php endif; ?>
                                <a href="servicios.php?id=<?php echo $servicio['id']; ?>" class="btn btn-sm">Más Información</a>
                            </div>
        <?php endforeach; ?>
</div>
<?php endif; ?>
</section>

<?php endif; ?>
                            
    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
    
    <!-- JavaScript -->
    <script src="js/main.js"></script>
</body>
</html>