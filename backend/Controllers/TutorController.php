<?php

class TutorController extends Controller
{
    public function index(): void
    {
        Auth::requireLogin();

        $busqueda = $_GET['q'] ?? '';
        $errorBusqueda = '';

        if (!is_string($busqueda)) {
            http_response_code(400);
            $busqueda = '';
            $errorBusqueda = 'El término de búsqueda no es válido.';
        } else {
            $busqueda = trim($busqueda);

            if (strlen($busqueda) > 150) {
                http_response_code(400);
                $busqueda = '';
                $errorBusqueda = 'La búsqueda no puede superar los 150 caracteres.';
            }
        }

        $this->mostrarDirectorio($busqueda, '', [], $errorBusqueda);
    }

    public function guardar(): void
    {
        Auth::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . url('centro-tutores'), true, 303);
            exit;
        }

        $datos = $_POST;
        $token = $datos['csrf_token'] ?? null;

        if (!is_string($token) || !Csrf::validar($token)) {
            http_response_code(403);
            unset($_SESSION['csrf_token']);

            $this->mostrarDirectorio(
                '',
                'El formulario venció. Revisá los datos y volvé a enviarlo.',
                $datos
            );

            return;
        }

        try {
            $this->model('TutorModel')->crear($datos);

            $_SESSION['flash_tutor_creado'] =
                'El tutor se registró correctamente.';

            header('Location: ' . url('centro-tutores'), true, 303);
            exit;

        } catch (InvalidArgumentException $error) {
            http_response_code(422);

            $this->mostrarDirectorio(
                '',
                $error->getMessage(),
                $datos
            );

        } catch (mysqli_sql_exception $error) {
            error_log(
                'Error SQL al registrar tutor: ' . $error->getMessage()
            );

            http_response_code(500);

            $this->mostrarDirectorio(
                '',
                'No se pudo registrar el tutor. Revisá los datos e intentá nuevamente.',
                $datos
            );
        }
    }

    private function mostrarDirectorio(
        string $busqueda = '',
        string $error = '',
        array $datos = [],
        string $errorBusqueda = ''
    ): void {
        $tutores = $errorBusqueda === ''
            ? $this->model('TutorModel')->buscar($busqueda)
            : [];

        $this->view(
            'dashboard/centro-tutores',
            [
                'tutores' => $tutores,
                'busqueda' => $busqueda,
                'errorBusqueda' => $errorBusqueda,
                'csrfToken' => Csrf::token(),
                'error' => $error,
                'datos' => $datos,
                'volverUrl' => url('centro-consultas'),
                'buscarUrl' => url('centro-tutores'),
                'guardarUrl' => url('centro-tutores/guardar'),
            ],
            'app',
            ['app-layout.css'],
            ['dashboard.js'],
            [
                'bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js',
                'jquery-4.0.0.min.js.js',
            ]
        );
    }
}
