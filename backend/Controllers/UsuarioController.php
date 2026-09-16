<?php

class UsuarioController extends Controller
{

    //
    // Index llama a la vista del titulo de la página, mediante usuario y gestion de roles.
    //

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