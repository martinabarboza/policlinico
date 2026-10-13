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
     * Procesa el formulario de login 
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

        $usuarioModel = $this->model('Usuario');

        if (!ctype_digit($cedula)) {
            $this->fallarLogin('La cédula debe contener solamente números.', $cedula);
            return;
        }

        $usuario = $usuarioModel->buscarPorCedula((int) $cedula);

        if (!$usuario || !password_verify($password, $usuario['passw_Usuario'])) {
            $this->fallarLogin('Documento o contraseña incorrectos.', $cedula);
            return;
        }

        Auth::login($usuario);
        $usuarioModel->actualizarUltimoLogin((int) $usuario['id_Usuario']);

        header('Location: ' . url('dashboard'));
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

    private function fallarLogin(string $mensaje, string $cedula): void
    {
        $_SESSION['auth_error'] = $mensaje;
        $_SESSION['old_cedula'] = $cedula;
        header('Location: ' . url('login'));
        exit;
    }
}