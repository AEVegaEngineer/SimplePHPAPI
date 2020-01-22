<?php
class User{
 
    // nomb re de tablas y conexión a base de datos
    private $conn;
    private $table_name = "products";
 
    // propiedades de objetos
    public $id;
    public $name;
    public $description;
    public $price;
    public $category_id;
    public $category_name;
    public $created;
 
    // constructor de conexión a base de datos con $db
    public function __construct($db){
        $this->conn = $db;
    }
    // leer usuario_
    function read(){
     
        // Ejecuta el select
        $query = "SELECT * from usuario_ where login = '10'";
     
        // Prepara la sentencia query
        $stmt = $this->conn->prepare($query);
     
        // Ejecuta query
        $stmt->execute();
     
        return $stmt;
    }
    function login($username,$pass){
        // Codifica el pass con md5 para compararlo con el pass del usuario en bd, (md5 no tiene desencripción)
        $encoded_pass = md5($pass);

        //Ejecuta el select del usuario para verificar el login

        $query = "SELECT * from usuario_ where login = '".$username."' AND pass = '".$encoded_pass."'";
     
        // Prepara la sentencia query
        $stmt = $this->conn->prepare($query);
     
        // Ejecuta query
        $stmt->execute();
     
        return $stmt;
    }
}

?>