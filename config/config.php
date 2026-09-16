<?php

// ACA SE DEFIENEN LAS DIRECCIONES IMPORTANTES

define('ROOT_PATH', dirname(__DIR__));

define('APP_PATH', ROOT_PATH . '/backend');
define('BASE_URL', '');
define('ASSETS_URL', BASE_URL . '/activos');
define('CONTROLLERS_PATH', APP_PATH . '/Controllers');
define('MODELS_PATH', APP_PATH . '/Models');
define('VIEWS_PATH', APP_PATH . '/Views');
define('INCLUDES_PATH', APP_PATH . '/Views/layouts');
define('CORE_PATH', APP_PATH . '/Core');
define('HELPERS_PATH', APP_PATH . '/Helpers');


// Configuracion privada 

// return [
// 'DB_USER' => 'user', 
// 'DB_PASS' => '123456', 
// 'DB_HOST' => 'db',
// 'DB_NAME' => 'policlinico_db',
// 'SECRET' => 'Esto es una prueba'
// ];

// ?>