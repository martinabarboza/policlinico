<?php

class ConsultaModel extends Model
{
    /**
     * Lista pacientes que pertenecen a la especie elegida en el formulario.
     *
     * @return list<array{id_Paciente: int, nombre_Paciente: string, numchip_Paciente: string|null, nombre_Tutor: string, especie: string}>
     */
    public function listarPacientesPorEspecie(string $especie): array
    {
        $stmt = $this->db->prepare(
            "SELECT
                p.id_Paciente,
                p.nombre_Paciente,
                p.numchip_Paciente,
                t.nombre_Tutor,
                e.nombre_Especie AS especie
             FROM PACIENTE p
             INNER JOIN TUTOR t ON t.id_Tutor = p.id_Tutor
             INNER JOIN RAZA r ON r.id_Raza = p.id_Raza
             INNER JOIN ESPECIE e ON e.id_Especie = r.id_Especie
             WHERE e.nombre_Especie = ?
             ORDER BY p.nombre_Paciente ASC"
        );
        $stmt->bind_param('s', $especie);
        $stmt->execute();

        $pacientes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $pacientes;
    }

    /**
     * Guarda una consulta y los datos clínicos presentes en el formulario como una unidad.
     *
     * @param array<string, mixed> $datos
     * @param list<int> $estudianteIds
     * @param ?int $profesorId
     */
    public function crear(
        array $datos,
        int $pacienteId,
        string $especie,
        array $estudianteIds = [],
        ?int $profesorId = null
    ): int {
        $this->db->begin_transaction();

        try {
            $estudianteIds = array_values(array_unique($estudianteIds));
            foreach ($estudianteIds as $estudianteId) {
                $this->validarParticipante($estudianteId, 'estudiante');
            }
            if ($profesorId !== null) {
                $this->validarParticipante($profesorId, 'profesor');
            }

            $stmt = $this->db->prepare(
                "SELECT p.id_Paciente
                 FROM PACIENTE p
                 INNER JOIN RAZA r ON r.id_Raza = p.id_Raza
                 INNER JOIN ESPECIE e ON e.id_Especie = r.id_Especie
                 WHERE p.id_Paciente = ? AND e.nombre_Especie = ?
                 LIMIT 1"
            );
            $stmt->bind_param('is', $pacienteId, $especie);
            $stmt->execute();
            $pacienteExiste = $stmt->get_result()->fetch_assoc() !== null;
            $stmt->close();

            if (!$pacienteExiste) {
                throw new InvalidArgumentException('El paciente no existe o no corresponde a la especie seleccionada.');
            }

            $consultaId = $this->ejecutarInsert(
                "INSERT INTO CONSULTA
                    (id_Paciente, tipo_Consulta, fecha_hora_Consulta, estado_proceso_Consulta, motivo_Consulta)
                 VALUES (?, ?, ?, 'Pendiente', ?)",
                'isss',
                [$pacienteId, $datos['tipo_Consulta'], $datos['fecha_hora_Consulta'], $datos['motivo_Consulta']]
            );

            foreach ($estudianteIds as $estudianteId) {
                $this->ejecutarInsert(
                    'INSERT INTO USUARIO_CONSULTA (id_Usuario, id_Consulta) VALUES (?, ?)',
                    'ii',
                    [$estudianteId, $consultaId]
                );
            }
            if ($profesorId !== null) {
                $this->ejecutarInsert(
                    'INSERT INTO USUARIO_CONSULTA (id_Usuario, id_Consulta) VALUES (?, ?)',
                    'ii',
                    [$profesorId, $consultaId]
                );
            }

            $camposAnamnesis = [
                'sanitaria_Anamnesis',
                'ambiental_Anamnesis',
                'remota_fisiologica_Anamnesis',
                'remota_patologica_Anamnesis',
                'proxima_fisiologica_Anamnesis',
                'proxima_patologica_Anamnesis',
            ];
            $anamnesis = $this->obtenerCamposOpcionales($datos, $camposAnamnesis);

            if ($this->hayDatos($anamnesis)) {
                $this->ejecutarInsert(
                    "INSERT INTO ANAMNESIS
                        (id_Consulta, sanitaria_Anamnesis, ambiental_Anamnesis,
                         remota_fisiologica_Anamnesis, remota_patologica_Anamnesis,
                         proxima_fisiologica_Anamnesis, proxima_patologica_Anamnesis)
                     VALUES (?, ?, ?, ?, ?, ?, ?)",
                    'i' . str_repeat('s', count($anamnesis)),
                    array_merge([$consultaId], $anamnesis)
                );
            }

            $camposExamenGeneral = [
                'peso_ExamenG',
                'CC_ExamenG',
                'TR_ExamenG',
                'FC_ExamenG',
                'FR_ExamenG',
                'TLLC_ExamenG',
                'sensorio_ExamenG',
                'facies_ExamenG',
                'piel_subcutaneo_ExamenG',
                'mucosas_ExamenG',
                'grandes_funciones_ExamenG',
                'actitudes_anomalas_ExamenG',
                'hidratacion_ExamenG',
                'ganglios_linfaticos_ExamenG',
                'aparentes_ExamenG',
            ];
            $examenGeneral = $this->obtenerCamposOpcionales($datos, $camposExamenGeneral);
            $camposExamenEquino = [
                'temperatura_cascos_ExamenEq',
                'pulso_digital_ExamenEq',
                'senos_ExamenEq',
                'csd_ExamenEq',
                'cid_ExamenEq',
                'csi_ExamenEq',
                'cii_ExamenEq',
            ];
            $examenEquino = $this->obtenerCamposOpcionales($datos, $camposExamenEquino);
            $hayExamenEquino = $especie === 'Equino' && $this->hayDatos($examenEquino);

            if ($this->hayDatos($examenGeneral) || $hayExamenEquino) {
                $examenGeneralId = $this->ejecutarInsert(
                    "INSERT INTO EXAMEN_GENERAL
                        (id_Consulta, peso_ExamenG, CC_ExamenG, TR_ExamenG, FC_ExamenG,
                         FR_ExamenG, TLLC_ExamenG, sensorio_ExamenG, facies_ExamenG,
                         piel_subcutaneo_ExamenG, mucosas_ExamenG, grandes_funciones_ExamenG,
                         actitudes_anomalas_ExamenG, hidratacion_ExamenG,
                         ganglios_linfaticos_ExamenG, aparentes_ExamenG)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                    'i' . str_repeat('s', count($examenGeneral)),
                    array_merge([$consultaId], $examenGeneral)
                );

                if ($hayExamenEquino) {
                    $this->ejecutarInsert(
                        "INSERT INTO EXAMEN_EQUINO
                            (id_ExamenG, temperatura_cascos_ExamenEq, pulso_digital_ExamenEq,
                             senos_ExamenEq, csd_ExamenEq, cid_ExamenEq, csi_ExamenEq, cii_ExamenEq)
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
                        'i' . str_repeat('s', count($examenEquino)),
                        array_merge([$examenGeneralId], $examenEquino)
                    );
                }
            }

            if (!empty($datos['archivo_resenia'])) {
                if (!isset($examenGeneralId)) {
                    $examenGeneralId = $this->ejecutarInsert(
                        "INSERT INTO EXAMEN_GENERAL (id_Consulta) VALUES (?)",
                        'i',
                        [$consultaId]
                    );
                }
                $this->ejecutarInsert(
                    'INSERT INTO `RESEÑA` (id_ExamenG, archivoURL_RESEÑA) VALUES (?, ?)',
                    'is',
                    [$examenGeneralId, $datos['archivo_resenia']]
                );
            }

            foreach ($datos['diagnosticos'] ?? [] as $diagnostico) {
                $this->ejecutarInsert(
                    'INSERT INTO DIAGNOSTICO (id_Consulta, tipo_Diagnostico, descripcion_Diagnostico) VALUES (?, ?, ?)',
                    'iss',
                    [$consultaId, $diagnostico['tipo'], $diagnostico['descripcion']]
                );
            }

            foreach ($datos['paraclinicos'] ?? [] as $paraclinico) {
                $this->ejecutarInsert(
                    'INSERT INTO PARACLINICO
                        (id_Consulta, tipo_Paraclinico, resultado_Paraclinico, archivoURL_Paraclinico)
                     VALUES (?, ?, ?, ?)',
                    'isss',
                    [
                        $consultaId,
                        $paraclinico['tipo'],
                        $paraclinico['resultado'] ?: null,
                        $paraclinico['archivo'] ?? null,
                    ]
                );
            }

            foreach ($datos['pronosticos'] ?? [] as $pronostico) {
                $this->ejecutarInsert(
                    'INSERT INTO PRONOSTICO (id_Consulta, tipo_Pronostico) VALUES (?, ?)',
                    'is',
                    [$consultaId, $pronostico]
                );
            }

            if (($datos['tratamiento'] ?? '') !== '') {
                $this->ejecutarInsert(
                    'INSERT INTO TRATAMIENTO (id_Consulta, descripcion_Tratamiento) VALUES (?, ?)',
                    'is',
                    [$consultaId, $datos['tratamiento']]
                );
            }

            $this->db->commit();

            return $consultaId;
        } catch (Throwable $error) {
            $this->db->rollback();
            throw $error;
        }
    }

