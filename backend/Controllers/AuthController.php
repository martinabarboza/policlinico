<?php

/** Atiende el inicio y cierre de sesión; la contraseña nunca se guarda en la sesión. */
class AuthController extends Controller
{
    /**
    * Muestra el formulario de acceso y limpia los mensajes de un intento anterior.
     */
    public function login(): void
    {
        // Si ya está logueado, no tiene sentido mostrarle el login de nuevo.
        if (Auth::check()) {
            header('Location: ' . url('dashboard'));
            exit;
        }

        $this->view('auth/login', [
            'csrfToken' => Csrf::token(),
            'error'     => $_SESSION['auth_error'] ?? null,
            'oldCedula'  => $_SESSION['old_cedula'] ?? '',
        ], 'landing');

        unset($_SESSION['auth_error'], $_SESSION['old_cedula']);
    }

    /**
    * Verifica CSRF, estado de cuenta y hash de contraseña antes de abrir sesión.
     */
    public function authenticate(): void
    {
        $cedula    = trim($_POST['cedula'] ?? '');
        $password = $_POST['password'] ?? '';
        $token    = $_POST['csrf_token'] ?? '';

        if (!Csrf::validar($token)) {
            $this->fallarLogin('Tu sesión expiró, por favor intentá de nuevo.', $cedula);
            return;
        }

        if ($cedula === '' || $password === '') {
            $this->fallarLogin('Completá tu documento y tu contraseña.', $cedula);
            return;
        }

        $usuarioModel = $this->model('UsuarioModel');

        if (!ctype_digit($cedula)) {
            $this->fallarLogin('La cédula debe contener solamente números.', $cedula);
            return;
        }

        $usuario = $usuarioModel->buscarPorCedula($cedula);

        if (!$usuario || !password_verify($password, $usuario['passw_Usuario'])) {
            $this->fallarLogin('Documento o contraseña incorrectos.', $cedula);
            return;
        }

        if ($usuario['estado_Usuario'] !== 'activo') {
            $this->fallarLogin('Esta cuenta está desactivada, si cree que es un error por favor contacte con el administrador a cargo del sistema', $cedula);
            return;
        }

        Auth::login($usuario);
        $usuarioModel->actualizarUltimoLogin((int) $usuario['id_Usuario']);

        header('Location: ' . url('dashboard'));
        exit;
    }

    /** Cierra la sesión actual y redirige al formulario de acceso. */
    public function logout(): void
    {
        Auth::logout();
        header('Location: ' . url('login'));
        exit;
    }

    /** Guarda el error y la cédula para mostrarlos tras redirigir al login. */
    private function fallarLogin(string $mensaje, string $cedula): void
    {
        $_SESSION['auth_error'] = $mensaje;
        $_SESSION['old_cedula'] = $cedula;
        header('Location: ' . url('login'));
        exit;
    }
}