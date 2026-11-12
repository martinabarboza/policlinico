<?php

/** Muestra el módulo informativo de hospitalización para usuarios autenticados. */
class HospitalizacionController extends Controller
{
    /** Renderiza la vista; las operaciones de ingreso/alta aún no están implementadas. */
    public function index()
    {   
        Auth::requireLogin();
        $this->view('dashboard/hospitalizacion',[],'app',['app-layout.css'], ['dashboard.js'], ['bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js', 'jquery-4.0.0.min.js.js']);
    }
}
