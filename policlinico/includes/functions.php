<?php
/**
 * Archivo de funciones generales para el Policlínico Veterinario
 */

/**
 * Conecta a la base de datos MySQL
 * 
 * @return mysqli Conexión a la base de datos
 */
function conectarDB() {
    global $config;
    
    try {
        $conn = new mysqli(
            $config['db_host'],
            $config['db_user'],
            $config['db_pass'],
            $config['db_name']
        );
        
        // Verificar conexión
        if ($conn->connect_error) {
            throw new Exception("Error de conexión a la base de datos: " . $conn->connect_error);
        }
        
        // Establecer charset
        $conn->set_charset($config['db_charset']);
        
        return $conn;
    } catch (Exception $e) {
        registrarError("Error al conectar a la base de datos: " . $e->getMessage());
        
        // En caso de error crítico, mostrar mensaje y terminar
        if ($config['debug_mode']) {
            die("Error de conexión a la base de datos. Por favor, contacte al administrador.");
        } else {
            redirectError(500);
        }
    }
}

/**
 * Limpia y sanitiza una cadena de texto
 * 
 * @param string $dato Dato a limpiar
 * @return string Dato limpio
 */
function limpiarDato($dato) {
    $dato = trim($dato);
    $dato = stripslashes($dato);
    $dato = htmlspecialchars($dato, ENT_QUOTES, 'UTF-8');
    return $dato;
}

/**
 * Redirige a una URL específica
 * 
 * @param string $url URL a redireccionar
 * @return void
 */
function redirigir($url) {
    header("Location: {$url}");
    exit;
}

/**
 * Redirige a una página de error
 * 
 * @param int $codigo Código de error HTTP
 * @return void
 */
function redirectError($codigo) {
    header("Location: " . BASE_URL . "/error.php?codigo={$codigo}");
    exit;
}

/**
 * Obtiene todos los testimonios aprobados
 * 
 * @param mysqli $conn Conexión a la base de datos
 * @return array Lista de testimonios aprobados
 */
function obtenerTestimoniosAprobados($conn) {
    try {
        $sql = "SELECT * FROM testimonios WHERE aprobado = 1 ORDER BY fecha DESC";
        $resultado = $conn->query($sql);

        $testimonios = [];
        if ($resultado->num_rows > 0) {
            while ($testimonio = $resultado->fetch_assoc()) {
                $testimonios[] = $testimonio;
            }
        }

        return $testimonios;
    } catch (Exception $e) {
        registrarError("Error al obtener testimonios: " . $e->getMessage());
        return [];
    }
}
function generarEstrellas($valoracion) {
    $html = '<div class="estrellas">';
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $valoracion) {
            $html .= '<span>★</span>';
        } else {
            $html .= '<span>☆</span>';
        }
    }
    $html .= '</div>';
    return $html;
}
/**
 * Verifica si el usuario está logueado
 * 
 * @return bool True si está logueado, false en caso contrario
 */
function estaLogueado() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Requiere que el usuario esté logueado para acceder a una página
 * 
 * @param string $redirect URL a redireccionar si no está logueado
 * @return void
 */
function requiereLogin($redirect = null) {
    if (!estaLogueado()) {
        if ($redirect) {
            $_SESSION['redirect_after_login'] = $redirect;
        } else {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        }
        
        redirigir(BASE_URL . '/login.php');
    }
}

/**
 * Requiere que el usuario tenga rol de administrador
 * 
 * @return void
 */
function requiereAdmin() {
    requiereLogin();
    
    if ($_SESSION['user_role'] !== ROLE_ADMIN) {
        // Registrar intento no autorizado
        $conn = conectarDB();
        
        $user_id = $_SESSION['user_id'];
        $ip = $_SERVER['REMOTE_ADDR'];
        $detalles = "Intento de acceso a área administrativa - Usuario: " . $_SESSION['user_username'];
        
        $sql = "INSERT INTO log_acciones (usuario_id, accion, detalles, ip_address) VALUES (?, 'acceso_denegado', ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iss', $user_id, $detalles, $ip);
        $stmt->execute();
        
        $conn->close();
        
        // Redirigir a página de error
        redirectError(403);
    }
}

