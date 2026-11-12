<?php 

/** Construye una ruta local absoluta para enlaces y redirecciones de la aplicación. */
function url(string $ruta = ''): string{
    return '/' . ltrim($ruta, '/');
}