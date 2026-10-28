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

    public function modificarUsuario(): void {
        Auth::requireLogin();
        Auth::requirePermiso('admin.total');

        if (!isset($_GET['cedula'])) {
            header('Location: /usuarios?error=Usuario_No_Encontrado');
            exit;
        }

        $cedula = $_GET['cedula'];

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

            if (!isset($_POST['cedulaOld'])){
                header('Location: /usuarios?error=Falta_identificador');
                exit;
            }

            // Guarda la cédula antigua para encontrar al usuario en la bd.
            $cedulaOld = /*(int)*/ $_POST['cedulaOld'];

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

            $usuarioObj->setCedulaUsuario($_POST['cedula']);
            $usuarioObj->setNombreUsuario($_POST['nombre']);
            $usuarioObj->setApellidoUsuario($_POST['apellido']);
            $usuarioObj->setRolUsuario($_POST['rol']);
            $usuarioObj->setEmailUsuario($_POST['email']);

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
            else {
                header('Location: /usuarios?error=Error_al_Modificar_Usuario');
                exit;
            }
        }
    }
}