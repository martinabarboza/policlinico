<?php
$config = require __DIR__ . '/../config/config.php';
$clave_secreta = $config['SECRET'];
$estado_auth;

//Verifica si existe la sessión
if (isset($_COOKIE['auten_token'])) {

    //Obtiene de la cookie tomanto en cuenta el $ para separar la session de la firma
    list($sesionCodificada64, $firmaRecibida) = explode('$', $_COOKIE['auten_token']);

    //Recrea el hash de la misma manera
    $firmaRecreada = hash_hmac('sha256', $sesionCodificada64, $clave_secreta);


    //Verifica el hash recreado si conside con el recibido la clave es la correspondiente
    //Firma como fue creada: $firma = hash_hmac('sha256',$sesionCodificada64,$clave_secreta);
    if (hash_equals($firmaRecibida, $firmaRecreada)) {
        $deco_sesionCodificada64 = json_decode(base64_decode($sesionCodificada64), true);

        if ($deco_sesionCodificada64['vencimiento'] < time()) {
            $estado_auth = (['autenticacion' => 'vencida']);

        } else {
            $estado_auth = (['autenticacion' => 'vigente']);

        }
    } else {
        $estado_auth = (['autenticacion' => 'alterada']);

    }
} else {
    $estado_auth = (['autenticacion' => 'null']);
    
}
?>