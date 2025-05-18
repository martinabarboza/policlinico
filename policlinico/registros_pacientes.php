<?php
// Incluir configuración y funciones comunes
include 'includes/config.php';
include 'includes/functions.php';

// Verificar que el usuario esté autenticado
requiereAutenticacion();

// Obtener el ID de la mascota si se proporciona
$mascota_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Variable para almacenar los datos de la mascota
$mascota = null;
$propietario = null;
$registros_medicos = [];
$citas = [];

// Conectar a la base de datos
$conn = conectarDB();

// Si se proporciona un ID de mascota, obtener sus datos
if ($mascota_id > 0) {
    // Obtener datos de la mascota
    $query = "SELECT m.*, p.nombre_completo as propietario_nombre, p.email as propietario_email, p.telefono as propietario_telefono 
              FROM mascotas m 
              JOIN propietarios p ON m.propietario_id = p.id 
              WHERE m.id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $mascota_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows > 0) {
        $mascota = $resultado->fetch_assoc();
        
        // Obtener propietario
        $propietario_id = $mascota['propietario_id'];
        $query = "SELECT * FROM propietarios WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $propietario_id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        if ($resultado->num_rows > 0) {
            $propietario = $resultado->fetch_assoc();
        }
        
        // Obtener registros médicos
        $query = "SELECT rm.*, u.nombre_completo as veterinario_nombre 
                  FROM registros_medicos rm
                  JOIN personal p ON rm.veterinario_id = p.id
                  JOIN usuarios u ON p.usuario_id = u.id
                  WHERE rm.mascota_id = ?
                  ORDER BY rm.fecha DESC";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $mascota_id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        if ($resultado->num_rows > 0) {
            while ($registro = $resultado->fetch_assoc()) {
                $registros_medicos[] = $registro;
            }
        }
        
        // Obtener citas
        $query = "SELECT c.*, s.nombre as servicio_nombre, 
                 (SELECT u.nombre_completo FROM personal p JOIN usuarios u ON p.usuario_id = u.id WHERE p.id = c.veterinario_id) as veterinario_nombre
                 FROM citas c 
                 JOIN servicios s ON c.servicio_id = s.id
                 WHERE c.mascota_id = ?
                 ORDER BY c.fecha DESC, c.hora DESC";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $mascota_id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        if ($resultado->num_rows > 0) {
            while ($cita = $resultado->fetch_assoc()) {
                $citas[] = $cita;
            }
        }
    } else {
        // Mascota no encontrada
        $_SESSION['mensaje'] = "Mascota no encontrada";
        $_SESSION['tipo_mensaje'] = "error";
        redireccionar('lista_pacientes.php');
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
    <title>Registro Médico - Policlinico Veterinario</title>
    <meta name="description" content="Gestión de registros médicos para pacientes de nuestra clínica veterinaria.">
    
    <!-- CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <!-- Cabecera -->
    <?php include 'includes/header.php'; ?>
    
    <section class="record-section">
        <div class="container">
            <?php if ($mascota): ?>
                <div class="record-header">
                    <h1>Registro Médico: <?php echo $mascota['nombre']; ?></h1>
                    <a href="lista_pacientes.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver a lista
                    </a>
                </div>
                
                <div class="record-container">
                    <!-- Información del paciente -->
                    <div class="record-card">
                        <h2>Información del Paciente</h2>
                        <div class="patient-info">
                            <div class="info-group">
                                <span class="label">Nombre:</span>
                                <span class="value"><?php echo $mascota['nombre']; ?></span>
                            </div>
                            <div class="info-group">
                                <span class="label">Especie:</span>
                                <span class="value"><?php echo $mascota['especie']; ?></span>
                            </div>
                            <?php if (!empty($mascota['raza'])): ?>
                            <div class="info-group">
                                <span class="label">Raza:</span>
                                <span class="value"><?php echo $mascota['raza']; ?></span>
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($mascota['fecha_nacimiento'])): ?>
                            <div class="info-group">
                                <span class="label">Fecha de Nacimiento:</span>
                                <span class="value"><?php echo formatearFecha($mascota['fecha_nacimiento']); ?></span>
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($mascota['genero'])): ?>
                            <div class="info-group">
                                <span class="label">Género:</span>
                                <span class="value"><?php echo $mascota['genero']; ?></span>
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($mascota['notas'])): ?>
                            <div class="info-group full-width">
                                <span class="label">Notas:</span>
                                <span class="value"><?php echo $mascota['notas']; ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Información del propietario -->
                    <div class="record-card">
                        <h2>Información del Propietario</h2>
                        <div class="owner-info">
                            <div class="info-group">
                                <span class="label">Nombre:</span>
                                <span class="value"><?php echo $propietario['nombre_completo']; ?></span>
                            </div>
                            <div class="info-group">
                                <span class="label">Email:</span>
                                <span class="value"><?php echo $propietario['email']; ?></span>
                            </div>
                            <div class="info-group">
                                <span class="label">Teléfono:</span>
                                <span class="value"><?php echo $propietario['telefono']; ?></span>
                            </div>
                            <?php if (!empty($propietario['direccion'])): ?>
                            <div class="info-group">
                                <span class="label">Dirección:</span>
                                <span class="value"><?php echo $propietario['direccion']; ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Historial de Citas -->
                    <div class="record-card full-width">
                        <div class="card-header-with-actions">
                            <h2>Historial de Citas</h2>
                            <?php if (esAdmin() || $_SESSION['user_role'] === 'vet'): ?>
                            <a href="crear_cita.php?mascota_id=<?php echo $mascota_id; ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Nueva Cita
                            </a>
                            <?php endif; ?>
                        </div>
                        
                        <?php if (empty($citas)): ?>
                            <p class="empty-message">No hay citas registradas para esta mascota.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Hora</th>
                                            <th>Servicio</th>
                                            <th>Veterinario</th>
                                            <th>Estado</th>
                                            <th>Notas</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($citas as $cita): ?>
                                            <tr>
                                                <td><?php echo formatearFecha($cita['fecha']); ?></td>
                                                <td><?php echo $cita['hora']; ?></td>
                                                <td><?php echo $cita['servicio_nombre']; ?></td>
                                                <td><?php echo $cita['veterinario_nombre'] ?: 'Sin asignar'; ?></td>
                                                <td>
                                                    <span class="status-badge status-<?php echo $cita['estado']; ?>">
                                                        <?php echo ucfirst($cita['estado']); ?>
                                                    </span>
                                                </td>
                                                <td><?php echo truncarTexto($cita['notas'], 50); ?></td>
                                                <td class="action-buttons">
                                                    <a href="ver_cita.php?id=<?php echo $cita['id']; ?>" class="btn btn-view btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <?php if (esAdmin() || $_SESSION['user_role'] === 'vet'): ?>
                                                    <a href="editar_cita.php?id=<?php echo $cita['id']; ?>" class="btn btn-edit btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Registros Médicos -->
                    <div class="record-card full-width">
                        <div class="card-header-with-actions">
                            <h2>Registros Médicos</h2>
                            <?php if (esAdmin() || $_SESSION['user_role'] === 'vet'): ?>
                            <a href="crear_registro_medico.php?mascota_id=<?php echo $mascota_id; ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Nuevo Registro
                            </a>
                            <?php endif; ?>
                        </div>
                        
                        <?php if (empty($registros_medicos)): ?>
                            <p class="empty-message">No hay registros médicos para esta mascota.</p>
                        <?php else: ?>
                            <div class="medical-records">
                                <?php foreach ($registros_medicos as $registro): ?>
                                    <div class="medical-record-item">
                                        <div class="record-header">
                                            <div class="record-date">
                                                <i class="fas fa-calendar-alt"></i>
                                                <?php echo formatearFecha($registro['fecha'], true); ?>
                                            </div>
                                            <div class="record-vet">
                                                <i class="fas fa-user-md"></i>
                                                <?php echo $registro['veterinario_nombre']; ?>
                                            </div>
                                            <div class="record-actions">
                                                <?php if (esAdmin() || $_SESSION['user_role'] === 'vet'): ?>
                                                <a href="editar_registro_medico.php?id=<?php echo $registro['id']; ?>" class="btn btn-edit btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="record-body">
                                            <div class="diagnosis">
                                                <h4>Diagnóstico</h4>
                                                <p><?php echo $registro['diagnostico']; ?></p>
                                            </div>
                                            <div class="treatment">
                                                <h4>Tratamiento</h4>
                                                <p><?php echo $registro['tratamiento']; ?></p>
                                            </div>
                                            <?php if (!empty($registro['notas'])): ?>
                                            <div class="notes">
                                                <h4>Notas</h4>
                                                <p><?php echo $registro['notas']; ?></p>
                                            </div>
                                            <?php endif; ?>
                                            <?php if (!empty($registro['seguimiento'])): ?>
                                            <div class="follow-up">
                                                <h4>Seguimiento</h4>
                                                <p><?php echo $registro['seguimiento']; ?></p>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="error-message-container">
                    <h1>Mascota no encontrada</h1>
                    <p>Lo sentimos, la mascota que estás buscando no existe o no tienes permisos para verla.</p>
                    <a href="lista_pacientes.php" class="btn btn-primary">
                        <i class="fas fa-list"></i> Ver Lista de Pacientes
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </section>
    
    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
    
    <!-- JavaScript -->
    <script src="js/main.js"></script>
</body>
</html>