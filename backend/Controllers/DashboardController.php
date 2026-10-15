<?php

class DashboardController extends Controller
{
    /**
     * Prepara la agenda y las consultas pendientes para la vista.
     * Acepta GET ?fecha=AAAA-MM-DD; si falta o no es válida, usa hoy.
     */
    public function index()
    {
        Auth::requireLogin();

        $fechaActual = new DateTimeImmutable('today');
        $errorFecha = null;

        if (isset($_GET['fecha'])) {
            $fechaSolicitada = $_GET['fecha'];

            // La comparación también detecta fechas imposibles que PHP normaliza.
            if (is_string($fechaSolicitada) && preg_match('/\A[0-9]{4}-[0-9]{2}-[0-9]{2}\z/', $fechaSolicitada)) {
                $fechaParseada = DateTimeImmutable::createFromFormat('!Y-m-d', $fechaSolicitada);

                if ($fechaParseada && $fechaParseada->format('Y-m-d') === $fechaSolicitada) {
                    $fechaActual = $fechaParseada;
                } else {
                    $errorFecha = 'La fecha indicada no es válida. Se muestran las consultas de hoy.';
                }
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

        $this->view('dashboard/index', [
            'agendaDelDia' => $agendaDelDia,
            'pacientesEnEspera' => $pacientesEnEspera,
            'fechaDelDashboard' => $fechaActual->format('d/m/Y'),
            'fechaSeleccionada' => $fechaActual->format('Y-m-d'),
            'errorFecha' => $errorFecha,
        ], 'app', ['dashboard.css', 'app-layout.css']);
    }

    /**
     * Asigna a cada estado una clase visual conocida por los estilos del dashboard.
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
