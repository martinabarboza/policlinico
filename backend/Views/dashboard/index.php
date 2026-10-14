<?php
/**
 * Datos entregados por DashboardController para renderizar la agenda.
 *
 * @var array $agendaDelDia
 * @var array $pacientesEnEspera
 * @var string $fechaDelDashboard
 */
?>

<!-- Empieza Contenido -->

<!-- Botones Principales -->
<div class="container-fluid pt-4 px-4 d-flex flex-wrap gap-3">
    <a href="nueva-ficha" class="dashboard-card">
        <span class="dashboard-card__icon dashboard-card__icon--primary">
            <i class="fa fa-plus-circle"></i>
        </span>
        <span class="dashboard-card__content">
            <span class="dashboard-card__title">Nueva ficha</span>
            <span class="dashboard-card__value">Crear registro</span>
        </span>
        <i class="fa fa-chevron-right dashboard-card__arrow"></i>
    </a>

    <!-- Consultas diarias -->
    <a href="consultas-diarias" class="dashboard-card">
        <span class="dashboard-card__icon dashboard-card__icon--success">
            <i class="fa fa-calendar-day" aria-hidden="true"></i>
        </span>
        <span class="dashboard-card__content">
            <span class="dashboard-card__title">Consultas diarias</span>
            <span class="dashboard-card__value">0</span>
        </span>
        <i class="fa fa-chevron-right dashboard-card__arrow" aria-hidden="true"></i>
    </a>

    <!-- Consultas incompletas -->
    <a href="consultas-incompletas" class="dashboard-card">
        <span class="dashboard-card__icon dashboard-card__icon--danger">
            <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
        </span>
        <span class="dashboard-card__content">
            <span class="dashboard-card__title">Consultas incompletas</span>
            <span class="dashboard-card__value">0</span>
        </span>
        <i class="fa fa-chevron-right dashboard-card__arrow" aria-hidden="true"></i>
    </a>

    <!-- Próximas consultas -->
    <a href="proximas-consultas" class="dashboard-card">
        <span class="dashboard-card__icon dashboard-card__icon--warning">
            <i class="fa fa-clock" aria-hidden="true"></i>
        </span>
        <span class="dashboard-card__content">
            <span class="dashboard-card__title">Próximas consultas</span>
            <span class="dashboard-card__value">Ver consultas</span>
        </span>
        <i class="fa fa-chevron-right dashboard-card__arrow" aria-hidden="true"></i>
    </a>
</div>
<!-- Termina Botones Principales -->


