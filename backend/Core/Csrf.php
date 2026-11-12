<?php

/** Genera y valida tokens CSRF asociados a la sesión actual. */
class Csrf
{
    private const SESSION_KEY = 'csrf_token';

    /** Devuelve el token de sesión actual y crea uno aleatorio si falta. */
    public static function token(): string
    {
        if (empty($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = bin2hex(random_bytes(32));
        }

        return $_SESSION[self::SESSION_KEY];
    }

    /** Compara de forma constante el token recibido con el token de sesión. */
    public static function validar(?string $tokenRecibido): bool
    {
        if (empty($_SESSION[self::SESSION_KEY]) || empty($tokenRecibido)) {
            return false;
        }

        return hash_equals($_SESSION[self::SESSION_KEY], $tokenRecibido);
    }
}