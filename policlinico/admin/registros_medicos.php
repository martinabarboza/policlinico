<?php
// Incluir configuración y funciones comunes
include '../includes/config.php';
include '../includes/functions.php';

// Verificar que el usuario tenga permisos de veterinario
requiereVeterinario();

// Conectar a la base de datos
$conn = conectarDB();

// Variables para filtrado y paginación
$busqueda = limpiarDato($_GET['busqueda'] ?? '');
$filtro_mascota = isset($_GET['mascota_id']) ? (int)$_GET['mascota_id'] : 0;
$filtro_propietario = isset($_GET['propietario_id']) ? (int)$_GET['propietario_id'] : 0;
$filtro_desde = limpiarDato($_GET['desde'] ?? '');
$filtro_hasta = limpiarDato($_GET['hasta'] ?? '');
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$registros_por_pagina = $config['items_per_page'];

// Construir la consulta SQL base
$sql_base = "SELECT rm.*, 
              m.nombre as mascota_nombre, 
              m.especie as mascota_especie,
              p.nombre_completo as propietario_nombre,
              u.nombre_completo as veterinario_nombre
            FROM registros_medicos rm
            INNER JOIN mascotas m ON rm.mascota_id = m.id
            INNER JOIN propietarios p ON m.propietario_id = p.id
            INNER JOIN personal pe ON rm.veterinario_id = pe.id
            INNER JOIN usuarios u ON pe.usuario_id = u.id
            WHERE 1=1";

// Aplicar filtros
$params = [];
$tipos = '';

if (!empty($busqueda)) {
    $sql_base .= " AND (m.nombre LIKE ? OR p.nombre_completo LIKE ? OR rm.diagnostico LIKE ?)";
    $busqueda_param = '%' . $busqueda . '%';
    $params[] = $busqueda_param;
    $params[] = $busqueda_param;
    $params[] = $busqueda_param;
    $tipos .= 'sss';
}

if ($filtro_mascota > 0) {
    $sql_base .= " AND rm.mascota_id = ?";
    $params[] = $filtro_mascota;
    $tipos .= 'i';
}

if ($filtro_propietario > 0) {
    $sql_base .= " AND m.propietario_id = ?";
    $params[] = $filtro_propietario;
    $tipos .= 'i';
}

if (!empty($filtro_desde)) {
    $sql_base .= " AND rm.fecha >= ?";
    $params[] = $filtro_desde;
    $tipos .= 's';
}

if (!empty($filtro_hasta)) {
    $sql_base .= " AND rm.fecha <= ?";
    $params[] = $filtro_hasta;
    $tipos .= 's';
}

// Si no es administrador, mostrar solo registros de este veterinario
if ($_SESSION['user_role'] !== 'admin') {
    $sql_base .= " AND rm.veterinario_id = (SELECT id FROM personal WHERE usuario_id = ?)";
    $params[] = $_SESSION['user_id'];
    $tipos .= 'i';
}

// Contar total de registros para paginación
$sql_count = "SELECT COUNT(*) as total FROM ($sql_base) as subquery";

if (!empty($params)) {
    $stmt = $conn->prepare($sql_count);
    $stmt->bind_param($tipos, ...$params);
    $stmt->execute();
    $resultado_total = $stmt->get_result();
} else {
    $resultado_total = $conn->query($sql_count);
}

$fila_total = $resultado_total->fetch_assoc();
$total_registros = $fila_total['total'];
$total_paginas = ceil($total_registros / $registros_por_pagina);

// Ajustar página actual si está fuera de rango
if ($pagina_actual < 1) {
    $pagina_actual = 1;
} elseif ($pagina_actual > $total_paginas && $total_paginas > 0) {
    $pagina_actual = $total_paginas;
}

$offset = ($pagina_actual - 1) * $registros_por_pagina;

// Obtener registros para la página actual
$sql_base .= " ORDER BY rm.fecha DESC, rm.id DESC LIMIT ?, ?";
$params[] = $offset;
$params[] = $registros_por_pagina;
$tipos .= 'ii';

$stmt = $conn->prepare($sql_base);
if (!empty($params)) {
    $stmt->bind_param($tipos, ...$params);
}
$stmt->execute();
$resultado = $stmt->get_result();

$registros = [];
if ($resultado->num_rows > 0) {
    while ($registro = $resultado->fetch_assoc()) {
        $registros[] = $registro;
    }
}

// Obtener mascotas para el filtro
$sql = "SELECT m.id, m.nombre, p.nombre_completo as propietario_nombre 
        FROM mascotas m 
        INNER JOIN propietarios p ON m.propietario_id = p.id 
        ORDER BY m.nombre";
