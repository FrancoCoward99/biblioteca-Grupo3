<?php
$mysqli = new mysqli("localhost", "root", "", "biblioteca_web");

if ($mysqli->connect_errno) {
    die("Error de conexión: " . $mysqli->connect_error);
}


?>
