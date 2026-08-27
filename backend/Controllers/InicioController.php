<?php
class InicioController extends  Controller
{
    public function index()
    {
        require __DIR__ . '/../Views/landing/inicio.php';
    }

    public function obtenerServicios(): array
    {   //NOTA
        //Armar un model que obtenga los servicios de la base de datos.
        // Y que se guarden en el array recorriendolos con for.
        //

        return [
            [
                'titulo' => 'Consultas y atención al público',
                'descripcion' => 'Acceso al módulo de agendas, gestión de turnos y trámites para usuarios y propietarios.',
                'imagen' => 'activos/imgs/consulta-veterinaria.avif',
                'link' => 'login'
            ],

            [
                'titulo' => 'Gestión de pacientes',
                'descripcion' => 'Registro, consulta y actualización de información clínica de los pacientes veterinarios.',
                'imagen' => 'activos/imgs/consulta-veterinaria.avif',
                'link' => '#'
            ],

            [
                'titulo' => 'Gestión de profesionales',
                'descripcion' => 'Administración de profesionales veterinarios, especialidades y datos relacionados.',
                'imagen' => 'activos/imgs/consulta-veterinaria.avif',
                'link' => '#'
            ],

            [
                'titulo' => 'Historias clínicas',
                'descripcion' => 'Consulta y gestión de historias clínicas, diagnósticos, tratamientos y antecedentes.',
                'imagen' => 'activos/imgs/consulta-veterinaria.avif',
                'link' => '#'
            ],

            [
                'titulo' => 'Investigación epidemiológica',
                'descripcion' => 'Acceso a información y herramientas destinadas al análisis de datos epidemiológicos.',
                'imagen' => 'activos/imgs/consulta-veterinaria.avif',
                'link' => '#'
            ],

            [
                'titulo' => 'Reportes y estadísticas',
                'descripcion' => 'Generación y consulta de reportes estadísticos sobre la actividad de la policlínica.',
                'imagen' => 'activos/imgs/consulta-veterinaria.avif',
                'link' => '#'
            ]
        ];
    }
}
