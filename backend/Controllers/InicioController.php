<?php
class InicioController
{
    public function index()
    {
        require __DIR__ . '/../Views/landing_views/inicio.php';
    }

    public function obtenerServicios()
    {   //NOTA
        //Armar un model que obtenga los servicios de la base de datos.
        // Y que se guarden en el array recorriendolos con for.
        //
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
?>