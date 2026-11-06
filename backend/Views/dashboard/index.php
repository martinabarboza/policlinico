<?php

/**
 * Datos entregados por DashboardController para renderizar el dashboard.
 * Agenda: incluye todas las consultas del día seleccionado, cualquiera sea su estado.
 * En espera: consultas de la agenda cuyo estado normalizado es `Pendiente`.
 * Hospitalizados: filas HOSPITALIZACION con estado `Activa` y fecha de egreso NULL.
 * Para cargar datos: registrar fecha y estado en CONSULTA; para internar, crear además
 * una fila HOSPITALIZACION ligada al paciente, con estado `Activa` y egreso NULL.
 * Al dar el alta, completar la fecha de egreso para excluirla del listado de activos.
 *
 * @var list<array{hora: string, fecha_hora: string, tipo: string, estado: string, clase_estado: string, motivo: string|null, nombre_paciente: string, nombre_tutor: string}> $agendaDelDia
 * @var list<array{hora: string, fecha_hora: string, tipo: string, estado: string, clase_estado: string, motivo: string|null, nombre_paciente: string, nombre_tutor: string}> $pacientesEnEspera
 * @var list<array{fecha_ingreso: string, fecha_ingreso_formateada: string, nombre_paciente: string, nombre_tutor: string, motivo: string|null, responsable: string, estado: string}> $pacientesHospitalizados
 * @var int $cantidadConsultasDiarias Total de consultas de hoy, sin filtrar estado.
 * @var int $cantidadConsultasIncompletas Total histórico con estado Pendiente o En curso.
 * @var string $fechaDelDashboard
 * @var string $fechaSeleccionada
 * @var string|null $errorFecha
 */
?>

<!-- Empieza Contenido -->

<!-- Accesos principales -->
<div class="container-fluid pt-4 px-4">
    <div class="row g-3">
        <div class="col-12 col-md-3">
            <a href="<?= htmlspecialchars(url('centro-tutores'), ENT_QUOTES, 'UTF-8') ?>" class="tarjeta-inicio h-100" style="width: 100%;">
                <span class="tarjeta-inicio__icono tarjeta-inicio__icono--principal">
                    <i class="fa fa-user-plus" aria-hidden="true"></i>
                </span>
                <span class="tarjeta-inicio__contenido">
                    <span class="tarjeta-inicio__titulo">Nuevo Tutor</span>
                    <span class="tarjeta-inicio__valor">Registrar tutor</span>
                </span>
            </a>
        </div>
        <div class="col-12 col-md-3">
            <a href="<?= htmlspecialchars(url('pacientes/crear'), ENT_QUOTES, 'UTF-8') ?>" class="tarjeta-inicio h-100" style="width: 100%;">
                <span class="tarjeta-inicio__icono tarjeta-inicio__icono--principal">
                    <i class="fa fa-paw" aria-hidden="true"></i>
                </span>
                <span class="tarjeta-inicio__contenido">
                    <span class="tarjeta-inicio__titulo">Nuevo Paciente</span>
                    <span class="tarjeta-inicio__valor">Registrar paciente</span>
                </span>
            </a>
        </div>
        <div class="col-12 col-md-3">
            <a href="<?= htmlspecialchars(url('centro-consultas/nueva'), ENT_QUOTES, 'UTF-8') ?>" class="tarjeta-inicio h-100" style="width: 100%;">
                <span class="tarjeta-inicio__icono tarjeta-inicio__icono--principal">
                    <i class="fa fa-notes-medical" aria-hidden="true"></i>
                </span>
                <span class="tarjeta-inicio__contenido">
                    <span class="tarjeta-inicio__titulo">Nueva Consulta</span>
                    <span class="tarjeta-inicio__valor">Iniciar consulta</span>
                </span>
            </a>
        </div>
    </div>
    <div class="row g-3 mt-0">
        <div class="col-12 col-md-auto">
            <section class="tarjeta-inicio tarjeta-inicio--consultas h-100" style="width: 300px; max-width: 100%;" aria-label="Consultas">
                <span class="tarjeta-inicio__icono tarjeta-inicio__icono--principal">
                    <i class="fa fa-calendar-check" aria-hidden="true"></i>
                </span>
                <span class="tarjeta-inicio__contenido">
                    <span class="tarjeta-inicio__titulo">Consultas</span>
                    <div class="consultas-resumen d-flex justify-content-around text-center">
                        <a class="consultas-resumen__item d-flex flex-column text-decoration-none" href="<?= htmlspecialchars(url('consultas-diarias'), ENT_QUOTES, 'UTF-8') ?>">
                            <span class="fw-bold"><?= (int) $cantidadConsultasDiarias ?></span>
                            <small class="text-muted">Hoy</small>
                        </a>
                        <a class="consultas-resumen__item d-flex flex-column text-decoration-none" href="<?= htmlspecialchars(url('consultas-incompletas'), ENT_QUOTES, 'UTF-8') ?>">
                            <span class="fw-bold<?= $cantidadConsultasIncompletas > 0 ? ' text-warning-emphasis' : '' ?>">
                                <?= (int) $cantidadConsultasIncompletas ?>
                            </span>
                            <small class="text-muted">Incompletas</small>
                        </a>
                        <a class="consultas-resumen__item d-flex flex-column text-decoration-none" href="<?= htmlspecialchars(url('proximas-consultas'), ENT_QUOTES, 'UTF-8') ?>">
                            <span class="fw-bold"><i class="bi bi-chevron-right" aria-hidden="true"></i></span>
                            <small class="text-muted">Próximas</small>
                        </a>
                    </div>
                </span>
            </section>
        </div>
    </div>
