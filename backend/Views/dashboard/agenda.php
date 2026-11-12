<?php
/**
 * Agenda diaria/semanal; recibe turnos, opciones de búsqueda, permisos y estado del formulario.
 * Las acciones POST publicadas aquí usan el token CSRF entregado por AgendaController.
 */
$e = static fn($valor): string => htmlspecialchars((string) ($valor ?? ''), ENT_QUOTES, 'UTF-8');
$turnos = is_array($turnos ?? null) ? $turnos : [];
$pacientes = is_array($pacientes ?? null) ? $pacientes : [];
$usuarios = is_array($usuarios ?? null) ? $usuarios : [];
$oldInput = is_array($oldInput ?? null) ? $oldInput : [];
$busquedaPaciente = is_string($busquedaPaciente ?? null) ? $busquedaPaciente : '';
$busquedaUsuario = is_string($busquedaUsuario ?? null) ? $busquedaUsuario : '';
$vistaAgenda = ($vistaAgenda ?? 'dia') === 'semana' ? 'semana' : 'dia';
$fechaBase = new DateTimeImmutable($fechaSeleccionada ?? 'today');
$esPendiente = static fn(array $turno): bool =>
    strtolower(trim((string) ($turno['estado'] ?? ''))) === 'pendiente';
?>

<main class="container-fluid agenda-page usuarios-page py-4">
    <header class="usuarios-header">
        <div class="usuarios-header__title">
            <div class="usuarios-header__icon">
                <i class="fa-solid fa-calendar-days" aria-hidden="true"></i>
            </div>
            <div>
                <p class="usuarios-header__eyebrow">Organización</p>
                <h1 class="h3 mb-0">Agenda clínica</h1>
            </div>
        </div>
        <div class="text-secondary small">Turnos de 30 minutos; los participantes se completan al atender la consulta.</div>
    </header>

    <?php if (is_string($mensaje ?? null) && $mensaje !== ''): ?>
        <div class="alert alert-success mt-4" role="status"><?= $e($mensaje) ?></div>
    <?php endif; ?>
    <?php if (is_string($error ?? null) && $error !== ''): ?>
        <div class="alert alert-danger mt-4" role="alert"><?= $e($error) ?></div>
    <?php endif; ?>

    <section class="card shadow-sm mt-4">
        <div class="card-body">
            <form method="get" action="<?= $e(url('agenda')) ?>" class="row g-3 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="form-label" for="agenda-fecha">Fecha</label>
                    <input class="form-control" type="date" id="agenda-fecha" name="fecha"
                           value="<?= $e($fechaSeleccionada) ?>" required>
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label" for="agenda-vista">Vista</label>
                    <select class="form-select" id="agenda-vista" name="vista">
                        <option value="dia" <?= $vistaAgenda === 'dia' ? 'selected' : '' ?>>Día</option>
                        <option value="semana" <?= $vistaAgenda === 'semana' ? 'selected' : '' ?>>Semana</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button class="btn btn-outline-primary" type="submit">Ver agenda</button>
                </div>
                <div class="col-auto ms-md-auto d-flex gap-2">
                    <?php $paso = $vistaAgenda === 'semana' ? '7 days' : '1 day'; ?>
                    <a class="btn btn-outline-secondary"
                       href="<?= $e(url('agenda')) ?>?fecha=<?= $e($fechaBase->modify('-' . $paso)->format('Y-m-d')) ?>&amp;vista=<?= $e($vistaAgenda) ?>"
                       aria-label="Período anterior">‹ Anterior</a>
                    <a class="btn btn-outline-secondary"
                       href="<?= $e(url('agenda')) ?>?fecha=<?= $e($fechaBase->modify('+' . $paso)->format('Y-m-d')) ?>&amp;vista=<?= $e($vistaAgenda) ?>"
                       aria-label="Período siguiente">Siguiente ›</a>
                </div>
            </form>
            <p class="small text-secondary mt-3 mb-0">
                Período: <?= $e((new DateTimeImmutable($fechaInicio))->format('d/m/Y')) ?>
                <?php if ($fechaInicio !== $fechaFin): ?>
                    al <?= $e((new DateTimeImmutable($fechaFin))->format('d/m/Y')) ?>
                <?php endif; ?>
            </p>
        </div>
    </section>

    <section class="card shadow-sm mt-4">
        <div class="card-body">
            <h2 class="h5 mb-1">Agendar consulta</h2>
            <p class="text-secondary">La consulta se crea como Pendiente. El usuario elegido queda asociado para iniciar el registro; luego se pueden agregar los demás participantes desde la consulta.</p>

            <form method="get" action="<?= $e(url('agenda')) ?>" class="row g-3 align-items-end mb-4">
                <input type="hidden" name="fecha" value="<?= $e($fechaSeleccionada) ?>">
                <input type="hidden" name="vista" value="<?= $e($vistaAgenda) ?>">
                <div class="col-12 col-lg-5">
                    <label class="form-label" for="buscar-paciente-agenda">Buscar paciente</label>
                    <input class="form-control" type="search" id="buscar-paciente-agenda"
                           name="busquedaPaciente" value="<?= $e($busquedaPaciente) ?>"
                           placeholder="Nombre o chip..." maxlength="100">
                </div>
                <div class="col-12 col-lg-5">
                    <label class="form-label" for="buscar-usuario-agenda">Buscar usuario</label>
                    <input class="form-control" type="search" id="buscar-usuario-agenda"
                           name="busquedaUsuario" value="<?= $e($busquedaUsuario) ?>"
                           placeholder="Nombre o documento..." maxlength="100">
                </div>
                <div class="col-12 col-lg-2">
                    <button class="btn btn-outline-primary w-100" type="submit">Buscar</button>
                </div>
            </form>

            <?php if ($busquedaPaciente !== '' && !$pacientes): ?>
                <p class="small text-secondary">No se encontraron pacientes para esa búsqueda.</p>
            <?php endif; ?>
            <?php if ($busquedaUsuario !== '' && !$usuarios): ?>
                <p class="small text-secondary">No se encontraron usuarios para esa búsqueda.</p>
            <?php endif; ?>

            <form method="post" action="<?= $e(url('agenda/guardar')) ?>" class="row g-3">
                <input type="hidden" name="csrf_token" value="<?= $e($csrfToken) ?>">
                <input type="hidden" name="busquedaPaciente" value="<?= $e($busquedaPaciente) ?>">
                <input type="hidden" name="busquedaUsuario" value="<?= $e($busquedaUsuario) ?>">
                <div class="col-12 col-lg-6">
                    <label class="form-label" for="turno-paciente">Paciente *</label>
                    <select class="form-select" id="turno-paciente" name="id_Paciente" required>
                        <option value="">Buscá y seleccioná un paciente</option>
                        <?php foreach ($pacientes as $paciente): ?>
                            <?php $idPaciente = (string) ($paciente['id'] ?? ''); ?>
                            <option value="<?= $e($idPaciente) ?>"
                                <?= (string) ($oldInput['id_Paciente'] ?? '') === $idPaciente ? 'selected' : '' ?>>
                                <?= $e($paciente['nombre'] ?? '') ?>
                                <?php if (($paciente['chip'] ?? '') !== ''): ?> · Chip: <?= $e($paciente['chip']) ?><?php endif; ?>
                                · Tutor: <?= $e($paciente['tutor'] ?? '') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12 col-lg-6">
                    <label class="form-label" for="turno-usuario">Usuario que inicia la consulta *</label>
                    <select class="form-select" id="turno-usuario" name="id_Usuario" required>
                        <option value="">Buscá y seleccioná un usuario</option>
                        <?php foreach ($usuarios as $usuario): ?>
                            <?php $idUsuario = (string) ($usuario['id_Usuario'] ?? ''); ?>
                            <option value="<?= $e($idUsuario) ?>"
                                <?= (string) ($oldInput['id_Usuario'] ?? '') === $idUsuario ? 'selected' : '' ?>>
                                <?= $e(trim(($usuario['nombre_Usuario'] ?? '') . ' ' . ($usuario['apellido_Usuario'] ?? ''))) ?>
                                · <?= $e($usuario['rol_Usuario'] ?? '') ?>
                                · Doc. <?= $e($usuario['cedula_Usuario'] ?? '') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <label class="form-label" for="turno-fecha">Fecha y hora *</label>
                    <input class="form-control" type="datetime-local" id="turno-fecha"
                           name="fecha_hora_Consulta" required
                           value="<?= $e($oldInput['fecha_hora_Consulta'] ?? '') ?>">
                </div>
                <div class="col-12 col-md-5">
                    <label class="form-label" for="turno-tipo">Tipo *</label>
                    <select class="form-select" id="turno-tipo" name="tipo_Consulta" required>
                        <option value="">Seleccioná un tipo</option>
                        <?php foreach (['Consulta general', 'Control', 'Consulta equina', 'Hospitalización'] as $tipo): ?>
                            <option value="<?= $e($tipo) ?>"
                                <?= ($oldInput['tipo_Consulta'] ?? '') === $tipo ? 'selected' : '' ?>>
                                <?= $e($tipo) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12 col-md-7">
                    <label class="form-label" for="turno-motivo">Motivo *</label>
                    <input class="form-control" type="text" id="turno-motivo" name="motivo_Consulta"
                           maxlength="65535" required
                           value="<?= $e($oldInput['motivo_Consulta'] ?? '') ?>">
                </div>
                <div class="col-12">
                    <button class="btn btn-primary" type="submit">Guardar turno pendiente</button>
                </div>
            </form>
        </div>
    </section>

    <section class="card shadow-sm mt-4">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <h2 class="h5 mb-0">Turnos del período</h2>
                <span class="badge text-bg-light"><?= count($turnos) ?> turno(s)</span>
            </div>

            <?php if (!$turnos): ?>
                <p class="text-secondary mb-0">No hay consultas agendadas para este período.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle agenda-table">
                        <thead>
                            <tr>
                                <?php if ($vistaAgenda === 'semana'): ?><th>Fecha</th><?php endif; ?>
                                <th>Hora</th>
                                <th>Paciente y tutor</th>
                                <th>Tipo / motivo</th>
                                <th>Usuarios asociados</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($turnos as $turno): ?>
                                <?php
                                $fechaTurno = new DateTimeImmutable($turno['fecha_hora']);
                                $turnoPendiente = $esPendiente($turno);
                                $diasSemana = ['Sun' => 'dom', 'Mon' => 'lun', 'Tue' => 'mar', 'Wed' => 'mié', 'Thu' => 'jue', 'Fri' => 'vie', 'Sat' => 'sáb'];
                                ?>
                                <tr>
                                    <?php if ($vistaAgenda === 'semana'): ?>
                                        <td><?= $e($diasSemana[$fechaTurno->format('D')] . ' ' . $fechaTurno->format('d/m')) ?></td>
                                    <?php endif; ?>
                                    <td><time datetime="<?= $e($fechaTurno->format(DateTimeInterface::ATOM)) ?>">
                                        <?= $e($fechaTurno->format('H:i')) ?>
                                    </time></td>
                                    <td>
                                        <strong><?= $e($turno['paciente'] ?? '') ?></strong>
                                        <span class="d-block small text-secondary">
                                            <?= $e($turno['especie'] ?? 'Especie sin definir') ?> · <?= $e($turno['tutor'] ?? '') ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?= $e($turno['tipo'] ?? 'Consulta') ?>
                                        <span class="d-block small text-secondary"><?= $e($turno['motivo'] ?? '') ?></span>
                                    </td>
                                    <td><?= $e($turno['usuarios'] ?? 'Sin usuarios asociados') ?></td>
                                    <td><span class="badge <?= $turnoPendiente ? 'text-bg-warning' : 'text-bg-secondary' ?>">
                                        <?= $e($turno['estado'] ?? 'Sin estado') ?>
                                    </span></td>
                                    <td class="agenda-actions">
                                        <a class="btn btn-sm btn-outline-primary"
                                           href="<?= $e(url('centro-consultas')) ?>?consulta=<?= (int) $turno['id'] ?>">
                                            Ver ficha
                                        </a>
                                        <?php if ($puedeGestionar && $turnoPendiente): ?>
                                            <details class="agenda-reschedule mt-2">
                                                <summary>Reprogramar</summary>
                                                <form method="post" action="<?= $e(url('agenda/reprogramar')) ?>" class="d-grid gap-2 mt-2">
                                                    <input type="hidden" name="csrf_token" value="<?= $e($csrfToken) ?>">
                                                    <input type="hidden" name="consulta_id" value="<?= (int) $turno['id'] ?>">
                                                    <input type="hidden" name="return_fecha" value="<?= $e($fechaSeleccionada) ?>">
                                                    <label class="visually-hidden" for="fecha-turno-<?= (int) $turno['id'] ?>">Nueva fecha y hora</label>
                                                    <input class="form-control form-control-sm" type="datetime-local"
                                                           id="fecha-turno-<?= (int) $turno['id'] ?>"
                                                           name="fecha_hora_Consulta" required
                                                           value="<?= $e($fechaTurno->format('Y-m-d\TH:i')) ?>">
                                                    <button class="btn btn-sm btn-outline-primary" type="submit">Guardar horario</button>
                                                </form>
                                            </details>
                                            <form method="post" action="<?= $e(url('agenda/cancelar')) ?>" class="mt-2">
                                                <input type="hidden" name="csrf_token" value="<?= $e($csrfToken) ?>">
                                                <input type="hidden" name="consulta_id" value="<?= (int) $turno['id'] ?>">
                                                <input type="hidden" name="return_fecha" value="<?= $e($fechaSeleccionada) ?>">
                                                <button class="btn btn-sm btn-outline-danger" type="submit">Cancelar turno</button>
                                            </form>
                                        <?php endif; ?>
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