    private function validarParticipante(int $usuarioId, string $rol): void
    {
        if ($usuarioId < 1 || !in_array($rol, ['estudiante', 'profesor'], true)) {
            throw new InvalidArgumentException('Uno de los participantes seleccionados no es válido.');
        }

        $stmt = $this->db->prepare(
            "SELECT id_Usuario
             FROM USUARIO
             WHERE id_Usuario = ?
               AND LOWER(TRIM(rol_Usuario)) = ?
               AND LOWER(TRIM(estado_Usuario)) = 'activo'
             LIMIT 1"
        );
        $stmt->bind_param('is', $usuarioId, $rol);
        $stmt->execute();
        $existe = $stmt->get_result()->fetch_assoc() !== null;
        $stmt->close();

        if (!$existe) {
            throw new InvalidArgumentException('Uno de los participantes no existe, no está activo o no tiene el rol requerido.');
        }
    }

    public function obtenerArchivoResenia(int $reseniaId): ?string
    {
        $stmt = $this->db->prepare('SELECT archivoURL_RESEÑA FROM `RESEÑA` WHERE id_Reseña = ? LIMIT 1');
        $stmt->bind_param('i', $reseniaId);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $resultado['archivoURL_RESEÑA'] ?? null;
    }

    public function obtenerArchivoParaclinico(int $paraclinicoId): ?string
    {
        $stmt = $this->db->prepare(
            'SELECT archivoURL_Paraclinico FROM PARACLINICO WHERE id_Paraclinico = ? LIMIT 1'
        );
        $stmt->bind_param('i', $paraclinicoId);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $resultado['archivoURL_Paraclinico'] ?? null;
    }

