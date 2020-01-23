<?php
include_once '/templates/header-users.php';
 
// incluyo archivos de base de datos y objeto
include_once '../config/database.php';
include_once '../usuarios/objects.php';
 
include_once '/templates/instanciar_objetos_db.php';

require_once '../vendor/autoload.php';

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
 
    // array de usuarios que se codificarán en el token
    $token=array();
    
    // trae tabla de contenido
    // fetch() es mas rápido que fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extrae línea
        // esto convierte $row['name'] a
        // solo $name
        extract($row);    
        
        $token["id"] = $id;
        $token["login"] = $login;
        $token["estado"] = html_entity_decode($estado);
        $token["tipo"] = $tipo;
        $token["fk_personal"] = $fk_personal;


        $token["iat"] = $time;  
        $token["exp"] = $time + (60*60);
    }
    //Codifica Token para el login usando HS256

    $key = 'Colegio_de_Medicos_19422581';

    $jwt = JWT::encode($token, $key);

    $data = JWT::decode($jwt, $key, array('HS256'));

    // Establece código de respuesta - 200 OK
    http_response_code(200);
 
    // Muestra data de usuarios en formato json
    echo json_encode($data);
} 
else
{ 
    // Establece código de respuesta - 404 Not found
    http_response_code(404);
    // envía un usuario vacío      
    $token["id"] = null;
    $token["login"] = null;
    $token["estado"] = null;
    $token["tipo"] = null;
    $token["fk_personal"] = null;

    $token["iat"] = $time;  
    $token["exp"] = $time + (60*60);        
    
    echo json_encode($token);
}

