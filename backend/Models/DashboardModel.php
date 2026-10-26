<?php

class DashboardModel extends Model
{
    /**
     * Obtiene las consultas de una fecha, con paciente y tutor, ordenadas por hora.
     * El rango incluye la medianoche inicial y excluye la del día siguiente.
     * No filtra por estado: la agenda muestra consultas de cualquier estado.
     * Para que una consulta aparezca, `fecha_hora_Consulta` debe estar dentro del día.
     *
     * @return list<array{
     *     id_consulta: int,
     *     fecha_hora: string,
     *     tipo: string|null,
     *     estado: string|null,
     *     motivo: string|null,
     *     nombre_paciente: string,
     *     nombre_tutor: string
     * }>
     */
    public function obtenerConsultasDelDia(DateTimeImmutable $fecha): array
    {
        $inicioDelDia = $fecha->setTime(0, 0)->format('Y-m-d H:i:s');
        $inicioDelDiaSiguiente = $fecha->modify('+1 day')->setTime(0, 0)->format('Y-m-d H:i:s');

        $stmt = $this->db->prepare(
            "SELECT
                c.id_Consulta AS id_consulta,
                c.fecha_hora_Consulta AS fecha_hora,
                c.tipo_Consulta AS tipo,
                c.estado_proceso_Consulta AS estado,
                c.motivo_Consulta AS motivo,
                p.nombre_Paciente AS nombre_paciente,
                t.nombre_Tutor AS nombre_tutor
             FROM CONSULTA c
             INNER JOIN PACIENTE p ON p.id_Paciente = c.id_Paciente
             INNER JOIN TUTOR t ON t.id_Tutor = p.id_Tutor
             WHERE c.fecha_hora_Consulta >= ?
               AND c.fecha_hora_Consulta < ?
             ORDER BY c.fecha_hora_Consulta ASC"
        );
        $stmt->bind_param('ss', $inicioDelDia, $inicioDelDiaSiguiente);
        $stmt->execute();

        $consultas = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $consultas;
    }

    /** Cuenta todas las consultas del día indicado, sin importar su estado. */
    public function contarConsultasDelDia(DateTimeImmutable $fecha): int
    {
        $inicioDelDia = $fecha->setTime(0, 0)->format('Y-m-d H:i:s');
        $inicioDelDiaSiguiente = $fecha->modify('+1 day')->setTime(0, 0)->format('Y-m-d H:i:s');

        $stmt = $this->db->prepare(
            "SELECT COUNT(*) AS cantidad
             FROM CONSULTA
             WHERE fecha_hora_Consulta >= ?
               AND fecha_hora_Consulta < ?"
        );
        $stmt->bind_param('ss', $inicioDelDia, $inicioDelDiaSiguiente);
        $stmt->execute();

        $cantidad = (int) $stmt->get_result()->fetch_assoc()['cantidad'];
        $stmt->close();

        return $cantidad;
    }

