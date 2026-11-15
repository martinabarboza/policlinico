<?php
$e = static fn($valor): string => htmlspecialchars(
    is_scalar($valor) ? (string) $valor : '',
    ENT_QUOTES,
    'UTF-8'
);
$resultados = is_array($resultados ?? null) ? $resultados : [];
$ficha = is_array($ficha ?? null) ? $ficha : null;
$internaciones = is_array($internaciones ?? null) ? $internaciones : [];
$busquedaPaciente = is_string($busquedaPaciente ?? null) ? $busquedaPaciente : '';
$error = is_string($error ?? null) ? $error : '';
$paciente = $ficha['paciente'] ?? [];
$consultas = is_array($ficha['consultas'] ?? null) ? $ficha['consultas'] : [];

$renderCampos = static function (array $campos) use ($e): void {
    ?>
    <dl class="consulta-detalle__campos mb-0">
        <?php foreach ($campos as $etiqueta => $valor): ?>
            <?php
            if (
                in_array($etiqueta, ['id', 'id_paciente', 'archivo'], true)
                || $valor === null
                || (is_string($valor) && trim($valor) === '')
            ) {
                continue;
            }
            ?>
            <div>
                <dt><?= $e($etiqueta) ?></dt>
                <dd><?= nl2br($e($valor)) ?></dd>
            </div>
        <?php endforeach; ?>
    </dl>
    <?php
};

$seccionesClinicas = [
    'participantes' => 'Profesionales participantes',
    'anamnesis' => 'Anamnesis',
    'examenes_generales' => 'Examen general',
    'examenes_particulares' => 'Exámenes particulares',
    'examenes_equinos' => 'Evaluación equina',
    'diagnosticos' => 'Diagnósticos',
    'tratamientos' => 'Tratamientos',
    'pronosticos' => 'Pronósticos',
];
?>

