<?php
// Incluir configuración y funciones
include 'includes/config.php';
include 'includes/functions.php';

// Verificar que se haya recibido una fecha
if (!isset($_GET['fecha']) || empty($_GET['fecha'])) {
    // Devolver array vacío si no hay fecha
    header('Content-Type: application/json');
    echo json_encode([]);
    exit;
}

// Obtener parámetros
$fecha = limpiarDato($_GET['fecha']);
$veterinario_id = isset($_GET['veterinario_id']) ? (int)$_GET['veterinario_id'] : null;
$cita_id = isset($_GET['cita_id']) ? (int)$_GET['cita_id'] : null;

// Validar fecha
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
    header('Content-Type: application/json');
    echo json_encode([]);
    exit;
}

// Comprobar si la fecha es hoy o posterior
if (strtotime($fecha) < strtotime(date('Y-m-d'))) {
    header('Content-Type: application/json');
    echo json_encode([]);
    exit;
}

// Conectar a la base de datos
$conn = conectarDB();

// Obtener todas las horas disponibles para la fecha
$horas_disponibles = obtenerHorarioDisponible($conn, $fecha, $veterinario_id, $cita_id);

// Cerrar conexión
$conn->close();

// Devolver resultado en formato JSON
header('Content-Type: application/json');
echo json_encode($horas_disponibles);
?>