</div>
<!-- Termina Botones Principales -->

<div class="container-fluid pt-4 px-4">
    <form method="get" action="dashboard" class="d-flex flex-wrap align-items-end gap-2">
        <div>
            <label for="fecha-dashboard" class="form-label">Consultar agenda por fecha</label>
            <input
                type="date"
                id="fecha-dashboard"
                name="fecha"
                class="form-control"
                value="<?= htmlspecialchars($fechaSeleccionada, ENT_QUOTES, 'UTF-8') ?>"
                required>
        </div>
        <button type="submit" class="btn btn-primary">Ver fecha</button>
    </form>
    <?php if ($errorFecha !== null): ?>
        <p class="text-danger mt-2 mb-0" role="alert"><?= htmlspecialchars($errorFecha, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>
</div>

<!-- Agenda y pacientes en espera -->
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12 col-xl-6">
            <section class="panel-inicio" aria-labelledby="waiting-title">
                <div class="panel-inicio__encabezado">
                    <div class="d-flex align-items-center gap-3">
                        <span class="panel-inicio__icono panel-inicio__icono--espera" aria-hidden="true">
                            <i class="fa fa-hourglass-half"></i>
                        </span>
                        <div>
                            <h2 class="panel-inicio__titulo" id="waiting-title">Pacientes en espera</h2>
                            <p class="panel-inicio__subtitulo">Consultas pendientes · <?= htmlspecialchars($fechaDelDashboard, ENT_QUOTES, 'UTF-8') ?></p>
                        </div>
                    </div>
                    <span class="panel-inicio__contador" aria-label="<?= count($pacientesEnEspera) ?> pacientes en espera">
                        <?= count($pacientesEnEspera) ?>
                    </span>
                </div>

                <?php if ($pacientesEnEspera): ?>
                    <ul class="lista-inicio">
                        <?php foreach ($pacientesEnEspera as $pacienteEnEspera): ?>
                            <li class="lista-inicio__elemento">
                                <time class="lista-inicio__hora" datetime="<?= htmlspecialchars($pacienteEnEspera['fecha_hora'], ENT_QUOTES, 'UTF-8') ?>">
                                    <?= htmlspecialchars($pacienteEnEspera['hora'], ENT_QUOTES, 'UTF-8') ?>
                                </time>
                                <div class="lista-inicio__detalles">
                                    <h3 class="lista-inicio__nombre"><?= htmlspecialchars($pacienteEnEspera['nombre_paciente'], ENT_QUOTES, 'UTF-8') ?></h3>
                                    <p class="lista-inicio__metadatos">
                                        Tutor: <?= htmlspecialchars($pacienteEnEspera['nombre_tutor'], ENT_QUOTES, 'UTF-8') ?>
                                        <?php if (!empty($pacienteEnEspera['motivo'])): ?>
                                            <span class="lista-inicio__separador" aria-hidden="true">·</span>
                                            <?= htmlspecialchars($pacienteEnEspera['motivo'], ENT_QUOTES, 'UTF-8') ?>
                                        <?php endif; ?>
                                    </p>
                                </div>
                                <span class="estado-inicio estado-inicio--pendiente">Pendiente</span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <div class="vacio-inicio">
                        <i class="fa fa-circle-check" aria-hidden="true"></i>
                        <p>No hay pacientes en espera</p>
                        <span>Las consultas pendientes de esta fecha aparecerán aquí.</span>
                    </div>
                <?php endif; ?>
            </section>
        </div>
        <div class="col-sm-12 col-xl-6">
            <section class="panel-inicio" aria-labelledby="schedule-title">
                <div class="panel-inicio__encabezado">
                    <div class="d-flex align-items-center gap-3">
                        <span class="panel-inicio__icono panel-inicio__icono--agenda" aria-hidden="true">
                            <i class="fa fa-calendar-day"></i>
                        </span>
                        <div>
                            <h2 class="panel-inicio__titulo" id="schedule-title">Agenda del día</h2>
                            <p class="panel-inicio__subtitulo"><?= htmlspecialchars($fechaDelDashboard, ENT_QUOTES, 'UTF-8') ?></p>
                        </div>
                    </div>
                    <span class="panel-inicio__contador" aria-label="<?= count($agendaDelDia) ?> consultas en agenda">
                        <?= count($agendaDelDia) ?>
                    </span>
                </div>

                <?php if ($agendaDelDia): ?>
                    <ol class="lista-inicio lista-inicio--agenda">
                        <?php foreach ($agendaDelDia as $consulta): ?>
                            <li class="lista-inicio__elemento">
                                <time class="lista-inicio__hora" datetime="<?= htmlspecialchars($consulta['fecha_hora'], ENT_QUOTES, 'UTF-8') ?>">
                                    <?= htmlspecialchars($consulta['hora'], ENT_QUOTES, 'UTF-8') ?>
                                </time>
                                <div class="lista-inicio__detalles">
                                    <h3 class="lista-inicio__nombre"><?= htmlspecialchars($consulta['nombre_paciente'], ENT_QUOTES, 'UTF-8') ?></h3>
                                    <p class="lista-inicio__metadatos">
                                        <?= htmlspecialchars($consulta['tipo'], ENT_QUOTES, 'UTF-8') ?>
                                        <span class="lista-inicio__separador" aria-hidden="true">·</span>
                                        Tutor: <?= htmlspecialchars($consulta['nombre_tutor'], ENT_QUOTES, 'UTF-8') ?>
                                    </p>
                                </div>
                                <span class="estado-inicio <?= htmlspecialchars($consulta['clase_estado'], ENT_QUOTES, 'UTF-8') ?>">
                                    <?= htmlspecialchars($consulta['estado'], ENT_QUOTES, 'UTF-8') ?>
                                </span>
                            </li>
                        <?php endforeach; ?>
                    </ol>
                <?php else: ?>
                    <div class="vacio-inicio">
                        <i class="fa fa-calendar-xmark" aria-hidden="true"></i>
                        <p>No hay consultas agendadas para esta fecha</p>
                        <span>Cuando se registren consultas, las verás ordenadas por horario.</span>
                    </div>
                <?php endif; ?>
            </section>
        </div>
    </div>
</div>
<!-- Termina Agenda y pacientes en espera -->


<!-- Empieza Hospitalizados -->
<?php if (in_array(strtoupper(trim(Auth::rol() ?? '')), ['DIRECTOR', 'PROFESOR'], true)): ?>
<div class="container-fluid pt-4 px-4">
    <div class="bg-light text-center rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="text-start">
                <h6 class="mb-1">Pacientes Hospitalizados</h6>
                <small class="text-muted">Internaciones activas registradas en el sistema.</small>
            </div>
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
                    </tr>
                </thead>
                <tbody>
                    <?php if ($pacientesHospitalizados): ?>
                        <?php foreach (array_slice($pacientesHospitalizados, 0, 5) as $pacienteHospitalizado): ?>
                            <tr>
                                <td>
                                    <time datetime="<?= htmlspecialchars($pacienteHospitalizado['fecha_ingreso'], ENT_QUOTES, 'UTF-8') ?>">
                                        <?= htmlspecialchars($pacienteHospitalizado['fecha_ingreso_formateada'], ENT_QUOTES, 'UTF-8') ?>
                                    </time>
                                </td>
                                <td><?= htmlspecialchars($pacienteHospitalizado['nombre_paciente'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($pacienteHospitalizado['nombre_tutor'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($pacienteHospitalizado['motivo'] ?: 'Sin motivo registrado', ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($pacienteHospitalizado['responsable'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><span class="badge bg-warning text-dark p-2"><?= htmlspecialchars($pacienteHospitalizado['estado'], ENT_QUOTES, 'UTF-8') ?></span></td>
                            </tr>
                        <?php endforeach; ?>

                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No hay pacientes hospitalizados actualmente.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="text-end mt-3">
            <a href="<?= htmlspecialchars(url('hospitalizacion'), ENT_QUOTES, 'UTF-8') ?>">Ver todos</a>
        </div>
    </div>
</div>
<?php endif; ?>
<!-- Termina Pacientes Hospitalizados -->

<!-- Fin Contenido -->