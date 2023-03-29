<?php

    //Llamamos a modelo.php
    require_once '../modelos/modelo.php';

    
     function getParams($input){

        $filterParams = [];
        
     
        foreach($input as $param => $value){

            $filterParams[] = "$param=:$param";
        }

        return implode(", ", $filterParams);

    }

    
    function bindAllValues($query, $params){

        foreach($params as $param => $value){
            $query->bindValue(':'.$param, $value);
            
        }

        return $query;

    }

    //Creamos un nuevo objeto tipo modelo
    $modelo = new modelo();

    //Si la petición del servidor es GET
    if ($_SERVER['REQUEST_METHOD'] == 'GET'){

        if(isset($_GET["accion"])){
            //listamos los usuarios 
            if($_GET['accion']=="logs"){
                $modelo->logs();
            }
            //listamos una entrada
        }else if(isset($_GET['id'])){
              $modelo->listarEntrada($_GET['id']);
        }else{
            //listamos muchas entradas
            $modelo->listarEntradas();
        }
    }

    
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){

        //para insertar una nueva entrada en la base de datos
        $modelo->addEntradas();

    }

    //Si la petición del servidor es DELETE
    if ($_SERVER['REQUEST_METHOD'] == 'DELETE'){

        //Se llama a la función de modelo que elimina una de las entradas de la base de datos
        $modelo->delEntradas();

    }

    //Si la petición del servidor es PUT
    if ($_SERVER['REQUEST_METHOD'] == 'PUT'){

        //Se llama a la función de modelo que mediante parámetros puede modificar los valores de algunos campos
        $modelo->actEntradas();

    }

    //En caso de que ninguna de las opciones anteriores se haya ejecutado
    header("HTTP/1.1 400 Bad Request");
