<?php
// Incluir configuración y funciones comunes
include '../includes/config.php';
include '../includes/functions.php';

// Verificar que el usuario esté logueado
requiereLogin();

// Conectar a la base de datos
$conn = conectarDB();

// Estadísticas para el dashboard
$estadisticas = [
    'citas_hoy' => 0,
    'citas_pendientes' => 0,
    'mascotas_total' => 0,
    'propietarios_total' => 0
];

try {
    // Citas para hoy
    $fecha_hoy = date('Y-m-d');
    $sql = "SELECT COUNT(*) as total FROM citas WHERE fecha = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $fecha_hoy);
    $stmt->execute();
    $resultado = $stmt->get_result();
    if ($resultado && $fila = $resultado->fetch_assoc()) {
        $estadisticas['citas_hoy'] = $fila['total'];
    }
    
    // Citas pendientes (a partir de hoy)
    $sql = "SELECT COUNT(*) as total FROM citas WHERE fecha >= ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $fecha_hoy);
    $stmt->execute();
    $resultado = $stmt->get_result();
    if ($resultado && $fila = $resultado->fetch_assoc()) {
        $estadisticas['citas_pendientes'] = $fila['total'];
    }
    
    // Total de mascotas
    $sql = "SELECT COUNT(*) as total FROM mascotas";
    $resultado = $conn->query($sql);
    if ($resultado && $fila = $resultado->fetch_assoc()) {
        $estadisticas['mascotas_total'] = $fila['total'];
    }
    
    // Total de propietarios
    $sql = "SELECT COUNT(*) as total FROM propietarios";
    $resultado = $conn->query($sql);
    if ($resultado && $fila = $resultado->fetch_assoc()) {
        $estadisticas['propietarios_total'] = $fila['total'];
    }
} catch (Exception $e) {
    registrarError("Error al obtener estadísticas para dashboard: " . $e->getMessage());
}

// Obtener citas del día
$citas_hoy = [];
try {
    $sql = "SELECT c.*, 
            m.nombre as mascota_nombre, 
            p.nombre_completo as propietario_nombre,
            s.nombre as servicio_nombre,
            CONCAT(pe.nombre, ' ', pe.apellido) as veterinario_nombre
            FROM citas c
            INNER JOIN mascotas m ON c.mascota_id = m.id
            INNER JOIN propietarios p ON m.propietario_id = p.id
            INNER JOIN servicios s ON c.servicio_id = s.id
            LEFT JOIN personal pe ON c.veterinario_id = pe.id
            WHERE c.fecha = ?
            ORDER BY c.hora";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $fecha_hoy);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows > 0) {
        while ($cita = $resultado->fetch_assoc()) {
            $citas_hoy[] = $cita;
        }
    }
} catch (Exception $e) {
    registrarError("Error al obtener citas del día: " . $e->getMessage());
}

// Obtener actividad reciente (últimas acciones en log)
$actividad_reciente = [];
try {
    $sql = "SELECT l.*, 
            COALESCE(u.nombre_completo, 'Sistema') as usuario_nombre
            FROM log_acciones l
            LEFT JOIN usuarios u ON l.usuario_id = u.id
            ORDER BY l.fecha_hora DESC
            LIMIT 10";
    $resultado = $conn->query($sql);
    
    if ($resultado->num_rows > 0) {
        while ($accion = $resultado->fetch_assoc()) {
            $actividad_reciente[] = $accion;
        }
    }
} catch (Exception $e) {
    registrarError("Error al obtener actividad reciente: " . $e->getMessage());
}

