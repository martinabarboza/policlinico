<?php 

class Auth{

    private const SESSION_KEY = 'usuario';

    public static function login(array $usuario): void{

    session_regenerate_id(true);

    $_SESSION[self::SESSION_KEY] = [
                'id'       => (int) $usuario['id_usuario'],
            'nombre'   => $usuario['nombre_usuario'],
            'apellido' => $usuario['apellido_usuario'],
            'cedula' => $usuario['cedula_usuario'],
            'email'    => $usuario['email_usuario'],
            'rol'      => $usuario['rol_usuario'],
        ];
    }

    public static function logout(): void{
        unset($_SESSION[self::SESSION_KEY]);
        session_regenerate_id(true);
    }

    public static function check(): bool{
        return isset($_SESSION[self::SESSION_KEY]);
    }

    public static function user(): ?array
    {
        return $_SESSION[self::SESSION_KEY] ?? null;
    }

    public static function id(): ?int
    {
        return $_SESSION[self::SESSION_KEY]['id'] ?? null;
    }

    public static function rol(): ?string
    {
        return $_SESSION[self::SESSION_KEY]['rol'] ?? null;
    } 

    public static function requireLogin(): void
    {
        if (!self::check()) {
            header('Location: ' . url('login'));
            exit;
        }
    }

     public static function requirePermiso(string $permiso): void
    {
        self::requireLogin();

        $rol = self::rol();

        $permitido = match ($permiso) {
            'admin.total' => $rol === 'DIRECTOR',
            default       => false,
        };

        if (!$permitido) {
            http_response_code(403);
            echo 'No tenés permisos para acceder a esta sección.';
            exit;
        }
    }
}