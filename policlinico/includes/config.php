<?php
// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Configuración de zona horaria
date_default_timezone_set('America/Montevideo');

// Configuración de la aplicación
$config = [
    // Datos del sitio
    'site_name' => 'Policlínico Veterinario',
    'site_description' => 'Centro de atención veterinaria de la Universidad de la República',
    'site_email' => 'contacto@policlinicoveterinario.edu.uy',
    'site_phone' => '+(598) 98 230 818',
    'site_address' => 'Av. Juan Harriague 930, Salto, Uruguay',
     'site_url' => 'http://localhost/policlinico',
    
    // Redes sociales
    'social_facebook' => 'https://www.facebook.com/policlinicoveterinario',
    'social_instagram' => 'https://www.instagram.com/policlinicoveterinario',
    'social_twitter' => 'https://twitter.com/policlinicoveterinario',
    
    // Configuración de base de datos
    'db_host' => 'localhost',
    'db_name' => 'policlinico_veterinario',
    'db_user' => 'root',
    'db_pass' => '',
    'db_charset' => 'utf8mb4',
    
    // Configuración de correo
    'mail_host' => 'marbarbozapintos@gmail.com',
    'mail_port' => 587,
    'mail_username' => 'marbarbozapintos',
    'mail_password' => 'password', // Completar en entorno de producción
    'mail_encryption' => 'tls',
    'mail_from_name' => 'Policlínico Veterinario',
    
    // Configuraciones de la aplicación
    'items_per_page' => 10,
    'max_file_size' => 5 * 1024 * 1024, // 5MB
    'allowed_extensions' => ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'],
    
    // Citas
    'horario_inicio' => 8, // 8:00 AM
    'horario_fin' => 18, // 6:00 PM
    'duracion_cita' => 30, // minutos
    'dias_laborables' => [1, 2, 3, 4, 5], // Lunes a Viernes
    
    // Otros
    'debug_mode' => true, // Cambiar a false en producción
];

// Definición de constantes
define('BASE_URL', 'http://localhost/policlinico'); // Cambiar según el entorno
define('SITE_NAME', $config['site_name']);
define('ADMIN_PATH', 'admin/index.php');
define('UPLOAD_PATH', __DIR__ . '/../uploads');
define('LOGS_PATH', __DIR__ . '/../logs');

// Constantes para roles
define('ROLE_ADMIN', 'admin');
define('ROLE_VET', 'vet');
define('ROLE_ASSISTANT', 'assistant');
define('ROLE_RECEPTIONIST', 'receptionist');

// Funciones auxiliares para la configuración

/**
 * Obtiene un valor de configuración
 * 
 * @param string $key Clave de configuración
 * @param mixed $default Valor por defecto si no existe la clave
 * @return mixed Valor de configuración
 */
function config($key, $default = null) {
    global $config;
    return $config[$key] ?? $default;
}

/**
 * Registra un mensaje de error
 * 
 * @param string $message Mensaje de error
 * @param string $file Archivo donde ocurrió el error
 * @param int $line Línea donde ocurrió el error
 * @return void
 */
function registrarError($message, $file = '', $line = 0) {
    // Verificar directorio de logs
    if (!is_dir(LOGS_PATH)) {
        mkdir(LOGS_PATH, 0755, true);
    }
    
    $log_file = LOGS_PATH . '/error_' . date('Y-m-d') . '.log';
    $timestamp = date('Y-m-d H:i:s');
    
    // Formatear mensaje
    $log_message = "[{$timestamp}] {$message} - File: {$file}, Line: {$line}" . PHP_EOL;
    
    // Escribir en archivo
    file_put_contents($log_file, $log_message, FILE_APPEND);
    
    // Si está en modo debug, mostrar error
    if (config('debug_mode')) {
        error_log($message);
    }
}

// Configurar manejo de errores personalizado
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    $error_type = '';
    
    switch ($errno) {
        case E_ERROR:
        case E_USER_ERROR:
            $error_type = 'FATAL ERROR';
            break;
        case E_WARNING:
        case E_USER_WARNING:
            $error_type = 'WARNING';
            break;
        case E_NOTICE:
        case E_USER_NOTICE:
            $error_type = 'NOTICE';
            break;
        default:
            $error_type = 'ERROR';
    }
    
    registrarError("[{$error_type}] {$errstr}", $errfile, $errline);
    
    // Para errores fatales, terminar la ejecución
    if ($errno === E_ERROR || $errno === E_USER_ERROR) {
        exit(1);
    }
    
    // Devolver false para permitir el manejador de errores estándar de PHP
    return false;
});

// Registrar errores no capturados
register_shutdown_function(function() {
    $error = error_get_last();
    
    if ($error !== null && ($error['type'] === E_ERROR || $error['type'] === E_PARSE || $error['type'] === E_COMPILE_ERROR)) {
        registrarError("[SHUTDOWN] {$error['message']}", $error['file'], $error['line']);
    }
});