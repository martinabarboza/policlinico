<?php

/**
 * Listado invocado desde las tarjetas del dashboard.
 * `consultas-diarias`: todas las consultas de hoy; `consultas-incompletas`: Pendiente
 * o En curso de cualquier fecha. El filtro se decide en el modelo antes de renderizar.
 * @var string $titulo
 * @var string $subtitulo
 * @var list<array{id_consulta: int, fecha_hora: string, fecha_formateada: string, detalle_url: string, tipo: string|null, estado: string|null, clase_estado: string, motivo: string|null, nombre_paciente: string, nombre_tutor: string}> $consultas
 */
?>
<div class="container-fluid pt-4 px-4">
    <div class="bg-light rounded p-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h1 class="h4 mb-1"><?= htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8') ?></h1>
                <p class="text-muted mb-0"><?= htmlspecialchars($subtitulo, ENT_QUOTES, 'UTF-8') ?></p>
            </div>
            <a href="dashboard" class="btn btn-outline-primary">Volver al dashboard</a>
        </div>

        <?php if ($consultas): ?>
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead>
                        <tr>
                            <th scope="col">Fecha y hora</th>
                            <th scope="col">Paciente</th>
                            <th scope="col">Tutor</th>
                            <th scope="col">Tipo</th>
                            <th scope="col">Motivo</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($consultas as $consulta): ?>
                            <tr>
                                <td><?= htmlspecialchars($consulta['fecha_formateada'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($consulta['nombre_paciente'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($consulta['nombre_tutor'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($consulta['tipo'] ?: 'Consulta', ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($consulta['motivo'] ?: 'Sin motivo registrado', ENT_QUOTES, 'UTF-8') ?></td>
                                <td>
                                    <span class="estado-inicio <?= htmlspecialchars($consulta['clase_estado'], ENT_QUOTES, 'UTF-8') ?>">
                                        <?= htmlspecialchars($consulta['estado'] ?: 'Sin estado', ENT_QUOTES, 'UTF-8') ?>
                                    </span>
                                </td>
                                <td>
                                    <a class="btn btn-sm btn-outline-primary"
                                       href="<?= htmlspecialchars($consulta['detalle_url'], ENT_QUOTES, 'UTF-8') ?>">
                                        Ver consulta
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-muted text-center py-4 mb-0">No hay consultas para mostrar.</p>
        <?php endif; ?>
    </div>
</div>
