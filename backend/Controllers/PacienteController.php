<?php

class PacienteController extends Controller
{
    public function crear(): void
    {
        Auth::requireLogin();
        if (!$this->puedeCrearPacientes()) {
            http_response_code(403);
            echo 'Solo estudiantes, profesores y directores pueden registrar pacientes.';
            return;
        }
        $this->mostrarFormulario();
    }

    public function guardar(): void
    {
        Auth::requireLogin();
        if (!$this->puedeCrearPacientes()) {
            http_response_code(403);
            echo 'Solo estudiantes, profesores y directores pueden registrar pacientes.';
            return;
        }
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . url('pacientes/crear'), true, 303);
            exit;
        }

        $datos = $_POST;
        $token = $datos['csrf_token'] ?? null;
        if (!is_string($token) || !Csrf::validar($token)) {
            http_response_code(403);
            $this->mostrarFormulario($datos, 'El formulario venció. Revisá los datos y volvé a enviarlo.');
            return;
        }

        try {
            $pacienteId = $this->model('PacienteModel')->crearPacienteConTutor($datos);
            $_SESSION['flash_paciente_creado'] = 'El paciente se registró correctamente y ya está seleccionado para la nueva consulta.';
            header('Location: ' . url('centro-consultas/nueva') . '?paciente=' . $pacienteId, true, 303);
            exit;
        } catch (InvalidArgumentException $error) {
            http_response_code(422);
            $this->mostrarFormulario($datos, $error->getMessage());
        } catch (mysqli_sql_exception $error) {
            error_log('Error SQL al registrar paciente: ' . $error->getMessage());
            http_response_code(500);
            $this->mostrarFormulario($datos, 'No se pudo registrar el paciente. Revisá los datos e intentá nuevamente.');
        }
    }

    private function mostrarFormulario(array $datos = [], string $error = ''): void
    {
        $this->view(
            'dashboard/paciente-crear',
            [
                'razas' => $this->model('PacienteModel')->listarRazasEquinas(),
                'csrfToken' => Csrf::token(),
                'error' => $error,
                'datos' => $datos,
                'volverUrl' => url('centro-consultas'),
                'guardarUrl' => url('pacientes/guardar'),
            ],
            'app',
            ['app-layout.css', 'paciente-crear.css'],
            ['dashboard.js'],
            ['bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js', 'jquery-4.0.0.min.js.js']
        );
    }

    private function puedeCrearPacientes(): bool
    {
        return in_array(strtoupper(trim(Auth::rol() ?? '')), ['ESTUDIANTE', 'PROFESOR', 'DIRECTOR'], true);
    }
}
