<?php 

class Auth{

    //CONSTANTE PARA LA SESION DEL USUARIO
    private const SESSION_KEY = 'usuario';

    //INICIA LA SESION DEL USUARIO
    public static function login(array $usuario): void{

    //DESTRUYE LA SESION ANTERIOR Y CREA UNA NUEVA SESION PARA EL USUARIO
    session_regenerate_id(true);

    //ALMACENA LOS DATOS DEL USUARIO EN LA SESION
    $_SESSION[self::SESSION_KEY] = [
                'id'       => (int) $usuario['id_usuario'],
            'nombre'   => $usuario['nombre_usuario'],
            'apellido' => $usuario['apellido_usuario'],
            'cedula' => $usuario['cedula_usuario'],
            'email'    => $usuario['email_usuario'],
            'rol'      => $usuario['rol_usuario'],
        ];
    }

    //CIERRA LA SESION DEL USUARIO ELIMINANDO LOS DATOS DE 
    //LA SESION Y GENERANDO UN NUEVO ID DE SESION
    public static function logout(): void{
        unset($_SESSION[self::SESSION_KEY]);
        session_regenerate_id(true);
    }

    //VERIFICA SI EL USUARIO INICIO SESIÓN
    public static function check(): bool{
        return isset($_SESSION[self::SESSION_KEY]);
    }

    //OBTIENE LOS DATOS DEL USUARIO LOGUEADO
    public static function user(): ?array
    {
        return $_SESSION[self::SESSION_KEY] ?? null;
    }

    //OBTIENE EL ID DEL USUARIO LOGUEADO
    public static function id(): ?int
    {
        return $_SESSION[self::SESSION_KEY]['id'] ?? null;
    }

    //OBTIENE EL ROL DEL USUARIO LOGUEADO
    public static function rol(): ?string
    {
        return $_SESSION[self::SESSION_KEY]['rol'] ?? null;
    } 

    //DEVUELVE EL USUARIO A LA PAGINA LOGIN SI NO ESTA LOGEADO
    public static function requireLogin(): void
    {
        if (!self::check()) {
            header('Location: ' . url('login'));
            exit;
        }
    }

    // VERIFICA EL PERMISO DEL USUARIO SI ES DIRECTOR PARA ACCEDER
    // A SECCIONES DONDE SOLO EL PUEDE ACCEDER
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