    /**
     * Cuenta consultas de cualquier fecha con estado `Pendiente` o `En curso`.
     * Ignora mayúsculas y espacios al comparar; excluye finalizadas, canceladas y
     * cualquier otro estado. Este criterio debe coincidir con obtenerConsultasIncompletas().
     */
    public function contarConsultasIncompletas(): int
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) AS cantidad
             FROM CONSULTA
             WHERE LOWER(TRIM(estado_proceso_Consulta)) IN ('pendiente', 'en curso')"
        );
        $stmt->execute();

        $cantidad = (int) $stmt->get_result()->fetch_assoc()['cantidad'];
        $stmt->close();

        return $cantidad;
    }

    /**
     * Lista consultas de cualquier fecha con estado `Pendiente` o `En curso` para el panel.
     * Finalizadas, canceladas y cualquier otro estado no forman parte del listado.
     * Mantener este filtro sincronizado con contarConsultasIncompletas() para que
     * el contador coincida con la cantidad de filas mostradas.
     *
     * @return list<array{
     *     id_consulta: int,
     *     fecha_hora: string,
     *     tipo: string|null,
     *     estado: string|null,
     *     motivo: string|null,
     *     nombre_paciente: string,
     *     nombre_tutor: string
     * }>
     */
    public function obtenerConsultasIncompletas(): array
    {
        $stmt = $this->db->prepare(
            "SELECT
                c.id_Consulta AS id_consulta,
                c.fecha_hora_Consulta AS fecha_hora,
                c.tipo_Consulta AS tipo,
                c.estado_proceso_Consulta AS estado,
                c.motivo_Consulta AS motivo,
                p.nombre_Paciente AS nombre_paciente,
                t.nombre_Tutor AS nombre_tutor
             FROM CONSULTA c
             INNER JOIN PACIENTE p ON p.id_Paciente = c.id_Paciente
             INNER JOIN TUTOR t ON t.id_Tutor = p.id_Tutor
             WHERE LOWER(TRIM(c.estado_proceso_Consulta)) IN ('pendiente', 'en curso')
             ORDER BY c.fecha_hora_Consulta DESC"
        );
        $stmt->execute();

        $consultas = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $consultas;
    }

    /**
     * Lista consultas futuras no finalizadas ni canceladas, ordenadas cronológicamente.
     *
     * @return list<array{
     *     id_consulta: int,
     *     fecha_hora: string,
     *     tipo: string|null,
     *     estado: string|null,
     *     motivo: string|null,
     *     nombre_paciente: string,
     *     nombre_tutor: string
     * }>
     */
    public function obtenerProximasConsultas(): array
    {
        $stmt = $this->db->prepare(
            "SELECT
                c.id_Consulta AS id_consulta,
                c.fecha_hora_Consulta AS fecha_hora,
                c.tipo_Consulta AS tipo,
                c.estado_proceso_Consulta AS estado,
                c.motivo_Consulta AS motivo,
                p.nombre_Paciente AS nombre_paciente,
                t.nombre_Tutor AS nombre_tutor
             FROM CONSULTA c
             INNER JOIN PACIENTE p ON p.id_Paciente = c.id_Paciente
             INNER JOIN TUTOR t ON t.id_Tutor = p.id_Tutor
             WHERE c.fecha_hora_Consulta >= NOW()
               AND COALESCE(LOWER(TRIM(c.estado_proceso_Consulta)), '') NOT IN
                   ('finalizada', 'cancelada', 'cancelado')
             ORDER BY c.fecha_hora_Consulta ASC"
        );
        $stmt->execute();

        $consultas = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $consultas;
    }

    /**
     * Busca hospitalizaciones sin egreso y con estado `Activa` (ignora mayúsculas y espacios).
     * Al dar el alta, completar `fecha_egreso_Hospitalizacion`; así deja de aparecer
     * en el listado activo, aunque el estado siga indicando `Activa`.
     * Para cargar una internación se necesita un paciente existente. La consulta y el
     * responsable son opcionales; la base completa la fecha de ingreso si se omite.
     *
     * @return list<array{
     *     fecha_ingreso: string,
     *     nombre_paciente: string,
     *     nombre_tutor: string,
     *     motivo: string|null,
     *     responsable: string,
     *     estado: string
     * }>
     */
    public function obtenerPacientesHospitalizados(): array
    {
        $stmt = $this->db->prepare(
            "SELECT
                h.fecha_ingreso_Hospitalizacion AS fecha_ingreso,
                p.nombre_Paciente AS nombre_paciente,
                t.nombre_Tutor AS nombre_tutor,
                h.motivo_Hospitalizacion AS motivo,
                COALESCE(
                    NULLIF(TRIM(CONCAT_WS(' ', u.nombre_Usuario, u.apellido_Usuario)), ''),
                    'Sin asignar'
                ) AS responsable,
                h.estado_Hospitalizacion AS estado
             FROM HOSPITALIZACION h
             INNER JOIN PACIENTE p ON p.id_Paciente = h.id_Paciente
             INNER JOIN TUTOR t ON t.id_Tutor = p.id_Tutor
             LEFT JOIN USUARIO u ON u.id_Usuario = h.id_Usuario
             WHERE h.fecha_egreso_Hospitalizacion IS NULL
               AND LOWER(TRIM(h.estado_Hospitalizacion)) = 'activa'
             ORDER BY h.fecha_ingreso_Hospitalizacion ASC"
        );
        $stmt->execute();

        $pacientes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $pacientes;
    }
}