/**
 * Requiere que el usuario tenga rol de veterinario o administrador
 * 
 * @return void
 */
function requiereVeterinario() {
    requiereLogin();
    
    if ($_SESSION['user_role'] !== ROLE_VET && $_SESSION['user_role'] !== ROLE_ADMIN) {
        // Registrar intento no autorizado
        $conn = conectarDB();
        
        $user_id = $_SESSION['user_id'];
        $ip = $_SERVER['REMOTE_ADDR'];
        $detalles = "Intento de acceso a área de veterinarios - Usuario: " . $_SESSION['user_username'];
        
        $sql = "INSERT INTO log_acciones (usuario_id, accion, detalles, ip_address) VALUES (?, 'acceso_denegado', ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iss', $user_id, $detalles, $ip);
        $stmt->execute();
        
        $conn->close();
        
        // Redirigir a página de error
        redirectError(403);
    }
}

/**
 * Formatear una fecha para mostrar en formato local
 * 
 * @param string $fecha Fecha en formato MySQL (YYYY-MM-DD)
 * @param bool $con_hora Incluir hora en el formato
 * @return string Fecha formateada
 */
function formatearFecha($fecha, $con_hora = false) {
    if (empty($fecha)) {
        return '';
    }
    
    $timestamp = strtotime($fecha);
    
    if ($con_hora) {
        return date('d/m/Y H:i', $timestamp);
    } else {
        return date('d/m/Y', $timestamp);
    }
}

/**
 * Trunca un texto a una longitud específica
 * 
 * @param string $texto Texto a truncar
 * @param int $longitud Longitud máxima
 * @param string $sufijo Sufijo a agregar al truncar
 * @return string Texto truncado
 */
function truncarTexto($texto, $longitud = 100, $sufijo = '...') {
    if (strlen($texto) <= $longitud) {
        return $texto;
    }
    
    return substr($texto, 0, $longitud - strlen($sufijo)) . $sufijo;
}

/**
 * Formatear un precio
 * 
 * @param float $precio Precio a formatear
 * @param bool $con_simbolo Incluir símbolo de moneda
 * @return string Precio formateado
 */
function formatearPrecio($precio, $con_simbolo = true) {
    if (is_null($precio)) {
        return 'Consultar';
    }
    
    $precio_formateado = number_format($precio, 2, ',', '.');
    
    if ($con_simbolo) {
        return '$' . $precio_formateado;
    } else {
        return $precio_formateado;
    }
}

/**
 * Obtiene un servicio por su ID
 * 
 * @param mysqli $conn Conexión a la base de datos
 * @param int $id ID del servicio
 * @return array|null Datos del servicio o null si no existe
 */
function obtenerServicioPorId($conn, $id) {
    try {
        $sql = "SELECT * FROM servicios WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        if ($resultado->num_rows === 1) {
            return $resultado->fetch_assoc();
        }
        
        return null;
    } catch (Exception $e) {
        registrarError("Error al obtener servicio: " . $e->getMessage());
        return null;
    }
}

/**
 * Obtiene todos los servicios activos
 * 
 * @param mysqli $conn Conexión a la base de datos
 * @return array Lista de servicios
 */
function obtenerServicios($conn) {
    try {
        $sql = "SELECT * FROM servicios WHERE activo = 1 ORDER BY nombre";
        $resultado = $conn->query($sql);
        
        $servicios = [];
        if ($resultado->num_rows > 0) {
            while ($servicio = $resultado->fetch_assoc()) {
                $servicios[] = $servicio;
            }
        }
        
        return $servicios;
    } catch (Exception $e) {
        registrarError("Error al obtener servicios: " . $e->getMessage());
        return [];
    }
}

/**
 * Valida si una fecha es válida
 * 
 * @param string $fecha Fecha a validar (YYYY-MM-DD)
 * @return bool True si es válida, false en caso contrario
 */
function esValidaFecha($fecha) {
    if (empty($fecha)) {
        return false;
    }
    
    $partes = explode('-', $fecha);
    
    if (count($partes) !== 3) {
        return false;
    }
    
    return checkdate((int)$partes[1], (int)$partes[2], (int)$partes[0]);
}

