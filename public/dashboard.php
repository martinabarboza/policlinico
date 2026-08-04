<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
require __DIR__ . '/../config/config.php';

require CORE_PATH . '/Controller.php';
require CORE_PATH . '/Model.php';

require CONTROLLERS_PATH . '/DashboardController.php';
require MODELS_PATH . '/DashboardModel.php';

$controller = new DashboardController();
$controller->index();