<main class="container-fluid centro-consulta centro-pacientes usuarios-page py-4">
    <header class="usuarios-header">
        <div class="usuarios-header__title">
            <div class="usuarios-header__icon">
                <i class="fa-solid fa-paw" aria-hidden="true"></i>
            </div>
            <div>
                <p class="usuarios-header__eyebrow">Pacientes</p>
                <h1 class="h3 mb-0">Centro de pacientes</h1>
            </div>
        </div>
        <div class="text-secondary small">Una ficha de consulta con datos del paciente y su historia clínica.</div>
    </header>

    <?php if ($error !== ''): ?>
        <div class="alert alert-danger" role="alert"><?= $e($error) ?></div>
    <?php endif; ?>

    <section class="card centro-card centro-pacientes__busqueda" aria-labelledby="buscar-paciente-titulo">
        <div class="card-body">
            <h2 class="h5 mb-1" id="buscar-paciente-titulo">Buscar un paciente</h2>
            <p class="text-secondary">Buscá por nombre o número de chip para abrir su ficha completa.</p>
            <form method="get" action="<?= $e(url('centro-pacientes')) ?>" class="row g-3 align-items-end">
                <div class="col-12 col-md-9">
                    <label class="form-label" for="busqueda-paciente">Nombre o chip</label>
                    <input class="form-control" type="search" id="busqueda-paciente" name="busquedaPaciente"
                           value="<?= $e($busquedaPaciente) ?>" maxlength="100"
                           placeholder="Ej.: Relámpago o número de chip">
                </div>
                <div class="col-12 col-md-3 d-flex gap-2">
                    <button class="btn btn-primary flex-fill" type="submit">
                        <i class="fa-solid fa-magnifying-glass me-1" aria-hidden="true"></i>Buscar
                    </button>
                    <?php if ($busquedaPaciente !== ''): ?>
                        <a class="btn btn-outline-secondary" href="<?= $e(url('centro-pacientes')) ?>">Limpiar</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </section>

    <?php if ($ficha === null && $busquedaPaciente === ''): ?>
        <section class="card centro-card centro-pacientes__estado-vacio">
            <div>
                <i class="fa-solid fa-magnifying-glass d-block" aria-hidden="true"></i>
                <p class="mb-1 fw-semibold text-dark">Buscá un paciente para consultar su ficha</p>
                <span>La búsqueda acepta nombre o chip.</span>
            </div>
        </section>
    <?php elseif ($ficha === null && $busquedaPaciente !== ''): ?>
        <section class="card centro-card centro-pacientes__seccion">
            <div class="card-body">
                <h2 class="h5">Resultados de búsqueda</h2>
                <?php if (!$resultados): ?>
                    <p class="text-secondary mb-0">No encontramos pacientes para “<?= $e($busquedaPaciente) ?>”.</p>
                <?php else: ?>
                    <p class="text-secondary"><?= count($resultados) ?> coincidencia(s); se muestran hasta 50 resultados.</p>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Paciente</th>
                                    <th>Chip</th>
                                    <th>Tutor</th>
                                    <th>Ficha</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($resultados as $resultado): ?>
                                    <?php
                                    $urlFicha = url('centro-pacientes') . '?' . http_build_query([
                                        'busquedaPaciente' => $busquedaPaciente,
                                        'paciente' => (int) $resultado['id'],
                                    ]);
                                    ?>
                                    <tr>
                                        <td class="fw-semibold"><?= $e($resultado['nombre'] ?? '') ?></td>
                                        <td><?= $e($resultado['chip'] ?: 'Sin chip') ?></td>
                                        <td><?= $e($resultado['tutor'] ?? '') ?></td>
                                        <td>
                                            <a class="btn btn-sm btn-outline-primary" href="<?= $e($urlFicha) ?>">
                                                Ver ficha
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>

    <?php if ($ficha !== null): ?>
        <?php
        $datosPaciente = [
            'Especie' => $paciente['especie'] ?? null,
            'Raza' => $paciente['raza'] ?? null,
            'Microchip' => $paciente['chip'] ?? null,
            'Fecha de nacimiento' => $paciente['fecha_nacimiento'] ?? null,
            'Edad registrada' => $paciente['edad'] ?? null,
            'Sexo' => $paciente['sexo'] ?? null,
            'Color' => $paciente['color'] ?? null,
        ];
        $datosTutor = [
            'Nombre' => $paciente['tutor'] ?? null,
            'Documento' => $paciente['documento_tutor'] ?? null,
            'Teléfono' => $paciente['telefono_tutor'] ?? null,
            'Correo' => $paciente['email_tutor'] ?? null,
            'Dirección' => $paciente['direccion_tutor'] ?? null,
            'Notas' => $paciente['notas_tutor'] ?? null,
        ];
        $urlPdf = url('centro-consultas/historia-clinica') . '?paciente=' . (int) $paciente['id'];
        ?>

        <section class="card centro-card centro-pacientes__ficha" aria-labelledby="ficha-paciente-titulo">
            <div class="centro-pacientes__perfil">
                <div>
                    <p class="usuarios-header__eyebrow mb-1">Ficha del paciente</p>
                    <h2 class="h4" id="ficha-paciente-titulo"><?= $e($paciente['nombre'] ?? '') ?></h2>
                    <div class="centro-pacientes__perfil-meta">
                        <?= $e($paciente['especie'] ?? 'Especie sin registrar') ?>
                        <?php if (!empty($paciente['raza'])): ?> · <?= $e($paciente['raza']) ?><?php endif; ?>
                        <?php if (!empty($paciente['chip'])): ?> · Chip <?= $e($paciente['chip']) ?><?php endif; ?>
                    </div>
                </div>
                <a class="btn btn-outline-success" href="<?= $e($urlPdf) ?>">
                    <i class="fa-solid fa-file-pdf me-1" aria-hidden="true"></i>Descargar historia PDF
                </a>
            </div>
            <div class="card-body d-grid gap-4">
                <section>
                    <h3 class="h6 mb-3">Datos del paciente</h3>
                    <?php $renderCampos($datosPaciente); ?>
                </section>
                <section>
                    <h3 class="h6 mb-3">Datos del tutor</h3>
                    <?php $renderCampos($datosTutor); ?>
                </section>
            </div>
        </section>

        <section class="card centro-card centro-pacientes__seccion" aria-labelledby="internaciones-titulo">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                    <h2 class="h5 mb-0" id="internaciones-titulo">Internaciones</h2>
                    <span class="badge text-bg-light"><?= count($internaciones) ?> registro(s)</span>
                </div>
                <?php if (!$internaciones): ?>
                    <p class="text-secondary mb-0">No hay internaciones registradas para este paciente.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Ingreso</th>
                                    <th>Egreso</th>
                                    <th>Motivo</th>
                                    <th>Consulta asociada</th>
                                    <th>Responsable</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($internaciones as $internacion): ?>
                                    <tr>
                                        <td><?= $e($internacion['fecha_ingreso_Hospitalizacion'] ?? '') ?></td>
                                        <td><?= $e($internacion['fecha_egreso_Hospitalizacion'] ?? 'En curso') ?></td>
                                        <td><?= $e($internacion['motivo_Hospitalizacion'] ?? 'Sin motivo registrado') ?></td>
                                        <td>
                                            <?php if (!empty($internacion['id_Consulta'])): ?>
                                                <?= $e($internacion['tipo_Consulta'] ?? 'Consulta') ?>
                                                · <?= $e($internacion['fecha_hora_Consulta'] ?? '') ?>
                                            <?php else: ?>
                                                <span class="text-secondary">Sin consulta asociada</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $e($internacion['responsable'] ?? 'Sin responsable registrado') ?></td>
                                        <td>
                                            <span class="badge <?= strtolower((string) ($internacion['estado_Hospitalizacion'] ?? '')) === 'activa' ? 'text-bg-warning' : 'text-bg-secondary' ?>">
                                                <?= $e($internacion['estado_Hospitalizacion'] ?? 'Sin estado') ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <section class="card centro-card centro-pacientes__seccion" aria-labelledby="consultas-titulo">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                    <h2 class="h5 mb-0" id="consultas-titulo">Historia de consultas</h2>
                    <span class="badge text-bg-light"><?= count($consultas) ?> consulta(s)</span>
                </div>
                <?php if (!$consultas): ?>
                    <p class="text-secondary mb-0">No hay consultas clínicas registradas para este paciente.</p>
                <?php else: ?>
                    <?php foreach ($consultas as $detalle): ?>
                        <?php
                        $consulta = is_array($detalle['consulta'] ?? null) ? $detalle['consulta'] : [];
                        $fechaTexto = (string) ($consulta['Fecha y hora'] ?? '');
                        $marcaTiempo = strtotime($fechaTexto);
                        $estado = (string) ($consulta['Estado'] ?? 'Sin estado');
                        $estadoClave = strtolower(trim($estado));
                        $claseEstado = [
                            'pendiente' => 'centro-estado--pendiente',
                            'en curso' => 'centro-estado--en-curso',
                            'finalizada' => 'centro-estado--finalizada',
                        ][$estadoClave] ?? '';
                        ?>
                        <article class="centro-pacientes__consulta">
                            <details>
                                <summary>
                                    <span class="centro-pacientes__consulta-resumen">
                                        <strong><?= $marcaTiempo === false ? $e($fechaTexto) : $e(date('d/m/Y H:i', $marcaTiempo)) ?></strong>
                                        <span><?= $e($consulta['Tipo de consulta'] ?? 'Consulta') ?></span>
                                        <span class="centro-estado <?= $e($claseEstado) ?>"><?= $e($estado) ?></span>
                                        <span class="small text-secondary"><?= $e($consulta['Motivo'] ?? '') ?></span>
                                    </span>
                                </summary>
                                <div class="centro-pacientes__consulta-detalle">
                                    <section class="centro-pacientes__grupo">
                                        <h4>Consulta</h4>
                                        <?php $renderCampos([
                                            'Resultado final' => $consulta['Resultado final'] ?? null,
                                            'Próximo control' => $consulta['Próximo control'] ?? null,
                                            'Fecha de finalización' => $consulta['Fecha de finalización'] ?? null,
                                        ]); ?>
                                    </section>

                                    <?php foreach ($seccionesClinicas as $clave => $titulo): ?>
                                        <?php $registros = is_array($detalle[$clave] ?? null) ? $detalle[$clave] : []; ?>
                                        <?php if (!$registros): ?><?php continue; ?><?php endif; ?>
                                        <section class="centro-pacientes__grupo">
                                            <h4><?= $e($titulo) ?></h4>
                                            <?php foreach ($registros as $registro): ?>
                                                <?php $renderCampos($registro); ?>
                                            <?php endforeach; ?>
                                        </section>
                                    <?php endforeach; ?>

                                    <?php $paraclinicos = is_array($detalle['paraclinicos'] ?? null) ? $detalle['paraclinicos'] : []; ?>
                                    <?php if ($paraclinicos): ?>
                                        <section class="centro-pacientes__grupo">
                                            <h4>Estudios paraclínicos</h4>
                                            <?php foreach ($paraclinicos as $paraclinico): ?>
                                                <div class="mb-3">
                                                    <?php $renderCampos($paraclinico); ?>
                                                    <?php if (!empty($paraclinico['id']) && !empty($paraclinico['Archivo asociado'])): ?>
                                                        <a class="btn btn-sm btn-outline-primary mt-2"
                                                           href="<?= $e(url('centro-consultas/paraclinico')) ?>?id=<?= (int) $paraclinico['id'] ?>">
                                                            Abrir archivo adjunto
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </section>
                                    <?php endif; ?>

                                    <?php $resenias = is_array($detalle['resenias'] ?? null) ? $detalle['resenias'] : []; ?>
                                    <?php if ($resenias): ?>
                                        <section class="centro-pacientes__grupo">
                                            <h4>Reseñas gráficas</h4>
                                            <div class="d-flex flex-wrap gap-3">
                                                <?php foreach ($resenias as $resenia): ?>
                                                    <?php $urlResenia = url('centro-consultas/resenia') . '?id=' . (int) ($resenia['id'] ?? 0); ?>
                                                    <a href="<?= $e($urlResenia) ?>" target="_blank" rel="noopener">
                                                        <img class="centro-pacientes__resenia" src="<?= $e($urlResenia) ?>"
                                                             alt="Reseña gráfica de la consulta">
                                                    </a>
                                                <?php endforeach; ?>
                                            </div>
                                        </section>
                                    <?php endif; ?>
                                </div>
                            </details>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>
</main>
