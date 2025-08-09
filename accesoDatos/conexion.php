<?php
function abrirConexion(){

try{

    $host = "localhost";
    $user = "root";
    $password = "root";
<<<<<<< HEAD
=======
=======
    $password = "";
<<<<<<< Updated upstream

>>>>>>> origin/Daniela
=======
>>>>>>> Stashed changes
>>>>>>> cac5738dc434e1914d72a8acf0af0937de3c0cfa
    $db = "biblioteca_web";
    $port = 3306;

<<<<<<< Updated upstream
    $mysqli = new mysqli($host, $user, $password, $db, $port);
=======

    $mysqli = new mysqli($host, $user, $password, $db);
>>>>>>> Stashed changes

    if($mysqli->connect_error){
        throw new exception("Sucedió un error al realizar la conexión a la base de datos.");
    }

    $mysqli->set_charset('utf8mb4');

    return $mysqli;

}catch (Exception $e){

    return false;
}

}

function cerrarConexion($mysqli){

    if($mysqli instanceof mysqli){
        $mysqli->close();
    }

}
/*
function abrirConexion(){

try{

    $host = "localhost";
    $user = "root";
    $password = "hola";
    $db = "biblioteca_web";
    $port = "3307";
  

    $mysqli = new mysqli($host, $user, $password, $db, $port);

    if($mysqli->connect_error){
        throw new exception("Sucedió un error al realizar la conexión a la base de datos.");
    }

    $mysqli->set_charset('utf8mb4');

    return $mysqli;

}catch (Exception $e){

    return false;
}

}

function cerrarConexion($mysqli){

    if($mysqli instanceof mysqli){
        $mysqli->close();
    }

}*/
?>
