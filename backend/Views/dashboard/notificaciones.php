<?php
/** @var list<array{id_consulta: int, fecha_hora: string, fecha_formateada: string, estado: string, tipo: string|null, motivo: string|null, nombre_paciente: string, nombre_tutor: string, detalle_url: string}> $notificaciones */
?>

<main class="container-fluid pt-4 px-4">
    <section class="bg-light rounded p-4" aria-labelledby="notificaciones-title">
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-3 mb-4">
            <div>
                <h1 class="h3 mb-1" id="notificaciones-title">Alertas de consultas</h1>
                <p class="text-muted mb-0">Consultas cuyo horario pasó y siguen pendientes o en curso.</p>
            </div>
            <a class="btn btn-outline-primary" href="<?= htmlspecialchars(url('dashboard'), ENT_QUOTES, 'UTF-8') ?>">Volver al dashboard</a>
        </div>

        <?php if ($notificaciones): ?>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th scope="col">Fecha y hora</th>
                            <th scope="col">Paciente</th>
                            <th scope="col">Tutor</th>
                            <th scope="col">Tipo y motivo</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($notificaciones as $notificacion): ?>
                            <tr>
                                <td><time datetime="<?= htmlspecialchars($notificacion['fecha_hora'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($notificacion['fecha_formateada'], ENT_QUOTES, 'UTF-8') ?></time></td>
                                <td><?= htmlspecialchars($notificacion['nombre_paciente'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($notificacion['nombre_tutor'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                                <td>
                                    <span><?= htmlspecialchars($notificacion['tipo'] ?: 'Consulta', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></span>
                                    <small class="d-block text-muted"><?= htmlspecialchars($notificacion['motivo'] ?: 'Sin motivo registrado', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></small>
                                </td>
                                <td><span class="badge text-bg-warning"><?= htmlspecialchars($notificacion['estado'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></span></td>
                                <td><a class="btn btn-sm btn-outline-primary" href="<?= htmlspecialchars($notificacion['detalle_url'], ENT_QUOTES, 'UTF-8') ?>">Ver consulta</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fa fa-circle-check text-success fs-2" aria-hidden="true"></i>
                <p class="fw-semibold mt-3 mb-1">No tienes consultas atrasadas</p>
                <p class="text-muted mb-0">Las consultas pendientes que superen su horario aparecerán aquí.</p>
            </div>
        <?php endif; ?>
    </section>
</main>