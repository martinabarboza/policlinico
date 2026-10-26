<?php
$esc = static function ($valor): string {
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
};

$renderCampos = static function (array $campos) use ($esc): void {
    ?>
    <dl class="consulta-detalle__campos mb-0">
        <?php foreach ($campos as $etiqueta => $valor): ?>
            <?php if (in_array($etiqueta, ['id', 'id_paciente'], true) || $valor === null || trim((string) $valor) === ''): ?>
                <?php continue; ?>
            <?php endif; ?>
            <div>
                <dt><?= $esc($etiqueta) ?></dt>
                <dd><?= nl2br($esc($valor)) ?></dd>
            </div>
        <?php endforeach; ?>
    </dl>
    <?php
};

$renderSeccion = static function (string $titulo, array $registros) use ($renderCampos): void {
    ?>
    <section class="card centro-card consulta-detalle__seccion">
        <h2 class="h5 card-header bg-transparent py-3 mb-0"><?= htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8') ?></h2>
        <?php if ($registros): ?>
            <div class="card-body d-grid gap-3">
                <?php foreach ($registros as $registro): ?>
                    <div><?php $renderCampos($registro); ?></div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="card-body text-muted mb-0">No hay datos registrados en esta sección.</p>
        <?php endif; ?>
    </section>
    <?php
};
?>
<main class="centro-consulta consulta-detalle">
    <?php if ($errorDetalle !== null): ?>
        <div class="alert alert-danger" role="alert"><?= $esc($errorDetalle) ?></div>
        <a class="btn btn-outline-primary" href="/centro-consultas">Volver al centro de consulta</a>
    <?php else: ?>
        <header class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <p class="text-muted mb-1">Detalle clínico</p>
                <h1 class="h3 mb-0">Consulta #<?= (int) $detalle['consulta']['id'] ?></h1>
            </div>
            <a class="btn btn-outline-primary" href="/centro-consultas?paciente=<?= (int) $detalle['consulta']['id_paciente'] ?>">
                Volver a consultas del paciente
            </a>
        </header>

        <?php if ($mensajeEstado !== null): ?>
            <div class="alert alert-success" role="status"><?= $esc($mensajeEstado) ?></div>
        <?php endif; ?>
        <?php if ($errorEstado !== null): ?>
            <div class="alert alert-danger" role="alert"><?= $esc($errorEstado) ?></div>
        <?php endif; ?>

        <?php if (in_array(strtoupper(trim(Auth::rol() ?? '')), ['PROFESOR', 'DIRECTOR'], true)): ?>
        <section class="card centro-card consulta-detalle__seccion mb-4">
            <div class="card-body d-flex flex-wrap align-items-end justify-content-between gap-3">
                <div>
                    <h2 class="h5 mb-1">Estado de la consulta</h2>
                    <p class="mb-0 text-muted">Estado actual: <strong><?= $esc($detalle['consulta']['Estado'] ?? 'Sin registrar') ?></strong></p>
                </div>
                <form class="d-flex flex-wrap align-items-end gap-2" method="post" action="/centro-consultas/actualizar-estado">
                    <input type="hidden" name="csrf_token" value="<?= $esc($csrfToken) ?>">
                    <input type="hidden" name="consulta_id" value="<?= (int) $detalle['consulta']['id'] ?>">
                    <input type="hidden" name="vista" value="detalle">
                    <div>
                        <label class="form-label small text-muted mb-1" for="estado-consulta-detalle">Cambiar a</label>
                        <select class="form-select" id="estado-consulta-detalle" name="estado" required>
                            <option value="" selected disabled>Seleccioná un estado</option>
                            <?php foreach (['Pendiente', 'En curso', 'Finalizada'] as $estado): ?>
                                <option value="<?= $esc($estado) ?>"><?= $esc($estado) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button class="btn btn-primary" type="submit">Actualizar estado</button>
                </form>
            </div>
        </section>
        <?php endif; ?>

        <?php $renderSeccion('Consulta, paciente y tutor', [$detalle['consulta']]); ?>
        <?php $renderSeccion('Estudiantes y profesores participantes', $detalle['participantes']); ?>
        <?php $renderSeccion('Anamnesis', $detalle['anamnesis']); ?>
        <?php $renderSeccion('Examen general', $detalle['examenes_generales']); ?>
        <?php $renderSeccion('Exámenes particulares', $detalle['examenes_particulares']); ?>
        <?php $renderSeccion('Examen equino', $detalle['examenes_equinos']); ?>
        <section class="card centro-card consulta-detalle__seccion">
            <h2 class="h5 card-header bg-transparent py-3 mb-0">Reseña gráfica</h2>
            <?php if ($detalle['resenias']): ?>
                <div class="card-body d-grid gap-3">
                    <?php foreach ($detalle['resenias'] as $resenia): ?>
                        <a href="/centro-consultas/resenia?id=<?= (int) $resenia['id'] ?>" target="_blank" rel="noopener">
                            <img class="consulta-detalle__resenia"
                                 src="/centro-consultas/resenia?id=<?= (int) $resenia['id'] ?>"
                                 alt="Reseña gráfica de la consulta #<?= (int) $detalle['consulta']['id'] ?>">
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="card-body text-muted mb-0">No se adjuntó una reseña gráfica.</p>
            <?php endif; ?>
        </section>
        <?php $renderSeccion('Diagnósticos', $detalle['diagnosticos']); ?>
        <?php $renderSeccion('Tratamientos', $detalle['tratamientos']); ?>
        <section class="card centro-card consulta-detalle__seccion">
            <h2 class="h5 card-header bg-transparent py-3 mb-0">Paraclínicos</h2>
            <?php if ($detalle['paraclinicos']): ?>
                <div class="card-body d-grid gap-3">
                    <?php foreach ($detalle['paraclinicos'] as $paraclinico): ?>
                        <div>
                            <?php $renderCampos($paraclinico); ?>
                            <?php if (!empty($paraclinico['id']) && !empty($paraclinico['Archivo asociado'])): ?>
                                <a class="btn btn-outline-primary btn-sm mt-2"
                                   href="/centro-consultas/paraclinico?id=<?= (int) $paraclinico['id'] ?>">
                                    Abrir archivo adjunto
                                </a>
                            <?php else: ?>
                                <p class="text-muted small mb-0">Sin archivo adjunto.</p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="card-body text-muted mb-0">No hay datos registrados en esta sección.</p>
            <?php endif; ?>
        </section>
        <?php $renderSeccion('Pronósticos', $detalle['pronosticos']); ?>
        <?php $renderSeccion('Hospitalización asociada', $detalle['hospitalizaciones']); ?>
    <?php endif; ?>
</main>
