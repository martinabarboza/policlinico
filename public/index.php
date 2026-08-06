<?php


// ACA NUNCA VA HTML, EL HTML VA EN LAS VISTAS, ACA CARGAMOS EL CONFIG
// LLAMAMOS CONTROLLERS, MANEJADORES DE ERRORES,ETC 

require_once __DIR__ . '/../config/config.php';

require_once APP_PATH . '/Core/Controller.php';
require_once APP_PATH . '/Core/Model.php';

require_once APP_PATH . '/Controllers/InicioController.php';

$controller = new InicioController();
$controller->index();