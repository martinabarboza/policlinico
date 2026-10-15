<?php

class DashboardController extends Controller
{
    public function index()
    {   
        Auth::requireLogin();

        $fechaActual = new DateTimeImmutable('today');
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
        ],'app',['dashboard.css','app-layout.css']);
    }

    /**
     * Asigna a cada estado una clase visual conocida por los estilos del dashboard.
     */
    private function obtenerClaseEstado(?string $estado): string
    {
        switch (strtolower(trim((string) $estado))) {
            case 'pendiente':
                return 'dashboard-status--pending';
            case 'en curso':
                return 'dashboard-status--active';
            case 'finalizada':
                return 'dashboard-status--complete';
            case 'cancelada':
            case 'cancelado':
                return 'dashboard-status--cancelled';
            default:
                return 'dashboard-status--default';
        }
    }
}
