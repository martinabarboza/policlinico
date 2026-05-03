<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Policlínico Veterinario - Sistema de Gestión</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">


    <!-- Iconos -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Bootstrap Stylesheet -->
    <link href="../activos/libs/bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS -->
    <link href="../activos/css/style.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid position-relative bg-white d-flex p-0">
        <!-- Cargador de Página -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <!--Fin Cargador de Página -->


        <!-- Empieza Sidebar -->
        <div class="sidebar pe-4 pb-3">
            <nav class="navbar bg-light navbar-light">
                <a href="index.php" class="navbar-brand mx-4 mb-3">
                    <h3 class="text-primary"><i class="fa fa-horse me-2"></i>PoliVet</h3>
                </a>
                <div class="d-flex align-items-center ms-4 mb-4">
                    <div class="position-relative">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-person-fill"></i>
                        </div> 
                        <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0">Martina Barboza</h6>
                        <span>Admin</span>
                    </div>
                </div>
                <div class="navbar-nav w-100">
                    <a href="index.php" class="nav-item nav-link active"><i class="fa fa-home me-2"></i>Inicio</a>
                    <a href="blank.php" class="nav-item nav-link"><i class="fa fa-search me-2"></i>Consultorio</a>
                    <a href="#" class="nav-item nav-link"><i class="fa fa-users me-2"></i>Tutores</a>
                    <a href="#" class="nav-item nav-link"><i class="fa fa-calendar-alt me-2"></i>Agenda</a>
                     <a href="#" class="nav-item nav-link"><i class="fa fa-hospital-alt me-2"></i>Hospitalización</a>
                                        <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-stethoscope me-2"></i>Solicitudes</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="#" class="dropdown-item">Solicitudes de historias</a>
                            <a href="#" class="dropdown-item">Solicitudes de citas</a>
                            <a href="#" class="dropdown-item">Solicitudes de exámenes</a>
                        </div>
                    </div>


                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-user-cog me-2"></i>Administración</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="#" class="dropdown-item">Ajustes de la Veterinaria</a>
                            <a href="#" class="dropdown-item">Usuarios</a>
                            <a href="#" class="dropdown-item">Propietarios</a>
                            <a href="#" class="dropdown-item">Variables</a>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
        <!-- Fin Sidebar -->

            <!-- Empieza Navbar -->
        <div id="content" class="content">

            <nav class="navbar navbar-expand bg-light navbar-light sticky-top px-4 py-0">
                <a href="index.php" class="navbar-brand d-flex d-lg-none me-4">
                    <h2 class="text-primary mb-0"><i class="fa fa-horse"></i></h2>
                </a>
                <a href="#" class="sidebar-toggler flex-shrink-0">
                    <i class="fa fa-ellipsis-v"></i>
                </a>
<form class="d-none d-md-flex ms-4">
    <div class="dropdown w-100">
        <input class="form-control border-0 dropdown-toggle"
               type="search"
               placeholder="Buscar paciente..."
               data-bs-toggle="dropdown">
        <div class="dropdown-menu w-100 bg-white border-0 shadow">
            <a href="#" class="dropdown-item">Buscar por paciente</a>
            <a href="#" class="dropdown-item">Buscar por tutor</a>
            <a href="#" class="dropdown-item">Buscar por médico</a>
        </div>
    </div>
</form>
            
                <div class="navbar-nav align-items-center ms-auto">
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fa fa-envelope me-lg-2"></i>
                            <span class="d-none d-lg-inline-flex">Mensaje</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                            <a href="#" class="dropdown-item">
                                <div class="d-flex align-items-center">
                                                          <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-person-fill"></i>
                        </div> 
                                    <div class="ms-2">
                                        <h6 class="fw-normal mb-0">Bryan te ha mandado un mensaje</h6>
                                        <small>Ahora</small>
                                    </div>
                                </div>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item">
                                <div class="d-flex align-items-center">
                                                          <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-person-fill"></i>
                        </div> 
                                    <div class="ms-2">
                                        <h6 class="fw-normal mb-0">Andres te ha mandado un mensaje</h6>
                                        <small>hace 2 minutos</small>
                                    </div>
                                </div>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item">
                                <div class="d-flex align-items-center">
                                                           <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-person-fill"></i>
                        </div> 
                                    <div class="ms-2">
                                        <h6 class="fw-normal mb-0">Sofía te ha mandado un mensaje</h6>
                                        <small>hace 15 minutos</small>
                                    </div>
                                </div>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item text-center">Ver todos los mensajes</a>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fa fa-bell me-lg-2"></i>
                            <span class="d-none d-lg-inline-flex">Notificaciones</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                            <a href="#" class="dropdown-item">
                                <h6 class="fw-normal mb-0">Perfil actualizado</h6>
                                <small>hace 15 minutos</small>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item">
                                <h6 class="fw-normal mb-0">Nuevo usuario agregado</h6>
                                <small>hace 2 días</small>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item">
                                <h6 class="fw-normal mb-0">Contraseña cambiada</h6>
                                <small>hace 1 mes</small>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item text-center">Ver todas las notificaciones</a>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                                                   <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-person-fill"></i>
                        </div> 
                            <span class="d-none d-lg-inline-flex">Martina Barboza</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                            <a href="#" class="dropdown-item">Mi Perfil</a>
                            <a href="#" class="dropdown-item">Configuración</a>
                            <a href="#" class="dropdown-item">Cerrar Sesión</a>
                        </div>
                    </div>
                </div>
            </nav>
            <!-- Fin Navbar -->

            <!-- Empieza 404 -->
            <div class="container-fluid pt-4 px-4">
                <div class="row vh-100 bg-light rounded align-items-center justify-content-center mx-0">
                    <div class="col-md-6 text-center p-4">
                        <i class="bi bi-exclamation-triangle display-1 text-primary"></i>
                        <h1 class="display-1 fw-bold">404</h1>
                        <h1 class="mb-4">Página No Encontrada</h1>
                        <p class="mb-4">Lo sentimos, la página que buscas no existe en nuestro sitio web.
                            ¿Tal vez regresar a la página de inicio o intentar una búsqueda?</p>
                        <a class="btn btn-primary rounded-pill py-3 px-5" href="">Volver a la Página Principal</a>
                    </div>
                </div>
            </div>
            <!-- Termina 404 -->

            <!-- Empieza Footer -->
            <div class="container-fluid pt-4 px-4">
                <div class="bg-light rounded-top p-4">
                    <div class="row">
                        <div class="col-12 col-sm-6 text-center text-sm-start">
                            &copy; <a href="#">Policlínico Veterinario</a>, Todos los derechos reservados. 
                        </div>
                        <div class="col-12 col-sm-6 text-center text-sm-end">
                            Hecho por <a href="#">Petcraft</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Termina Footer -->
        </div>


    </div>
   <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-4.0.0.min.js"></script>
    <!-- Bootstrap JS -->
<script src="../activos/libs/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>

    <!--Javascript -->
    <script src="../activos/js/main.js"></script>
</body>

</html>