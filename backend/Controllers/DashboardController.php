<?php

class DashboardController extends Controller
{
    /**
     * Prepara los datos del dashboard para la vista.
     * Acepta GET ?fecha=AAAA-MM-DD; si falta o no es válida, usa hoy.
     * Reglas de estado:
     * - Agenda: todas las consultas cuya fecha_hora_Consulta cae en la fecha seleccionada.
     * - En espera: solo las consultas de esa agenda con estado `Pendiente`.
     * - Hospitalizados: registros activos devueltos por DashboardModel.
     * Las tarjetas cuentan consultas de hoy (todos los estados) e incompletas
     * (Pendiente o En curso, sin límite de fecha); cada contador tiene su listado.
     * El selector de fecha solo modifica la agenda y la lista de espera, no esos contadores.
     */
    public function index(): void
    {
        Auth::requireLogin();

        $fechaActual = new DateTimeImmutable('today');
        $errorFecha = null;

        if (isset($_GET['fecha'])) {
            $fechaSolicitada = $_GET['fecha'];
            $fechaParseada = is_string($fechaSolicitada)
                && preg_match('/\A[0-9]{4}-[0-9]{2}-[0-9]{2}\z/', $fechaSolicitada) === 1
                    ? DateTimeImmutable::createFromFormat('!Y-m-d', $fechaSolicitada)
                    : false;

            // La comparación rechaza fechas imposibles que PHP normaliza.
            if ($fechaParseada && $fechaParseada->format('Y-m-d') === $fechaSolicitada) {
                $fechaActual = $fechaParseada;
            } else {
                $errorFecha = 'La fecha indicada no es válida. Se muestran las consultas de hoy.';
            }
        }

        $modeloDashboard = $this->model('DashboardModel');
        $consultasDelDia = $modeloDashboard->obtenerConsultasDelDia($fechaActual);
        $agendaDelDia = [];
        $pacientesEnEspera = [];

        // El controlador prepara los datos para la vista; la plantilla solo los presenta.
        foreach ($consultasDelDia as $consulta) {
            $consultaParaVista = [
                'hora' => substr($consulta['fecha_hora'], 11, 5),
                'fecha_hora' => $consulta['fecha_hora'],
                'tipo' => $consulta['tipo'] ?: 'Consulta',
                'estado' => $consulta['estado'] ?: 'Sin estado',
                'clase_estado' => $this->obtenerClaseEstado($consulta['estado']),
                'motivo' => $consulta['motivo'],
                'nombre_paciente' => $consulta['nombre_paciente'],
                'nombre_tutor' => $consulta['nombre_tutor'],
            ];

            $agendaDelDia[] = $consultaParaVista;

            if (strtolower(trim((string) $consulta['estado'])) === 'pendiente') {
                $pacientesEnEspera[] = $consultaParaVista;
            }
        }

        $cantidadConsultasDiarias = $modeloDashboard->contarConsultasDelDia(new DateTimeImmutable('today'));
        $cantidadConsultasIncompletas = $modeloDashboard->contarConsultasIncompletas();

        $pacientesHospitalizados = $modeloDashboard->obtenerPacientesHospitalizados();
        foreach ($pacientesHospitalizados as $indice => $paciente) {
            $pacientesHospitalizados[$indice]['fecha_ingreso_formateada'] =
                (new DateTimeImmutable($paciente['fecha_ingreso']))->format('d/m/Y H:i');
        }

        $this->view(
            'dashboard/index',
            [
                'agendaDelDia' => $agendaDelDia,
                'pacientesEnEspera' => $pacientesEnEspera,
                'pacientesHospitalizados' => $pacientesHospitalizados,
                'cantidadConsultasDiarias' => $cantidadConsultasDiarias,
                'cantidadConsultasIncompletas' => $cantidadConsultasIncompletas,
                'fechaDelDashboard' => $fechaActual->format('d/m/Y'),
                'fechaSeleccionada' => $fechaActual->format('Y-m-d'),
                'errorFecha' => $errorFecha,
            ],
            'app',
            ['dashboard.css', 'app-layout.css'],
            ['dashboard.js'],
            ['bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js', 'jquery-4.0.0.min.js.js']
        );
    }

