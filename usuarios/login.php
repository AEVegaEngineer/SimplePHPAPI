<?php
//template para consultas
include_once '../templates/header_consultas.php';

// generate json web token
include_once '../config/core.php';
include_once '../vendor/firebase/php-jwt/src/BeforeValidException.php';
include_once '../vendor/firebase/php-jwt/src/ExpiredException.php';
include_once '../vendor/firebase/php-jwt/src/SignatureInvalidException.php';
include_once '../vendor/firebase/php-jwt/src/JWT.php';

use Firebase\JWT\JWT;

// inicializa objetos
$usuarios = new User($db);
 
// query usuarios
$credenciales = (json_decode(file_get_contents("php://input"), true));
$stmt = $usuarios->login($credenciales["username"], $credenciales["password"]);
$num = $stmt->rowCount();


 
//setea time para llevar el tiempo del token
$time = time();
// revisa si se encontró algún registro
if($num>0){
    // trae tabla de contenido
    // fetch() es mas rápido que fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    
    
    // array de usuarios que se codificarán en el token
    $token=array();
    
    // extrae la información del $row
    extract($row);

    //Revisa los dispositivos asociados al usuario logeado, si el dispositivo actual no existe lo registra como perteneciente al usuario
    $devices = new Device($db);
    $AuthDevices = $devices->AuthenticateDevices($id, $credenciales["deviceId"]);


    $token = array(
        "iat" => $iat,
        "data" => array(
            "id" => $id,
            "login" => $login,
            "estado" => $estado,
            "tipo" => $tipo,
            "fk_personal" => $fk_personal
       )
    );

    // establece el código de respuesta
    http_response_code(200);
    //lave del token
    // genera jwt
    $jwt = JWT::encode($token, $key);
    echo json_encode(
            array(
                "message" => "OK",
                "id" => $id,
                "jwt" => $jwt
            )
        );
} 
else
{ 
    // Establece código de respuesta - 404 Unauthorized
    http_response_code(401);
    echo json_encode(array("message" => "FAIL"));
}

