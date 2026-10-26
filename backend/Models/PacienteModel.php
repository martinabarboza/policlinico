<?php

class PacienteModel extends Model
{
    /** @return list<array{id_Raza: int, nombre_Raza: string}> */
    public function listarRazasEquinas(): array
    {
        $stmt = $this->db->prepare(
            "SELECT r.id_Raza, r.nombre_Raza
             FROM RAZA r
             INNER JOIN ESPECIE e ON e.id_Especie = r.id_Especie
             WHERE LOWER(TRIM(e.nombre_Especie)) = 'equino'
             ORDER BY r.nombre_Raza"
        );
        $stmt->execute();
        $razas = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $razas;
    }

    /**
     * Registra un paciente y su tutor dentro de una transacción.
     * Si el documento ya pertenece a un tutor, reutiliza ese registro.
     *
     * @param array<string, mixed> $datos
     */
    public function crearPacienteConTutor(array $datos): int
    {
        $camposObligatorios = ['nombre_Paciente', 'documento_Tutor', 'nombre_Tutor'];
        foreach ($camposObligatorios as $campo) {
            if (!is_string($datos[$campo] ?? null) || trim($datos[$campo]) === '') {
                throw new InvalidArgumentException('Completá el nombre del paciente y los datos obligatorios del tutor.');
            }
        }

        $nombrePaciente = trim($datos['nombre_Paciente']);
        $documentoTutor = trim($datos['documento_Tutor']);
        $nombreTutor = trim($datos['nombre_Tutor']);
        $chip = $this->valorOpcional($datos, 'numchip_Paciente');
        $fechaNacimiento = $this->valorOpcional($datos, 'fecha_nac_Paciente');
        $sexo = $this->valorOpcional($datos, 'sexo_Paciente');
        $color = $this->valorOpcional($datos, 'color_Paciente');
        $telefono = $this->valorOpcional($datos, 'telefono_Tutor');
        $direccion = $this->valorOpcional($datos, 'direccion_Tutor');
        $email = $this->valorOpcional($datos, 'email_Tutor');
        $notas = $this->valorOpcional($datos, 'notas_Tutor');
        $razaRaw = $this->valorOpcional($datos, 'id_Raza');
        $edadRaw = $this->valorOpcional($datos, 'edad_Paciente');

        if (
            strlen($nombrePaciente) > 100
            || strlen($documentoTutor) > 20
            || strlen($nombreTutor) > 150
            || ($chip !== null && strlen($chip) > 26)
            || ($sexo !== null && strlen($sexo) > 30)
            || ($color !== null && strlen($color) > 50)
            || ($telefono !== null && strlen($telefono) > 50)
            || ($direccion !== null && strlen($direccion) > 255)
            || ($email !== null && strlen($email) > 150)
        ) {
            throw new InvalidArgumentException('Uno de los campos supera la longitud permitida.');
        }
        if ($email !== null && filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw new InvalidArgumentException('Ingresá un correo electrónico válido para el tutor.');
        }
        if ($sexo !== null && !in_array($sexo, ['Hembra', 'Macho', 'Macho castrado'], true)) {
            throw new InvalidArgumentException('El sexo seleccionado no es válido.');
        }

        $edad = null;
        if ($fechaNacimiento !== null) {
            $fecha = DateTimeImmutable::createFromFormat('!Y-m-d', $fechaNacimiento);
            $erroresFecha = DateTimeImmutable::getLastErrors();
            if (
                !$fecha
                || ($erroresFecha !== false && ($erroresFecha['warning_count'] > 0 || $erroresFecha['error_count'] > 0))
                || $fecha->format('Y-m-d') !== $fechaNacimiento
                || $fecha > new DateTimeImmutable('today')
            ) {
                throw new InvalidArgumentException('La fecha de nacimiento no es válida.');
            }
            $edad = $fecha->diff(new DateTimeImmutable('today'))->y;
        } elseif ($edadRaw !== null) {
            if (!ctype_digit($edadRaw) || (int) $edadRaw > 100) {
                throw new InvalidArgumentException('La edad debe ser un número entre 0 y 100.');
            }
            $edad = (int) $edadRaw;
        }

        $razaId = null;
        if ($razaRaw !== null) {
            if (!ctype_digit($razaRaw) || (int) $razaRaw < 1) {
                throw new InvalidArgumentException('La raza seleccionada no es válida.');
            }
            $razaId = (int) $razaRaw;
        }

        $this->db->begin_transaction();
        try {
            if ($razaId === null) {
                $stmt = $this->db->prepare(
                    "SELECT r.id_Raza
                     FROM RAZA r
                     INNER JOIN ESPECIE e ON e.id_Especie = r.id_Especie
                     WHERE LOWER(TRIM(e.nombre_Especie)) = 'equino'
                       AND LOWER(TRIM(r.nombre_Raza)) = 'sin especificar'
                     LIMIT 1"
                );
                $stmt->execute();
                $razaSinEspecificar = $stmt->get_result()->fetch_assoc();
                $stmt->close();
                if ($razaSinEspecificar === null) {
                    throw new InvalidArgumentException('No está configurada la raza equina “Sin especificar” en el catálogo.');
                }
                $razaId = (int) $razaSinEspecificar['id_Raza'];
            }

            if ($razaId !== null) {
                $stmt = $this->db->prepare(
                    "SELECT r.id_Raza
                     FROM RAZA r
                     INNER JOIN ESPECIE e ON e.id_Especie = r.id_Especie
                     WHERE r.id_Raza = ?
                       AND LOWER(TRIM(e.nombre_Especie)) = 'equino'
                     LIMIT 1"
                );
                $stmt->bind_param('i', $razaId);
                $stmt->execute();
                $razaExiste = $stmt->get_result()->fetch_assoc() !== null;
                $stmt->close();
                if (!$razaExiste) {
                    throw new InvalidArgumentException('La raza elegida no pertenece al catálogo equino.');
                }
            }

            $stmt = $this->db->prepare('SELECT id_Tutor FROM TUTOR WHERE documento_Tutor = ? LIMIT 1');
            $stmt->bind_param('s', $documentoTutor);
            $stmt->execute();
            $tutorExistente = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if ($tutorExistente !== null) {
                $tutorId = (int) $tutorExistente['id_Tutor'];
            } else {
                $stmt = $this->db->prepare(
                    'INSERT INTO TUTOR
                        (documento_Tutor, nombre_Tutor, telefono_Tutor, direccion_Tutor, email_Tutor, notas_Tutor)
                     VALUES (?, ?, ?, ?, ?, ?)'
                );
                $stmt->bind_param(
                    'ssssss',
                    $documentoTutor,
                    $nombreTutor,
                    $telefono,
                    $direccion,
                    $email,
                    $notas
                );
                $stmt->execute();
                $tutorId = (int) $stmt->insert_id;
                $stmt->close();
            }

            $stmt = $this->db->prepare(
                'INSERT INTO PACIENTE
                    (id_Tutor, id_Raza, numchip_Paciente, nombre_Paciente, fecha_nac_Paciente,
                     edad_Paciente, sexo_Paciente, color_Paciente)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
            );
            $stmt->bind_param(
                'iisssiss',
                $tutorId,
                $razaId,
                $chip,
                $nombrePaciente,
                $fechaNacimiento,
                $edad,
                $sexo,
                $color
            );
            $stmt->execute();
            $pacienteId = (int) $stmt->insert_id;
            $stmt->close();

            $this->db->commit();

            return $pacienteId;
        } catch (Throwable $error) {
            $this->db->rollback();
            throw $error;
        }
    }

