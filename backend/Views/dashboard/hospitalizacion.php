<?php
// backend/Views/dashboard/hospitalizacion.php

$e = static fn($valor): string => htmlspecialchars(
    is_scalar($valor) ? (string) $valor : '',
    ENT_QUOTES,
    'UTF-8'
);
$hospitalizaciones = is_array($hospitalizaciones ?? null) ? $hospitalizaciones : [];
$pacientes = is_array($pacientes ?? null) ? $pacientes : [];
$consultas = is_array($consultas ?? null) ? $consultas : [];
$busquedaPaciente = is_string($busquedaPaciente ?? null) ? $busquedaPaciente : '';
$oldInput = is_array($oldInput ?? null) ? $oldInput : [];
$oldPacienteId = is_string($oldInput['id_Paciente'] ?? null) ? $oldInput['id_Paciente'] : '';
$oldConsultaId = is_string($oldInput['id_Consulta'] ?? null) ? $oldInput['id_Consulta'] : '';
$oldMotivo = is_string($oldInput['motivo_Hospitalizacion'] ?? null)
    ? $oldInput['motivo_Hospitalizacion']
    : '';
$mensaje = is_string($mensaje ?? null) ? $mensaje : '';
$error = is_string($error ?? null) ? $error : '';
?>

<main class="container-fluid usuarios-page hospitalizacion-page py-4">
    <header class="usuarios-header">
        <div class="usuarios-header__title">
            <div class="usuarios-header__icon">
                <i class="fa-solid fa-hospital" aria-hidden="true"></i>
            </div>
            <div>
                <p class="usuarios-header__eyebrow">Internación</p>
                <h1 class="h3 mb-0">Hospitalización</h1>
            </div>
        </div>
        <div class="text-secondary small">
            Ingresos, seguimiento y altas de pacientes internados
        </div>
    </header>

    <?php if (is_string($mensaje) && $mensaje !== ''): ?>
        <div class="alert alert-success mt-4" role="status"><?= $e($mensaje) ?></div>
    <?php endif; ?>
    <?php if (is_string($error) && $error !== ''): ?>
        <div class="alert alert-danger mt-4" role="alert"><?= $e($error) ?></div>
    <?php endif; ?>

    <!-- Formulario de ingreso -->
    <section class="card shadow-sm mt-4">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
                <div>
                    <h2 class="h5 mb-1">Nuevo ingreso</h2>
                    <p class="text-secondary mb-0">Buscá el paciente por nombre o chip y completá los datos de internación.</p>
                </div>
            </div>

            <form method="get" action="<?= $e(url('hospitalizacion')) ?>" class="row g-3 align-items-end mb-4">
                <div class="col-12 col-sm-8 col-lg-9">
                    <label class="form-label" for="buscar-paciente-hospitalizacion">Buscar paciente</label>
                    <input class="form-control" type="search" id="buscar-paciente-hospitalizacion"
                           name="busquedaPaciente" value="<?= $e($busquedaPaciente) ?>"
                           placeholder="Nombre o número de chip..." maxlength="100">
                </div>
                <div class="col-12 col-sm-4 col-lg-3 d-flex gap-2">
                    <button class="btn btn-outline-primary flex-fill" type="submit">
                        <i class="fa-solid fa-magnifying-glass me-1" aria-hidden="true"></i>Buscar
                    </button>
                    <?php if ($busquedaPaciente !== ''): ?>
                        <a class="btn btn-outline-secondary flex-fill" href="<?= $e(url('hospitalizacion')) ?>">Limpiar</a>
                    <?php endif; ?>
                </div>
            </form>

            <?php if ($busquedaPaciente === ''): ?>
                <p class="small text-secondary mb-4">Ingresá un nombre o chip para cargar pacientes y seleccionar uno.</p>
            <?php elseif (!$pacientes): ?>
                <p class="small text-secondary mb-4">No se encontraron pacientes para esa búsqueda.</p>
            <?php else: ?>
                <p class="small text-secondary mb-4">
                    Se muestran <?= count($pacientes) ?> coincidencia(s), con un máximo de 50 resultados.
                </p>
            <?php endif; ?>

            <form method="post" action="<?= $e(url('hospitalizacion/registrarIngreso')) ?>" class="row g-3">
                <input type="hidden" name="csrf_token" value="<?= $e($csrfToken) ?>">
                <input type="hidden" name="busquedaPaciente" value="<?= $e($busquedaPaciente) ?>">
                <div class="col-12 col-lg-6">
                    <label class="form-label" for="hospitalizacion-paciente">Paciente *</label>
                    <select class="form-select" id="hospitalizacion-paciente" name="id_Paciente"
                            required <?= !$pacientes ? 'disabled' : '' ?>>
                        <option value=""><?= $busquedaPaciente === '' ? 'Primero buscá un paciente' : 'Seleccioná un paciente' ?></option>
                        <?php foreach ($pacientes as $paciente): ?>
                            <?php $pacienteId = (string) ($paciente['id'] ?? ''); ?>
                            <option value="<?= $e($pacienteId) ?>"
                                <?= $oldPacienteId === $pacienteId ? 'selected' : '' ?>>
                                <?= $e($paciente['nombre'] ?? '') ?>
                                <?php if (($paciente['chip'] ?? '') !== ''): ?> · Chip: <?= $e($paciente['chip']) ?><?php endif; ?>
                                · Tutor: <?= $e($paciente['tutor'] ?? '') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12 col-lg-4">
                    <label class="form-label" for="hospitalizacion-consulta">Consulta asociada <span class="text-secondary">(opcional)</span></label>
                    <select class="form-select" id="hospitalizacion-consulta" name="id_Consulta"
                            <?= $oldPacienteId === '' ? 'disabled' : '' ?>>
                        <option value="">Seleccioná un paciente primero</option>
                        <?php foreach ($consultas as $consulta): ?>
                            <?php $consultaId = (string) $consulta['id_Consulta']; ?>
                            <option value="<?= $e($consultaId) ?>"
                                data-paciente-id="<?= $e($consulta['id_Paciente']) ?>"
                                <?= $oldConsultaId === $consultaId ? 'selected' : '' ?>>
                                <?= $e($consulta['tipo_Consulta'] ?? 'Consulta') ?> ·
                                <?= $e($consulta['fecha_hora_Consulta'] ?? '') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-text" id="hospitalizacion-consulta-ayuda">
                        Seleccioná un paciente para ver sus consultas previas.
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <label class="form-label" for="hospitalizacion-motivo">Motivo *</label>
                    <input class="form-control" type="text" id="hospitalizacion-motivo" name="motivo_Hospitalizacion"
                           value="<?= $e($oldMotivo) ?>"
                           maxlength="2000" required placeholder="Ej: Trauma, enfermedad aguda...">
                </div>
                <div class="col-12">
                    <button class="btn btn-primary" type="submit" <?= !$pacientes ? 'disabled' : '' ?>>
                        <i class="fa-solid fa-arrow-right-to-bracket me-1" aria-hidden="true"></i>Registrar ingreso
                    </button>
                    <button class="btn btn-outline-secondary" type="reset" <?= !$pacientes ? 'disabled' : '' ?>>Limpiar</button>
                </div>
            </form>
        </div>
    </section>

    <!-- Listado de internaciones -->
    <section class="card shadow-sm mt-4">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3 hospitalizacion-card-header">
                <h2 class="h5 mb-0">Internaciones activas</h2>
                <span class="badge text-bg-light"><?= count($hospitalizaciones) ?> paciente(s)</span>
            </div>

            <?php if (empty($hospitalizaciones)): ?>
                <p class="text-secondary mb-0">No hay pacientes internados actualmente.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle hospitalizacion-table">
                        <thead>
                            <tr>
                                <th>Ingreso</th>
                                <th>Paciente y tutor</th>
                                <th>Consulta</th>
                                <th>Motivo</th>
                                <th>Responsable</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($hospitalizaciones as $h): ?>
                                <?php $fechaIngreso = new DateTimeImmutable((string) $h['fecha_ingreso_Hospitalizacion']); ?>
                                <?php $consultaAsociada = trim((string) ($h['consulta'] ?? '')); ?>
                                <tr>
                                    <td>
                                        <time datetime="<?= $e($fechaIngreso->format('Y-m-d\TH:i:s')) ?>">
                                            <?= $e($fechaIngreso->format('d/m/Y H:i')) ?>
                                        </time>
                                    </td>
                                    <td>
                                        <strong><?= $e($h['nombre_Paciente'] ?? '') ?></strong>
                                        <span class="small text-secondary">· <?= $e($h['nombre_Tutor'] ?? '') ?></span>
                                    </td>
                                    <td><?= $e($consultaAsociada !== '' ? $consultaAsociada : 'Sin consulta asociada') ?></td>
                                    <td><?= $e($h['motivo_Hospitalizacion'] ?? 'Sin motivo') ?></td>
                                    <td><?= $e($h['responsable'] ?? 'N/A') ?></td>
                                    <td>
                                        <span class="hospitalizacion-badge activa"><?= $e($h['estado_Hospitalizacion']) ?></span>
                                    </td>
                                    <td class="hospitalizacion-actions">
                                        <form method="post" action="<?= $e(url('hospitalizacion/darAlta')) ?>"
                                              onsubmit="return confirm('¿Confirmás el alta de este paciente?')">
                                            <input type="hidden" name="csrf_token" value="<?= $e($csrfToken) ?>">
                                            <input type="hidden" name="id_Hospitalizacion" value="<?= $e($h['id_Hospitalizacion']) ?>">
                                            <button class="btn btn-sm btn-outline-danger" type="submit">Dar alta</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>
