<?php 

function url(string $ruta = ''): string{
    return '/' . ltrim($ruta, '/');
}