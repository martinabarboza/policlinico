<?php if ($errorBusqueda !== null || $busqueda !== null): ?>
    <div class="container-fluid pt-4 px-4">
        <section class="bg-white border rounded p-4" aria-labelledby="resultado-pacientes">
            <h2 class="h5" id="resultado-pacientes">Resultados de pacientes</h2>
            <?php if ($errorBusqueda !== null): ?>
                <p class="text-danger mb-0" role="alert"><?= htmlspecialchars($errorBusqueda, ENT_QUOTES, 'UTF-8') ?></p>
            <?php elseif ($pacientes): ?>
                <p class="text-muted">Coincidencias para “<?= htmlspecialchars($busqueda, ENT_QUOTES, 'UTF-8') ?>”.</p>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead><tr><th>Paciente</th><th>Chip</th><th>Tutor</th></tr></thead>
                        <tbody>
                            <?php foreach ($pacientes as $paciente): ?>
                                <tr>
                                    <td><?= htmlspecialchars($paciente['nombre'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($paciente['chip'] ?: 'Sin chip', ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($paciente['tutor'], ENT_QUOTES, 'UTF-8') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted mb-0">No se encontraron pacientes para “<?= htmlspecialchars($busqueda, ENT_QUOTES, 'UTF-8') ?>”.</p>
            <?php endif; ?>
        </section>
    </div>
<?php endif; ?>

<?php if ($errorFiltros !== null): ?>
    <div class="container-fluid pt-4 px-4">
        <p class="alert alert-danger" role="alert"><?= htmlspecialchars($errorFiltros, ENT_QUOTES, 'UTF-8') ?></p>
    </div>
<?php endif; ?>

<?php
/**
 * Vista: Centro de consulta.
 *
 * Variables que debe entregar el controlador:
 *   $urlCentro          string  URL base de la sección
 *   $filtros            array   ['q' => '', 'estado' => '', 'tipo' => '', 'paciente' => '']
 *   $resumen            array   ['total' => 0, 'en_curso' => 0, 'pendientes' => 0, 'hospitalizaciones' => 0]
 *   $consultas          array   cada item: id_consulta, fecha_hora, tipo, motivo, estado,
 *                               id_paciente, paciente, chip, tutor
 *   $hospitalizados     array   cada item: paciente, motivo, ingreso
 *   $proximosControles  array   cada item: paciente, fecha, motivo
 */

$esc = function ($valor) {
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
};

$estados = ['Pendiente', 'En curso', 'Finalizada'];
$tipos = ['Consulta general', 'Consulta equina', 'Control', 'Hospitalización'];
$claseEstado = [
    'Pendiente'  => 'centro-estado--pendiente',
    'En curso'   => 'centro-estado--en-curso',
    'Finalizada' => 'centro-estado--finalizada',
];
?>
<section class="centro-consulta">
    <?php if (is_string($mensajeExito) && $mensajeExito !== ''): ?>
        <div class="alert alert-success" role="status"><?= $esc($mensajeExito) ?></div>
    <?php endif; ?>
    <?php if ($mensajeEstado !== null): ?>
        <div class="alert alert-success" role="status"><?= $esc($mensajeEstado) ?></div>
    <?php endif; ?>
    <?php if ($errorEstado !== null): ?>
        <div class="alert alert-danger" role="alert"><?= $esc($errorEstado) ?></div>
    <?php endif; ?>

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Centro de consulta</h1>
            <p class="text-muted mb-0">Vista general de la actividad clínica y búsqueda de consultas.</p>
        </div>
        <?php if (in_array(strtoupper(trim(Auth::rol() ?? '')), ['ESTUDIANTE', 'PROFESOR', 'DIRECTOR'], true)): ?>
            <div class="d-flex flex-wrap gap-2">
                <a class="btn btn-outline-primary" href="/pacientes/crear">+ Registrar paciente</a>
                <a class="btn btn-primary" href="/centro-consultas/nueva">+ Nueva consulta</a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Resumen -->
    <div class="row row-cols-2 row-cols-lg-4 g-3 mb-4">
        <div class="col">
            <div class="card centro-card h-100"><div class="card-body">
                <div class="small text-muted">Consultas registradas</div>
                <div class="centro-kpi"><?= (int) $resumen['total'] ?></div>
            </div></div>
        </div>
        <div class="col">
            <div class="card centro-card h-100"><div class="card-body">
                <div class="small text-muted">En curso</div>
                <div class="centro-kpi centro-kpi--en-curso"><?= (int) $resumen['en_curso'] ?></div>
            </div></div>
        </div>
        <div class="col">
            <div class="card centro-card h-100"><div class="card-body">
                <div class="small text-muted">Pendientes</div>
                <div class="centro-kpi centro-kpi--pendiente"><?= (int) $resumen['pendientes'] ?></div>
            </div></div>
        </div>
        <div class="col">
            <div class="card centro-card h-100"><div class="card-body">
                <div class="small text-muted">Hospitalizaciones activas</div>
                <div class="centro-kpi centro-kpi--hosp"><?= (int) $resumen['hospitalizaciones'] ?></div>
            </div></div>
        </div>
    </div>

    <!-- Búsqueda y filtros (GET) -->
    <form class="card centro-card mb-4" method="get" action="<?= $esc($urlCentro) ?>">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-12 col-lg">
                    <label class="form-label small text-muted" for="filtro-q">Buscar</label>
                    <input class="form-control" type="text" id="filtro-q" name="q"
                           placeholder="Paciente, chip, tutor o documento"
                           value="<?= $esc($filtros['q']) ?>">
                </div>
                <div class="col-6 col-lg-2">
                    <label class="form-label small text-muted" for="filtro-estado">Estado</label>
                    <select class="form-select" id="filtro-estado" name="estado">
                        <option value="">Todos</option>
                        <?php foreach ($estados as $estado): ?>
                            <option value="<?= $esc($estado) ?>"<?= $filtros['estado'] === $estado ? ' selected' : '' ?>><?= $esc($estado) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-6 col-lg-2">
                    <label class="form-label small text-muted" for="filtro-tipo">Tipo</label>
                    <select class="form-select" id="filtro-tipo" name="tipo">
                        <option value="">Todos</option>
                        <?php foreach ($tipos as $tipo): ?>
                            <option value="<?= $esc($tipo) ?>"<?= $filtros['tipo'] === $tipo ? ' selected' : '' ?>><?= $esc($tipo) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12 col-lg-auto d-flex gap-2">
                    <button class="btn btn-outline-primary" type="submit">Buscar</button>
                    <a class="btn btn-outline-secondary" href="<?= $esc($urlCentro) ?>">Limpiar</a>
                </div>
            </div>
            <?php if ($filtros['paciente'] !== ''): ?>
                <input type="hidden" name="paciente" value="<?= (int) $filtros['paciente'] ?>">
            <?php endif; ?>
        </div>
    </form>

    <div class="row g-4">

        <!-- Tabla de consultas -->
        <div class="col-lg-8">
            <div class="card centro-card">
                <?php if ($filtros['paciente'] !== ''): ?>
                    <?php
                    $filtrosSinPaciente = $filtros;
                    unset($filtrosSinPaciente['paciente']);
                    $urlTodasConsultas = $urlCentro . '?' . http_build_query($filtrosSinPaciente);
                    ?>
                    <div class="d-flex justify-content-between align-items-center gap-3 border-bottom px-3 py-2">
                        <span class="small text-muted">Consultas del paciente seleccionado</span>
                        <a class="small" href="<?= $esc($urlTodasConsultas) ?>">Ver todas</a>
                    </div>
                <?php endif; ?>
                <?php if ($consultas): ?>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 centro-tabla">
                            <thead>
                                <tr>
                                    <th scope="col">Fecha</th>
                                    <th scope="col">Paciente</th>
                                    <th scope="col">Tipo y motivo</th>
                                    <th scope="col">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($consultas as $c): ?>
                                    <?php $marca = strtotime($c['fecha_hora']); ?>
                                    <?php
                                    $parametrosPaciente = $filtros;
                                    $parametrosPaciente['paciente'] = (string) (int) $c['id_paciente'];
                                    $urlConsultasPaciente = $urlCentro . '?' . http_build_query($parametrosPaciente);
                                    ?>
                                    <tr>
                                        <td>
                                            <?= $esc(date('d/m/Y', $marca)) ?>
                                            <small class="d-block text-muted"><?= $esc(date('H:i', $marca)) ?></small>
                                        </td>
                                        <td>
                                            <a class="fw-medium" href="<?= $esc($urlConsultasPaciente) ?>">
                                                <?= $esc($c['paciente'] ?? 'Nombre no encontrado') ?>
                                            </a>
                                            <small class="d-block text-muted">
                                                <?= $esc($c['chip'] ?? 'Sin registrar') ?> · Tutor: <?= $esc($c['tutor'] ?? 'Tutor no encontrado') ?>
                                            </small>
                                            <a class="btn btn-sm btn-outline-primary mt-2"
                                               href="<?= $esc($urlCentro . '?consulta=' . (int) $c['id_consulta']) ?>">
                                                Ver consulta
                                            </a>
                                        </td>
                                        <td>
                                            <?= $esc($c['tipo'] ?? 'Sin registrar') ?>
                                            <small class="d-block text-muted"><?= $esc($c['motivo'] ?? 'Sin registrar') ?></small>
                                        </td>
                                        <td>
                                            <span class="centro-estado <?= $esc($claseEstado[$c['estado']] ?? '') ?>">
                                                <?= $esc($c['estado'] ?? 'Sin registrar') ?>
                                            </span>
                                            <?php if ($puedeCambiarEstado): ?>
                                            <form class="mt-2" method="post" action="/centro-consultas/actualizar-estado">
                                                <input type="hidden" name="csrf_token" value="<?= $esc($csrfToken) ?>">
                                                <input type="hidden" name="consulta_id" value="<?= (int) $c['id_consulta'] ?>">
                                                <input type="hidden" name="vista" value="listado">
                                                <input type="hidden" name="return_q" value="<?= $esc($filtros['q']) ?>">
                                                <input type="hidden" name="return_estado" value="<?= $esc($filtros['estado']) ?>">
                                                <input type="hidden" name="return_tipo" value="<?= $esc($filtros['tipo']) ?>">
                                                <input type="hidden" name="return_paciente" value="<?= $esc($filtros['paciente']) ?>">
                                                <label class="visually-hidden" for="estado-consulta-<?= (int) $c['id_consulta'] ?>">Nuevo estado de consulta #<?= (int) $c['id_consulta'] ?></label>
                                                <select class="form-select form-select-sm" id="estado-consulta-<?= (int) $c['id_consulta'] ?>" name="estado" required>
                                                    <option value="" selected disabled>Cambiar estado...</option>
                                                    <?php foreach ($estados as $estado): ?>
                                                        <option value="<?= $esc($estado) ?>"><?= $esc($estado) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <button class="btn btn-sm btn-outline-primary mt-1" type="submit">Guardar</button>
                                            </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="card-body text-muted">No se encontraron consultas con esos filtros.</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Columna lateral -->
        <div class="col-lg-4">
            <div class="card centro-card mb-4">
                <h2 class="h6 card-header bg-transparent py-3">Hospitalizados activos</h2>
                <ul class="list-group list-group-flush">
                    <?php if ($hospitalizados): ?>
                        <?php foreach ($hospitalizados as $h): ?>
                            <li class="list-group-item">
                                <strong><?= $esc($h['paciente']) ?></strong>
                                <small class="d-block text-muted">
                                    <?= $esc($h['motivo'] ?? 'Sin registrar') ?> · ingreso <?= $esc(date('d/m', strtotime($h['ingreso']))) ?>
                                </small>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="list-group-item text-muted">No hay pacientes hospitalizados.</li>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="card centro-card">
                <h2 class="h6 card-header bg-transparent py-3">Próximos controles</h2>
                <ul class="list-group list-group-flush">
                    <?php if ($proximosControles): ?>
                        <?php foreach ($proximosControles as $p): ?>
                            <li class="list-group-item">
                                <strong><?= $esc($p['paciente']) ?></strong>
                                <small class="d-block text-muted">
                                    <?= $esc(date('d/m/Y', strtotime($p['fecha']))) ?> · <?= $esc($p['motivo'] ?? 'Sin registrar') ?>
                                </small>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="list-group-item text-muted">No hay controles programados.</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

    </div>
</section>
