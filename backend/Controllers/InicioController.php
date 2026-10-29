<?php
class InicioController extends  Controller
{
    public function index()
    {   
        $servicios = [];

        try {
            $servicios = $this->obtenerServicios();
        } catch (mysqli_sql_exception $error) {
            if ((int) $error->getCode() !== 1146) {
                throw $error;
            }

            error_log('No se pudo cargar el catálogo de servicios: la tabla SERVICIO no existe.');
        }

        $this->view('landing/inicio', ['servicios' => $servicios], 'landing');
    }
    
    public function obtenerServicios(): array
    {
        $modeloServicio = $this->model('Servicio');

        $servicios = $modeloServicio->getArray_Servicios();

        return $servicios;
    }
}
