<?php

class DashboardModel extends Model
{
    /**
     * Obtiene las consultas de una fecha, con paciente y tutor, ordenadas por hora.
     * El rango incluye la medianoche inicial y excluye la del día siguiente.
     *
     * @return list<array{
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
}
