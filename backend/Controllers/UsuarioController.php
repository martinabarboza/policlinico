<?php
require_once APP_PATH . '/Helpers/UsuarioClase.php';

/** Gestiona cuentas del sistema; todas las acciones requieren permiso de Dirección. */
class UsuarioController extends Controller
{
    /** Lista cuentas y permite filtrar entre activas e inactivas. */
    public function index(): void {
        Auth::requireLogin();
        Auth::requirePermiso('admin.total');

        $busqueda = isset($_GET['busqueda']) ? trim($_GET['busqueda']) : '';
        $checkinactivos = isset($_GET['checkestado']) ? true : false;

        $this->view('usuarios/index', [
            'tituloPagina' => 'Usuarios',
            'activeMenu'   => 'usuarios',
            //'roles'        => $this->model('Rol')->activos(), <= No me acuerdo que era esto pero lo dejo comentado por si acaso.
            'usuarios'     => $this->model('UsuarioModel')->listadoUsuarios($busqueda, $checkinactivos),
        ], 'app', ['app-layout.css', 'dashboard.css'], ['dashboard.js'], ['bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js', 'jquery-4.0.0.min.js.js']);
    }

    /** Vuelve a renderizar el formulario correspondiente conservando el error. */
    private function returnPorError(string $form, ?array $usuarioVerificacion, string $mensajeError): void {
        $this->view('usuarios/'. $form, [
            'tituloPagina' => 'Modificar Usuario',
            'activeMenu' => 'usuarios',
            'usuarioEdit' => $usuarioVerificacion,
            'error' => $mensajeError
        ], 'app', ['app-layout.css', 'dashboard.css'], ['dashboard.js'], ['bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js', 'jquery-4.0.0.min.js.js']);
    }

    public function perfilUsuario(): void {
        Auth::requireLogin();

        $this->view('usuarios/perfil', [
            'tituloPagina' => 'Perfil',
            'activeMenu' => 'perfil',
            'usuario' => Auth::user()
        ], 'app', ['app-layout.css', 'dashboard.css'], ['dashboard.js'], ['bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js', 'jquery-4.0.0.min.js.js']);
    }
    
        /** Busca una cuenta por cédula y presenta su formulario de edición. */
    public function modificarUsuario(): void {
        Auth::requireLogin();
        Auth::requirePermiso('admin.total');

        if (!isset($_GET['cedula'])) {
            header('Location: /usuarios?error=Usuario_No_Encontrado');
            exit;
        }

        $cedula = trim((string) $_GET['cedula']);

        // Valida que la cedula tenga exactamente 8 números enteros
        if (!preg_match('/^\d{8}$/', $cedula)) {
            $this->returnPorError('modificarUsuario', null, 'La cédula debe contener exactamente 8 números.');
            return;
        }

        $usuarioEdit = $this->model('UsuarioModel')->buscarPorCedula($cedula);

        if (!$usuarioEdit) {
            header('Location: /usuarios?error=Usuario_No_Encontrado');
            exit;
        }

        if ($usuarioEdit) {
            $this->view('usuarios/modificarUsuario', [
                'tituloPagina' => 'Modificar Usuario',
                'activeMenu' => 'usuarios',
                'usuarioEdit' => $usuarioEdit
            ], 'app', ['app-layout.css', 'dashboard.css'], ['dashboard.js'], ['bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js', 'jquery-4.0.0.min.js.js']);
        }
    }

