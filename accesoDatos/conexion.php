<?php
function abrirConexion() {
    $host = "localhost";
    $user = "root";
    $password = "hola";
    $db = "biblioteca_web";
    $port = 3307;

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    try {
        $mysqli = new mysqli($host, $user, $password, $db, $port);
        $mysqli->set_charset('utf8mb4');
        return $mysqli;
    } catch (Exception $e) {
        error_log("Error de conexión: " . $e->getMessage());
        return false;
    }
}

function cerrarConexion($mysqli) {
    if ($mysqli instanceof mysqli) {
        $mysqli->close();
    }
}
?>
