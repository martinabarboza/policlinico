<?php

/** Prepara la página pública de inicio y su catálogo de servicios. */
class InicioController extends  Controller
{
    /** Muestra la landing; si falta el catálogo, conserva la página sin servicios. */
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
    
    /** Obtiene del modelo las tarjetas que presenta la landing. */
    public function obtenerServicios(): array
    {
        $modeloServicio = $this->model('Servicio');

        $servicios = $modeloServicio->getArray_Servicios();

        return $servicios;
    }
}
