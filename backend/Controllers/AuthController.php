<?php

class AuthController extends Controller
{
    /**
     * Muestra el formulario de login (GET /login).
     */
    public function login(): void
    {
        // Si ya está logueado, no tiene sentido mostrarle el login de nuevo.
        if (Auth::check()) {
            header('Location: ' . url('dashboard.php'));
            exit;
        }

        $this->view('auth/login', [
            'csrfToken' => Csrf::token(),
            'error'     => $_SESSION['auth_error'] ?? null,
            'oldEmail'  => $_SESSION['old_email'] ?? '',
        ], 'landing');

        unset($_SESSION['auth_error'], $_SESSION['old_email']);
    }

    /**
     * Procesa el formulario de login 
     */
    public function authenticate(): void
    {
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $token    = $_POST['csrf_token'] ?? '';

        if (!Csrf::validar($token)) {
            $this->fallarLogin('Tu sesión expiró, por favor intentá de nuevo.', $email);
            return;
        }

        if ($email === '' || $password === '') {
            $this->fallarLogin('Completá tu correo y tu contraseña.', $email);
            return;
        }

        $usuarioModel = $this->model('Usuario');
        $usuario = $usuarioModel->buscarPorEmail($email);

        if (!$usuario || !password_verify($password, $usuario['passwd_usuario'])) {
            $this->fallarLogin('Correo o contraseña incorrectos.', $email);
            return;
        }

        Auth::login($usuario);
        $usuarioModel->actualizarUltimoLogin((int) $usuario['id_usuario']);

        header('Location: ' . url('dashboard.php'));
        exit;
    }

    /**
     * Cierra la sesion (GET /logout).
     */
    public function logout(): void
    {
        Auth::logout();
        header('Location: ' . url('login'));
        exit;
    }

    private function fallarLogin(string $mensaje, string $email): void
    {
        $_SESSION['auth_error'] = $mensaje;
        $_SESSION['old_email']  = $email;
        header('Location: ' . url('login'));
        exit;
    }
}