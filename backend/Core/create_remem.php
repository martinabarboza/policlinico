<?php
$config = require __DIR__ . '/../config/config.php';
$clave_secreta = $config['SECRET'];

//Se verifica la autenticidad del usuario, se obtiene el id unicamente para trabajar con la session
//$id = $usuario_id;
$vencimiento_final = time() + 60*60*24*50;

try {
    // Intentamos generar el JTI de forma segura
    $pdqk = bin2hex(random_bytes(16));

    // \ esto ayuda a referiri al Exception del núcleo del php
} catch (\Exception $e) {
    // Si falla el generador seguro, usamos una alternativa como fallback
    // bin2hex + random_int es una alternativa aceptable si random_bytes falla
    $pdqk = bin2hex(pack('L', mt_rand()) . pack('L', mt_rand()));
}

$session = ['usuario_id' => $id, 'vencimiento' => $vencimiento_final, 'pdqk' => $pdqk ];

//Creacción del token
$sesionCodificada64 = base64_encode(json_encode($session));

//Creación de la firma session+clave hasheada en sha256;
$firma = hash_hmac('sha256', $sesionCodificada64, $clave_secreta);

//Mescla el JSON y la Firma(sha256(session+clave)) separado solamente por un $ xd
$remen_token = $sesionCodificada64 . '$' . $firma;
    
//guardarenGalletita ;3

//Produccion
/*
setcookie('remen_token', $remen_token, [
    'expires' => $vencimiento_final,   // 50 días
    'path' => '/',
    'domain' => 'stylocamion.com',// tu dominio
    'secure' => true,             // solo HTTPS
    'httponly' => true,           // JS no puede leer
    'samesite' => 'Lax'           // No se envía en requests cross-site
]);
*/


//Localhost

setcookie('remen_token', $remen_token, [
    'expires' => $vencimiento_final,   // 50 días
    'path' => '/', 
    'secure' => false, // porque no tenés HTTPS en localhost 
    'httponly' => true, 
    'samesite' => 'Lax' 
]);

?>