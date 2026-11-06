<?php
require_once APP_PATH . '/Helpers/UsuarioClase.php';
class UsuarioController extends Controller
{

    //
    // Index llama a la vista del titulo de la página, mediante usuario y gestion de roles.
    //

    public function index(): void
    {
        Auth::requireLogin();
        Auth::requirePermiso('admin.total');

        $busqueda = isset($_GET['busqueda']) ? trim($_GET['busqueda']) : '';

        $this->view('usuarios/index', [
            'tituloPagina' => 'Usuarios',
            'activeMenu'   => 'usuarios',
            //'roles'        => $this->model('Rol')->activos(), <= No me acuerdo que era esto pero lo dejo comentado por si acaso.
            'usuarios'     => $this->model('UsuarioModel')->listadoUsuarios($busqueda),
        ], 'app', ['app-layout.css', 'dashboard.css'], ['dashboard.js'], ['bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js', 'jquery-4.0.0.min.js.js']);
    }

    //
    // Llama a la vista de modificar usuario, utilizando la cédula del usuario.
    //

    private function returnPorError($form, $usuarioVerificacion, string $mensajeError): void
    {
        if ($form === 'modificar') {
        $this->view('usuarios/modificarUsuario', [
            'tituloPagina' => 'Modificar Usuario',
            'activeMenu' => 'usuarios',
            'usuarioEdit' => $usuarioVerificacion,
            'error' => $mensajeError
        ], 'app', ['app-layout.css', 'dashboard.css'], ['dashboard.js'], ['bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js', 'jquery-4.0.0.min.js.js']);
        }
        if ($form === 'crear') {
            $this->view('usuarios/crearUsuario', [
            'tituloPagina' => 'Crear Nuevo Usuario',
            'activeMenu' => 'usuarios',
            'usuarioEdit' => $usuarioVerificacion,
            'error' => $mensajeError
        ], 'app', ['app-layout.css', 'dashboard.css'], ['dashboard.js'], ['bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js', 'jquery-4.0.0.min.js.js']);
        }
        return;
    }

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
            $this->returnPorError('modificar', null, 'La cédula debe contener exactamente 8 números.');
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
    // Permite Actualizar un Usuario en la base de datos utilizando la cédula.
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
                $this->returnPorError('modificar', $usuarioActual, 'La cédula debe contener exactamente 8 números');
                return;
            }
            if (!filter_var($emailNuevo, FILTER_VALIDATE_EMAIL)) {
                $this->returnPorError('modificar', $usuarioActual, 'El correo electrónico no es válido');
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

            $comprobacion = $this->model('UsuarioModel')->modificarUsuario($usuarioObj, $cedulaOld);

            // Comprueba si se modificó correctamente y redirige a la página de usuarios con un mensaje de éxito o error.
            if ($comprobacion) {
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

    //
    // Crea un nuevo usuario en la base de datos.
    //
    public function crearUsuario(): void {
        Auth::requireLogin();
        Auth::requirePermiso('admin.total');

        
        
        // Valida que la solicitud sea POST y que todos los campos requeridos estén presentes.
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
           if (!isset($_POST['cedula'], $_POST['nombre'], $_POST['apellido'], $_POST['rol'], $_POST['email'], $_POST['passw'])) {
                $this->returnPorError('crear', null, 'Por favor, completa todos los campos requeridos.');
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
                $this->returnPorError('crear', null, 'La cédula debe contener exactamente 8 números.');
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->returnPorError('crear', null, 'El correo electrónico no es válido.');
            }
            
            $usuarioExistente = $this->model('UsuarioModel')->buscarPorCedula($cedula);
            if ($usuarioExistente) {
                $this->returnPorError('crear', null, 'Ya existe un usuario con esa cédula.');
            }

            // Crea una instancia del modelo de usuario y llama al método para crear un nuevo usuario.
            $usuarioNuevo = $this->model('UsuarioModel');
            $comprobacion = $usuarioNuevo->crearUsuarioNuevo($cedula, $nombre, $apellido, $rol, $email, $passw);

            // Comprueba si se crea correctamente el usuario y redirige a la pagina de usuarios con un mensaje de éxito o error.
            if ($comprobacion) {
                header('Location: /usuarios?success=Usuario_Creado_Correctamente');
                exit;
            } 
            else 
            {
                $this->returnPorError('crear', null, 'Fallo al Crear Usuario');
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
}