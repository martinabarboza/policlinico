<?php
// Incluir configuración y funciones
include 'includes/config.php';
include 'includes/functions.php';

// Verificar si hay un usuario logueado
if (estaLogueado()) {
    // Registrar el logout en la base de datos
    $conn = conectarDB();
    
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['user_username'];
    $ip = $_SERVER['REMOTE_ADDR'];
    $detalles = "Logout de usuario - " . $username;
    
    $sql = "INSERT INTO log_acciones (usuario_id, accion, detalles, ip_address) VALUES (?, 'logout', ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iss', $user_id, $detalles, $ip);
    $stmt->execute();
    
    $conn->close();
}

// Destruir la sesión
session_destroy();

// Redirigir a la página de inicio o login
redirigir('index.php');
?>