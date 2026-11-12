<?php

/** Administra el catálogo de servicios; todas las operaciones son exclusivas de Dirección. */
class ServicioController extends Controller
{
    public function index(): void
    {
        Auth::requirePermiso('admin.total');

        $modelo = $this->model('Servicio');
        $editarId = $_GET['editar'] ?? null;
        $servicioEditando = null;
        $error = $_SESSION['servicio_error'] ?? null;
        $mensaje = $_SESSION['servicio_mensaje'] ?? null;
        $datosFormulario = $_SESSION['servicio_datos'] ?? null;
        $idFormulario = $_SESSION['servicio_editar_id'] ?? null;
        unset($_SESSION['servicio_error'], $_SESSION['servicio_mensaje'], $_SESSION['servicio_datos'], $_SESSION['servicio_editar_id']);

        if ($editarId !== null) {
            if (!is_string($editarId) || !ctype_digit($editarId) || (int) $editarId < 1) {
                http_response_code(400);
                $error = 'El identificador del servicio no es válido.';
            } else {
                $servicioEditando = $modelo->obtener((int) $editarId);
                if ($servicioEditando === null) {
                    http_response_code(404);
                    $error = 'No se encontró el servicio seleccionado.';
                }
            }
        }

        if (is_array($datosFormulario)) {
            $servicioEditando = is_string($idFormulario) && ctype_digit($idFormulario)
                ? ['id_Servicio' => (int) $idFormulario] + ($servicioEditando ?? [])
                : null;
            $servicioEditando = array_merge($servicioEditando ?? [], $datosFormulario);
        }

        $this->view(
            'dashboard/servicios',
            [
                'servicios' => $modelo->listar(),
                'servicioEditando' => $servicioEditando,
                'csrfToken' => Csrf::token(),
                'error' => is_string($error) ? $error : null,
                'mensaje' => is_string($mensaje) ? $mensaje : null,
            ],
            'app',
            ['app-layout.css', 'agenda.css'],
            ['dashboard.js'],
            ['bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js', 'jquery-4.0.0.min.js.js']
        );
    }

    public function guardar(): void
    {
        Auth::requirePermiso('admin.total');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . url('servicios'), true, 303);
            exit;
        }

        $datos = $_POST;
        $servicioId = $datos['servicio_id'] ?? '';
        if (
            !is_string($servicioId)
            || ($servicioId !== '' && (!ctype_digit($servicioId) || (int) $servicioId < 1))
        ) {
            http_response_code(400);
            echo 'El identificador del servicio no es válido.';
            return;
        }

        $token = $datos['csrf_token'] ?? null;
        if (!is_string($token) || !Csrf::validar($token)) {
            $this->guardarErrorFormulario($datos, $servicioId, 'El formulario venció. Revisá los datos y volvé a enviarlo.');
        }

        try {
            $modelo = $this->model('Servicio');
            if ($servicioId === '') {
                $modelo->crear($datos, (int) Auth::id());
                $_SESSION['servicio_mensaje'] = 'El servicio se creó correctamente.';
            } else {
                $modelo->actualizar((int) $servicioId, $datos);
                $_SESSION['servicio_mensaje'] = 'El servicio se actualizó correctamente.';
            }
        } catch (InvalidArgumentException $error) {
            $this->guardarErrorFormulario($datos, $servicioId, $error->getMessage());
        } catch (mysqli_sql_exception $error) {
            error_log('No se pudo guardar el servicio: ' . $error->getMessage());
            $this->guardarErrorFormulario($datos, $servicioId, 'No se pudo guardar el servicio. Intentá nuevamente.');
        }

        header('Location: ' . url('servicios'), true, 303);
        exit;
    }

    public function eliminar(): void
    {
        Auth::requirePermiso('admin.total');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . url('servicios'), true, 303);
            exit;
        }

        $servicioId = $_POST['servicio_id'] ?? null;
        $token = $_POST['csrf_token'] ?? null;
        if (
            !is_string($servicioId) || !ctype_digit($servicioId) || (int) $servicioId < 1
            || !is_string($token) || !Csrf::validar($token)
        ) {
            http_response_code(403);
            $_SESSION['servicio_error'] = 'La solicitud de eliminación no es válida o venció.';
            header('Location: ' . url('servicios'), true, 303);
            exit;
        }

        try {
            $this->model('Servicio')->eliminar((int) $servicioId);
            $_SESSION['servicio_mensaje'] = 'El servicio se eliminó correctamente.';
        } catch (InvalidArgumentException $error) {
            $_SESSION['servicio_error'] = $error->getMessage();
        } catch (mysqli_sql_exception $error) {
            error_log('No se pudo eliminar el servicio: ' . $error->getMessage());
            $_SESSION['servicio_error'] = 'No se pudo eliminar el servicio. Intentá nuevamente.';
        }

        header('Location: ' . url('servicios'), true, 303);
        exit;
    }

    private function guardarErrorFormulario(array $datos, string $servicioId, string $mensaje): never
    {
        $_SESSION['servicio_error'] = $mensaje;
        $_SESSION['servicio_datos'] = $datos;
        $_SESSION['servicio_editar_id'] = $servicioId;
        header('Location: ' . url('servicios'), true, 303);
        exit;
    }
}