<?php if ($errorBusqueda !== null || $busqueda !== null): ?>
    <div class="container-fluid pt-4 px-4">
        <section class="bg-white border rounded p-4" aria-labelledby="resultado-tutores">
            <h2 class="h5" id="resultado-tutores">Resultados de tutores</h2>
            <?php if ($errorBusqueda !== null): ?>
                <p class="text-danger mb-0" role="alert"><?= htmlspecialchars($errorBusqueda, ENT_QUOTES, 'UTF-8') ?></p>
            <?php elseif ($tutores): ?>
                <p class="text-muted">Coincidencias para “<?= htmlspecialchars($busqueda, ENT_QUOTES, 'UTF-8') ?>”.</p>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead><tr><th>Tutor</th><th>Documento</th><th>Teléfono</th><th>Email</th></tr></thead>
                        <tbody>
                            <?php foreach ($tutores as $tutor): ?>
                                <tr>
                                    <td><?= htmlspecialchars($tutor['nombre'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($tutor['documento'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($tutor['telefono'] ?: 'Sin teléfono', ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($tutor['email'] ?: 'Sin email', ENT_QUOTES, 'UTF-8') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted mb-0">No se encontraron tutores para “<?= htmlspecialchars($busqueda, ENT_QUOTES, 'UTF-8') ?>”.</p>
            <?php endif; ?>
        </section>
    </div>
<?php endif; ?>

<div class="container-fluid pt-4 px-4">
    <div class="bg-light rounded p-4 mb-4">
        <div class="d-flex align-items-center gap-3 mb-3">
            <span class="btn-square bg-primary text-white"><i class="fa fa-users"></i></span>
            <div>
                <h1 class="h3 mb-1">Tutores</h1>
                <p class="text-muted mb-0">Punto de partida para administrar los datos de contacto y los pacientes asociados.</p>
            </div>
        </div>
        <p class="mb-0">Cada paciente pertenece a un tutor mediante <code>PACIENTE.id_Tutor</code>.</p>
    </div>

    <div class="row g-4">
        <div class="col-md-6 col-xl-4">
            <section class="bg-light rounded p-4 h-100">
                <h2 class="h5"><i class="fa fa-address-card text-primary me-2"></i>Directorio</h2>
                <p class="text-muted mb-0">Preparar una lista de tutores con búsqueda por nombre, documento o teléfono.</p>
            </section>
        </div>
        <div class="col-md-6 col-xl-4">
            <section class="bg-light rounded p-4 h-100">
                <h2 class="h5"><i class="fa fa-user-plus text-primary me-2"></i>Registro</h2>
                <p class="text-muted mb-0">La tabla <code>TUTOR</code> almacena documento, nombre, teléfono, dirección, email y notas.</p>
            </section>
        </div>
        <div class="col-md-6 col-xl-4">
            <section class="bg-light rounded p-4 h-100">
                <h2 class="h5"><i class="fa fa-paw text-primary me-2"></i>Pacientes</h2>
                <p class="text-muted mb-0">Desde el detalle de un tutor se podrá consultar qué pacientes tiene asociados.</p>
            </section>
        </div>
    </div>

    <div class="bg-white border rounded p-4 mt-4">
        <h2 class="h5">Próximos pasos de desarrollo</h2>
        <ol class="mb-0">
            <li>Crear el listado y la búsqueda de tutores.</li>
            <li>Agregar alta, edición y validación de documento y datos de contacto.</li>
            <li>Mostrar los pacientes relacionados usando la clave <code>id_Tutor</code>.</li>
        </ol>
    </div>
</div>
