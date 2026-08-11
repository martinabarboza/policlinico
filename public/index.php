<?php

require_once __DIR__ . '/../config/config.php';

require_once APP_PATH . '/../backend/Helpers/url.php';
require_once APP_PATH . '/../backend/Core/Router.php';

require_once APP_PATH . '/Core/Controller.php';
require_once APP_PATH . '/Core/Model.php';

require_once APP_PATH . '/Controllers/InicioController.php';


$router = new Router();

require_once APP_PATH . '/../routes/web.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$router->dispatch($uri, $method);