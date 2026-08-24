<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'secure'   => false,
    'httponly' => true,
    'samesite' => 'Lax',
]);
session_start();

require __DIR__ . '/../config/config.php';

require APP_PATH . '/../backend/Helpers/url.php';
require CORE_PATH . '/Database.php';
require CORE_PATH . '/Model.php';
require CORE_PATH . '/Controller.php';
require CORE_PATH . '/Auth.php';

require CONTROLLERS_PATH . '/DashboardController.php';
require MODELS_PATH . '/DashboardModel.php';

$controller = new DashboardController();
$controller->index();