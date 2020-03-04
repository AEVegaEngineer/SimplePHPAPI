<?php
//template para consultas
include_once '../templates/header_consultas.php';
//template con las libs de token
// cabeceras requeridas
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// necesario para decodificar jwt
include_once '../config/core.php';
include_once '../vendor/firebase/php-jwt/src/BeforeValidException.php';
include_once '../vendor/firebase/php-jwt/src/ExpiredException.php';
include_once '../vendor/firebase/php-jwt/src/SignatureInvalidException.php';
include_once '../vendor/firebase/php-jwt/src/JWT.php';
use \Firebase\JWT\JWT;
 
// obtener la data por post
$data = json_decode(file_get_contents("php://input"));
 
// obtener jwt
$jwt=isset($data->jwt) ? $data->jwt : "";
$id=isset($data->id) ? $data->id : "";
 
// if jwt is not empty

if($jwt){
 
    // si logra decodificar significa que el token es correcto
    try {
        // decodifica jwt
        $decoded = JWT::decode($jwt, $key, array('HS256'));
        
        // inicializa objetos
        $notif = new Notificacion($db);
         
        // query notif
        $stmt = $notif->leerNotificacion($id);
                
        // revisa si el token fue decodificado correctamente
        //if($decoded)        
        if($stmt == "OK")
        {            
            echo json_encode(array(
            "message" => "Notificación ".$id." leída"));
            // establece el código de respuesta
            http_response_code(200);
        } 
        else
        { 
            // Establece código de respuesta - 404 Not Found
            http_response_code(404);
            echo json_encode(array("message" => "ACTUALIZACIÓN DE DATA FALLIDA"));
        }

        /**************************************/
 
    }
 
    // si falla el decode significa que el decode es inválido
	catch (Exception $e){
	 
	    // establece el código de respuesta
	    http_response_code(401);
	 
	    // le dice al usuario que ha sido denegado el acceso
	    echo json_encode(array(
	        "message" => "Acceso denegado.",
	        "error" => $e->getMessage()
	    ));
	}
}
 
// muestra el error del mensaje si jwt esta vacío
else{
 
    // establece el código de respuesta
    http_response_code(404);
 
    // le dice al usuario que ha sido denegado el acceso
    echo json_encode(array("message" => "Acceso denegado."));
}
?>