    private function valorOpcional(array $datos, string $campo): ?string
    {
        $valor = $datos[$campo] ?? '';
        if (!is_string($valor)) {
            throw new InvalidArgumentException('Los datos del formulario no tienen un formato válido.');
        }

        $valor = trim($valor);
        return $valor === '' ? null : $valor;
    }

    /**
     * Busca por nombre o chip; limita la lista a 50 coincidencias.
     *
     * @return list<array{id: int, nombre: string, chip: string|null, tutor: string}>
     */
    public function buscar(string $texto): array
    {
        $termino = '%' . $texto . '%';
        $stmt = $this->db->prepare(
            "SELECT
                p.id_Paciente AS id,
                p.nombre_Paciente AS nombre,
                p.numchip_Paciente AS chip,
                t.nombre_Tutor AS tutor
             FROM PACIENTE p
             INNER JOIN TUTOR t ON t.id_Tutor = p.id_Tutor
             WHERE p.nombre_Paciente LIKE ?
                OR p.numchip_Paciente LIKE ?
             ORDER BY p.nombre_Paciente ASC
             LIMIT 50"
        );
        $stmt->bind_param('ss', $termino, $termino);
        $stmt->execute();

        $pacientes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $pacientes;
    }

    /**
     * Lista pacientes por tutor específico, limitado a 50 coincidencias.
     *
     * @param int $idTutor Identificador del tutor
     * @return array Lista de pacientes asociados al tutor
     */
    public function listarPorTutor(int $idTutor): array
    {
        $stmt = $this->db->prepare(
            "SELECT
                p.id_Paciente AS id,
                p.nombre_Paciente AS nombre,
                p.numchip_Paciente AS chip
             FROM PACIENTE p
             WHERE p.id_Tutor = ?
             ORDER BY p.nombre_Paciente ASC
             LIMIT 50"
        );
        $stmt->bind_param('i', $idTutor);
        $stmt->execute();

        $pacientes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $pacientes;
    }
}