    /**
     * Actualiza el estado de una consulta existente.
     */
    public function actualizarEstado(int $consultaId, string $estado): void
    {
        $estadosPermitidos = ['Pendiente', 'En curso', 'Finalizada'];
        if ($consultaId < 1 || !in_array($estado, $estadosPermitidos, true)) {
            throw new InvalidArgumentException('La consulta o el estado seleccionado no son válidos.');
        }

        $stmt = $this->db->prepare(
            'UPDATE CONSULTA SET estado_proceso_Consulta = ? WHERE id_Consulta = ?'
        );
        $stmt->bind_param('si', $estado, $consultaId);
        $stmt->execute();
        $consultaExiste = $stmt->affected_rows > 0;
        $stmt->close();

        if (!$consultaExiste) {
            $stmt = $this->db->prepare('SELECT id_Consulta FROM CONSULTA WHERE id_Consulta = ? LIMIT 1');
            $stmt->bind_param('i', $consultaId);
            $stmt->execute();
            $consultaExiste = $stmt->get_result()->fetch_assoc() !== null;
            $stmt->close();
        }

        if (!$consultaExiste) {
            throw new InvalidArgumentException('No se encontró la consulta que querés actualizar.');
        }
    }

    /** Ejecuta una inserción preparada y devuelve el ID generado. */
    private function ejecutarInsert(string $sql, string $tipos, array $valores): int
    {
        $stmt = $this->db->prepare($sql);
        $parametros = [$tipos];
        foreach ($valores as $indice => &$valor) {
            $parametros[] = &$valor;
        }
        call_user_func_array([$stmt, 'bind_param'], $parametros);
        $stmt->execute();
        $id = (int) $stmt->insert_id;
        $stmt->close();

        return $id;
    }

