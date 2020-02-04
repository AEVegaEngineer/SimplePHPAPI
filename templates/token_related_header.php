<?php
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
?>