<?php
// Incluir configuración y funciones
include 'includes/config.php';
include 'includes/functions.php';

// Si el usuario ya está logueado, redirigir al panel
if (estaLogueado()) {
    redirigir(ADMIN_PATH);
}

// Variables para el formulario y mensajes
$mensaje = '';
$tipo_mensaje = '';
$username = '';

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitizar y validar datos del formulario
    $username = limpiarDato($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validar datos
    $errores = [];
    
    if (empty($username)) {
        $errores['username'] = 'Por favor, ingrese su nombre de usuario';
    }
    
    if (empty($password)) {
        $errores['password'] = 'Por favor, ingrese su contraseña';
    }
    
    // Si no hay errores, intentar login
    if (empty($errores)) {
        // Conectar a la base de datos
        $conn = conectarDB();
        
        try {
            // Buscar usuario por nombre de usuario
            $sql = "SELECT * FROM usuarios WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $resultado = $stmt->get_result();
            
            if ($resultado->num_rows === 1) {
                $usuario = $resultado->fetch_assoc();
                
                // Verificar contraseña
                if (password_verify($password, $usuario['password'])) {
                    // Contraseña correcta, iniciar sesión
                    $_SESSION['user_id'] = $usuario['id'];
                    $_SESSION['user_username'] = $usuario['username'];
                    $_SESSION['user_role'] = $usuario['rol'];
                    $_SESSION['user_fullname'] = $usuario['nombre_completo'];
                    
                    // Registrar el login
                    $ip = $_SERVER['REMOTE_ADDR'];
                    $detalles = "Login exitoso - " . $usuario['username'];
                    
                    $sql = "INSERT INTO log_acciones (usuario_id, accion, detalles, ip_address) VALUES (?, 'login', ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('iss', $usuario['id'], $detalles, $ip);
                    $stmt->execute();
                    
                    // Redirigir al panel o página de destino
                    if (isset($_SESSION['redirect_after_login']) && !empty($_SESSION['redirect_after_login'])) {
                        $redirect = $_SESSION['redirect_after_login'];
                        unset($_SESSION['redirect_after_login']);
                        redirigir($redirect);
                    } else {
                        redirigir(ADMIN_PATH);
                    }
                } else {
                    // Contraseña incorrecta
                    $mensaje = 'Credenciales inválidas. Por favor, verifique su nombre de usuario y contraseña.';
                    $tipo_mensaje = 'error';
                    
                    // Registrar intento fallido
                    $ip = $_SERVER['REMOTE_ADDR'];
                    $detalles = "Intento fallido para el usuario " . $username . " (contraseña incorrecta)";
                    
                    $sql = "INSERT INTO log_acciones (usuario_id, accion, detalles, ip_address) VALUES (NULL, 'login_fallido', ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('ss', $detalles, $ip);
                    $stmt->execute();
                }
            } else {
                // Usuario no encontrado
                $mensaje = 'Credenciales inválidas. Por favor, verifique su nombre de usuario y contraseña.';
                $tipo_mensaje = 'error';
                
                // Registrar intento fallido
                $ip = $_SERVER['REMOTE_ADDR'];
                $detalles = "Intento fallido para el usuario " . $username . " (usuario no encontrado)";
                
                $sql = "INSERT INTO log_acciones (usuario_id, accion, detalles, ip_address) VALUES (NULL, 'login_fallido', ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('ss', $detalles, $ip);
                $stmt->execute();
            }
        } catch (Exception $e) {
            $mensaje = 'Ha ocurrido un error. Por favor, inténtelo de nuevo más tarde.';
            $tipo_mensaje = 'error';
            
            // En un entorno de producción, registrar el error
            error_log('Error en login: ' . $e->getMessage());
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
    <title>Iniciar Sesión - Policlínico Veterinario</title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/styles.css">
    
    <!-- Favicon -->
    <link rel="icon" href="assets/favicon.ico">
    
    <style>
        .login-page {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: calc(100vh - 300px);
        }
        
        .login-container {
            width: 100%;
            max-width: 400px;
            background-color: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 2rem;
        }
        
        .login-logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-logo img {
            max-width: 200px;
        }
        
        .login-title {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .password-toggle {
            position: relative;
        }
        
        .password-toggle i {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--gray);
        }
        
        .login-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1.5rem;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <?php include 'includes/header.php'; ?>
    
    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <div class="container">
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li>Iniciar Sesión</li>
            </ul>
        </div>
    </div>
    
    <!-- Sección de Login -->
    <section class="login-page section-padding">
        <div class="login-container">
            <div class="login-logo">
                <img src="assets/logo.svg" alt="Policlínico Veterinario">
            </div>
            
            <h1 class="login-title">Iniciar Sesión</h1>
            
            <?php if (!empty($mensaje)): ?>
                <div class="alert alert-<?php echo $tipo_mensaje; ?>">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>
            
            <form id="loginForm" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <div class="form-group">
                    <label for="username">Nombre de Usuario <span class="required">*</span></label>
                    <input type="text" id="username" name="username" value="<?php echo $username; ?>" required>
                    <?php if (!empty($errores['username'])): ?>
                        <div class="error-message"><?php echo $errores['username']; ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="password">Contraseña <span class="required">*</span></label>
                    <div class="password-toggle">
                        <input type="password" id="password" name="password" required>
                        <i class="fas fa-eye" id="togglePassword"></i>
                    </div>
                    <?php if (!empty($errores['password'])): ?>
                        <div class="error-message"><?php echo $errores['password']; ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="login-actions">
                    <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
                    <a href="recuperar_contrasena.php">¿Olvidó su contraseña?</a>
                </div>
            </form>
        </div>
    </section>
    
    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
    
    <!-- JavaScript -->
    <script src="js/main.js"></script>
    <script>
        // Script para mostrar/ocultar contraseña
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const password = document.getElementById('password');
            
            if (togglePassword && password) {
                togglePassword.addEventListener('click', function() {
                    // Cambiar el tipo de input
                    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                    password.setAttribute('type', type);
                    
                    // Cambiar el ícono
                    this.classList.toggle('fa-eye');
                    this.classList.toggle('fa-eye-slash');
                });
            }
        });
    </script>
</body>
</html>