<?php
$config = require __DIR__ . '/../config/config.php';
$clave_secreta = $config['SECRET'];
$remem_auth;

//Verifica si existe la sessión
if (isset($_COOKIE['remen_token'])) {

    //Obtiene de la cookie tomando en cuenta el $ para separar la session de la firma
    list($sesionCodificada64, $firmaRecibida) = explode('$', $_COOKIE['remen_token']);

    //Recrea el hash de la misma manera
    $firmaRecreada = hash_hmac('sha256', $sesionCodificada64, $clave_secreta);


    //Verifica el hash recreado si conside con el recibido la clave es la correspondiente
    //Firma como fue creada: $firma = hash_hmac('sha256',$sesionCodificada64,$clave_secreta);
    if (hash_equals($firmaRecibida, $firmaRecreada)) {
        $deco_sesionCodificada64 = json_decode(base64_decode($sesionCodificada64), true);

        if ($deco_sesionCodificada64['vencimiento'] > time()) {
            $remem_auth = (['autenticacion' => 'activa']);

            $tiempo_restante = floor(($deco_sesionCodificada64['vencimiento'] - time()) / 86400);

            //Si se llega a vencer
            if ($tiempo_restante < 15) {
                try {
                    // Intentamos generar el JTI de forma segura
                    $pdqk = bin2hex(random_bytes(16));

                    // \ esto ayuda a referiri al Exception del núcleo del php
                } catch (\Exception $e) {
                    // Si falla el generador seguro, usamos una alternativa como fallback
                    // bin2hex + random_int es una alternativa aceptable si random_bytes falla
                    $pdqk = bin2hex(pack('L', mt_rand()) . pack('L', mt_rand()));
                }

                $regen_session = ['usuario_id' => $deco_sesionCodificada64['usuario_id'], 'vencimiento' => time() + 60 * 60 * 24 * 30, 'pdqk' => $pdqk];

                //Creacción del token a regenerar
                $sesionCodificada64 = base64_encode(json_encode($regen_session));

                //Creación de la firma session+clave hasheada en sha256;
                $firma = hash_hmac('sha256', $sesionCodificada64, $clave_secreta);

                //Mescla el JSON y la Firma(sha256(session+clave)) separado solamente por un $ xd
                $regen_token = $sesionCodificada64 . '$' . $firma;

                //guardarenGalletita ;3
                setcookie('remen_token', $regen_token, [
                    'expires' => time() + 60 * 60 * 24 * 50,   // 50 días
                    'path' => '/',
                    'domain' => 'stylocamion.com',              // dominio
                    'secure' => true,                           // solo HTTPS
                    'httponly' => true,                         // JS no puede leer
                    'samesite' => 'Lax'                         // No se envía en requests cross-site
                ]);
            }
        }
    } else {
        $remem_auth = (['autenticacion' => 'expirada']);
    }
} else {
    $remem_auth = (['autenticacion' => 'null']);
}
