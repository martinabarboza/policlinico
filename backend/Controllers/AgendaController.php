<?php

class AgendaController extends Controller
{
    public function index(): void
    {
        Auth::requireLogin();

        $fechaSolicitada = $_GET['fecha'] ?? date('Y-m-d');
        $fecha = is_string($fechaSolicitada)
            ? DateTimeImmutable::createFromFormat('!Y-m-d', $fechaSolicitada)
            : false;
        $erroresFecha = DateTimeImmutable::getLastErrors();
        $error = null;
        if (
            !$fecha
            || ($erroresFecha !== false && ($erroresFecha['warning_count'] > 0 || $erroresFecha['error_count'] > 0))
            || $fecha->format('Y-m-d') !== $fechaSolicitada
        ) {
            http_response_code(400);
            $fecha = new DateTimeImmutable('today');
            $error = 'La fecha indicada no es válida; se muestra la agenda de hoy.';
        }

        $vista = $_GET['vista'] ?? 'dia';
        if (!is_string($vista) || !in_array($vista, ['dia', 'semana'], true)) {
            http_response_code(400);
            $vista = 'dia';
            $error = 'La vista seleccionada no es válida.';
        }

        $inicio = $fecha->setTime(0, 0);
        if ($vista === 'semana') {
            $inicio = $inicio->modify('monday this week');
        }
        $fin = $vista === 'semana'
            ? $inicio->modify('+1 week')
            : $inicio->modify('+1 day');

        $mensaje = $_SESSION['agenda_mensaje'] ?? null;
        unset($_SESSION['agenda_mensaje']);
        $error ??= $_SESSION['agenda_error'] ?? null;
        unset($_SESSION['agenda_error']);

        $modelo = $this->model('AgendaModel');
        $this->view(
            'dashboard/agenda',
            [
                'turnos' => $modelo->listar($inicio, $fin),
                'pacientes' => $modelo->listarPacientes(),
                'veterinarios' => $modelo->listarVeterinarios(),
                'fechaSeleccionada' => $fecha->format('Y-m-d'),
                'fechaInicio' => $inicio->format('Y-m-d'),
                'fechaFin' => $fin->modify('-1 day')->format('Y-m-d'),
                'vistaAgenda' => $vista,
                'csrfToken' => Csrf::token(),
                'error' => $error,
                'mensaje' => $mensaje,
                'oldInput' => [],
                'puedeGestionar' => $this->puedeGestionar(),
            ],
            'app',
            ['app-layout.css', 'agenda.css'],
            ['dashboard.js'],
            ['bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js', 'jquery-4.0.0.min.js.js']
        );
    }

    public function guardar(): void
    {
        Auth::requireLogin();
        if (!$this->puedeGestionar()) {
            http_response_code(403);
            echo 'Solo profesores y directores pueden gestionar la agenda.';
            return;
        }

        $datos = $_POST;
        if (!$this->csrfValido($datos['csrf_token'] ?? null)) {
            http_response_code(403);
            $this->mostrarFormulario($datos, 'El formulario venció. Volvé a intentarlo.');
            return;
        }

        $paciente = $datos['id_Paciente'] ?? null;
        $veterinario = $datos['id_Profesor'] ?? null;
        $fechaHora = $datos['fecha_hora_Consulta'] ?? null;
        $tipo = $datos['tipo_Consulta'] ?? null;
        $motivo = $datos['motivo_Consulta'] ?? '';
        if (
            !is_string($paciente) || !ctype_digit($paciente) || (int) $paciente < 1
            || !is_string($veterinario) || !ctype_digit($veterinario) || (int) $veterinario < 1
            || !is_string($fechaHora) || !is_string($tipo) || !is_string($motivo)
        ) {
            http_response_code(422);
            $this->mostrarFormulario($datos, 'Completá los datos del turno con valores válidos.');
            return;
        }

        try {
            $consultaId = $this->model('AgendaModel')->crearTurno(
                (int) $paciente,
                (int) $veterinario,
                $fechaHora,
                $tipo,
                trim($motivo)
            );
            $_SESSION['agenda_mensaje'] = 'El turno se agendó correctamente como pendiente.';
            header('Location: ' . url('agenda') . '?fecha=' . rawurlencode(substr($fechaHora, 0, 10)), true, 303);
            exit;
        } catch (InvalidArgumentException $error) {
            http_response_code(422);
            $this->mostrarFormulario($datos, $error->getMessage());
        } catch (mysqli_sql_exception $error) {
            error_log('No se pudo agendar el turno: ' . $error->getMessage());
            http_response_code(500);
            $this->mostrarFormulario($datos, 'No se pudo agendar el turno. Intentá nuevamente.');
        }
    }