    /** Actualiza la cuenta indicada por su cédula anterior; el hash cambia solo si se envía contraseña. */
    public function actualizarUsuario(): void {

        Auth::requireLogin();
        Auth::requirePermiso('admin.total');

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuarioObj = new UsuarioObj();

            if (!isset($_POST['cedulaOld']) || !isset($_POST['cedula'])) {
                header('Location: /usuarios?error=Falta_identificador');
                exit;
            }

            $cedulaOld = /*(int)*/ $_POST['cedulaOld']; // Guarda la cédula antigua para encontrar al usuario en la bd.
            $cedulaNueva = trim($_POST['cedula']); // Guarda la cédula nueva para actualizarla en la bd.
            $emailNuevo = trim($_POST['email']); // Guarda el email nuevo para actualizarlo en la bd.

            // Comprueba si el usuario existe antes de intentar modificarlo.
            $usuarioActual = $this->model('UsuarioModel')->buscarPorCedula($cedulaOld);


            /*if (!$usuarioActual){
                die("No existe: ". $cedulaOld); <-- Es para probar si busca correctamente la cedula.
            }*/

            // Redirige si el usuario no existe.
            if (!$usuarioActual) {
                header('Location: /usuarios?error=Usuario_No_Encontrado');
                exit;
            }

            // Validaciones de cédula y correo electrónico.
            if (!preg_match('/^\d{8}$/', $cedulaNueva)) {
                $this->returnPorError('modificarUsuario', $usuarioActual, 'La cédula debe contener exactamente 8 números');
                return;
            }
            if (!filter_var($emailNuevo, FILTER_VALIDATE_EMAIL)) {
                $this->returnPorError('modificarUsuario', $usuarioActual, 'El correo electrónico no es válido');
                return;
            }
            
            $usuarioObj->setCedulaUsuario($cedulaNueva);
            $usuarioObj->setNombreUsuario($_POST['nombre']);
            $usuarioObj->setApellidoUsuario($_POST['apellido']);
            $usuarioObj->setRolUsuario($_POST['rol']);
            $usuarioObj->setEmailUsuario($emailNuevo);

            // Mantiene la contraseña actual si el campo está vacío.
            if (!empty($_POST['passw'])) {
                $usuarioObj->setPasswUsuario(password_hash($_POST['passw'], PASSWORD_DEFAULT));
            }
            else {
                $usuarioObj->setPasswUsuario($usuarioActual['passw_Usuario']);
            }

            // Se llama al modelo para actualizar el usuario.
            $model = $this->model('UsuarioModel');

            // Comprueba si se modificó correctamente y redirige a la página de usuarios con un mensaje de éxito o error.
            if ($model->modificarUsuario($usuarioObj, $cedulaOld)) {
                header('Location: /usuarios?success=Usuario_Modificado_Correctamente');
                exit;
            }
            else 
            {
                header('Location: /usuarios?error=Error_al_Modificar_Usuario');
                exit;
            }
        }
    }

    /** Procesa el alta por POST o muestra el formulario vacío por GET. */
    public function crearUsuario(): void {
        Auth::requireLogin();
        Auth::requirePermiso('admin.total');
        
        // Valida que la solicitud sea POST y que todos los campos requeridos estén presentes.
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
           if (!isset($_POST['cedula'], $_POST['nombre'], $_POST['apellido'], $_POST['rol'], $_POST['email'], $_POST['passw'])) {
                $this->returnPorError('crearUsuario', null, 'Por favor, completa todos los campos requeridos.');
                return;
            }
            
            // Valida y procesa los datos del formulario.
            $cedula = trim($_POST['cedula']);
            $nombre = trim($_POST['nombre']);
            $apellido = trim($_POST['apellido']);
            $rol = trim($_POST['rol']);
            $email = trim($_POST['email']);
            $passw = password_hash($_POST['passw'], PASSWORD_DEFAULT);

            // Validaciones de cédula y correo electrónico.
            if (!preg_match('/^\d{8}$/', $cedula)) {
                $this->returnPorError('crearUsuario', null, 'La cédula debe contener exactamente 8 números.');
                return;
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->returnPorError('crearUsuario', null, 'El correo electrónico no es válido.');
                return;
            }
            if (!$passw || $passw === ''){
                $this->returnPorError('crearUsuario', null, 'La contraseña no debe estar vacía');
                return;
            }

            $model = $this->model('UsuarioModel');
            // Comprueba si ya existe un usuario con esa cedula antes de crearlo.
            if ($model->buscarPorCedula($cedula)) {
                $this->returnPorError('crearUsuario', null, 'Ya existe un usuario con esa cédula.');
                return;
            }

            // Comprueba si se crea correctamente el usuario y redirige a la pagina de usuarios con un mensaje de éxito o error.
            if ($model->crearUsuarioNuevo($cedula, $nombre, $apellido, $rol, $email, $passw)) {
                header('Location: /usuarios?success=Usuario_Creado_Correctamente');
                exit;
            } 
            else 
            {
                $this->returnPorError('crearUsuario', null, 'Fallo al Crear Usuario');
                return;
            }
        } 
        else { 
            // Si  no es POST, muestra el formulario de creación de usuario.
        $this->view('usuarios/crearUsuario', [
            'tituloPagina' => 'Crear Nuevo Usuario',
            'activeMenu' => 'usuarios'
            ], 'app', ['app-layout.css', 'dashboard.css'], ['dashboard.js'], ['bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js', 'jquery-4.0.0.min.js.js']
        );}
    }

    /** Activa o desactiva una cuenta sin eliminar sus registros relacionados. */
    public function cambiarEstadoUsuario() {
        Auth::requireLogin();
        Auth::requirePermiso('admin.total');

        // Valida que la solicitud sea POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Validaciones
            if (!isset($_POST['cedula'])) {
                header('Location: /usuarios?error=Error_en_la_Cedula');
                exit;
            }
            if (!isset($_POST['estado'])) {
                header('Location: /usuarios?error=Error_en_el_Estado');
                exit;
            }
            if ($_POST['estado'] !== 'activo' && $_POST['estado'] !== 'inactivo'){
                header('Location: /usuarios?error=Error_en_el_Estado');
                exit;
            }
            
            // Obtiene la cédula y el estado del usuario a cambiar desde POST
            $cedula = trim($_POST['cedula']);
            $estado = trim($_POST['estado']);

            // Se llama al modelo
            $model = $this->model('UsuarioModel');

            // Comprueba si el usuario existe antes de intentar cambiar su estado.
            if (!$model->buscarPorCedula($cedula)) {
                header('Location: /usuarios?error=No_Existe_el_Usuario');
                exit;
            }

            // Se comprueba si se cambia correctamente el estado antes de redirigir a la pagina de usuarios con un mensaje de exito o error
            if ($model->cambiarStatusUsuario($cedula, $estado)) {
                header('Location: /usuarios?success=Estado_del_Usuario_Cambiado');
                exit;
            } 
            else {
                header('Location: /usuarios?error=Fallo_al_cambiar_Estado');
                exit;
            }
        } 
    }

    // Función de borrado de usuarios, no se si se va a implementar.
    /*public function borrarUsuario() {
        Auth::requireLogin();
        Auth::requirePermiso('admin.total');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Location: /usuarios');
            exit;
        }

        if (!isset($_POST['cedula'])) {
            header('Location: /usuarios?error=Error_en_la_Cedula');
            exit;
        }
            
        $cedula = trim($_POST['cedula']);

        if (!preg_match('/^\d{8}$/', $cedula)){
            header('Location: /usuarios?error=Cedula_Invalida');
            exit;
        }

        $model = $this->model('UsuarioModel');

        if (!$model->buscarPorCedula($cedula)){
            header('Location: /usuarios?error=No_Existe_Este_Usuario');
            exit;
        }

        if ($model->borrarUsuario($cedula)) {
            header('Location: /usuarios?success=Usuario_Borrado_Correctamente');
            exit;
        }
        else {
            header('Location: /usuarios?error=Fallo_al_Borrar');
            exit;
        }
    }*/
}