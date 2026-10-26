<?php

session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'secure'   => false, // pasar a true cuando el sitio corra bajo HTTPS
    'httponly' => true,
    'samesite' => 'Lax',
]);
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
require_once APP_PATH . '/Controllers/Centro_ConsultasController.php';
require_once APP_PATH . '/Controllers/Centro_TutoresController.php';
require_once APP_PATH . '/Controllers/TutorController.php';
require_once APP_PATH . '/Controllers/PacienteController.php';
require_once APP_PATH . '/Controllers/AgendaController.php';
require_once APP_PATH . '/Controllers/HospitalizacionController.php';



$router = new Router();

require_once APP_PATH . '/../routes/web.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$router->dispatch($uri, $method);