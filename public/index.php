<?php

$forwardedProto = strtolower(trim(explode(',', $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '')[0]));
$httpsDirecto = isset($_SERVER['HTTPS']) && strtolower((string) $_SERVER['HTTPS']) !== 'off';
$conexionSegura = $httpsDirecto || $forwardedProto === 'https';

session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'secure'   => $conexionSegura,
    'httponly' => true,
    'samesite' => 'Lax',
]);
// Permite que las sesiones marcadas como recordadas sigan disponibles hasta 30 días.
ini_set('session.gc_maxlifetime', '2592000');
session_start();

require_once __DIR__ . '/../config/config.php';

require_once APP_PATH . '/../backend/Helpers/url.php';
require_once APP_PATH . '/../backend/Core/Router.php';

require_once APP_PATH . '/Core/Database.php';
require_once APP_PATH . '/Core/Model.php';
require_once APP_PATH . '/Core/Controller.php';
require_once APP_PATH . '/Core/Auth.php';
require_once APP_PATH . '/Core/Csrf.php';

//Instanciar los controllers para hacer accesible.
require_once APP_PATH . '/Controllers/InicioController.php';
require_once APP_PATH . '/Controllers/AuthController.php';
require_once APP_PATH . '/Controllers/DashboardController.php';
require_once APP_PATH . '/Controllers/NotificacionController.php';
require_once APP_PATH . '/Controllers/Centro_ConsultasController.php';
require_once APP_PATH . '/Controllers/Centro_TutoresController.php';
require_once APP_PATH . '/Controllers/TutorController.php';
require_once APP_PATH . '/Controllers/PacienteController.php';
require_once APP_PATH . '/Controllers/AgendaController.php';
require_once APP_PATH . '/Controllers/HospitalizacionController.php';
require_once APP_PATH . '/Controllers/UsuarioController.php';


$router = new Router();

require_once APP_PATH . '/../routes/web.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$router->dispatch($uri, $method);