// Cerrar conexión
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Policlínico Veterinario</title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body class="admin-body">
    <div class="admin-container">
        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>
        
        <!-- Contenido principal -->
        <main class="admin-content">
            <div class="admin-header">
                <h1>Dashboard</h1>
                <div class="user-info">
                    <span><?php echo $_SESSION['user_fullname']; ?></span>
                    <a href="../logout.php" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Salir
                    </a>
                </div>
            </div>
            
            <!-- Estadísticas -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon" style="background-color: rgba(0, 131, 193, 0.1); color: #0083c1;">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Citas Hoy</h3>
                        <div class="stat-value"><?php echo $estadisticas['citas_hoy']; ?></div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background-color: rgba(118, 146, 60, 0.1); color: #76923c;">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Citas Pendientes</h3>
                        <div class="stat-value"><?php echo $estadisticas['citas_pendientes']; ?></div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background-color: rgba(255, 152, 0, 0.1); color: #ff9800;">
                        <i class="fas fa-paw"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Total Mascotas</h3>
                        <div class="stat-value"><?php echo $estadisticas['mascotas_total']; ?></div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background-color: rgba(33, 150, 243, 0.1); color: #2196f3;">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Total Propietarios</h3>
                        <div class="stat-value"><?php echo $estadisticas['propietarios_total']; ?></div>
                    </div>
                </div>
            </div>
            
            <!-- Citas del día -->
            <div class="admin-card">
                <div class="card-header-with-actions">
                    <h2>Citas para Hoy</h2>
                    <a href="citas.php" class="btn btn-primary btn-sm">
                        <i class="fas fa-calendar-alt"></i> Ver Todas
                    </a>
                </div>
                
                <?php if (empty($citas_hoy)): ?>
                    <p class="empty-message">No hay citas programadas para hoy.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Hora</th>
                                    <th>Mascota</th>
                                    <th>Propietario</th>
                                    <th>Servicio</th>
                                    <th>Veterinario</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($citas_hoy as $cita): ?>
                                    <tr>
                                        <td><?php echo $cita['hora']; ?></td>
                                        <td>
                                            <a href="ver_mascota.php?id=<?php echo $cita['mascota_id']; ?>">
                                                <?php echo $cita['mascota_nombre']; ?>
                                            </a>
                                        </td>
                                        <td><?php echo $cita['propietario_nombre']; ?></td>
                                        <td><?php echo $cita['servicio_nombre']; ?></td>
                                        <td><?php echo $cita['veterinario_nombre'] ?: 'No asignado'; ?></td>
                                        <td>
                                            <?php if ($cita['estado'] === 'pendiente'): ?>
                                                <span class="badge badge-warning">Pendiente</span>
                                            <?php elseif ($cita['estado'] === 'completada'): ?>
                                                <span class="badge badge-success">Completada</span>
                                            <?php elseif ($cita['estado'] === 'cancelada'): ?>
                                                <span class="badge badge-danger">Cancelada</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="action-buttons">
                                            <a href="ver_cita.php?id=<?php echo $cita['id']; ?>" class="btn btn-view btn-sm" title="Ver Detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="editar_cita.php?id=<?php echo $cita['id']; ?>" class="btn btn-edit btn-sm" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php if ($cita['estado'] === 'pendiente' && ($_SESSION['user_role'] === ROLE_ADMIN || $_SESSION['user_role'] === ROLE_VET)): ?>
                                                <a href="completar_cita.php?id=<?php echo $cita['id']; ?>" class="btn btn-success btn-sm" title="Marcar como Completada">
                                                    <i class="fas fa-check"></i>
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
            
            <!-- Actividad Reciente -->
            <div class="admin-card">
                <h2>Actividad Reciente</h2>
                
                <?php if (empty($actividad_reciente)): ?>
                    <p class="empty-message">No hay actividad reciente registrada.</p>
                <?php else: ?>
                    <div class="activity-timeline">
                        <?php foreach ($actividad_reciente as $actividad): ?>
                            <div class="activity-item">
                                <div class="activity-icon">
                                    <?php 
                                    $icono = 'fa-info-circle';
                                    $color = 'var(--primary-blue)';
                                    
                                    switch ($actividad['accion']) {
                                        case 'login':
                                            $icono = 'fa-sign-in-alt';
                                            $color = 'var(--success)';
                                            break;
                                        case 'logout':
                                            $icono = 'fa-sign-out-alt';
                                            $color = 'var(--info)';
                                            break;
                                        case 'crear':
                                            $icono = 'fa-plus-circle';
                                            $color = 'var(--primary-blue)';
                                            break;
                                        case 'editar':
                                            $icono = 'fa-edit';
                                            $color = 'var(--warning)';
                                            break;
                                        case 'eliminar':
                                            $icono = 'fa-trash';
                                            $color = 'var(--danger)';
                                            break;
                                        case 'login_fallido':
                                            $icono = 'fa-exclamation-circle';
                                            $color = 'var(--danger)';
                                            break;
                                        case 'acceso_denegado':
                                            $icono = 'fa-ban';
                                            $color = 'var(--danger)';
                                            break;
                                    }
                                    ?>
                                    <i class="fas <?php echo $icono; ?>" style="color: <?php echo $color; ?>;"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-header">
                                        <span class="activity-user"><?php echo $actividad['usuario_nombre']; ?></span>
                                        <span class="activity-time"><?php echo formatearFecha($actividad['fecha_hora'], true); ?></span>
                                    </div>
                                    <div class="activity-description">
                                        <?php echo $actividad['detalles']; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
    
    <!-- JavaScript -->
    <script src="../js/main.js"></script>
    <script src="../js/admin.js"></script>
</body>
</html>