    public function reprogramar(): void
    {
        Auth::requireLogin();
        if (!$this->puedeGestionar()) {
            http_response_code(403);
            echo 'Solo profesores y directores pueden gestionar la agenda.';
            return;
        }

        $consultaId = $_POST['consulta_id'] ?? null;
        $veterinario = $_POST['id_Profesor'] ?? null;
        $fechaHora = $_POST['fecha_hora_Consulta'] ?? null;
        if (
            !$this->csrfValido($_POST['csrf_token'] ?? null)
            || !is_string($consultaId) || !ctype_digit($consultaId) || (int) $consultaId < 1
            || !is_string($veterinario) || !ctype_digit($veterinario) || (int) $veterinario < 1
            || !is_string($fechaHora)
        ) {
            $_SESSION['agenda_error'] = 'El formulario de reprogramación no es válido o venció.';
            $this->redirigirFechaAgenda($_POST['return_fecha'] ?? null);
        }

        $fechaRetorno = $_POST['return_fecha'] ?? null;
        try {
            $this->model('AgendaModel')->reprogramar((int) $consultaId, (int) $veterinario, $fechaHora);
            $_SESSION['agenda_mensaje'] = 'El turno se reprogramó correctamente.';
            $fechaRetorno = substr($fechaHora, 0, 10);
        } catch (InvalidArgumentException $error) {
            $_SESSION['agenda_error'] = $error->getMessage();
        } catch (mysqli_sql_exception $error) {
            error_log('No se pudo reprogramar el turno: ' . $error->getMessage());
            $_SESSION['agenda_error'] = 'No se pudo reprogramar el turno. Intentá nuevamente.';
        }

        $this->redirigirFechaAgenda($fechaRetorno);
    }

    public function cancelar(): void
    {
        Auth::requireLogin();
        if (!$this->puedeGestionar()) {
            http_response_code(403);
            echo 'Solo profesores y directores pueden gestionar la agenda.';
            return;
        }

        $consultaId = $_POST['consulta_id'] ?? null;
        if (
            !$this->csrfValido($_POST['csrf_token'] ?? null)
            || !is_string($consultaId) || !ctype_digit($consultaId) || (int) $consultaId < 1
        ) {
            $_SESSION['agenda_error'] = 'El formulario de cancelación no es válido o venció.';
            $this->redirigirFechaAgenda($_POST['return_fecha'] ?? null);
        }

        try {
            $this->model('AgendaModel')->cancelar((int) $consultaId);
            $_SESSION['agenda_mensaje'] = 'El turno se canceló; el registro clínico se conservó.';
        } catch (InvalidArgumentException $error) {
            $_SESSION['agenda_error'] = $error->getMessage();
        } catch (mysqli_sql_exception $error) {
            error_log('No se pudo cancelar el turno: ' . $error->getMessage());
            $_SESSION['agenda_error'] = 'No se pudo cancelar el turno. Intentá nuevamente.';
        }

        $this->redirigirFechaAgenda($_POST['return_fecha'] ?? null);
    }

    private function mostrarFormulario(array $datos, string $error): void
    {
        $fecha = new DateTimeImmutable('today');
        $this->view(
            'dashboard/agenda',
            [
                'turnos' => [],
                'pacientes' => $this->model('AgendaModel')->listarPacientes(),
                'veterinarios' => $this->model('AgendaModel')->listarVeterinarios(),
                'fechaSeleccionada' => $fecha->format('Y-m-d'),
                'fechaInicio' => $fecha->format('Y-m-d'),
                'fechaFin' => $fecha->format('Y-m-d'),
                'vistaAgenda' => 'dia',
                'csrfToken' => Csrf::token(),
                'error' => $error,
                'mensaje' => null,
                'oldInput' => $datos,
                'puedeGestionar' => $this->puedeGestionar(),
            ],
            'app',
            ['app-layout.css', 'agenda.css'],
            ['dashboard.js'],
            ['bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js', 'jquery-4.0.0.min.js.js']
        );
    }

    private function csrfValido($token): bool
    {
        return is_string($token) && Csrf::validar($token);
    }

    private function puedeGestionar(): bool
    {
        return in_array(strtoupper(trim(Auth::rol() ?? '')), ['PROFESOR', 'DIRECTOR'], true);
    }

    private function redirigirFechaAgenda($fecha): void
    {
        $destino = url('agenda');
        if (
            is_string($fecha)
            && preg_match('/\A[0-9]{4}-[0-9]{2}-[0-9]{2}\z/', $fecha) === 1
        ) {
            $destino .= '?fecha=' . rawurlencode($fecha);
        }
        header('Location: ' . $destino, true, 303);
        exit;
    }
}
