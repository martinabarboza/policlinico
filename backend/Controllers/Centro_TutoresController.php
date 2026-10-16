<?php

class Centro_TutoresController extends Controller
{
    /**
     * Lee `?busqueda=` de la URL y muestra coincidencias de tutores.
     * Si falta el término, deja el centro listo para continuar el desarrollo.
     */
    public function index(): void
    {
        Auth::requireLogin();

        $busqueda = $_GET['busqueda'] ?? null;
        $errorBusqueda = isset($_GET['error']) ? 'Ingresa un término de búsqueda válido.' : null;
        $tutores = [];

        if ($busqueda !== null) {
            if (!is_string($busqueda)) {
                http_response_code(400);
                $errorBusqueda = 'El término de búsqueda no es válido.';
                $busqueda = '';
            } else {
                $busqueda = trim($busqueda);
                if ($busqueda === '' || strlen($busqueda) > 100) {
                    $errorBusqueda = 'Ingresa un término de búsqueda de hasta 100 caracteres.';
                } else {
                    $tutores = $this->model('TutorModel')->buscar($busqueda);
                }
            }
        }

        $this->view(
            'dashboard/centro-tutores',
            [
                'busqueda' => $busqueda,
                'tutores' => $tutores,
                'errorBusqueda' => $errorBusqueda,
            ],
            'app',
            ['app-layout.css'],
            ['dashboard.js'],
            ['bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js', 'jquery-4.0.0.min.js.js']
        );
    }
}
