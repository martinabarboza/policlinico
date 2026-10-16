<div class="container-fluid pt-4 px-4">
    <div class="bg-light rounded p-4 mb-4">
        <div class="d-flex align-items-center gap-3 mb-3">
            <span class="btn-square bg-primary text-white"><i class="fa fa-hospital-alt"></i></span>
            <div>
                <h1 class="h3 mb-1">Hospitalización</h1>
                <p class="text-muted mb-0">Punto de partida para administrar ingresos, seguimiento y altas de pacientes internados.</p>
            </div>
        </div>
        <p class="mb-0">El registro se guarda en <code>HOSPITALIZACION</code> y se vincula a un paciente mediante <code>id_Paciente</code>.</p>
    </div>

    <div class="row g-4">
        <div class="col-md-6 col-xl-4">
            <section class="bg-light rounded p-4 h-100">
                <h2 class="h5"><i class="fa fa-sign-in-alt text-primary me-2"></i>Ingreso</h2>
                <p class="text-muted mb-0">Registrar paciente, fecha de ingreso, motivo, responsable y consulta asociada cuando corresponda.</p>
            </section>
        </div>
        <div class="col-md-6 col-xl-4">
            <section class="bg-light rounded p-4 h-100">
                <h2 class="h5"><i class="fa fa-heartbeat text-primary me-2"></i>Seguimiento</h2>
                <p class="text-muted mb-0">Consultar internaciones activas y definir qué datos clínicos actualizar durante la estadía.</p>
            </section>
        </div>
        <div class="col-md-6 col-xl-4">
            <section class="bg-light rounded p-4 h-100">
                <h2 class="h5"><i class="fa fa-sign-out-alt text-primary me-2"></i>Alta</h2>
                <p class="text-muted mb-0">Al egresar, registrar <code>fecha_egreso_Hospitalizacion</code>; el dashboard considera activa una fila sin egreso y con estado <code>Activa</code>.</p>
            </section>
        </div>
    </div>

    <div class="bg-white border rounded p-4 mt-4">
        <h2 class="h5">Próximos pasos de desarrollo</h2>
        <ol class="mb-0">
            <li>Construir el listado de internaciones activas.</li>
            <li>Agregar el formulario de ingreso y validación del paciente existente.</li>
            <li>Implementar seguimiento y cierre con fecha de egreso.</li>
            <li>Conservar el historial de hospitalizaciones anteriores.</li>
        </ol>
    </div>
</div>
