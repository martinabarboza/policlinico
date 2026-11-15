<?php

/** Coordina los ingresos y altas de pacientes hospitalizados. */
class HospitalizacionController extends Controller
{
    /** Muestra las hospitalizaciones activas y el formulario de ingreso. */
    public function index(): void
    {
        Auth::requireLogin();

        $busquedaPaciente = $_GET['busquedaPaciente'] ?? '';
        $errorBusqueda = null;
        if (!is_string($busquedaPaciente) || strlen($busquedaPaciente) > 100) {
            http_response_code(400);
            $busquedaPaciente = '';
            $errorBusqueda = 'La búsqueda no puede superar los 100 caracteres.';
        } else {
            $busquedaPaciente = trim($busquedaPaciente);
        }

        $mensaje = $_SESSION['hospitalizacion_mensaje'] ?? null;
        $error = $errorBusqueda ?? ($_SESSION['hospitalizacion_error'] ?? null);
        unset($_SESSION['hospitalizacion_mensaje'], $_SESSION['hospitalizacion_error']);

        $this->mostrarPagina(
            ['busquedaPaciente' => $busquedaPaciente],
            is_string($error) ? $error : null,
            is_string($mensaje) ? $mensaje : null
        );
    }

    /** Valida y registra un nuevo ingreso. */
    public function registrarIngreso(): void
    {
        Auth::requireLogin();

        $datos = $_POST;
        if (!$this->csrfValido($datos['csrf_token'] ?? null)) {
            http_response_code(403);
            $this->mostrarPagina($datos, 'El formulario venció. Volvé a intentarlo.');
            return;
        }

        $pacienteId = $datos['id_Paciente'] ?? null;
        $consultaId = $datos['id_Consulta'] ?? '';
        $motivo = $datos['motivo_Hospitalizacion'] ?? null;
        $busquedaPaciente = $datos['busquedaPaciente'] ?? '';
        if (
            !is_string($pacienteId) || !ctype_digit($pacienteId) || (int) $pacienteId < 1
            || !is_string($consultaId)
            || ($consultaId !== '' && (!ctype_digit($consultaId) || (int) $consultaId < 1))
            || !is_string($motivo) || strlen($motivo) > 2000
            || !is_string($busquedaPaciente) || strlen($busquedaPaciente) > 100
        ) {
            http_response_code(422);
            $this->mostrarPagina($datos, 'Completá los datos del ingreso con valores válidos.');
            return;
        }

        try {
            $this->model('HospitalizacionModel')->registrarIngreso(
                (int) $pacienteId,
                $consultaId === '' ? null : (int) $consultaId,
                (int) Auth::id(),
                $motivo
            );
            $_SESSION['hospitalizacion_mensaje'] = 'El paciente ingresó correctamente.';
            $destino = url('hospitalizacion');
            $busquedaPaciente = trim($busquedaPaciente);
            if ($busquedaPaciente !== '') {
                $destino .= '?busquedaPaciente=' . rawurlencode($busquedaPaciente);
            }
            header('Location: ' . $destino, true, 303);
            exit;
        } catch (InvalidArgumentException $error) {
            http_response_code(422);
            $this->mostrarPagina($datos, $error->getMessage());
        } catch (mysqli_sql_exception $error) {
            error_log('No se pudo registrar el ingreso hospitalario: ' . $error->getMessage());
            http_response_code(500);
            $this->mostrarPagina($datos, 'No se pudo registrar el ingreso. Intentá nuevamente.');
        }
    }

    /** Registra el alta de una internación activa. */
    public function darAlta(): void
    {
        Auth::requireLogin();

        $id = $_POST['id_Hospitalizacion'] ?? null;
        if (!$this->csrfValido($_POST['csrf_token'] ?? null)) {
            http_response_code(403);
            $this->mostrarPagina([], 'El formulario venció. Volvé a intentarlo.');
            return;
        }
        if (!is_string($id) || !ctype_digit($id) || (int) $id < 1) {
            http_response_code(422);
            $this->mostrarPagina([], 'La internación indicada no es válida.');
            return;
        }

        try {
            if ($this->model('HospitalizacionModel')->registrarAlta((int) $id)) {
                $_SESSION['hospitalizacion_mensaje'] = 'El paciente recibió el alta correctamente.';
            } else {
                $_SESSION['hospitalizacion_error'] = 'La internación ya no está activa o no existe.';
            }
        } catch (mysqli_sql_exception $error) {
            error_log('No se pudo registrar el alta hospitalaria: ' . $error->getMessage());
            $_SESSION['hospitalizacion_error'] = 'No se pudo registrar el alta. Intentá nuevamente.';
        }

        header('Location: ' . url('hospitalizacion'), true, 303);
        exit;
    }

    /** Renderiza la pantalla conservando los datos enviados cuando hay errores. */
    private function mostrarPagina(array $oldInput = [], ?string $error = null, ?string $mensaje = null): void
    {
        $busquedaPaciente = $oldInput['busquedaPaciente'] ?? '';
        if (!is_string($busquedaPaciente) || strlen($busquedaPaciente) > 100) {
            $busquedaPaciente = '';
            $oldInput['busquedaPaciente'] = '';
            $error ??= 'La búsqueda no puede superar los 100 caracteres.';
        }
        $busquedaPaciente = trim($busquedaPaciente);
        $oldInput['busquedaPaciente'] = $busquedaPaciente;

        $model = $this->model('HospitalizacionModel');
        $this->view(
            'dashboard/hospitalizacion',
            [
                'hospitalizaciones' => $model->obtenerActivas(),
                'pacientes' => $busquedaPaciente !== ''
                    ? $this->model('PacienteModel')->buscar($busquedaPaciente)
                    : [],
                'consultas' => $model->listarConsultas(),
                'busquedaPaciente' => $busquedaPaciente,
                'oldInput' => $oldInput,
                'csrfToken' => Csrf::token(),
                'mensaje' => $mensaje,
                'error' => $error,
            ],
            'app',
            ['app-layout.css', 'hospitalizacion.css'],
            ['dashboard.js', 'hospitalizacion.js'],
            ['bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js', 'jquery-4.0.0.min.js.js']
        );
    }

    private function csrfValido($token): bool
    {
        return is_string($token) && Csrf::validar($token);
    }
}
