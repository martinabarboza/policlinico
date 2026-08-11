<?php

class UsuarioController extends Controller
{
    public function index(): void
    {
        Auth::requireLogin();
        Auth::requirePermiso('admin.total');

        $this->view('usuarios/index', [
            'tituloPagina' => 'Usuarios',
            'activeMenu'   => 'usuarios',
            'roles'        => $this->model('Rol')->activos(),
        ]);
    }
}