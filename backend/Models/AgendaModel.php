<?php

class AgendaModel extends Model
{
    /**
    * Lista turnos del intervalo [desde, hasta), junto con paciente, tutor y usuarios.
    * El extremo final es exclusivo para evitar duplicar turnos entre períodos consecutivos.
    *
     * @return list<array{
     *     id: int,
     *     fecha_hora: string,
     *     estado: string,
     *     tipo: string|null,
     *     motivo: string|null,
     *     paciente: string,
     *     tutor: string,
     *     especie: string,
     *     usuarios: string
     * }>
     */
    public function listar(DateTimeImmutable $desde, DateTimeImmutable $hasta): array
    {
        $inicio = $desde->format('Y-m-d H:i:s');
        $fin = $hasta->format('Y-m-d H:i:s');
        $stmt = $this->db->prepare(
            "SELECT
                c.id_Consulta AS id,
                c.fecha_hora_Consulta AS fecha_hora,
                c.estado_proceso_Consulta AS estado,
                c.tipo_Consulta AS tipo,
                c.motivo_Consulta AS motivo,
                p.nombre_Paciente AS paciente,
                t.nombre_Tutor AS tutor,
                e.nombre_Especie AS especie,
                COALESCE(
                    (
                        SELECT GROUP_CONCAT(
                            DISTINCT TRIM(CONCAT_WS(' ', ua.nombre_Usuario, ua.apellido_Usuario))
                            ORDER BY ua.nombre_Usuario SEPARATOR ', '
                        )
                        FROM USUARIO_CONSULTA uca
                        INNER JOIN USUARIO ua ON ua.id_Usuario = uca.id_Usuario
                        WHERE uca.id_Consulta = c.id_Consulta
                    ),
                    'Sin usuarios asociados'
                ) AS usuarios
             FROM CONSULTA c
             INNER JOIN PACIENTE p ON p.id_Paciente = c.id_Paciente
             INNER JOIN TUTOR t ON t.id_Tutor = p.id_Tutor
             LEFT JOIN RAZA r ON r.id_Raza = p.id_Raza
             LEFT JOIN ESPECIE e ON e.id_Especie = r.id_Especie
             WHERE c.fecha_hora_Consulta >= ? AND c.fecha_hora_Consulta < ?
             ORDER BY c.fecha_hora_Consulta, c.id_Consulta"
        );
        $stmt->bind_param('ss', $inicio, $fin);
        $stmt->execute();
        $turnos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $turnos;
    }

    public function crearTurno(
        int $pacienteId,
        int $usuarioId,
        string $fechaHora,
        string $tipo,
        string $motivo
    ): int {
        $this->validarDatosTurno($pacienteId, $usuarioId, $fechaHora, $tipo, $motivo);
        $fechaHora = $this->validarFechaHoraFutura($fechaHora);
        // Consulta y asociación al usuario deben persistirse juntas o revertirse juntas.
        $this->db->begin_transaction();

        try {
            $this->validarPaciente($pacienteId);
            $this->validarUsuario($usuarioId);
            $stmt = $this->db->prepare(
                "INSERT INTO CONSULTA
                    (id_Paciente, tipo_Consulta, fecha_hora_Consulta,
                     estado_proceso_Consulta, motivo_Consulta)
                 VALUES (?, ?, ?, 'Pendiente', ?)"
            );
            $stmt->bind_param('isss', $pacienteId, $tipo, $fechaHora, $motivo);
            $stmt->execute();
            $consultaId = (int) $stmt->insert_id;
            $stmt->close();

            $stmt = $this->db->prepare(
                'INSERT INTO USUARIO_CONSULTA (id_Usuario, id_Consulta) VALUES (?, ?)'
            );
            $stmt->bind_param('ii', $usuarioId, $consultaId);
            $stmt->execute();
            $stmt->close();
            $this->db->commit();

            return $consultaId;
        } catch (Throwable $error) {
            $this->db->rollback();
            throw $error;
        }
    }