/**
 * Envía un correo electrónico
 * 
 * @param string $destinatario Correo del destinatario
 * @param string $asunto Asunto del correo
 * @param string $mensaje Contenido del correo
 * @param array $adjuntos Archivos adjuntos
 * @return bool True si se envió, false en caso contrario
 */
function enviarEmail($destinatario, $asunto, $mensaje, $adjuntos = []) {
    global $config;
    
    // Cargar PHPMailer si está disponible
    if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
        if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
            require __DIR__ . '/../vendor/autoload.php';
        } else {
            registrarError("No se pudo cargar PHPMailer para enviar correo.");
            return false;
        }
    }
    
    try {
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        
        // Configurar servidor
        $mail->isSMTP();
        $mail->Host = $config['mail_host'];
        $mail->SMTPAuth = true;
        $mail->Username = $config['mail_username'];
        $mail->Password = $config['mail_password'];
        $mail->SMTPSecure = $config['mail_encryption'];
        $mail->Port = $config['mail_port'];
        $mail->CharSet = 'UTF-8';
        
        // Configurar remitente y destinatario
        $mail->setFrom($config['mail_username'], $config['mail_from_name']);
        $mail->addAddress($destinatario);
        $mail->addReplyTo($config['mail_username'], $config['mail_from_name']);
        
        // Configurar contenido
        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body = $mensaje;
        $mail->AltBody = strip_tags($mensaje);
        
        // Agregar adjuntos
        foreach ($adjuntos as $adjunto) {
            $mail->addAttachment($adjunto);
        }
        
        // Enviar correo
        return $mail->send();
    } catch (Exception $e) {
        registrarError("Error al enviar correo: " . $e->getMessage());
        return false;
    }
}

/**
 * Generar un token aleatorio seguro
 * 
 * @param int $longitud Longitud del token
 * @return string Token generado
 */
function generarToken($longitud = 32) {
    if (function_exists('random_bytes')) {
        return bin2hex(random_bytes($longitud / 2));
    }
    
    if (function_exists('openssl_random_pseudo_bytes')) {
        return bin2hex(openssl_random_pseudo_bytes($longitud / 2));
    }
    
    // Fallback menos seguro
    $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $token = '';
    
    for ($i = 0; $i < $longitud; $i++) {
        $token .= $caracteres[mt_rand(0, strlen($caracteres) - 1)];
    }
    
    return $token;
}

/**
 * Verifica si una palabra es segura para URL (slug)
 * 
 * @param string $texto Texto a convertir
 * @return string Slug generado
 */
function generarSlug($texto) {
    // Convertir a minúsculas
    $texto = mb_strtolower($texto, 'UTF-8');
    
    // Reemplazar caracteres especiales
    $texto = preg_replace('~[^\\pL\d]+~u', '-', $texto);
    
    // Eliminar guiones al inicio y final
    $texto = trim($texto, '-');
    
    // Transliterar caracteres no ASCII
    if (function_exists('iconv')) {
        $texto = iconv('UTF-8', 'ASCII//TRANSLIT', $texto);
    }
    
    // Eliminar caracteres no alphanumericos
    $texto = preg_replace('~[^-\w]+~', '', $texto);
    
    if (empty($texto)) {
        return 'n-a';
    }
    
    return $texto;
}

/**
 * Obtiene la extensión de un archivo
 * 
 * @param string $nombre_archivo Nombre del archivo
 * @return string Extensión del archivo
 */
function obtenerExtension($nombre_archivo) {
    return strtolower(pathinfo($nombre_archivo, PATHINFO_EXTENSION));
}

/**
 * Verifica si una extensión está permitida
 * 
 * @param string $extension Extensión a verificar
 * @return bool True si está permitida, false en caso contrario
 */
function extensionPermitida($extension) {
    global $config;
    return in_array(strtolower($extension), $config['allowed_extensions']);
}

/**
 * Subir un archivo al servidor
 * 
 * @param array $archivo Información del archivo ($_FILES)
 * @param string $directorio Directorio de destino
 * @param string $nombre_nuevo Nombre nuevo para el archivo (opcional)
 * @return string|false Ruta del archivo subido o false si falla
 */
