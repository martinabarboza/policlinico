 <?php
//app/Views/dashboard/index.php
?>
 <!-- Empieza Contenido -->

            <!-- Botones Principales -->
            <div class="container-fluid pt-4 px-4">
                <div class="row g-4">
                    <div class="col-sm-6 col-xl-3">
                        <a href="blank.php" class="btn btn-light rounded d-flex align-items-center justify-content-between text-decoration-none p-5">
                            <i class="fa fa-plus-circle fa-3x text-primary"></i>
                            <div class="ms-3">
                                <h6 class="mb-0 text-dark">NUEVA FICHA</h6>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <a href="#" class="btn btn-light rounded d-flex align-items-center justify-content-between text-decoration-none p-5">
                            <i class="fa fa-calendar-day fa-3x text-success"></i>
                            <div class="ms-3">
                            <h6 class="mb-0 text-dark">CONSULTAS DIARIAS</h6>
                          <p class="mb-2 text-dark">34</p>

                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <a href="" class="btn btn-light rounded d-flex align-items-center justify-content-between text-decoration-none p-5">
                            <i class="fa fa-exclamation-triangle fa-3x text-danger"></i>
                            <div class="ms-3"> 
                                <h6 class="mb-0 text-dark">CONSULTAS INCOMPLETAS</h6>
                                <p class="mb-2 text-dark">5</p>
                               
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <a href="" class="btn btn-light rounded d-flex align-items-center justify-content-between text-decoration-none p-5">
                            <i class="fa fa-clock fa-3x text-warning"></i>
                            <div class="ms-3">
                             <h6 class="mb-0 text-dark">PRÓXIMAS CONSULTAS</h6>
                                <p class="mb-2 text-dark">Ver consultas</p>

                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <!-- Termina Botones Principales -->


            <!-- Tabla pacientes y agenda -->
            <div class="container-fluid pt-4 px-4">
                <div class="row g-4">
                    <div class="col-sm-12 col-xl-6">
                        <div class="bg-light text-center rounded p-4">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <h6 class="mb-0">Pacientes en Espera</h6>
                            </div>
                            <canvas id="patients"></canvas>
                        </div>
                    </div>
                    <div class="col-sm-12 col-xl-6">
                        <div class="bg-light text-center rounded p-4">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <h6 class="mb-0">Agenda del día</h6>

                            </div>
                            <canvas id="calendariodiario"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Termina Tabla pacientes y agenda -->


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
        <tr>
            <td>02 May 2026</td>
            <td>Relámpago</td>
            <td>Silva</td>
            <td>Herida en miembro anterior</td>
            <td>Dr. Pérez</td>
            <td><span class="badge bg-warning text-dark p-2">En observación</span></td>
            <td><a class="btn btn-sm btn-primary" href="">Ver ficha</a></td>
        </tr>
        <tr>

            <td>02 May 2026</td>
            <td>Mora</td>
            <td>Fernández</td>
            <td>Control postquirúrgico</td>
            <td>Dra. López</td>
            <td><span class="badge bg-info text-dark p-2">Seguimiento</span></td>
            <td><a class="btn btn-sm btn-primary" href="">Ver ficha</a></td>
        </tr>
        <tr>

            <td>01 May 2026</td>
            <td>Trueno</td>
            <td>García</td>
            <td>Cólico digestivo</td>
            <td>Dr. Martínez</td>
            <td><span class="badge bg-danger p-2">Crítico</span></td>
            <td><a class="btn btn-sm btn-primary" href="">Ver ficha</a></td>
        </tr>
        <tr>

            <td>01 May 2026</td>
            <td>Luna</td>
            <td>Rodríguez</td>
            <td>Reevaluación clínica</td>
            <td>Dra. Suárez</td>
            <td><span class="badge bg-success p-2">Estable</span></td>
            <td><a class="btn btn-sm btn-primary" href="">Ver ficha</a></td>
        </tr>
    </tbody>
</table>
                    </div>
                </div>
            </div>
            <!-- Termina Pacientes Hospitalizados -->

            <!-- Fin Contenido -->