<!-- Agenda y pacientes en espera -->
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12 col-xl-6">
            <section class="dashboard-panel" aria-labelledby="waiting-title">
                <div class="dashboard-panel__header">
                    <div class="d-flex align-items-center gap-3">
                        <span class="dashboard-panel__icon dashboard-panel__icon--waiting" aria-hidden="true">
                            <i class="fa fa-hourglass-half"></i>
                        </span>
                        <div>
                            <h2 class="dashboard-panel__title" id="waiting-title">Pacientes en espera</h2>
                            <p class="dashboard-panel__subtitle">Consultas pendientes · <?= htmlspecialchars($fechaDelDashboard, ENT_QUOTES, 'UTF-8') ?></p>
                        </div>
                    </div>
                    <span class="dashboard-panel__count" aria-label="<?= count($pacientesEnEspera) ?> pacientes en espera">
                        <?= count($pacientesEnEspera) ?>
                    </span>
                </div>

                <?php if ($pacientesEnEspera): ?>
                    <ul class="dashboard-list">
                        <?php foreach ($pacientesEnEspera as $pacienteEnEspera): ?>
                            <li class="dashboard-list__item">
                                <time class="dashboard-list__time" datetime="<?= htmlspecialchars($pacienteEnEspera['fecha_hora'], ENT_QUOTES, 'UTF-8') ?>">
                                    <?= htmlspecialchars($pacienteEnEspera['hora'], ENT_QUOTES, 'UTF-8') ?>
                                </time>
                                <div class="dashboard-list__details">
                                    <h3 class="dashboard-list__name"><?= htmlspecialchars($pacienteEnEspera['nombre_paciente'], ENT_QUOTES, 'UTF-8') ?></h3>
                                    <p class="dashboard-list__meta">
                                        Tutor: <?= htmlspecialchars($pacienteEnEspera['nombre_tutor'], ENT_QUOTES, 'UTF-8') ?>
                                        <?php if (!empty($pacienteEnEspera['motivo'])): ?>
                                            <span class="dashboard-list__separator" aria-hidden="true">·</span>
                                            <?= htmlspecialchars($pacienteEnEspera['motivo'], ENT_QUOTES, 'UTF-8') ?>
                                        <?php endif; ?>
                                    </p>
                                </div>
                                <span class="dashboard-status dashboard-status--pending">Pendiente</span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <div class="dashboard-empty">
                        <i class="fa fa-circle-check" aria-hidden="true"></i>
                        <p>No hay pacientes en espera</p>
                        <span>Las consultas pendientes de hoy aparecerán aquí.</span>
                    </div>
                <?php endif; ?>
            </section>
        </div>
        <div class="col-sm-12 col-xl-6">
            <section class="dashboard-panel" aria-labelledby="schedule-title">
                <div class="dashboard-panel__header">
                    <div class="d-flex align-items-center gap-3">
                        <span class="dashboard-panel__icon dashboard-panel__icon--schedule" aria-hidden="true">
                            <i class="fa fa-calendar-day"></i>
                        </span>
                        <div>
                            <h2 class="dashboard-panel__title" id="schedule-title">Agenda del día</h2>
                            <p class="dashboard-panel__subtitle"><?= htmlspecialchars($fechaDelDashboard, ENT_QUOTES, 'UTF-8') ?> · <?= count($agendaDelDia) ?> consultas</p>
                        </div>
                    </div>
                </div>

                <?php if ($agendaDelDia): ?>
                    <ol class="dashboard-list dashboard-list--schedule">
                        <?php foreach ($agendaDelDia as $consulta): ?>
                            <li class="dashboard-list__item">
                                <time class="dashboard-list__time" datetime="<?= htmlspecialchars($consulta['fecha_hora'], ENT_QUOTES, 'UTF-8') ?>">
                                    <?= htmlspecialchars($consulta['hora'], ENT_QUOTES, 'UTF-8') ?>
                                </time>
                                <div class="dashboard-list__details">
                                    <h3 class="dashboard-list__name"><?= htmlspecialchars($consulta['nombre_paciente'], ENT_QUOTES, 'UTF-8') ?></h3>
                                    <p class="dashboard-list__meta">
                                        <?= htmlspecialchars($consulta['tipo'], ENT_QUOTES, 'UTF-8') ?>
                                        <span class="dashboard-list__separator" aria-hidden="true">·</span>
                                        Tutor: <?= htmlspecialchars($consulta['nombre_tutor'], ENT_QUOTES, 'UTF-8') ?>
                                    </p>
                                </div>
                                <span class="dashboard-status <?= htmlspecialchars($consulta['clase_estado'], ENT_QUOTES, 'UTF-8') ?>">
                                    <?= htmlspecialchars($consulta['estado'], ENT_QUOTES, 'UTF-8') ?>
                                </span>
                            </li>
                        <?php endforeach; ?>
                    </ol>
                <?php else: ?>
                    <div class="dashboard-empty">
                        <i class="fa fa-calendar-xmark" aria-hidden="true"></i>
                        <p>No hay consultas agendadas para hoy</p>
                        <span>Cuando se registren consultas, las verás ordenadas por horario.</span>
                    </div>
                <?php endif; ?>
            </section>
        </div>
    </div>
</div>
<!-- Termina Agenda y pacientes en espera -->


<!-- Empieza Hospitalizados -->
<div class="container-fluid pt-4 px-4">
    <div class="bg-light text-center rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">Pacientes Hospitalizados</h6>
        </div>
        <div class="table-responsive">
            <table class="table bg-transparent text-start align-middle table-hover mb-0">
                <thead>
                    <tr class="text-dark">
                        <th scope="col">Ingreso</th>
                        <th scope="col">Paciente</th>
                        <th scope="col">Tutor</th>
                        <th scope="col">Motivo</th>
                        <th scope="col">Responsable</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Ejemplo
        <tr>
            <td>02 May 2026</td>
            <td>Relámpago</td>
            <td>Silva</td>
            <td>Herida en miembro anterior</td>
            <td>Dr. Pérez</td>
            <td><span class="badge bg-warning text-dark p-2">En observación</span></td>
            <td><a class="btn btn-sm btn-primary" href="">Ver ficha</a></td>
        </tr>
    -->
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No hay datos disponibles</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Termina Pacientes Hospitalizados -->

<!-- Fin Contenido -->