    /** @return list<?string> */
    private function obtenerCamposOpcionales(array $datos, array $campos): array
    {
        return array_map(
            static fn(string $campo): ?string => ($datos[$campo] ?? '') === '' ? null : $datos[$campo],
            $campos
        );
    }

    private function hayDatos(array $campos): bool
    {
        foreach ($campos as $valor) {
            if ($valor !== null && trim($valor) !== '') {
                return true;
            }
        }

        return false;
    }

    /**
     * Obtiene la consulta y todos sus registros relacionados para la vista de detalle.
     */
    public function obtenerDetalle(int $consultaId): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT
                c.id_Consulta AS id,
                p.id_Paciente AS id_paciente,
                c.tipo_Consulta AS `Tipo de consulta`,
                c.fecha_hora_Consulta AS `Fecha y hora`,
                c.estado_proceso_Consulta AS Estado,
                c.motivo_Consulta AS Motivo,
                c.resultado_final_Consulta AS `Resultado final`,
                c.fecha_proximo_control_Consulta AS `Próximo control`,
                c.fecha_fin_Consulta AS `Fecha de finalización`,
                p.nombre_Paciente AS Paciente,
                p.numchip_Paciente AS Chip,
                p.fecha_nac_Paciente AS `Fecha de nacimiento`,
                p.edad_Paciente AS Edad,
                p.sexo_Paciente AS Sexo,
                p.color_Paciente AS Color,
                p.foto_Paciente AS `Archivo de foto`,
                r.nombre_Raza AS Raza,
                e.nombre_Especie AS Especie,
                t.nombre_Tutor AS Tutor,
                t.documento_Tutor AS `Documento del tutor`,
                t.telefono_Tutor AS `Teléfono del tutor`,
                t.direccion_Tutor AS `Dirección del tutor`,
                t.email_Tutor AS `Correo del tutor`,
                t.notas_Tutor AS `Notas del tutor`
             FROM CONSULTA c
             INNER JOIN PACIENTE p ON p.id_Paciente = c.id_Paciente
             INNER JOIN TUTOR t ON t.id_Tutor = p.id_Tutor
             LEFT JOIN RAZA r ON r.id_Raza = p.id_Raza
             LEFT JOIN ESPECIE e ON e.id_Especie = r.id_Especie
             WHERE c.id_Consulta = ?
             LIMIT 1"
        );
        $stmt->bind_param('i', $consultaId);
        $stmt->execute();
        $consulta = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($consulta === null) {
            return null;
        }

        return [
            'consulta' => $consulta,
            'participantes' => $this->obtenerFilasDetalle(
                "SELECT
                    CONCAT_WS(' ', u.nombre_Usuario, u.apellido_Usuario) AS Participante,
                    u.cedula_Usuario AS `Cédula`,
                    u.rol_Usuario AS Rol
                 FROM USUARIO_CONSULTA uc
                 INNER JOIN USUARIO u ON u.id_Usuario = uc.id_Usuario
                 WHERE uc.id_Consulta = ?
                   AND LOWER(TRIM(u.rol_Usuario)) IN ('estudiante', 'profesor')
                 ORDER BY FIELD(LOWER(TRIM(u.rol_Usuario)), 'profesor', 'estudiante'),
                          u.apellido_Usuario, u.nombre_Usuario",
                $consultaId
            ),
            'anamnesis' => $this->obtenerFilasDetalle(
                "SELECT
                    sanitaria_Anamnesis AS `Antecedentes sanitarios`,
                    ambiental_Anamnesis AS Ambiente,
                    remota_fisiologica_Anamnesis AS `Fisiológica remota`,
                    remota_patologica_Anamnesis AS `Patológica remota`,
                    proxima_fisiologica_Anamnesis AS `Fisiológica próxima`,
                    proxima_patologica_Anamnesis AS `Patológica próxima`
                 FROM ANAMNESIS
                 WHERE id_Consulta = ?
                 ORDER BY id_Anamnesis",
                $consultaId
            ),
            'examenes_generales' => $this->obtenerFilasDetalle(
                "SELECT
                    peso_ExamenG AS Peso,
                    CC_ExamenG AS `Condición corporal`,
                    TR_ExamenG AS `Temperatura rectal`,
                    FC_ExamenG AS `Frecuencia cardíaca`,
                    FR_ExamenG AS `Frecuencia respiratoria`,
                    TLLC_ExamenG AS TLLC,
                    sensorio_ExamenG AS Sensorio,
                    facies_ExamenG AS Facies,
                    piel_subcutaneo_ExamenG AS `Piel y subcutáneo`,
                    mucosas_ExamenG AS Mucosas,
                    grandes_funciones_ExamenG AS `Grandes funciones`,
                    actitudes_anomalas_ExamenG AS `Actitudes anómalas`,
                    hidratacion_ExamenG AS `Hidratación`,
                    ganglios_linfaticos_ExamenG AS `Ganglios linfáticos`,
                    aparentes_ExamenG AS `Otras alteraciones aparentes`
                 FROM EXAMEN_GENERAL
                 WHERE id_Consulta = ?
                 ORDER BY id_ExamenG",
                $consultaId
            ),
            'examenes_particulares' => $this->obtenerFilasDetalle(
                "SELECT
                    ep.texto_libre_ExamenP AS `Examen particular`,
                    ep.observaciones_ExamenP AS Observaciones
                 FROM EXAMEN_PARTICULAR ep
                 INNER JOIN EXAMEN_GENERAL eg ON eg.id_ExamenG = ep.id_ExamenG
                 WHERE eg.id_Consulta = ?
                 ORDER BY ep.id_ExamenP",
                $consultaId
            ),
            'examenes_equinos' => $this->obtenerFilasDetalle(
                "SELECT
                    eq.temperatura_cascos_ExamenEq AS `Temperatura de cascos`,
                    eq.pulso_digital_ExamenEq AS `Pulso digital`,
                    eq.senos_ExamenEq AS Senos,
                    eq.csd_ExamenEq AS CSD,
                    eq.cid_ExamenEq AS CID,
                    eq.csi_ExamenEq AS CSI,
                    eq.cii_ExamenEq AS CII
                 FROM EXAMEN_EQUINO eq
                 INNER JOIN EXAMEN_GENERAL eg ON eg.id_ExamenG = eq.id_ExamenG
                 WHERE eg.id_Consulta = ?
                 ORDER BY eq.id_ExamenEq",
                $consultaId
            ),
            'resenias' => $this->obtenerFilasDetalle(
                "SELECT
                    r.id_Reseña AS id,
                    r.archivoURL_RESEÑA AS archivo
                 FROM RESEÑA r
                 INNER JOIN EXAMEN_GENERAL eg ON eg.id_ExamenG = r.id_ExamenG
                 WHERE eg.id_Consulta = ?
                 ORDER BY r.id_Reseña",
                $consultaId
            ),
            'diagnosticos' => $this->obtenerFilasDetalle(
                "SELECT
                    tipo_Diagnostico AS Tipo,
                    descripcion_Diagnostico AS `Descripción`
                 FROM DIAGNOSTICO
                 WHERE id_Consulta = ?
                 ORDER BY id_Diagnostico",
                $consultaId
            ),
            'tratamientos' => $this->obtenerFilasDetalle(
                "SELECT descripcion_Tratamiento AS Tratamiento
                 FROM TRATAMIENTO
                 WHERE id_Consulta = ?
                 ORDER BY id_Tratamiento",
                $consultaId
            ),
            'paraclinicos' => $this->obtenerFilasDetalle(
                "SELECT
                    id_Paraclinico AS id,
                    tipo_Paraclinico AS Tipo,
                    resultado_Paraclinico AS Resultado,
                    archivoURL_Paraclinico AS `Archivo asociado`
                 FROM PARACLINICO
                 WHERE id_Consulta = ?
                 ORDER BY id_Paraclinico",
                $consultaId
            ),
            'pronosticos' => $this->obtenerFilasDetalle(
                "SELECT tipo_Pronostico AS `Pronóstico`
                 FROM PRONOSTICO
                 WHERE id_Consulta = ?
                 ORDER BY id_Pronostico",
                $consultaId
            ),
            'hospitalizaciones' => $this->obtenerFilasDetalle(
                "SELECT
                    h.fecha_ingreso_Hospitalizacion AS `Fecha de ingreso`,
                    h.fecha_egreso_Hospitalizacion AS `Fecha de egreso`,
                    h.motivo_Hospitalizacion AS Motivo,
                    h.estado_Hospitalizacion AS Estado,
                    CONCAT_WS(' ', u.nombre_Usuario, u.apellido_Usuario) AS Responsable
                 FROM HOSPITALIZACION h
                 LEFT JOIN USUARIO u ON u.id_Usuario = h.id_Usuario
                 WHERE h.id_Consulta = ?
                 ORDER BY h.fecha_ingreso_Hospitalizacion",
                $consultaId
            ),
        ];
    }

    /** Ejecuta una consulta preparada que filtra por ID de consulta y devuelve sus filas. */
    private function obtenerFilasDetalle(string $sql, int $consultaId): array
    {
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $consultaId);
        $stmt->execute();

        $filas = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $filas;
    }

    /**
     * Lista consultas aplicando los filtros enviados por el centro.
     *
     * @param array{q: string, estado: string, tipo: string, paciente: string} $filtros
     * @return list<array{
     *     id_consulta: int,
     *     fecha_hora: string,
     *     tipo: string|null,
     *     motivo: string|null,
     *     estado: string|null,
     *     id_paciente: int,
     *     paciente: string,
     *     chip: string|null,
     *     tutor: string
     * }>
     */
    public function listar(array $filtros): array
    {
        $termino = '%' . $filtros['q'] . '%';
        $pacienteId = (int) $filtros['paciente'];

        $sql = "
            SELECT
                c.id_Consulta AS id_consulta,
                c.fecha_hora_Consulta AS fecha_hora,
                c.tipo_Consulta AS tipo,
                c.motivo_Consulta AS motivo,
                c.estado_proceso_Consulta AS estado,
                p.id_Paciente AS id_paciente,
                p.nombre_Paciente AS paciente,
                p.numchip_Paciente AS chip,
                t.nombre_Tutor AS tutor
            FROM CONSULTA c
            INNER JOIN PACIENTE p ON p.id_Paciente = c.id_Paciente
            INNER JOIN TUTOR t ON t.id_Tutor = p.id_Tutor
            WHERE (? = ''
                OR p.nombre_Paciente LIKE ?
                OR p.numchip_Paciente LIKE ?
                OR t.nombre_Tutor LIKE ?
                OR t.documento_Tutor LIKE ?)
              AND (? = '' OR LOWER(TRIM(c.estado_proceso_Consulta)) = LOWER(?))
              AND (? = '' OR LOWER(TRIM(c.tipo_Consulta)) = LOWER(?))
              AND (? = 0 OR c.id_Paciente = ?)
            ORDER BY c.fecha_hora_Consulta DESC
            LIMIT 100";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param(
            'sssssssssii',
            $filtros['q'],
            $termino,
            $termino,
            $termino,
            $termino,
            $filtros['estado'],
            $filtros['estado'],
            $filtros['tipo'],
            $filtros['tipo'],
            $pacienteId,
            $pacienteId
        );
        $stmt->execute();

        $consultas = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $consultas;
    }

    /** Devuelve contadores globales del centro, sin aplicar filtros de la tabla. */
    public function obtenerResumen(): array
    {
        $stmt = $this->db->prepare(
            "SELECT
                (SELECT COUNT(*) FROM CONSULTA) AS total,
                (SELECT COUNT(*) FROM CONSULTA
                 WHERE LOWER(TRIM(estado_proceso_Consulta)) = 'en curso') AS en_curso,
                (SELECT COUNT(*) FROM CONSULTA
                 WHERE LOWER(TRIM(estado_proceso_Consulta)) = 'pendiente') AS pendientes,
                (SELECT COUNT(*) FROM HOSPITALIZACION
                 WHERE fecha_egreso_Hospitalizacion IS NULL
                   AND LOWER(TRIM(estado_Hospitalizacion)) = 'activa') AS hospitalizaciones"
        );
        $stmt->execute();

        $resumen = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return [
            'total' => (int) $resumen['total'],
            'en_curso' => (int) $resumen['en_curso'],
            'pendientes' => (int) $resumen['pendientes'],
            'hospitalizaciones' => (int) $resumen['hospitalizaciones'],
        ];
    }

    /** @return list<array{id: int, cedula: string, nombre: string}> */
    public function listarEstudiantes(): array
    {
        return $this->listarUsuariosActivosPorRol('estudiante');
    }

    /** @return list<array{id: int, cedula: string, nombre: string}> */
    public function listarProfesores(): array
    {
        return $this->listarUsuariosActivosPorRol('profesor');
    }

    /** @return list<array{id: int, cedula: string, nombre: string}> */
    private function listarUsuariosActivosPorRol(string $rol): array
    {
        $stmt = $this->db->prepare(
            "SELECT
                id_Usuario AS id,
                cedula_Usuario AS cedula,
                CONCAT_WS(' ', nombre_Usuario, apellido_Usuario) AS nombre
             FROM USUARIO
             WHERE LOWER(TRIM(rol_Usuario)) = ?
               AND LOWER(TRIM(estado_Usuario)) = 'activo'
             ORDER BY apellido_Usuario, nombre_Usuario"
        );
        $stmt->bind_param('s', $rol);
        $stmt->execute();

        $usuarios = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $usuarios;
    }

    /**
     * Lista los controles futuros o programados para hoy.
     *
     * @return list<array{paciente: string, fecha: string, motivo: string|null}>
     */
    public function obtenerProximosControles(): array
    {
        $stmt = $this->db->prepare(
            "SELECT
                p.nombre_Paciente AS paciente,
                c.fecha_proximo_control_Consulta AS fecha,
                c.motivo_Consulta AS motivo
             FROM CONSULTA c
             INNER JOIN PACIENTE p ON p.id_Paciente = c.id_Paciente
             WHERE c.fecha_proximo_control_Consulta >= CURDATE()
             ORDER BY c.fecha_proximo_control_Consulta ASC
             LIMIT 10"
        );
        $stmt->execute();

        $controles = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $controles;
    }
}
