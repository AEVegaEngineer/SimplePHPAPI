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
 
// if jwt is not empty

if($jwt){
 
    // si logra decodificar significa que el token es correcto
    try {
        // decodifica jwt
        $decoded = JWT::decode($jwt, $key, array('HS256'));
        $id_usuario = $decoded->data->id;

 		// muestra las notificaciones
        //echo json_encode($decoded->data);
        /**************************************/
        // inicializa objetos
        $notif = new Notificacion($db);
         
        // query notif
        $stmt = $notif->traerNotificaciones($id_usuario);
        $num = $stmt->rowCount();
        
        // revisa si se encontró algún registro
        if($num>0){
         
            // array de notif que se codificarán en la respuesta
            $respuesta=array();
            
            // trae tabla de contenido
            // fetch() es mas rápido que fetchAll()
            // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            extract($row);
            $notif_array = [];

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                // extrae línea
                extract($row);    
                $respuesta = array(
                    "id_notif_cuerpo" => $id_notif_cuerpo,
                    "fk_cabecera" => $fk_cabecera,
                    "user" => $user,
                    "asunto" => $asunto,
                    "estado" => $estado,
                    "mensaje" => $mensaje,
                );
         
                array_push($notif_array, $respuesta);
            }

            // establece el código de respuesta
            http_response_code(200);
            
            echo json_encode($notif_array);
        } 
        else
        { 
            // Establece código de respuesta - 404 Not Found
            http_response_code(404);
            echo json_encode(array("message" => "FAILED TO PULL DATA"));
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