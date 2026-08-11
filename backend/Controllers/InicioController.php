<?php
class InicioController
{
    public function index()
    {
        require __DIR__ . '/../Views/landing_views/inicio.php';
    }

    public function obtenerServicios()
    {
        return [
            [
                'titulo' => 'Consultas y atención al publico',
                'descripcion' => 'Acceso al módulo de agendas, gestion de turnos y trámites para usuaríos y propietaios.',
                'imagen' => 'activos/imgs/consulta-veterinaria.avif',
                'link' => '#'
            ],
            [
                'titulo' => 'Consultas y atención al publico',
                'descripcion' => 'Acceso al módulo de agendas, gestion de turnos y trámites para usuaríos y propietaios.',
                'imagen' => 'activos/imgs/consulta-veterinaria.avif',
                'link' => '#'
            ],
            [
                'titulo' => 'Consultas y atención al publico',
                'descripcion' => 'Acceso al módulo de agendas, gestion de turnos y trámites para usuaríos y propietaios.',
                'imagen' => 'activos/imgs/consulta-veterinaria.avif',
                'link' => '#'
            ]
        ];
    }
}
$InicioController = new InicioController();
