<?php
$router->get('/', [InicioController::class, 'index']);
$router->get('/login', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'authenticate']);
$router->get('/cerrar-sesion', [AuthController::class, 'logout']);
$router->get('/dashboard', [DashboardController::class, 'index']);
$router->get('/buscar-centro', [DashboardController::class, 'buscarEnCentro']);
$router->get('/consultas-diarias', [DashboardController::class, 'consultasDiarias']);
$router->get('/consultas-incompletas', [DashboardController::class, 'consultasIncompletas']);
$router->get('/centro-consultas', [Centro_ConsultasController::class, 'index']);
$router->get('/centro-tutores', [Centro_TutoresController::class, 'index']);
$router->get('/agenda', [AgendaController::class, 'index']);
$router->get('/hospitalizacion', [HospitalizacionController::class, 'index']);

?>