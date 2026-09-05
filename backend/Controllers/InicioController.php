<?php
class InicioController extends  Controller
{
    public function index()
    {
        require __DIR__ . '/../Views/landing/inicio.php';
    }
    
    public function obtenerServicios(): array
    {
        $modeloServicio = $this->model('Servicio');

        $servicios = $modeloServicio->getArray_Servicios();

        return $servicios;
    }
}
