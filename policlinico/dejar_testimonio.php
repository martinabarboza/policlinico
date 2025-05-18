<?php
// Incluir configuración y funciones
include 'includes/config.php';
include 'includes/functions.php';

// Variables para el formulario y mensajes
$mensaje = '';
$tipo_mensaje = '';
$datos = [
    'nombre' => '',
    'valoracion' => '',
    'texto' => ''
];

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitizar y validar datos del formulario
    $datos['nombre'] = limpiarDato($_POST['nombre'] ?? '');
    $datos['valoracion'] = (int) ($_POST['valoracion'] ?? 0);
    $datos['texto'] = limpiarDato($_POST['texto'] ?? '');
    
    // Validar datos
    $errores = [];
    
    if (empty($datos['nombre'])) {
        $errores['nombre'] = 'Por favor, ingrese su nombre completo';
    }
    
    if (empty($datos['valoracion']) || $datos['valoracion'] < 1 || $datos['valoracion'] > 5) {
        $errores['valoracion'] = 'Por favor, seleccione una valoración entre 1 y 5 estrellas';
    }
    
    if (empty($datos['texto'])) {
        $errores['texto'] = 'Por favor, ingrese su testimonio';
    } elseif (strlen($datos['texto']) < 10) {
        $errores['texto'] = 'Su testimonio debe tener al menos 10 caracteres';
    }
    
    // Si no hay errores, procesar el testimonio
    if (empty($errores)) {
        // Conectar a la base de datos
        $conn = conectarDB();
        
        try {
            // Guardar el testimonio en la base de datos
            $sql = "INSERT INTO testimonios (autor_nombre, texto, valoracion, aprobado) VALUES (?, ?, ?, 0)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssi', $datos['nombre'], $datos['texto'], $datos['valoracion']);
            $stmt->execute();
            
            // Mensaje de éxito
            $mensaje = 'Gracias por compartir su experiencia. Su testimonio será revisado y publicado pronto.';
            $tipo_mensaje = 'success';
            
            // Registrar la acción
            $ip = $_SERVER['REMOTE_ADDR'];
            $detalles = "Testimonio de {$datos['nombre']} - Valoración: {$datos['valoracion']}/5";
            
            $sql = "INSERT INTO log_acciones (usuario_id, accion, detalles, ip_address) VALUES (NULL, 'nuevo_testimonio', ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $detalles, $ip);
            $stmt->execute();
            
            // Resetear los datos del formulario
            $datos = [
                'nombre' => '',
                'valoracion' => '',
                'texto' => ''
            ];
        } catch (Exception $e) {
            $mensaje = 'Ha ocurrido un error al enviar su testimonio. Por favor, inténtelo de nuevo más tarde.';
            $tipo_mensaje = 'error';
            
            // En un entorno de producción, registrar el error
            error_log('Error al guardar testimonio: ' . $e->getMessage());
        }
        
        // Cerrar conexión
        $conn->close();
    } else {
        // Hay errores, mostrar mensaje general
        $mensaje = 'Por favor, corrija los errores en el formulario.';
        $tipo_mensaje = 'error';
    }
}

// Obtener testimonios aprobados para mostrar
$conn = conectarDB();
$testimonios = obtenerTestimoniosAprobados($conn, 6);
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dejar Testimonio - Policlínico Veterinario</title>
    <meta name="description" content="Comparta su experiencia con el Policlínico Veterinario. Sus comentarios nos ayudan a mejorar nuestros servicios y a informar a otros dueños de mascotas.">
    
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
                <li>Dejar Testimonio</li>
            </ul>
        </div>
    </div>
    
    <!-- Sección de Testimonios -->
    <section class="testimonials-page section-padding">
        <div class="container">
            <div class="section-header">
                <h1>Comparta su Experiencia</h1>
                <p>Sus comentarios nos ayudan a mejorar nuestros servicios y a informar a otros dueños de mascotas.</p>
            </div>
            
            <?php if (!empty($mensaje)): ?>
                <div class="alert alert-<?php echo $tipo_mensaje; ?>">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>
            
            <div class="testimonial-form-container">
                <h2>Déjenos su Testimonio</h2>
                <form id="testimonialForm" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div class="form-group">
                        <label for="nombre">Nombre Completo <span class="required">*</span></label>
                        <input type="text" id="nombre" name="nombre" value="<?php echo $datos['nombre']; ?>" required>
                        <?php if (!empty($errores['nombre'])): ?>
                            <div class="error-message"><?php echo $errores['nombre']; ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label>Su Valoración <span class="required">*</span></label>
                        <div class="rating-input">
                            <div class="star-rating">
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                    <input type="radio" id="star<?php echo $i; ?>" name="valoracion" value="<?php echo $i; ?>" <?php echo $datos['valoracion'] == $i ? 'checked' : ''; ?>>
                                    <label for="star<?php echo $i; ?>"><i class="fas fa-star"></i></label>
                                <?php endfor; ?>
                            </div>
                            <div class="rating-text">Seleccione su valoración</div>
                        </div>
                        <?php if (!empty($errores['valoracion'])): ?>
                            <div class="error-message"><?php echo $errores['valoracion']; ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="texto">Su Comentario <span class="required">*</span></label>
                        <textarea id="texto" name="texto" rows="5" required><?php echo $datos['texto']; ?></textarea>
                        <?php if (!empty($errores['texto'])): ?>
                            <div class="error-message"><?php echo $errores['texto']; ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Enviar Testimonio</button>
                    </div>
                </form>
            </div>
            
            <!-- Testimonios existentes -->
            <?php if (!empty($testimonios)): ?>
                <div class="testimonials-showcase">
                    <h2>Lo que dicen nuestros clientes</h2>
                    <div class="testimonials-grid">
                        <?php foreach ($testimonios as $testimonio): ?>
                            <div class="testimonial-card">
                                <div class="testimonial-content">
                                    <p class="testimonial-text">"<?php echo $testimonio['texto']; ?>"</p>
                                    <?php echo generarEstrellas($testimonio['valoracion']); ?>
                                    <p class="testimonial-author">- <?php echo $testimonio['autor_nombre']; ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
    
    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
    
    <!-- JavaScript -->
    <script src="js/main.js"></script>
    <script>
        // Script para la funcionalidad de valoración por estrellas
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('.star-rating input');
            const ratingText = document.querySelector('.rating-text');
            
            const ratingLabels = {
                1: 'Muy insatisfecho',
                2: 'Insatisfecho',
                3: 'Normal',
                4: 'Satisfecho',
                5: 'Muy satisfecho'
            };
            
            // Inicializar el texto según la valoración seleccionada
            stars.forEach(star => {
                if (star.checked) {
                    ratingText.textContent = ratingLabels[star.value];
                }
                
                star.addEventListener('change', function() {
                    ratingText.textContent = ratingLabels[this.value];
                });
            });
        });
    </script>
</body>
</html>