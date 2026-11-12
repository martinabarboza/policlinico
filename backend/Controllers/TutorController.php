<?php

/** Atiende el directorio, alta y edición de tutores. */
class TutorController extends Controller
{
    /** Valida filtros opcionales y abre el formulario cuando se solicita editar. */
    public function index(): void
    {
        Auth::requireLogin();

        $busqueda = $_GET['busqueda'] ?? $_GET['q'] ?? '';
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

        $editando = null;
        if (array_key_exists('editar', $_GET)) {
            $id = $_GET['editar'];
            if (!is_string($id) || !ctype_digit($id) || (int) $id < 1) {
                http_response_code(400);
                $errorBusqueda = 'El identificador del tutor no es válido.';
            } else {
                $editando = $this->model('TutorModel')->obtener((int) $id);
                if ($editando === null) {
                    http_response_code(404);
                    $errorBusqueda = 'No se encontró el tutor solicitado.';
                }
            }
        }

        $this->mostrarDirectorio($busqueda, '', $editando ?? [], $errorBusqueda, $editando);
    }

    /** Valida CSRF y delega la creación al modelo. */
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

    /** Actualiza un tutor existente con validación de ID, CSRF y campos. */
    public function actualizar(): void
    {
        Auth::requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . url('centro-tutores'), true, 303);
            exit;
        }

        $datos = $_POST;
        $id = $datos['tutor_id'] ?? null;
        if (!is_string($id) || !ctype_digit($id) || (int) $id < 1) {
            http_response_code(400);
            echo 'El identificador del tutor no es válido.';
            return;
        }
        $tutorId = (int) $id;
        $token = $datos['csrf_token'] ?? null;
        if (!is_string($token) || !Csrf::validar($token)) {
            http_response_code(403);
            $this->mostrarDirectorio(
                '',
                'El formulario venció. Revisá los datos y volvé a enviarlo.',
                $datos,
                '',
                ['id_Tutor' => $tutorId]
            );
            return;
        }

        try {
            $this->model('TutorModel')->actualizar($tutorId, $datos);
            $_SESSION['flash_tutor_creado'] = 'Los datos del tutor se actualizaron correctamente.';
            header('Location: ' . url('centro-tutores'), true, 303);
            exit;
        } catch (InvalidArgumentException $error) {
            http_response_code(422);
            $this->mostrarDirectorio(
                '',
                $error->getMessage(),
                $datos,
                '',
                ['id_Tutor' => $tutorId]
            );
        } catch (mysqli_sql_exception $error) {
            error_log('Error SQL al actualizar tutor: ' . $error->getMessage());
            http_response_code(500);
            $this->mostrarDirectorio(
                '',
                'No se pudo actualizar el tutor. Revisá los datos e intentá nuevamente.',
                $datos,
                '',
                ['id_Tutor' => $tutorId]
            );
        }
    }

    /** Renderiza el directorio con resultados, errores y datos del formulario. */
    private function mostrarDirectorio(
        string $busqueda = '',
        string $error = '',
        array $datos = [],
        string $errorBusqueda = '',
        ?array $tutorEditando = null
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
                'tutorEditando' => $tutorEditando,
                'volverUrl' => url('centro-consultas'),
                'buscarUrl' => url('centro-tutores'),
                'guardarUrl' => url('centro-tutores/guardar'),
                'actualizarUrl' => url('centro-tutores/actualizar'),
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
