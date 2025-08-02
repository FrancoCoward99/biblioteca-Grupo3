<?php
session_start();

require_once '../accesoDatos/conexion.php';

if (!isset($_SESSION['nombreUsuario']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: ../login.php");
    exit;
}

$idUsuario = $_GET['id'] ?? null;

if (!$idUsuario) {
    echo '<script>alert("ID de usuario no válido."); window.location.href = "gestion_usuarios.php";</script>';
    exit;
}

$mysqli = abrirConexion();

$stmtCheck = $mysqli->prepare("SELECT id FROM usuarios WHERE id = ?");
$stmtCheck->bind_param("i", $idUsuario);
$stmtCheck->execute();
$resultCheck = $stmtCheck->get_result();

if ($resultCheck->num_rows === 0) {
    echo '<script>alert("Usuario no encontrado."); window.location.href = "gestion_usuarios.php";</script>';
    exit;
}

$delete = $mysqli->prepare("DELETE FROM usuarios WHERE id = ?");
$delete->bind_param("i", $idUsuario);
$delete->execute();

cerrarConexion($mysqli);

echo '<script>alert("Usuario eliminado correctamente."); window.location.href = "gestion_usuarios.php";</script>';
?>
