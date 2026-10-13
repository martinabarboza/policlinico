<?php
class InicioController extends  Controller
{
    public function index()
    {   
        $this->view('landing/inicio', [], 'landing');
    }
    
    public function obtenerServicios(): array
    {
        $modeloServicio = $this->model('Servicio');

        $servicios = $modeloServicio->getArray_Servicios();

        return $servicios;
    }
}
