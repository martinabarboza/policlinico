<?php

/** Muestra alertas activas asociadas al usuario autenticado. */
class NotificacionController extends Controller
{
    /** Carga el listado completo y prepara los datos del menú de notificaciones. */
    public function index(): void
    {
        Auth::requireLogin();

        $alertas = $this->model('NotificacionModel')
            ->obtenerAlertasAtrasadasPorUsuario((int) Auth::id());

        foreach ($alertas as $indice => $alerta) {
            $alertas[$indice]['fecha_formateada'] =
                (new DateTimeImmutable($alerta['fecha_hora']))->format('d/m/Y H:i');
            $alertas[$indice]['detalle_url'] =
                url('centro-consultas') . '?consulta=' . (int) $alerta['id_consulta'];
        }

        $this->view(
            'dashboard/notificaciones',
            [
                'notificaciones' => $alertas,
                'alertasNotificaciones' => array_slice($alertas, 0, 5),
            ],
            'app',
            ['dashboard.css', 'app-layout.css'],
            ['dashboard.js'],
            ['bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js', 'jquery-4.0.0.min.js.js']
        );
    }
}