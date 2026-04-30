<?php
header('Content-Type: application/json');
include_once('auth_token.php');

//Captura la solicitud proveniente del JS
$solicitud = json_decode(file_get_contents('php://input'), true);

//Verifica que el Json sea valido
if (!$solicitud || !is_array($solicitud)) {
    http_response_code(400);
    echo json_encode('Comunicación invalida');
    exit;
}

//Guarda en variable el servicio a comunicar, en caso de solicitud sin el formato esperado este queda vació
$servicio = $solicitud['servicio'] ?? '';

function comprobarAUTH($estado_auth)
{
    if ($estado_auth !== 'vigente') {
        http_response_code(401);
        echo json_encode('No autorizado');
        exit;
    }
}

//Gateway
switch ($servicio) {
    case 'usuario.login':
        $datos = $solicitud['datos'];
        require_once 'api/servicios/usuario/login_usuario.php';
        echo json_encode($respuesta);
        break;

    case 'usuario.signup':
        $datos = $solicitud['datos'];
        require_once 'api/servicios/usuario/signup_usuario.php';
        echo json_encode($respuesta);
        break;

    case 'nacionalidad.paises':
        require_once 'api/servicios/nacionalidad/obtener_paises.php';
        echo json_encode($respuesta);
        break;

    default:
        echo json_encode('Sucedio algo inesperado');
        http_response_code(400);
}
