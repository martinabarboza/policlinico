<?php
// Incluir configuración y funciones
include 'includes/config.php';
include 'includes/functions.php';

// Variables para el formulario y mensajes
$mensaje = '';
$tipo_mensaje = '';
$datos = [
    'nombre' => '',
    'email' => '',
    'telefono' => '',
    'asunto' => '',
    'mensaje' => ''
];

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitizar y validar datos del formulario
    $datos['nombre'] = limpiarDato($_POST['nombre'] ?? '');
    $datos['email'] = limpiarDato($_POST['email'] ?? '');
    $datos['telefono'] = limpiarDato($_POST['telefono'] ?? '');
    $datos['asunto'] = limpiarDato($_POST['asunto'] ?? '');
    $datos['mensaje'] = limpiarDato($_POST['mensaje'] ?? '');
    
    // Validar datos
    $errores = [];
    
    if (empty($datos['nombre'])) {
        $errores['nombre'] = 'Por favor, ingrese su nombre completo';
    }
    
    if (empty($datos['email'])) {
        $errores['email'] = 'Por favor, ingrese su email';
    } elseif (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
        $errores['email'] = 'Por favor, ingrese un email válido';
    }
    
    if (empty($datos['asunto'])) {
        $errores['asunto'] = 'Por favor, ingrese el asunto de su consulta';
    }
    
    if (empty($datos['mensaje'])) {
        $errores['mensaje'] = 'Por favor, ingrese su mensaje';
    }
    
    // Si no hay errores, procesar el mensaje
    if (empty($errores)) {
        // Conectar a la base de datos para guardar el mensaje
        $conn = conectarDB();
        
        try {
            // Guardar el mensaje en la base de datos (tabla de mensajes de contacto)
            // Nota: En un sistema real sería recomendable tener una tabla específica para esto
            
            // En este caso, simularemos el envío de email
            $destinatario = $config['admin_email'];
            $asunto = "Contacto web: {$datos['asunto']}";
            
            $cuerpoEmail = "<h2>Nuevo mensaje de contacto</h2>";
            $cuerpoEmail .= "<p><strong>Nombre:</strong> {$datos['nombre']}</p>";
            $cuerpoEmail .= "<p><strong>Email:</strong> {$datos['email']}</p>";
            $cuerpoEmail .= "<p><strong>Teléfono:</strong> {$datos['telefono']}</p>";
            $cuerpoEmail .= "<p><strong>Asunto:</strong> {$datos['asunto']}</p>";
            $cuerpoEmail .= "<p><strong>Mensaje:</strong><br>{$datos['mensaje']}</p>";
            
            // En un entorno real, aquí enviaríamos el email
            // enviarEmail($destinatario, $asunto, $cuerpoEmail);
            
            // En su lugar, solo registramos la acción
            $ip = $_SERVER['REMOTE_ADDR'];
            $detalles = "Mensaje de {$datos['nombre']} ({$datos['email']})";
            
            $sql = "INSERT INTO log_acciones (usuario_id, accion, detalles, ip_address) VALUES (NULL, 'contacto_web', ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $detalles, $ip);
            $stmt->execute();
            
            // Mensaje de éxito
            $mensaje = 'Su mensaje ha sido enviado correctamente. Nos pondremos en contacto a la brevedad.';
            $tipo_mensaje = 'success';
            
            // Resetear los datos del formulario
            $datos = [
                'nombre' => '',
                'email' => '',
                'telefono' => '',
                'asunto' => '',
                'mensaje' => ''
            ];
        } catch (Exception $e) {
            $mensaje = 'Ha ocurrido un error al enviar su mensaje. Por favor, inténtelo de nuevo más tarde o contáctenos directamente.';
            $tipo_mensaje = 'error';
            
            // En un entorno de producción, registrar el error
            error_log('Error al enviar mensaje de contacto: ' . $e->getMessage());
        }
        
        // Cerrar conexión
        $conn->close();
    } else {
        // Hay errores, mostrar mensaje general
        $mensaje = 'Por favor, corrija los errores en el formulario.';
        $tipo_mensaje = 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto - Policlínico Veterinario</title>
    <meta name="description" content="Contacte con el Policlínico Veterinario para consultas, información sobre servicios o programar una visita. Estamos a su disposición para atender a su mascota.">
    
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
                <li>Contacto</li>
            </ul>
        </div>
    </div>
    
    <!-- Sección de Contacto -->
    <section class="contact-page section-padding">
        <div class="container">
            <div class="section-header">
                <h1>Contacto</h1>
                <p>Estamos a su disposición para responder a sus consultas y brindarle la mejor atención para su mascota.</p>
            </div>
            
            <?php if (!empty($mensaje)): ?>
                <div class="alert alert-<?php echo $tipo_mensaje; ?>">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>
            
            <div class="contact-content">
                <div class="contact-form-container">
                    <h2>Envíenos un mensaje</h2>
                    <form id="contactForm" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="nombre">Nombre Completo <span class="required">*</span></label>
                                <input type="text" id="nombre" name="nombre" value="<?php echo $datos['nombre']; ?>" required>
                                <?php if (!empty($errores['nombre'])): ?>
                                    <div class="error-message"><?php echo $errores['nombre']; ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email <span class="required">*</span></label>
                                <input type="email" id="email" name="email" value="<?php echo $datos['email']; ?>" required>
                                <?php if (!empty($errores['email'])): ?>
                                    <div class="error-message"><?php echo $errores['email']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="telefono">Teléfono</label>
                                <input type="tel" id="telefono" name="telefono" value="<?php echo $datos['telefono']; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="asunto">Asunto <span class="required">*</span></label>
                                <input type="text" id="asunto" name="asunto" value="<?php echo $datos['asunto']; ?>" required>
                                <?php if (!empty($errores['asunto'])): ?>
                                    <div class="error-message"><?php echo $errores['asunto']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="mensaje">Mensaje <span class="required">*</span></label>
                            <textarea id="mensaje" name="mensaje" rows="6" required><?php echo $datos['mensaje']; ?></textarea>
                            <?php if (!empty($errores['mensaje'])): ?>
                                <div class="error-message"><?php echo $errores['mensaje']; ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="form-group form-actions">
                            <button type="submit" class="btn btn-primary">Enviar Mensaje</button>
                        </div>
                    </form>
                </div>
                
                <div class="contact-info-container">
                    <div class="contact-info-card">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <h3>Dirección</h3>
                        <p>Campus Universitario CENUR Litoral Norte<br>
                        Rivera 1350, Salto, Uruguay</p>
                    </div>
                    
                    <div class="contact-info-card">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <h3>Teléfono</h3>
                        <p><a href="tel:+59847334816">+598 4733 4816</a></p>
                    </div>
                    
                    <div class="contact-info-card">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <h3>Email</h3>
                        <p><a href="mailto:policlinico@fvet.edu.uy">policlinico@fvet.edu.uy</a></p>
                    </div>
                    
                    <div class="contact-info-card">
                        <div class="contact-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h3>Horario de Atención</h3>
                        <p>Lunes a Viernes: 8:00 - 18:00<br>
                        Sábados: 8:00 - 12:00</p>
                    </div>
                    
                    <div class="social-media">
                        <h3>Síganos en redes sociales</h3>
                        <div class="social-icons">
                            <a href="#" target="_blank" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" target="_blank" class="social-icon"><i class="fab fa-instagram"></i></a>
                            <a href="#" target="_blank" class="social-icon"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="map-container">
                <h2>Ubicación</h2>
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3393.3432945681757!2d-57.96760482376904!3d-31.383731974220403!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95addd5dffbc9823%3A0x17a79a5eb06bbb34!2sCENUR%20Litoral%20Norte%20-%20Sede%20Salto!5e0!3m2!1ses!2suy!4v1684178352284!5m2!1ses!2suy" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
    
    <!-- JavaScript -->
    <script src="js/main.js"></script>
</body>
</html>