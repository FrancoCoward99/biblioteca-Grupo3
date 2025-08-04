<?php
session_start();
if (!isset($_SESSION['nombre_usuario']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: login.php");
    exit;
}

require_once '../accesoDatos/conexion.php';
$mysqli = abrirConexion();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_prestamo'] ?? null;
    $comentario = $_POST['comentario'] ?? '';

    if ($id && $comentario !== '') {
        $sql = "UPDATE prestamos 
                SET estado = 'rechazado', 
                    fecha_respuesta = NOW(),
                    comentario_rechazo = ?
                WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("si", $comentario, $id);

        if ($stmt->execute()) {
            header("Location: admin_solicitudes.php?msg=rechazado");
            exit;
        } else {
            echo "Error al actualizar el estado del préstamo.";
        }
    } else {
        echo "Datos incompletos.";
    }
} else {
    echo "Acceso no permitido.";
}
?>
