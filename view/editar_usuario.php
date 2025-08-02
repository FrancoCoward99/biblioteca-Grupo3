<?php
session_start();

if (!isset($_SESSION['nombre_usuario']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: login.php");
    exit;
}

require_once '../accesoDatos/conexion.php';
$mysqli = abrirConexion();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: gestion_usuarios.php");
    exit;
}

$id = (int) $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $rol = $_POST['rol'];
    $grado = $_POST['grado'];

    $stmt = $mysqli->prepare("UPDATE usuarios SET nombre = ?, correo = ?, rol = ?, grado = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $nombre, $correo, $rol, $grado, $id);
    $stmt->execute();
    $stmt->close();
    cerrarConexion($mysqli);

    header("Location: gestion_usuarios.php");
    exit;
}

$stmt = $mysqli->prepare("SELECT nombre, correo, rol, grado FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();
$stmt->close();
cerrarConexion($mysqli);

if (!$usuario) {
    header("Location: gestion_usuarios.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Usuario</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
  body{
  background-image: url('../imagenes/ChatGPT\ Image\ 8\ jun\ 2025\,\ 12_13_23\ p.m..png');
    }
</style>
</head>
<body>
<?php include 'componentes/navbar_admin.php'; ?>

<div class="container mt-5">
  <form method="POST" class="border p-4 rounded bg-light">
    <h2 class="mb-4">Editar Usuario</h2>
    <div class="mb-3">
      <label for="nombre" class="form-label">Nombre</label>
      <input type="text" name="nombre" id="nombre" class="form-control" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
    </div>
    <div class="mb-3">
      <label for="correo" class="form-label">Correo</label>
      <input type="email" name="correo" id="correo" class="form-control" value="<?= htmlspecialchars($usuario['correo']) ?>" required>
    </div>
    <div class="mb-3">
        <label for="rol" class="form-label">Rol</label>
        <select name="rol" id="rol" class="form-select" required>
            <option value="administrador" <?= $usuario['rol'] === 'administrador' ? 'selected' : '' ?>>Administrador</option>
            <option value="estudiante" <?= $usuario['rol'] === 'estudiante' ? 'selected' : '' ?>>Estudiante</option>
        </select>
    </div>

    <div class="mb-3">
      <label for="grado" class="form-label">Grado</label>
      <input type="text" name="grado" id="grado" class="form-control" value="<?= htmlspecialchars($usuario['grado']) ?>">
    </div>
    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    <a href="gestion_usuarios.php" class="btn btn-secondary">Cancelar</a>
  </form>
</div>

</body>
</html>
