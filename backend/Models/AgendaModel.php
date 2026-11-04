<?php

class AgendaModel extends Model
{
    /**
     * @return list<array{
     *     id: int,
     *     fecha_hora: string,
     *     estado: string,
     *     tipo: string|null,
     *     motivo: string|null,
     *     paciente: string,
     *     tutor: string,
     *     especie: string,
     *     veterinario: string
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
                            DISTINCT TRIM(CONCAT_WS(' ', uv.nombre_Usuario, uv.apellido_Usuario))
                            ORDER BY uv.nombre_Usuario SEPARATOR ', '
                        )
                        FROM USUARIO_CONSULTA ucv
                        INNER JOIN USUARIO uv ON uv.id_Usuario = ucv.id_Usuario
                        WHERE ucv.id_Consulta = c.id_Consulta
                          AND LOWER(TRIM(uv.rol_Usuario)) = 'profesor'
                    ),
                    'Sin asignar'
                ) AS veterinario,
                COALESCE(
                    (
                        SELECT MIN(ucv.id_Usuario)
                        FROM USUARIO_CONSULTA ucv
                        INNER JOIN USUARIO uv ON uv.id_Usuario = ucv.id_Usuario
                        WHERE ucv.id_Consulta = c.id_Consulta
                          AND LOWER(TRIM(uv.rol_Usuario)) = 'profesor'
                    ),
                    0
                ) AS veterinario_id
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

    /** @return list<array{id_Paciente: int, nombre_Paciente: string, especie: string, tutor: string}> */
    public function listarPacientes(): array
    {
        $stmt = $this->db->prepare(
            "SELECT p.id_Paciente, p.nombre_Paciente, e.nombre_Especie AS especie,
                    t.nombre_Tutor AS tutor
             FROM PACIENTE p
             INNER JOIN TUTOR t ON t.id_Tutor = p.id_Tutor
             LEFT JOIN RAZA r ON r.id_Raza = p.id_Raza
             LEFT JOIN ESPECIE e ON e.id_Especie = r.id_Especie
             ORDER BY e.nombre_Especie, p.nombre_Paciente"
        );
        $stmt->execute();
        $pacientes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $pacientes;
    }

    /** @return list<array{id_Usuario: int, nombre: string}> */
    public function listarVeterinarios(): array
    {
        $stmt = $this->db->prepare(
            "SELECT id_Usuario,
                    TRIM(CONCAT_WS(' ', nombre_Usuario, apellido_Usuario)) AS nombre
             FROM USUARIO
             WHERE LOWER(TRIM(rol_Usuario)) = 'profesor'
               AND LOWER(TRIM(estado_Usuario)) = 'activo'
             ORDER BY nombre_Usuario, apellido_Usuario"
        );
        $stmt->execute();
        $veterinarios = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $veterinarios;
    }

    public function crearTurno(
        int $pacienteId,
        int $veterinarioId,
        string $fechaHora,
        string $tipo,
        string $motivo
    ): int {
        $this->validarDatosTurno($pacienteId, $veterinarioId, $fechaHora, $tipo, $motivo);
        $fechaHora = $this->validarFechaHoraFutura($fechaHora);
        $this->db->begin_transaction();

        try {
            $this->validarPaciente($pacienteId);
            $this->bloquearVeterinario($veterinarioId);
            $this->validarChoqueHorario($veterinarioId, $fechaHora);
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
            $stmt->bind_param('ii', $veterinarioId, $consultaId);
            $stmt->execute();
            $stmt->close();
            $this->db->commit();

            return $consultaId;
        } catch (Throwable $error) {
            $this->db->rollback();
            throw $error;
        }
    }

    public function reprogramar(int $consultaId, int $veterinarioId, string $fechaHora): void
    {
        if ($consultaId < 1) {
            throw new InvalidArgumentException('El turno seleccionado no es válido.');
        }
        $fechaHora = $this->validarFechaHoraFutura($fechaHora);
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

            $this->bloquearVeterinario($veterinarioId);
            $this->validarChoqueHorario($veterinarioId, $fechaHora, $consultaId);
            $stmt = $this->db->prepare(
                "UPDATE CONSULTA SET fecha_hora_Consulta = ?
                 WHERE id_Consulta = ?
                   AND LOWER(TRIM(estado_proceso_Consulta)) = 'pendiente'"
            );
            $stmt->bind_param('si', $fechaHora, $consultaId);
            $stmt->execute();
            $stmt->close();

            $stmt = $this->db->prepare(
                "DELETE uc FROM USUARIO_CONSULTA uc
                 INNER JOIN USUARIO u ON u.id_Usuario = uc.id_Usuario
                 WHERE uc.id_Consulta = ?
                   AND LOWER(TRIM(u.rol_Usuario)) = 'profesor'"
            );
            $stmt->bind_param('i', $consultaId);
            $stmt->execute();
            $stmt->close();
            $stmt = $this->db->prepare(
                'INSERT INTO USUARIO_CONSULTA (id_Usuario, id_Consulta) VALUES (?, ?)'
            );
            $stmt->bind_param('ii', $veterinarioId, $consultaId);
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
        int $veterinarioId,
        string $fechaHora,
        string $tipo,
        string $motivo
    ): void {
        if ($pacienteId < 1 || $veterinarioId < 1) {
            throw new InvalidArgumentException('Seleccioná un paciente y un veterinario.');
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

    private function bloquearVeterinario(int $veterinarioId): void
    {
        $stmt = $this->db->prepare(
            "SELECT id_Usuario
             FROM USUARIO
             WHERE id_Usuario = ?
               AND LOWER(TRIM(rol_Usuario)) = 'profesor'
               AND LOWER(TRIM(estado_Usuario)) = 'activo'
             FOR UPDATE"
        );
        $stmt->bind_param('i', $veterinarioId);
        $stmt->execute();
        $existe = $stmt->get_result()->fetch_assoc() !== null;
        $stmt->close();
        if (!$existe) {
            throw new InvalidArgumentException('El veterinario seleccionado no está activo.');
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

    private function validarChoqueHorario(
        int $veterinarioId,
        string $fechaHora,
        ?int $consultaIdExcluir = null
    ): void {
        $consultaIdExcluir = $consultaIdExcluir ?? 0;
        $stmt = $this->db->prepare(
            "SELECT c.id_Consulta
             FROM CONSULTA c
             INNER JOIN USUARIO_CONSULTA uc ON uc.id_Consulta = c.id_Consulta
             WHERE uc.id_Usuario = ?
               AND (? = 0 OR c.id_Consulta <> ?)
               AND LOWER(TRIM(c.estado_proceso_Consulta)) NOT IN ('finalizada', 'cancelada', 'cancelado')
               AND c.fecha_hora_Consulta < DATE_ADD(?, INTERVAL 30 MINUTE)
               AND DATE_ADD(c.fecha_hora_Consulta, INTERVAL 30 MINUTE) > ?
             LIMIT 1"
        );
        $inicio = $fechaHora;
        $stmt->bind_param('iiiss', $veterinarioId, $consultaIdExcluir, $consultaIdExcluir, $inicio, $inicio);
        $stmt->execute();
        $hayChoque = $stmt->get_result()->fetch_assoc() !== null;
        $stmt->close();
        if ($hayChoque) {
            throw new InvalidArgumentException('Ese veterinario ya tiene una consulta que se superpone con ese horario.');
        }
    }
}
