<?php

/** Controla altas y ediciones de pacientes junto con los datos del tutor. */
class PacienteController extends Controller
{
    /** Comprueba el rol y prepara las razas disponibles para la especie elegida. */
    public function crear(): void
    {
        Auth::requireLogin();
        if (!$this->puedeCrearPacientes()) {
            http_response_code(403);
            echo 'Solo estudiantes, profesores y directores pueden registrar pacientes.';
            return;
        }
        $especie = $_GET['especie'] ?? 'Equino';
        if (!is_string($especie) || !in_array($especie, ['Equino', 'Canino', 'Felino'], true)) {
            http_response_code(400);
            $especie = 'Equino';
        }
        $this->mostrarFormulario(['especie_Paciente' => $especie], '', null, $especie);
    }

    /** Valida la solicitud protegida por CSRF y registra paciente/tutor. */
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
            $this->mostrarFormulario($datos, 'El formulario venció. Revisá los datos y volvé a enviarlo.', null, $datos['especie_Paciente'] ?? 'Equino');
            return;
        }

        try {
            $pacienteId = $this->model('PacienteModel')->crearPacienteConTutor($datos);
            $_SESSION['flash_paciente_creado'] = 'El paciente se registró correctamente y ya está seleccionado para la nueva consulta.';
            $especiesConsulta = ['Canino' => 'Perro', 'Felino' => 'Gato', 'Equino' => 'Equino'];
            $especie = $especiesConsulta[$datos['especie_Paciente'] ?? 'Equino'] ?? 'Equino';
            header('Location: ' . url('centro-consultas/nueva') . '?paciente=' . $pacienteId . '&especie=' . rawurlencode($especie), true, 303);
            exit;
        } catch (InvalidArgumentException $error) {
            http_response_code(422);
            $this->mostrarFormulario($datos, $error->getMessage(), null, $datos['especie_Paciente'] ?? 'Equino');
        } catch (mysqli_sql_exception $error) {
            error_log('Error SQL al registrar paciente: ' . $error->getMessage());
            http_response_code(500);
            $this->mostrarFormulario($datos, 'No se pudo registrar el paciente. Revisá los datos e intentá nuevamente.', null, $datos['especie_Paciente'] ?? 'Equino');
        }
    }

    /** Carga la ficha existente después de validar ID y permisos. */
    public function editar(): void
    {
        Auth::requireLogin();
        if (!$this->puedeCrearPacientes()) {
            http_response_code(403);
            echo 'Solo estudiantes, profesores y directores pueden editar pacientes.';
            return;
        }

        $id = $_GET['id'] ?? null;
        if (!is_string($id) || !ctype_digit($id) || (int) $id < 1) {
            http_response_code(400);
            echo 'El identificador del paciente no es válido.';
            return;
        }

        $modelo = $this->model('PacienteModel');
        $ficha = $modelo->obtenerFicha((int) $id);
        if ($ficha === null) {
            http_response_code(404);
            echo 'No se encontró el paciente solicitado.';
            return;
        }

        $this->mostrarFormulario($ficha, '', (int) $id, $ficha['nombre_Especie'] ?? '');
    }

    /** Actualiza paciente y tutor conservando la especie original al manejar errores. */
    public function actualizar(): void
    {
        Auth::requireLogin();
        if (!$this->puedeCrearPacientes()) {
            http_response_code(403);
            echo 'Solo estudiantes, profesores y directores pueden editar pacientes.';
            return;
        }
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . url('centro-consultas'), true, 303);
            exit;
        }

        $datos = $_POST;
        $id = $datos['paciente_id'] ?? null;
        if (!is_string($id) || !ctype_digit($id) || (int) $id < 1) {
            http_response_code(400);
            echo 'El identificador del paciente no es válido.';
            return;
        }
        $pacienteId = (int) $id;
        $modelo = $this->model('PacienteModel');
        $fichaActual = $modelo->obtenerFicha($pacienteId);
        if ($fichaActual === null) {
            http_response_code(404);
            echo 'No se encontró el paciente solicitado.';
            return;
        }

        $token = $datos['csrf_token'] ?? null;
        if (!is_string($token) || !Csrf::validar($token)) {
            http_response_code(403);
            $this->mostrarFormulario($datos, 'El formulario venció. Revisá los datos y volvé a enviarlo.', $pacienteId, $fichaActual['nombre_Especie'] ?? '');
            return;
        }

        try {
            $modelo->actualizar($pacienteId, $datos);
            $_SESSION['flash_success'] = 'Los datos del paciente y su tutor se actualizaron correctamente.';
            header('Location: ' . url('centro-consultas') . '?paciente=' . $pacienteId, true, 303);
            exit;
        } catch (InvalidArgumentException $error) {
            http_response_code(422);
            $this->mostrarFormulario($datos, $error->getMessage(), $pacienteId, $fichaActual['nombre_Especie'] ?? '');
        } catch (mysqli_sql_exception $error) {
            error_log('Error SQL al actualizar paciente: ' . $error->getMessage());
            http_response_code(500);
            $this->mostrarFormulario($datos, 'No se pudo actualizar el paciente. Revisá los datos e intentá nuevamente.', $pacienteId, $fichaActual['nombre_Especie'] ?? '');
        }
    }

    /** Prepara catálogos y valores del formulario para alta, edición o error. */
    private function mostrarFormulario(
        array $datos = [],
        string $error = '',
        ?int $pacienteId = null,
        $especie = ''
    ): void
    {
        $modelo = $this->model('PacienteModel');
        if (!is_string($especie) || !in_array($especie, ['Equino', 'Canino', 'Felino'], true)) {
            $especieRecibida = $datos['especie_Paciente'] ?? '';
            $especie = is_string($especieRecibida)
                && in_array($especieRecibida, ['Equino', 'Canino', 'Felino'], true)
                    ? $especieRecibida
                    : 'Equino';
        }
        $datos['especie_Paciente'] = $especie;
        $this->view(
            'dashboard/paciente-crear',
            [
                'razas' => $modelo->listarRazasPorEspecie($especie),
                'especies' => $modelo->listarEspecies(),
                'csrfToken' => Csrf::token(),
                'error' => $error,
                'datos' => $datos,
                'pacienteId' => $pacienteId,
                'especie' => $especie,
                'volverUrl' => $pacienteId === null
                    ? url('centro-consultas')
                    : url('centro-consultas') . '?paciente=' . $pacienteId,
                'guardarUrl' => $pacienteId === null
                    ? url('pacientes/guardar')
                    : url('pacientes/actualizar'),
            ],
            'app',
            ['app-layout.css', 'paciente-crear.css'],
            ['dashboard.js'],
            ['bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js', 'jquery-4.0.0.min.js.js']
        );
    }

    /** El sistema permite gestionar pacientes a estudiantes, profesores y directores. */
    private function puedeCrearPacientes(): bool
    {
        return in_array(strtoupper(trim(Auth::rol() ?? '')), ['ESTUDIANTE', 'PROFESOR', 'DIRECTOR'], true);
    }
}
