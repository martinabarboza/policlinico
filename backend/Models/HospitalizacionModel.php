<?php

/** Gestiona operaciones con la tabla HOSPITALIZACION. */
class HospitalizacionModel extends Model
{
    /** Listado de internaciones activas. */
    public function obtenerActivas(): array
    {
        return $this->obtenerFilasDetalle(
            "SELECT
                h.id_Hospitalizacion,
                h.id_Paciente,
                h.id_Consulta,
                h.fecha_ingreso_Hospitalizacion,
                h.motivo_Hospitalizacion,
                h.estado_Hospitalizacion,
                p.nombre_Paciente,
                t.nombre_Tutor,
                CONCAT_WS(' ', u.nombre_Usuario, u.apellido_Usuario) AS responsable,
                CONCAT_WS(
                    ' · ',
                    c.tipo_Consulta,
                    DATE_FORMAT(c.fecha_hora_Consulta, '%d/%m/%Y %H:%i')
                ) AS consulta
             FROM HOSPITALIZACION h
             INNER JOIN PACIENTE p ON p.id_Paciente = h.id_Paciente
             LEFT JOIN TUTOR t ON t.id_Tutor = p.id_Tutor
             LEFT JOIN USUARIO u ON u.id_Usuario = h.id_Usuario
             LEFT JOIN CONSULTA c ON c.id_Consulta = h.id_Consulta
             WHERE h.fecha_egreso_Hospitalizacion IS NULL
               AND LOWER(TRIM(h.estado_Hospitalizacion)) = 'activa'
             ORDER BY h.fecha_ingreso_Hospitalizacion DESC"
        );
    }

    /** Historial completo de internaciones de un paciente, incluidas las ya finalizadas. */
    public function listarPorPaciente(int $pacienteId): array
    {
        $stmt = $this->db->prepare(
            "SELECT
                h.id_Hospitalizacion,
                h.id_Consulta,
                h.fecha_ingreso_Hospitalizacion,
                h.fecha_egreso_Hospitalizacion,
                h.motivo_Hospitalizacion,
                h.estado_Hospitalizacion,
                CONCAT_WS(' ', u.nombre_Usuario, u.apellido_Usuario) AS responsable,
                c.tipo_Consulta,
                c.fecha_hora_Consulta
             FROM HOSPITALIZACION h
             LEFT JOIN USUARIO u ON u.id_Usuario = h.id_Usuario
             LEFT JOIN CONSULTA c ON c.id_Consulta = h.id_Consulta
             WHERE h.id_Paciente = ?
             ORDER BY h.fecha_ingreso_Hospitalizacion DESC"
        );
        $stmt->bind_param('i', $pacienteId);
        $stmt->execute();
        $internaciones = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $internaciones;
    }

    /** Ingreso de paciente a hospitalización. */
    public function registrarIngreso(
        int $pacienteId,
        ?int $consultaId,
        int $usuarioId,
        string $motivo
    ): int
    {
        $motivo = trim($motivo);
        if ($pacienteId < 1 || $usuarioId < 1 || $motivo === '' || strlen($motivo) > 2000) {
            throw new InvalidArgumentException('Completá los datos del ingreso con valores válidos.');
        }

        $this->db->begin_transaction();
        try {
            if (!$this->pacienteExiste($pacienteId, true)) {
                throw new InvalidArgumentException('Paciente no encontrado.');
            }

            if ($this->estaHospitalizado($pacienteId)) {
                throw new InvalidArgumentException('El paciente ya está hospitalizado.');
            }

            if ($consultaId !== null && !$this->consultaPerteneceAPaciente($consultaId, $pacienteId)) {
                throw new InvalidArgumentException('La consulta seleccionada no corresponde al paciente.');
            }

            $stmt = $this->db->prepare(
                "INSERT INTO HOSPITALIZACION
                    (id_Paciente, id_Consulta, id_Usuario, fecha_ingreso_Hospitalizacion,
                     motivo_Hospitalizacion, estado_Hospitalizacion)
                 VALUES (?, ?, ?, NOW(), ?, 'Activa')"
            );
            $stmt->bind_param('iiis', $pacienteId, $consultaId, $usuarioId, $motivo);
            $stmt->execute();
            $hospitalizacionId = (int) $stmt->insert_id;
            $stmt->close();

            $this->db->commit();
            return $hospitalizacionId;
        } catch (Throwable $error) {
            $this->db->rollback();
            throw $error;
        }
    }

    /** Alta de paciente. */
    public function registrarAlta(int $hospitalizacionId): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE HOSPITALIZACION
             SET fecha_egreso_Hospitalizacion = NOW(),
                 estado_Hospitalizacion = 'Finalizada'
             WHERE id_Hospitalizacion = ?
               AND fecha_egreso_Hospitalizacion IS NULL
               AND LOWER(TRIM(estado_Hospitalizacion)) = 'activa'"
        );
        $stmt->bind_param('i', $hospitalizacionId);
        $stmt->execute();
        $actualizado = $stmt->affected_rows > 0;
        $stmt->close();

        return $actualizado;
    }

    /** Listado de consultas. */
    public function listarConsultas(): array
    {
        return $this->obtenerFilasDetalle(
            "SELECT
                c.id_Consulta,
                c.id_Paciente,
                c.tipo_Consulta,
                c.fecha_hora_Consulta,
                p.nombre_Paciente
             FROM CONSULTA c
             INNER JOIN PACIENTE p ON p.id_Paciente = c.id_Paciente
             ORDER BY c.fecha_hora_Consulta DESC"
        );
    }

    /** Comprobar si paciente existe. */
    private function pacienteExiste(int $pacienteId, bool $bloquear = false): bool
    {
        $sql = 'SELECT 1 FROM PACIENTE WHERE id_Paciente = ? LIMIT 1';
        if ($bloquear) {
            $sql .= ' FOR UPDATE';
        }
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $pacienteId);
        $stmt->execute();
        $existe = $stmt->get_result()->fetch_assoc() !== null;
        $stmt->close();
        return $existe;
    }

    /** Comprobar si paciente está hospitalizado. */
    private function estaHospitalizado(int $pacienteId): bool
    {
        $stmt = $this->db->prepare(
            "SELECT 1 FROM HOSPITALIZACION
             WHERE id_Paciente = ?
               AND fecha_egreso_Hospitalizacion IS NULL
               AND LOWER(TRIM(estado_Hospitalizacion)) = 'activa'"
        );
        $stmt->bind_param('i', $pacienteId);
        $stmt->execute();
        $estaHospitalizado = $stmt->get_result()->fetch_assoc() !== null;
        $stmt->close();
        return $estaHospitalizado;
    }

    /** Comprobar que la consulta seleccionada pertenezca al paciente. */
    private function consultaPerteneceAPaciente(int $consultaId, int $pacienteId): bool
    {
        $stmt = $this->db->prepare(
            'SELECT 1 FROM CONSULTA WHERE id_Consulta = ? AND id_Paciente = ? LIMIT 1'
        );
        $stmt->bind_param('ii', $consultaId, $pacienteId);
        $stmt->execute();
        $pertenece = $stmt->get_result()->fetch_assoc() !== null;
        $stmt->close();
        return $pertenece;
    }

    /** Obtener filas con JOINs. */
    private function obtenerFilasDetalle(string $sql): array
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $filas = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $filas;
    }
}