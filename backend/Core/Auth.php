<?php

/** Gestiona la identidad y los permisos guardados en la sesión PHP. */
class Auth
{
    private const SESSION_KEY = 'usuario';
    private const REMEMBER_DURATION = 2592000;

    /** Inicia una sesión nueva y conserva solo los campos necesarios en ella. */
    public static function login(array $usuario): void
    {
        // Cambiar el ID al autenticar evita reutilizar el identificador previo.
        session_regenerate_id(true);

        $_SESSION[self::SESSION_KEY] = [
            'id'       => (int) $usuario['id_Usuario'],
            'nombre'   => $usuario['nombre_Usuario'],
            'apellido' => $usuario['apellido_Usuario'],
            'cedula' => $usuario['cedula_Usuario'],
            'email'    => $usuario['email_Usuario'],
            'rol'      => $usuario['rol_Usuario'],
            'estado'   => $usuario['estado_Usuario'],
        ];
    }

    /** Elimina la identidad autenticada y renueva el identificador de sesión. */
    public static function logout(): void
    {
        unset($_SESSION[self::SESSION_KEY]);
        session_regenerate_id(true);
        self::setRememberMe(false);
    }

    /** Configura la cookie de sesión como persistente o de solo navegador. */
    public static function setRememberMe(bool $remember): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE || session_id() === '') {
            return;
        }

        $cookie = session_get_cookie_params();
        $options = [
            'expires' => $remember ? time() + self::REMEMBER_DURATION : 0,
            'path' => $cookie['path'] ?: '/',
            'secure' => $cookie['secure'],
            'httponly' => $cookie['httponly'],
            'samesite' => $cookie['samesite'] ?: 'Lax',
        ];
        if ($cookie['domain'] !== '') {
            $options['domain'] = $cookie['domain'];
        }

        setcookie(session_name(), session_id(), $options);
    }

    /** Indica si la sesión contiene una identidad autenticada. */
    public static function check(): bool
    {
        return isset($_SESSION[self::SESSION_KEY]);
    }

    /** Devuelve los datos mínimos del usuario actual o null si no inició sesión. */
    public static function user(): ?array
    {
        return $_SESSION[self::SESSION_KEY] ?? null;
    }

    /** Devuelve el ID del usuario autenticado. */
    public static function id(): ?int
    {
        return $_SESSION[self::SESSION_KEY]['id'] ?? null;
    }

    /** Devuelve el rol del usuario autenticado. */
    public static function rol(): ?string
    {
        return $_SESSION[self::SESSION_KEY]['rol'] ?? null;
    }

    /** Redirige al login y detiene la petición si no hay sesión autenticada. */
    public static function requireLogin(): void
    {
        if (!self::check()) {
            header('Location: ' . url('login'));
            exit;
        }
    }

    /**
     * Comprueba un permiso de la aplicación y responde con 403 si falta.
     * Los permisos se resuelven aquí para que los controladores no dupliquen reglas.
     */
    public static function requirePermiso(string $permiso): void
    {
        self::requireLogin();

        $rol = self::rol();

        $permitido = match ($permiso) {
            'admin.total' => $rol === 'Director',
            default       => false,
        };

        if (!$permitido) {
            http_response_code(403);
            echo 'No tenés permisos para acceder a esta sección.';
            exit;
        }
    }
}
