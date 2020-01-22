<?php
// cabeceras requeridas
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// incluyo archivos de base de datos y objeto
include_once '../config/database.php';
include_once '../usuarios/objects.php';
 
// instanciar objetos de base de datos y productos
$database = new Database();
$db = $database->getConnection();
 
// inicializa objetos
$usuarios = new User($db);
 
// query usuarios
$stmt = $usuarios->read();
$num = $stmt->rowCount();
 
// revisa si se encontró algún registro
if($num>0){
 
    // array de usuarios
    $usr_array=array();
    $usr_array["records"]=array();
 
    // trae tabla de contenido
    // fetch() es mas rápido que fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extrae línea
        // esto convierte $row['name'] a
        // solo $name
        extract($row);    
        $objeto_usuario=array(
            "id" => $id,
            "login" => $login,
            /*"pass" => md5($pass),*/
            "estado" => html_entity_decode($estado),
            "tipo" => $tipo,
            "fk_personal" => $fk_personal
        );
 
        array_push($usr_array["records"], $objeto_usuario);
    }
 
    // Establece código de respuesta - 200 OK
    http_response_code(200);
 
    // Muestra data de usuarios en formato json
    echo json_encode($usr_array);
} 
else
{ 
    // Establece código de respuesta - 404 Not found
    http_response_code(404);
    echo json_encode(
        array("message" => "No se encontraron usuarios.")
    );
}