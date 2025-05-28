<?php
// Incluir configuración y funciones
include 'includes/config.php';
include 'includes/functions.php';

// Conectar a la base de datos
$conn = conectarDB();

// Variables para el formulario y mensajes
$mensaje = '';
$tipo_mensaje = '';
$datos = [
    'nombre' => '',
    'email' => '',
    'telefono' => '',
    'mascota_nombre' => '',
    'mascota_especie' => '',
    'servicio_id' => '',
    'fecha' => '',
    'hora' => '',
    'notas' => ''
];

// Obtener servicios disponibles
$servicios = obtenerServicios($conn);

// Obtener horas disponibles (inicialmente vacío, se cargará por AJAX)
$horas_disponibles = [];

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitizar y validar datos del formulario
    $datos['nombre'] = limpiarDato($_POST['nombre'] ?? '');
    $datos['email'] = limpiarDato($_POST['email'] ?? '');
    $datos['telefono'] = limpiarDato($_POST['telefono'] ?? '');
    $datos['mascota_nombre'] = limpiarDato($_POST['mascota_nombre'] ?? '');
    $datos['mascota_especie'] = limpiarDato($_POST['mascota_especie'] ?? '');
    $datos['servicio_id'] = (int) ($_POST['servicio_id'] ?? 0);
    $datos['fecha'] = limpiarDato($_POST['fecha'] ?? '');
    $datos['hora'] = limpiarDato($_POST['hora'] ?? '');
    $datos['notas'] = limpiarDato($_POST['notas'] ?? '');
    
    // Validar datos (validación básica, se complementa con JavaScript)
    $errores = [];
    
    if (empty($datos['nombre'])) {
        $errores['nombre'] = 'Por favor, ingrese su nombre completo';
    }
    
    if (empty($datos['email'])) {
        $errores['email'] = 'Por favor, ingrese su email';
    } elseif (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
        $errores['email'] = 'Por favor, ingrese un email válido';
    }
    
    if (empty($datos['telefono'])) {
        $errores['telefono'] = 'Por favor, ingrese su teléfono';
    }
    
    if (empty($datos['mascota_nombre'])) {
        $errores['mascota_nombre'] = 'Por favor, ingrese el nombre de su mascota';
    }
    
    if (empty($datos['mascota_especie'])) {
        $errores['mascota_especie'] = 'Por favor, seleccione el tipo de mascota';
    }
    
    if (empty($datos['servicio_id']) || $datos['servicio_id'] <= 0) {
        $errores['servicio_id'] = 'Por favor, seleccione un servicio';
    }
    
    if (empty($datos['fecha'])) {
        $errores['fecha'] = 'Por favor, seleccione una fecha';
    } elseif (strtotime($datos['fecha']) < strtotime(date('Y-m-d'))) {
        $errores['fecha'] = 'La fecha debe ser igual o posterior a hoy';
    }
    
    if (empty($datos['hora'])) {
        $errores['hora'] = 'Por favor, seleccione una hora';
    }
    
    // Si no hay errores, procesar la solicitud
    if (empty($errores)) {
        // Verificar si el propietario existe
        $sql = "SELECT id FROM propietarios WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $datos['email']);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        // Iniciar transacción
        $conn->begin_transaction();
        
        try {
            $propietario_id = 0;
            
            // Si el propietario no existe, crearlo
            if ($resultado->num_rows === 0) {
                $sql = "INSERT INTO propietarios (nombre_completo, email, telefono) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('sss', $datos['nombre'], $datos['email'], $datos['telefono']);
                $stmt->execute();
                
                $propietario_id = $conn->insert_id;
            } else {
                $fila = $resultado->fetch_assoc();
                $propietario_id = $fila['id'];
                
                // Actualizar datos de contacto
                $sql = "UPDATE propietarios SET nombre_completo = ?, telefono = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('ssi', $datos['nombre'], $datos['telefono'], $propietario_id);
                $stmt->execute();
            }
            
            // Verificar si la mascota existe
            $sql = "SELECT id FROM mascotas WHERE nombre = ? AND propietario_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('si', $datos['mascota_nombre'], $propietario_id);
            $stmt->execute();
            $resultado = $stmt->get_result();
            
            $mascota_id = 0;
            
            // Si la mascota no existe, crearla
            if ($resultado->num_rows === 0) {
                $sql = "INSERT INTO mascotas (nombre, especie, propietario_id) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('ssi', $datos['mascota_nombre'], $datos['mascota_especie'], $propietario_id);
                $stmt->execute();
                
                $mascota_id = $conn->insert_id;
            } else {
                $fila = $resultado->fetch_assoc();
                $mascota_id = $fila['id'];
            }
            
            // Crear la cita
            $sql = "INSERT INTO citas (mascota_id, servicio_id, fecha, hora, estado, notas) VALUES (?, ?, ?, ?, 'pendiente', ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('iisss', $mascota_id, $datos['servicio_id'], $datos['fecha'], $datos['hora'], $datos['notas']);
            $stmt->execute();
            
            // Confirmar la transacción
            $conn->commit();
            
            // Mensaje de éxito
            $mensaje = 'Su solicitud de cita ha sido recibida correctamente. Nos pondremos en contacto a la brevedad para confirmarla.';
            $tipo_mensaje = 'success';
            
            // Enviar email de confirmación (en un entorno real)
            /*
            $asunto = 'Confirmación de Solicitud de Cita - Policlínico Veterinario';
            $cuerpo = '<h2>¡Gracias por solicitar una cita!</h2>';
            $cuerpo .= '<p>Hemos recibido su solicitud para el día ' . formatearFecha($datos['fecha']) . ' a las ' . $datos['hora'] . '.</p>';
            $cuerpo .= '<p>Un miembro de nuestro equipo se pondrá en contacto con usted para confirmar la cita.</p>';
            enviarEmail($datos['email'], $asunto, $cuerpo);
            */
            
            // Resetear los datos del formulario
            $datos = [
                'nombre' => '',
                'email' => '',
                'telefono' => '',
                'mascota_nombre' => '',
                'mascota_especie' => '',
                'servicio_id' => '',
                'fecha' => '',
                'hora' => '',
                'notas' => ''
            ];
        } catch (Exception $e) {
            // Revertir la transacción en caso de error
            $conn->rollback();
            
            $mensaje = 'Ha ocurrido un error al procesar su solicitud. Por favor, inténtelo de nuevo más tarde o contáctenos directamente.';
            $tipo_mensaje = 'error';
            
            // En un entorno de producción, registrar el error
            error_log('Error al crear cita: ' . $e->getMessage());
        }
    } else {
        // Hay errores, mostrar mensaje general
        $mensaje = 'Por favor, corrija los errores en el formulario.';
        $tipo_mensaje = 'error';
    }
}