function subirArchivo($archivo, $directorio, $nombre_nuevo = '') {
    global $config;
    
    // Verificar que haya un archivo
    if (!isset($archivo['name']) || empty($archivo['name'])) {
        return false;
    }
    
    // Verificar tamaño
    if ($archivo['size'] > $config['max_file_size']) {
        return false;
    }
    
    // Verificar extensión
    $extension = obtenerExtension($archivo['name']);
    if (!extensionPermitida($extension)) {
        return false;
    }
    
    // Crear directorio si no existe
    if (!is_dir($directorio)) {
        mkdir($directorio, 0755, true);
    }
    
    // Generar nombre único si no se especifica uno
    if (empty($nombre_nuevo)) {
        $nombre_base = pathinfo($archivo['name'], PATHINFO_FILENAME);
        $nombre_nuevo = generarSlug($nombre_base) . '-' . uniqid() . '.' . $extension;
    } else {
        $nombre_nuevo = $nombre_nuevo . '.' . $extension;
    }
    
    $ruta_destino = $directorio . '/' . $nombre_nuevo;
    
    // Mover archivo
    if (move_uploaded_file($archivo['tmp_name'], $ruta_destino)) {
        return $ruta_destino;
    }
    
    return false;
}

/**
 * Verificar seguridad para ejecutar una acción (CSRF)
 * 
 * @param string $token Token a verificar
 * @return bool True si es válido, false en caso contrario
 */
function verificarToken($token) {
    return isset($_SESSION['token']) && $token === $_SESSION['token'];
}

/**
 * Genera un token de seguridad para formularios (CSRF)
 * 
 * @return string Token generado
 */
function generarTokenFormulario() {
    if (!isset($_SESSION['token'])) {
        $_SESSION['token'] = generarToken();
    }
    
    return $_SESSION['token'];
}

/**
 * Obtiene las horas disponibles para citas
 * 
 * @param mysqli $conn Conexión a la base de datos
 * @param string $fecha Fecha en formato YYYY-MM-DD
 * @param int $veterinario_id ID del veterinario (opcional)
 * @return array Horas disponibles
 */
function obtenerHorasDisponibles($conn, $fecha, $veterinario_id = null) {
    global $config;
    
    // Verificar que la fecha sea válida
    if (!esValidaFecha($fecha)) {
        return [];
    }
    
    // Obtener día de la semana (1 = Lunes, 7 = Domingo)
    $dia_semana = date('N', strtotime($fecha));
    
    // Verificar si es un día laborable
    if (!in_array($dia_semana, $config['dias_laborables'])) {
        return [];
    }
    
    // Construir horas disponibles
    $horas_disponibles = [];
    $duracion_cita = $config['duracion_cita']; // en minutos
    
    for ($hora = $config['horario_inicio']; $hora < $config['horario_fin']; $hora++) {
        for ($minuto = 0; $minuto < 60; $minuto += $duracion_cita) {
            $hora_str = sprintf('%02d:%02d', $hora, $minuto);
            $horas_disponibles[] = $hora_str;
        }
    }
    
    // Obtener citas existentes para esa fecha
    $citas_existentes = [];
    
    try {
        $sql = "SELECT TIME_FORMAT(hora, '%H:%i') as hora_cita 
                FROM citas 
                WHERE fecha = ?";
        
        $params = [$fecha];
        $tipos = 's';
        
        // Si se especifica veterinario, filtrar por él
        if ($veterinario_id) {
            $sql .= " AND veterinario_id = ?";
            $params[] = $veterinario_id;
            $tipos .= 'i';
        }
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param($tipos, ...$params);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        if ($resultado->num_rows > 0) {
            while ($cita = $resultado->fetch_assoc()) {
                $citas_existentes[] = $cita['hora_cita'];
            }
        }
    } catch (Exception $e) {
        registrarError("Error al obtener citas existentes: " . $e->getMessage());
        return [];
    }
    
    // Filtrar horas ocupadas
    return array_diff($horas_disponibles, $citas_existentes);
}