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

<div class="container-fluid pt-4 px-4">
    <div class="bg-light rounded p-4 mb-4">
        <div class="d-flex align-items-center gap-3 mb-3">
            <span class="btn-square bg-primary text-white"><i class="fa fa-stethoscope"></i></span>
            <div>
                <h1 class="h3 mb-1">Consultorio</h1>
                <p class="text-muted mb-0">Punto de partida para la atención clínica y el registro de consultas.</p>
            </div>
        </div>
        <p class="mb-0">Esta sección puede reunir la búsqueda del paciente, el registro de una consulta y el acceso a su historia clínica.</p>
    </div>

    <div class="row g-4">
        <div class="col-md-6 col-xl-4">
            <section class="bg-light rounded p-4 h-100">
                <h2 class="h5"><i class="fa fa-paw text-primary me-2"></i>Paciente</h2>
                <p class="text-muted mb-0">Comenzar buscando o seleccionando un paciente existente antes de abrir una nueva consulta.</p>
            </section>
        </div>
        <div class="col-md-6 col-xl-4">
            <section class="bg-light rounded p-4 h-100">
                <h2 class="h5"><i class="fa fa-notes-medical text-primary me-2"></i>Consulta</h2>
                <p class="text-muted mb-0">La tabla <code>CONSULTA</code> contiene fecha, tipo, motivo, estado y resultado final.</p>
            </section>
        </div>
        <div class="col-md-6 col-xl-4">
            <section class="bg-light rounded p-4 h-100">
                <h2 class="h5"><i class="fa fa-file-medical text-primary me-2"></i>Historia clínica</h2>
                <p class="text-muted mb-0">Al desarrollar el formulario, considerar los registros relacionados: anamnesis, examen, diagnóstico y tratamiento.</p>
            </section>
        </div>
    </div>

    <div class="bg-white border rounded p-4 mt-4">
        <h2 class="h5">Próximos pasos de desarrollo</h2>
        <ol class="mb-0">
            <li>Definir el flujo para seleccionar o registrar al paciente.</li>
            <li>Crear el formulario de consulta y validar sus campos requeridos.</li>
            <li>Guardar la consulta y sus secciones clínicas relacionadas.</li>
            <li>Permitir consultar y actualizar el estado del registro.</li>
        </ol>
    </div>
</div>