// Cerrar conexión
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitar Cita - Policlínico Veterinario</title>
    <meta name="description" content="Solicite una cita para su mascota en el Policlínico Veterinario. Ofrecemos servicios médicos de alta calidad incluyendo consultas, vacunaciones, cirugías y atención de emergencias.">
    
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
                <li>Solicitar Cita</li>
            </ul>
        </div>
    </div>
    
    <!-- Sección de Citas -->
    <section class="appointment section-padding">
        <div class="container">
            <div class="section-header">
                <h1>Solicitar Cita</h1>
                <p>Complete el formulario a continuación para agendar una cita para su mascota.</p>
            </div>
            
            <?php if (!empty($mensaje)): ?>
                <div class="alert alert-<?php echo $tipo_mensaje; ?>">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>
            
            <div class="appointment-content">
                <div class="appointment-form-container">
                    <form id="appointmentForm" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
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
                                <label for="telefono">Teléfono <span class="required">*</span></label>
                                <input type="tel" id="telefono" name="telefono" value="<?php echo $datos['telefono']; ?>" required>
                                <?php if (!empty($errores['telefono'])): ?>
                                    <div class="error-message"><?php echo $errores['telefono']; ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="form-group">
                                <label for="mascota_nombre">Nombre de la Mascota <span class="required">*</span></label>
                                <input type="text" id="mascota_nombre" name="mascota_nombre" value="<?php echo $datos['mascota_nombre']; ?>" required>
                                <?php if (!empty($errores['mascota_nombre'])): ?>
                                    <div class="error-message"><?php echo $errores['mascota_nombre']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="mascota_especie">Tipo de Mascota <span class="required">*</span></label>
                                <select id="mascota_especie" name="mascota_especie" required>
                                    <option value="">Seleccione una opción</option>
                                    <?php foreach (obtenerEspecies() as $valor => $etiqueta): ?>
                                        <option value="<?php echo $valor; ?>" <?php echo $datos['mascota_especie'] === $valor ? 'selected' : ''; ?>>
                                            <?php echo $etiqueta; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (!empty($errores['mascota_especie'])): ?>
                                    <div class="error-message"><?php echo $errores['mascota_especie']; ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="form-group">
                                <label for="servicio_id">Servicio <span class="required">*</span></label>
                                <select id="servicio_id" name="servicio_id" required>
                                    <option value="">Seleccione un servicio</option>
                                    <?php foreach ($servicios as $servicio): ?>
                                        <option value="<?php echo $servicio['id']; ?>" <?php echo (int)$datos['servicio_id'] === (int)$servicio['id'] ? 'selected' : ''; ?>>
                                            <?php echo $servicio['nombre']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (!empty($errores['servicio_id'])): ?>
                                    <div class="error-message"><?php echo $errores['servicio_id']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="fecha">Fecha Preferida <span class="required">*</span></label>
                                <input type="date" id="fecha" name="fecha" class="date-picker" min="<?php echo date('Y-m-d'); ?>" value="<?php echo $datos['fecha']; ?>" required>
                                <?php if (!empty($errores['fecha'])): ?>
                                    <div class="error-message"><?php echo $errores['fecha']; ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="form-group">
                                <label for="hora">Hora Preferida <span class="required">*</span></label>
                                <select id="hora" name="hora" required <?php echo empty($datos['fecha']) ? 'disabled' : ''; ?>>
                                    <option value="">Seleccione una hora</option>
                                    <?php foreach ($horas_disponibles as $hora): ?>
                                        <option value="<?php echo $hora; ?>" <?php echo $datos['hora'] === $hora ? 'selected' : ''; ?>>
                                            <?php echo $hora; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (empty($horas_disponibles)): ?>
                                    <small>Seleccione una fecha para ver las horas disponibles</small>
                                <?php endif; ?>
                                <?php if (!empty($errores['hora'])): ?>
                                    <div class="error-message"><?php echo $errores['hora']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="notas">Motivo de la consulta o comentarios adicionales</label>
                            <textarea id="notas" name="notas" rows="4"><?php echo $datos['notas']; ?></textarea>
                        </div>
                        
                        <div class="form-group form-actions">
                            <button type="submit" class="btn btn-primary">Solicitar Cita</button>
                        </div>
                    </form>
                </div>
                
                <div class="appointment-info">
                    <div class="info-card">
                        <h3>Horario de Atención</h3>
                        <ul class="schedule-list">
                            <li>
                                <span class="day">Lunes a Viernes:</span>
                                <span class="hours">8:00 - 18:00</span>
                            </li>
                            <li>
                                <span class="day">Sábados:</span>
                                <span class="hours">8:00 - 12:00</span>
                            </li>
                            <li>
                                <span class="day">Domingos:</span>
                                <span class="hours">Cerrado</span>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="info-card">
                        <h3>Información Importante</h3>
                        <ul class="info-list">
                            <li><i class="fas fa-info-circle"></i> Las citas se confirman por teléfono o email.</li>
                            <li><i class="fas fa-info-circle"></i> Por favor, llegue 10 minutos antes de su cita.</li>
                            <li><i class="fas fa-info-circle"></i> Traiga el carnet de vacunación de su mascota.</li>
                            <li><i class="fas fa-info-circle"></i> Si no puede asistir, por favor cancele su cita con al menos 24 horas de anticipación.</li>
                        </ul>
                    </div>
                    
                    <div class="info-card">
                        <h3>¿Tiene una emergencia?</h3>
                        <p>En caso de emergencia, contáctenos de inmediato:</p>
                        <p class="emergency-contact">
                            <i class="fas fa-phone"></i> <a href="tel:+59847334816">+598 4733 4816</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
    
    <!-- JavaScript -->
    <script src="js/main.js"></script>
    <script>
        // Script para cargar horas disponibles cuando se selecciona una fecha
        document.addEventListener('DOMContentLoaded', function() {
            const fechaInput = document.getElementById('fecha');
            const horaSelect = document.getElementById('hora');
            
            if (fechaInput && horaSelect) {
                fechaInput.addEventListener('change', function() {
                    const fecha = this.value;
                    
                    if (fecha) {
                        // Habilitar el select de hora
                        horaSelect.disabled = true;
                        horaSelect.innerHTML = '<option value="">Cargando horas...</option>';
                        
                        // Hacer petición AJAX para obtener horas disponibles
                        fetch(`horas_disponibles.php?fecha=${fecha}`)
                            .then(response => response.json())
                            .then(data => {
                                horaSelect.innerHTML = '<option value="">Seleccione una hora</option>';
                                
                                if (data.length === 0) {
                                    const option = document.createElement('option');
                                    option.textContent = 'No hay horas disponibles';
                                    option.disabled = true;
                                    horaSelect.appendChild(option);
                                } else {
                                    data.forEach(hora => {
                                        const option = document.createElement('option');
                                        option.value = hora;
                                        option.textContent = hora;
                                        horaSelect.appendChild(option);
                                    });
                                }
                                
                                horaSelect.disabled = false;
                            })
                            .catch(error => {
                                console.error('Error al cargar horas disponibles:', error);
                                horaSelect.innerHTML = '<option value="">Error al cargar horas</option>';
                                horaSelect.disabled = true;
                            });
                    } else {
                        horaSelect.innerHTML = '<option value="">Seleccione una fecha primero</option>';
                        horaSelect.disabled = true;
                    }
                });
            }
        });
    </script>
</body>
</html>