    /** Muestra todas las consultas de hoy, sin filtrar por estado. */
    public function consultasDiarias(): void
    {
        Auth::requireLogin();

        $fecha = new DateTimeImmutable('today');
        $consultas = $this->model('DashboardModel')->obtenerConsultasDelDia($fecha);
        $this->mostrarListadoConsultas(
            'Consultas diarias',
            'Consultas registradas para hoy · ' . $fecha->format('d/m/Y'),
            $consultas
        );
    }

    /** Muestra consultas de cualquier fecha cuyo estado sea Pendiente o En curso. */
    public function consultasIncompletas(): void
    {
        Auth::requireLogin();

        $consultas = $this->model('DashboardModel')->obtenerConsultasIncompletas();
        $this->mostrarListadoConsultas(
            'Consultas incompletas',
            'Consultas pendientes o en curso',
            $consultas
        );
    }

    /**
     * Prepara fecha y clase de estado antes de entregar las consultas a la tabla compartida.
     * El listado de consultas diarias y el de incompletas reutilizan esta misma vista.
     */
    private function mostrarListadoConsultas(string $titulo, string $subtitulo, array $consultas): void
    {
        foreach ($consultas as $indice => $consulta) {
            $consultas[$indice]['fecha_formateada'] =
                (new DateTimeImmutable($consulta['fecha_hora']))->format('d/m/Y H:i');
            $consultas[$indice]['clase_estado'] = $this->obtenerClaseEstado($consulta['estado']);
        }

        $this->view(
            'dashboard/listado-consultas',
            [
                'titulo' => $titulo,
                'subtitulo' => $subtitulo,
                'consultas' => $consultas,
            ],
            'app',
            ['dashboard.css', 'app-layout.css'],
            ['dashboard.js'],
            ['bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js', 'jquery-4.0.0.min.js.js']
        );
    }

    /**
     * Recibe `tipoBusqueda` y `busqueda` del formulario del navbar y redirige
     * al centro elegido, conservando el texto en la URL para que se pueda interpretar.
     */
    public function buscarEnCentro(): void
    {
        Auth::requireLogin();

        $tipo = $_GET['tipoBusqueda'] ?? '';
        $busqueda = $_GET['busqueda'] ?? '';

        if (!is_string($tipo) || !is_string($busqueda)) {
            http_response_code(400);
            echo 'La búsqueda enviada no es válida.';
            return;
        }

        if ($tipo === 'paciente') {
            $destino = 'centro-consultas';
        } elseif ($tipo === 'tutor') {
            $destino = 'centro-tutores';
        } else {
            http_response_code(400);
            echo 'El tipo de búsqueda no es válido.';
            return;
        }

        $busqueda = trim($busqueda);
        if ($busqueda === '' || strlen($busqueda) > 100) {
            header('Location: ' . url($destino) . '?' . http_build_query([
                'error' => 'busqueda_invalida',
            ]));
            exit;
        }

        header('Location: ' . url($destino) . '?' . http_build_query([
            'busqueda' => $busqueda,
        ]));
        exit;
    }

    /**
     * Asigna a cada estado una clase visual conocida por los estilos del dashboard.
     * `Pendiente`, `En curso`, `Finalizada`, `Cancelada` y `Cancelado` tienen clases
     * específicas; los demás valores usan la clase predeterminada.
     */
    private function obtenerClaseEstado(?string $estado): string
    {
        switch (strtolower(trim((string) $estado))) {
            case 'pendiente':
                return 'estado-inicio--pendiente';
            case 'en curso':
                return 'estado-inicio--en-curso';
            case 'finalizada':
                return 'estado-inicio--finalizada';
            case 'cancelada':
            case 'cancelado':
                return 'estado-inicio--cancelada';
            default:
                return 'estado-inicio--predeterminado';
        }
    }
}
