<?php

class Modelo
{
    // atributos que serán empleados en la conexion
    private $conexion;

    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $db = "bdblog";

    //constructor de la clase y ejecutará el metodo conectar
    public function __construct()
    {

        $this->conectar();
    }

    /**
     * metodo conectar para realizar la union con la base de datos. Va a devolver un dato de tipo boolena true si 
     * la conexion es correcta y false si no se ha realizado.
     * @return boolean
     */

    public function conectar()
    {

        try {

            $this->conexion = new PDO("mysql:host=$this->host;dbname=$this->db", $this->user, $this->pass);
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return true;
        } catch (PDOException $ex) {

            return $ex->getMessage();
        }
    }

    /**
     * funcion que nos permite saber si estamos conectados a la base de datos
     * @return boolean
     */
    public function estaConectado()
    {
        if ($this->conexion) {

            return true;
        } else {

            return false;
        }
    }



    //Funciones de la tabla entrada

    public function logs(){
        try {

            $sql = "SELECT * FROM usuarios";

            $query = $this->conexion->prepare($sql);

            $query->execute();

            $query->setFetchMode(PDO::FETCH_ASSOC);

            header("HTTP/1.1 200 OK");

            //En este caso se trata de FETCHALL porque obtendrá más de una fila de la base de datos como información
            echo json_encode( $query->fetchAll()  );
            exit();

        } catch (PDOException $e) {

            return $e->getMessage();

        }
    }
    /***
     * funcion para listar todas las entradas de la base de datos
     * contendrá un array: correcto que nos dice si el listado se ha realizado bien o no;
     * datos que almacena los datos obtenidos en la consulta de la tabla, error que
     * almacena un mensaje de la situcion erronea
     */


    public function listarEntradas(){

        
        try {
            
            $sql = "SELECT  * FROM entradas ";

            $query = $this->conexion->prepare($sql);

            $query->execute();

            $query->setFetchMode(PDO::FETCH_ASSOC);

            header("HTTP/1.1 200 OK");

            //En este caso se trata de FETCHALL porque obtendrá más de una fila de la base de datos como información
            echo json_encode( $query->fetchAll()  );
             
            exit();

        } catch (PDOException $e) {

            return $e->getMessage();

        }
    }
   

    /**
     * listar una entrada
     */

     public function listarEntrada($id){


        try {

            //Se establece la sentencia SQL
            $sql = "SELECT * FROM usuarios INNER JOIN entradas ON entradas.usuario_id=usuarios.id WHERE entradas.id= :id";

            //Se prepara la sentencia en una conexión específica, que es la que hemos realizado
            $query = $this->conexion->prepare($sql);

            //Se ejecuta la sentencia preparada y el campo id obtendrá el valor de aquel que se le pase por parámetro
            $query->execute(['id' => $id]);
                
            header("HTTP/1.1 200 OK");

            //Se devuelve el json de los valores obtenidos cuando se realice un FETCH a la sentencia
            echo json_encode(  $query->fetch(PDO::FETCH_ASSOC)  );
            
            exit();

        } catch (PDOException $e) {

            return $e->getMessage();

        }


     }
       

    /**
     * funcion que va a eliminar una entrada por el id
     */

    public function delEntradas(){
      
        try {

            //Obtenemos una id como parámetro mediante $_GET
            $id = $_GET['id'];

            $sql = "DELETE FROM entradas where id=:id";
            
            $query = $this->conexion->prepare($sql);

            //La entrada que se borrará será la que tenga el id especificado
            $query->bindValue(':id', $id);

            $query->execute();

            header("HTTP/1.1 200 OK");
            exit();

        } catch (PDOException $e) {

            return $e->getMessage();

        }
    }

    public function addEntradas(){

       
        try {

            
            $input = $_POST;

            $sql= "INSERT INTO entradas(usuario_id,categoria_id,titulo,imagen,descripcion,fecha) VALUES (:usuario_id, :categoria_id, :titulo, :imagen, :descripcion, :fecha)";
            
            $query = $this->conexion->prepare($sql);
            
            bindAllValues($query, $input);
            
            $query->execute();

            $postId = $this->conexion->lastInsertId();

            if($postId){

                $input['id'] = $postId;
                header("HTTP/1.1 200 OK");
                echo json_encode($input);
                exit();

            }

        } catch (PDOException $e) {

            return $e->getMessage();

        }

    }

/**
 * funcion para actualizar las entradas
 */
    public function actEntradas(){
        try {

            
            $input = $_GET;

            
            $postId = $input['id'];

           
            $fields = getParams($input);
        
            $sql = "UPDATE entradas SET $fields WHERE id='$postId'";
        
            $query = $this->conexion->prepare($sql);

            bindAllValues($query, $input);
        
            $query->execute();
            
            header("HTTP/1.1 200 OK");
            exit();

        } catch (PDOException $e) {

            return $e->getMessage();

        }
    }

}
?>