$resultado = $conn->query($sql);

$mascotas = [];
if ($resultado->num_rows > 0) {
    while ($mascota = $resultado->fetch_assoc()) {
        $mascotas[] = $mascota;
    }
}

// Obtener propietarios para el filtro
$sql = "SELECT id, nombre_completo, email FROM propietarios ORDER BY nombre_completo";
$resultado = $conn->query($sql);

$propietarios = [];
if ($resultado->num_rows > 0) {
    while ($propietario = $resultado->fetch_assoc()) {
        $propietarios[] = $propietario;
    }
}

// Cerrar conexión
$conn->close();

// Mensaje de operación
$mensaje = '';
$tipo_mensaje = '';

if (isset($_SESSION['mensaje']) && isset($_SESSION['tipo_mensaje'])) {
    $mensaje = $_SESSION['mensaje'];
    $tipo_mensaje = $_SESSION['tipo_mensaje'];
    
    // Limpiar mensajes de sesión
    unset($_SESSION['mensaje']);
    unset($_SESSION['tipo_mensaje']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registros Médicos - Policlínico Veterinario</title>
    
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
                <h1>Registros Médicos</h1>
                <div class="user-info">
                    <span><?php echo $_SESSION['user_fullname']; ?></span>
                    <a href="../logout.php" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Salir
                    </a>
                </div>
            </div>
            
            <?php if (!empty($mensaje)): ?>
                <div class="alert alert-<?php echo $tipo_mensaje; ?>">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>
            
            <div class="admin-card">
                <div class="card-header-with-actions">
                    <h2>Filtrar Registros</h2>
                    <a href="crear_registro_medico.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nuevo Registro
                    </a>
                </div>
                
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" class="filter-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="busqueda">Buscar</label>
                            <input type="text" id="busqueda" name="busqueda" value="<?php echo $busqueda; ?>" placeholder="Nombre, diagnóstico...">
                        </div>
                        
                        <div class="form-group">
                            <label for="mascota_id">Mascota</label>
                            <select id="mascota_id" name="mascota_id">
                                <option value="">Todas las mascotas</option>
                                <?php foreach ($mascotas as $mascota): ?>
                                    <option value="<?php echo $mascota['id']; ?>" <?php echo $filtro_mascota == $mascota['id'] ? 'selected' : ''; ?>>
                                        <?php echo $mascota['nombre']; ?> (<?php echo $mascota['propietario_nombre']; ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="propietario_id">Propietario</label>
                            <select id="propietario_id" name="propietario_id">
                                <option value="">Todos los propietarios</option>
                                <?php foreach ($propietarios as $propietario): ?>
                                    <option value="<?php echo $propietario['id']; ?>" <?php echo $filtro_propietario == $propietario['id'] ? 'selected' : ''; ?>>
                                        <?php echo $propietario['nombre_completo']; ?> (<?php echo $propietario['email']; ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="desde">Desde</label>
                            <input type="date" id="desde" name="desde" value="<?php echo $filtro_desde; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="hasta">Hasta</label>
                            <input type="date" id="hasta" name="hasta" value="<?php echo $filtro_hasta; ?>">
                        </div>
                    </div>
                    
                    <div class="filter-actions">
                        <button type="submit" class="btn btn-primary">Filtrar</button>
                        <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-outline">Limpiar Filtros</a>
                    </div>
                </form>
            </div>
            
            <div class="admin-card">
                <h2>Lista de Registros Médicos</h2>
                
                <?php if (empty($registros)): ?>
                    <p class="empty-message">No se encontraron registros médicos con los criterios de búsqueda especificados.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Mascota</th>
                                    <th>Propietario</th>
                                    <th>Diagnóstico</th>
                                    <th>Tratamiento</th>
                                    <th>Veterinario</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($registros as $registro): ?>
                                    <tr>
                                        <td><?php echo formatearFecha($registro['fecha']); ?></td>
                                        <td>
                                            <a href="ver_mascota.php?id=<?php echo $registro['mascota_id']; ?>">
                                                <?php echo $registro['mascota_nombre']; ?>
                                            </a>
                                            <small>(<?php echo $registro['mascota_especie']; ?>)</small>
                                        </td>
                                        <td><?php echo $registro['propietario_nombre']; ?></td>
                                        <td><?php echo truncarTexto($registro['diagnostico'], 50); ?></td>
                                        <td><?php echo truncarTexto($registro['tratamiento'], 50); ?></td>
                                        <td><?php echo $registro['veterinario_nombre']; ?></td>
                                        <td class="action-buttons">
                                            <a href="ver_registro_medico.php?id=<?php echo $registro['id']; ?>" class="btn btn-view btn-sm" title="Ver Detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="editar_registro_medico.php?id=<?php echo $registro['id']; ?>" class="btn btn-edit btn-sm" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php if ($_SESSION['user_role'] === 'admin'): ?>
                                                <a href="eliminar_registro_medico.php?id=<?php echo $registro['id']; ?>" class="btn btn-delete btn-sm delete-confirm" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Paginación -->
                    <?php if ($total_paginas > 1): ?>
                        <div class="pagination">
                            <?php if ($pagina_actual > 1): ?>
                                <a href="?pagina=1<?php echo !empty($busqueda) ? '&busqueda=' . urlencode($busqueda) : ''; ?><?php echo $filtro_mascota > 0 ? '&mascota_id=' . $filtro_mascota : ''; ?><?php echo $filtro_propietario > 0 ? '&propietario_id=' . $filtro_propietario : ''; ?><?php echo !empty($filtro_desde) ? '&desde=' . urlencode($filtro_desde) : ''; ?><?php echo !empty($filtro_hasta) ? '&hasta=' . urlencode($filtro_hasta) : ''; ?>" class="pagination-link">
                                    <i class="fas fa-angle-double-left"></i>
                                </a>
                                <a href="?pagina=<?php echo $pagina_actual - 1; ?><?php echo !empty($busqueda) ? '&busqueda=' . urlencode($busqueda) : ''; ?><?php echo $filtro_mascota > 0 ? '&mascota_id=' . $filtro_mascota : ''; ?><?php echo $filtro_propietario > 0 ? '&propietario_id=' . $filtro_propietario : ''; ?><?php echo !empty($filtro_desde) ? '&desde=' . urlencode($filtro_desde) : ''; ?><?php echo !empty($filtro_hasta) ? '&hasta=' . urlencode($filtro_hasta) : ''; ?>" class="pagination-link">
                                    <i class="fas fa-angle-left"></i>
                                </a>
                            <?php endif; ?>
                            
                            <?php
                                $start_page = max(1, $pagina_actual - 2);
                                $end_page = min($total_paginas, $pagina_actual + 2);
                                
                                for ($i = $start_page; $i <= $end_page; $i++):
                            ?>
                                <a href="?pagina=<?php echo $i; ?><?php echo !empty($busqueda) ? '&busqueda=' . urlencode($busqueda) : ''; ?><?php echo $filtro_mascota > 0 ? '&mascota_id=' . $filtro_mascota : ''; ?><?php echo $filtro_propietario > 0 ? '&propietario_id=' . $filtro_propietario : ''; ?><?php echo !empty($filtro_desde) ? '&desde=' . urlencode($filtro_desde) : ''; ?><?php echo !empty($filtro_hasta) ? '&hasta=' . urlencode($filtro_hasta) : ''; ?>" class="pagination-link <?php echo $i === $pagina_actual ? 'active' : ''; ?>">
                                    <?php echo $i; ?>
                                </a>
                            <?php endfor; ?>
                            
                            <?php if ($pagina_actual < $total_paginas): ?>
                                <a href="?pagina=<?php echo $pagina_actual + 1; ?><?php echo !empty($busqueda) ? '&busqueda=' . urlencode($busqueda) : ''; ?><?php echo $filtro_mascota > 0 ? '&mascota_id=' . $filtro_mascota : ''; ?><?php echo $filtro_propietario > 0 ? '&propietario_id=' . $filtro_propietario : ''; ?><?php echo !empty($filtro_desde) ? '&desde=' . urlencode($filtro_desde) : ''; ?><?php echo !empty($filtro_hasta) ? '&hasta=' . urlencode($filtro_hasta) : ''; ?>" class="pagination-link">
                                    <i class="fas fa-angle-right"></i>
                                </a>
                                <a href="?pagina=<?php echo $total_paginas; ?><?php echo !empty($busqueda) ? '&busqueda=' . urlencode($busqueda) : ''; ?><?php echo $filtro_mascota > 0 ? '&mascota_id=' . $filtro_mascota : ''; ?><?php echo $filtro_propietario > 0 ? '&propietario_id=' . $filtro_propietario : ''; ?><?php echo !empty($filtro_desde) ? '&desde=' . urlencode($filtro_desde) : ''; ?><?php echo !empty($filtro_hasta) ? '&hasta=' . urlencode($filtro_hasta) : ''; ?>" class="pagination-link">
                                    <i class="fas fa-angle-double-right"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="pagination-info">
                        Mostrando <?php echo ($total_registros > 0) ? ($offset + 1) : 0; ?>-<?php echo min($offset + $registros_por_pagina, $total_registros); ?> de <?php echo $total_registros; ?> registro(s)
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