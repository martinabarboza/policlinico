<div class="container-fluid pt-4 px-4">
    <div class="bg-light rounded p-4 mb-4">
        <div class="d-flex align-items-center gap-3 mb-3">
            <span class="btn-square bg-primary text-white"><i class="fa fa-calendar-alt"></i></span>
            <div>
                <h1 class="h3 mb-1">Agenda</h1>
                <p class="text-muted mb-0">Punto de partida para organizar y consultar las consultas por fecha y horario.</p>
            </div>
        </div>
        <p class="mb-0">La fecha y hora de cada turno se registra en <code>CONSULTA.fecha_hora_Consulta</code>.</p>
    </div>

    <div class="row g-4">
        <div class="col-md-6 col-xl-4">
            <section class="bg-light rounded p-4 h-100">
                <h2 class="h5"><i class="fa fa-calendar-day text-primary me-2"></i>Vista por fecha</h2>
                <p class="text-muted mb-0">Iniciar con un selector de fecha y una lista de consultas ordenadas por horario.</p>
            </section>
        </div>
        <div class="col-md-6 col-xl-4">
            <section class="bg-light rounded p-4 h-100">
                <h2 class="h5"><i class="fa fa-clipboard-list text-primary me-2"></i>Datos del turno</h2>
                <p class="text-muted mb-0">Mostrar paciente, tutor, tipo de consulta, motivo y estado del proceso.</p>
            </section>
        </div>
        <div class="col-md-6 col-xl-4">
            <section class="bg-light rounded p-4 h-100">
                <h2 class="h5"><i class="fa fa-filter text-primary me-2"></i>Estados</h2>
                <p class="text-muted mb-0">Usar <code>Pendiente</code>, <code>En curso</code>, <code>Finalizada</code> y <code>Cancelada</code> de forma consistente con el dashboard.</p>
            </section>
        </div>
    </div>

    <div class="bg-white border rounded p-4 mt-4">
        <h2 class="h5">Próximos pasos de desarrollo</h2>
        <ol class="mb-0">
            <li>Definir vistas diaria y semanal y la navegación entre fechas.</li>
            <li>Crear o reprogramar consultas conservando paciente, fecha y hora.</li>
            <li>Conectar cada turno con su paciente y tutor y ofrecer filtros por estado.</li>
        </ol>
    </div>
</div>
