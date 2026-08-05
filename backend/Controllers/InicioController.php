<?php
class InicioController {
    public function index () {
        require __DIR__ . '/../Views/landing_views/inicio.php';
    }
}
$InicioController = new InicioController();
?>