    public function reprogramar(int $consultaId, string $fechaHora): void
    {
        if ($consultaId < 1) {
            throw new InvalidArgumentException('El turno seleccionado no es válido.');
        }
        $fechaHora = $this->validarFechaHoraFutura($fechaHora);
        // El bloqueo impide que otro proceso cambie el turno mientras se valida su estado.
        $this->db->begin_transaction();

        try {
            $stmt = $this->db->prepare(
                "SELECT id_Paciente
                 FROM CONSULTA
                 WHERE id_Consulta = ?
                   AND LOWER(TRIM(estado_proceso_Consulta)) = 'pendiente'
                 FOR UPDATE"
            );
            $stmt->bind_param('i', $consultaId);
            $stmt->execute();
            $existe = $stmt->get_result()->fetch_assoc() !== null;
            $stmt->close();
            if (!$existe) {
                throw new InvalidArgumentException('Solo se pueden reprogramar turnos pendientes.');
            }

            $stmt = $this->db->prepare(
                "UPDATE CONSULTA SET fecha_hora_Consulta = ?
                 WHERE id_Consulta = ?
                   AND LOWER(TRIM(estado_proceso_Consulta)) = 'pendiente'"
            );
            $stmt->bind_param('si', $fechaHora, $consultaId);
            $stmt->execute();
            $stmt->close();
            $this->db->commit();
        } catch (Throwable $error) {
            $this->db->rollback();
            throw $error;
        }
    }

    public function cancelar(int $consultaId): void
    {
        if ($consultaId < 1) {
            throw new InvalidArgumentException('El turno seleccionado no es válido.');
        }

        // Solo se cancela la cita; sus datos clínicos se conservan.
        $stmt = $this->db->prepare(
            "UPDATE CONSULTA
             SET estado_proceso_Consulta = 'Cancelada', fecha_fin_Consulta = NULL
             WHERE id_Consulta = ?
               AND LOWER(TRIM(estado_proceso_Consulta)) = 'pendiente'"
        );
        $stmt->bind_param('i', $consultaId);
        $stmt->execute();
        $actualizado = $stmt->affected_rows > 0;
        $stmt->close();

        if (!$actualizado) {
            throw new InvalidArgumentException('Solo se pueden cancelar turnos pendientes.');
        }
    }

    private function validarDatosTurno(
        int $pacienteId,
        int $usuarioId,
        string $fechaHora,
        string $tipo,
        string $motivo
    ): void {
        if ($pacienteId < 1 || $usuarioId < 1) {
            throw new InvalidArgumentException('Seleccioná un paciente y un usuario.');
        }
        if (!in_array($tipo, ['Consulta general', 'Control', 'Consulta equina', 'Hospitalización'], true)) {
            throw new InvalidArgumentException('Seleccioná un tipo de consulta válido.');
        }
        if (trim($motivo) === '') {
            throw new InvalidArgumentException('Ingresá el motivo del turno.');
        }
        if (strlen($motivo) > 65535) {
            throw new InvalidArgumentException('El motivo supera la longitud permitida.');
        }
        $this->validarFechaHoraFutura($fechaHora);
    }

    private function validarFechaHoraFutura(string $fechaHora): string
    {
        $fecha = DateTimeImmutable::createFromFormat('!Y-m-d\TH:i', $fechaHora);
        $erroresFecha = DateTimeImmutable::getLastErrors();
        if (
            !$fecha
            || ($erroresFecha !== false && ($erroresFecha['warning_count'] > 0 || $erroresFecha['error_count'] > 0))
            || $fecha->format('Y-m-d\TH:i') !== $fechaHora
            || $fecha <= new DateTimeImmutable()
        ) {
            throw new InvalidArgumentException('Elegí una fecha y hora futuras válidas.');
        }

        return $fecha->format('Y-m-d H:i:s');
    }

    private function validarUsuario(int $usuarioId): void
    {
        $stmt = $this->db->prepare(
            'SELECT id_Usuario FROM USUARIO WHERE id_Usuario = ? FOR UPDATE'
        );
        $stmt->bind_param('i', $usuarioId);
        $stmt->execute();
        $existe = $stmt->get_result()->fetch_assoc() !== null;
        $stmt->close();
        if (!$existe) {
            throw new InvalidArgumentException('El usuario seleccionado no existe.');
        }
    }

    private function validarPaciente(int $pacienteId): void
    {
        $stmt = $this->db->prepare(
            "SELECT p.id_Paciente
             FROM PACIENTE p
             INNER JOIN RAZA r ON r.id_Raza = p.id_Raza
             INNER JOIN ESPECIE e ON e.id_Especie = r.id_Especie
             WHERE p.id_Paciente = ?
               AND e.nombre_Especie IN ('Equino', 'Canino', 'Felino')
             FOR UPDATE"
        );
        $stmt->bind_param('i', $pacienteId);
        $stmt->execute();
        $existe = $stmt->get_result()->fetch_assoc() !== null;
        $stmt->close();
        if (!$existe) {
            throw new InvalidArgumentException('El paciente seleccionado no existe o no tiene una especie válida.');
        }
    }

}
