<?php
session_start();
if (!isset($_SESSION['nombre_usuario']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: login.php");
    exit;
}

require_once '../accesoDatos/conexion.php';
$mysqli = abrirConexion();

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // 1. Obtener ID del libro
    $stmt = $mysqli->prepare("SELECT id_libro FROM prestamos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($id_libro);
    $stmt->fetch();
    $stmt->close();

    // 2. Marcar como aprobado
    $stmt = $mysqli->prepare("UPDATE prestamos SET estado = 'aprobado', fecha_respuesta = NOW() WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    // 3. Descontar cantidad del libro
    $stmt = $mysqli->prepare("UPDATE libros SET cantidad_disponible = cantidad_disponible - 1 WHERE id = ?");
    $stmt->bind_param("i", $id_libro);
    $stmt->execute();
    $stmt->close();

    header("Location: admin_solicitudes.php");
    exit;
} else {
    echo "ID no proporcionado.";
}
