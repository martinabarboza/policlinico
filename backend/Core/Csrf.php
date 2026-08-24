<?php


  // token CSRF version mvc
 
class Csrf
{
    private const SESSION_KEY = 'csrf_token';

    /**
     * Devuelve el token actual, generándolo si todavía no existe.
     */
    public static function token(): string
    {
        if (empty($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = bin2hex(random_bytes(32));
        }

        return $_SESSION[self::SESSION_KEY];
    }

    /**
     * Valida el token recibido contra el guardado en sesión.
     */
    public static function validar(?string $tokenRecibido): bool
    {
        if (empty($_SESSION[self::SESSION_KEY]) || empty($tokenRecibido)) {
            return false;
        }

        return hash_equals($_SESSION[self::SESSION_KEY], $tokenRecibido);
    }
}