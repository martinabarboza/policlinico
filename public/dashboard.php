<?php
/*
<--- PARA DEBUG --->
error_reporting(E_ALL);
ini_set('display_errors', 1);
*/
/*
session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'secure'   => false,
    'httponly' => true,
    'samesite' => 'Lax',
]);
*/

// SE INICIALISA LA SESIÓN PARA CORROBORAR QUE 
// EL USUARIO ESTE AUTENTICADO
session_start();


require __DIR__ . '/../config/config.php';
require HELPERS_PATH . '/url.php';
require CORE_PATH . '/Database.php';
require CORE_PATH . '/Model.php';
require CORE_PATH . '/Controller.php';
require CORE_PATH . '/Auth.php';
require CONTROLLERS_PATH . '/DashboardController.php';
require MODELS_PATH . '/DashboardModel.php';


Auth::requireLogin();
$controller = new DashboardController();
$controller->index();
