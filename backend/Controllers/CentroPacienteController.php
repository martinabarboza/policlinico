<?php

/** Presenta en modo de solo lectura la ficha completa de un paciente. */
class CentroPacienteController extends Controller
{
    public function index(): void
    {
        Auth::requireLogin();

        $busqueda = $_GET['busquedaPaciente'] ?? '';
        $error = null;
        if (!is_string($busqueda) || strlen($busqueda) > 100) {
            http_response_code(400);
            $busqueda = '';
            $error = 'La búsqueda no puede superar los 100 caracteres.';
        } else {
            $busqueda = trim($busqueda);
        }

        $pacienteId = $_GET['paciente'] ?? null;
        $ficha = null;
        $internaciones = [];
        $resultados = [];

        if ($pacienteId !== null && (!is_string($pacienteId) || !ctype_digit($pacienteId) || (int) $pacienteId < 1)) {
            http_response_code(400);
            $error ??= 'El identificador del paciente no es válido.';
            $pacienteId = null;
        }

        try {
            if ($busqueda !== '') {
                $resultados = $this->model('PacienteModel')->buscar($busqueda);
            }

            if (is_string($pacienteId)) {
                $ficha = $this->model('ConsultaModel')->obtenerHistorialClinicoPaciente((int) $pacienteId);
                if ($ficha === null) {
                    http_response_code(404);
                    $error = 'No se encontró el paciente solicitado.';
                } else {
                    $ficha['consultas'] = array_reverse($ficha['consultas']);
                    $internaciones = $this->model('HospitalizacionModel')->listarPorPaciente((int) $pacienteId);
                }
            }
        } catch (mysqli_sql_exception $exception) {
            error_log('No se pudo cargar el Centro de Pacientes: ' . $exception->getMessage());
            http_response_code(500);
            $error = 'No se pudo cargar la ficha del paciente. Intentá nuevamente.';
        }

        $this->view(
            'dashboard/centro-pacientes',
            [
                'busquedaPaciente' => $busqueda,
                'resultados' => $resultados,
                'ficha' => $ficha,
                'internaciones' => $internaciones,
                'error' => $error,
            ],
            'app',
            ['app-layout.css', 'centro-consulta.css', 'centro-pacientes.css'],
            ['dashboard.js'],
            ['bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js', 'jquery-4.0.0.min.js.js']
        );
    }
}
