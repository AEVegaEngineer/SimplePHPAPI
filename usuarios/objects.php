<?php
class User{
 
    // nomb re de tablas y conexión a base de datos
    private $conn;
 
    // constructor de conexión a base de datos con $db
    public function __construct($db){
        $this->conn = $db;
    }

    function login($username,$pass){
        // Codifica el pass con md5 para compararlo con el pass del usuario en bd, (md5 no tiene desencripción)
        $encoded_pass = md5($pass);

        //Ejecuta el select del usuario para verificar el login

        $query = "SELECT * from usuario_ where login = '".$username."' AND pass = '".$encoded_pass."'";
     
        $stmt = $this->conn->prepare($query); 
        $stmt->execute(); 
        return $stmt;
    }
}
class Notificacion{
    // nombre de tablas y conexión a base de datos
    private $conn;
 
    // constructor de conexión a base de datos con $db
    public function __construct($db){
        $this->conn = $db;
    }
    // leer notiticaciones
    function traerNotificaciones($user){
        // Ejecuta el select
        $query = "SELECT * from app_notif_cuerpo as cu join app_notif_cabecera as ca on cu.fk_cabecera = ca.id_cabecera where cu.fk_user_objetivo = '".$user."'";
        $stmt = $this->conn->prepare($query); 
        $stmt->execute(); 
        return $stmt;
    }

}

class Device{
    private $conn;

    public function __construct($db){
        $this->conn = $db;
    }

    function AuthenticateDevices($user_id,$deviceid){
        $query = "SELECT * from usuario_ as u join app_user_devices as d on u.id = d.user_id where u.id = '".$user_id."' and d.deviceid = '".$deviceid."';";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(); 

        if($stmt->rowCount() < 1) {
            $query2 = "INSERT INTO `intranet`.`app_user_devices` (`user_id`, `deviceid`) VALUES ('".$user_id."', '".$deviceid."');";
            $stmt2 = $this->conn->prepare($query2); 
            $stmt2->execute();
            return $stmt2; 
        }
        return $stmt;
    }
}
?>