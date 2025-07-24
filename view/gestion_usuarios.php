<?php
session_start();

if (!isset($_SESSION['nombre_usuario']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: login.php");
    exit;
}

require_once '../accesoDatos/conexion.php';
$mysqli = abrirConexion();

$resultado = $mysqli->query("SELECT id, nombre, correo, rol,fecha_creacion,grado FROM usuarios");

if (!$resultado) {
    die("Error en la consulta: " . $mysqli->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestión de Usuarios</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="../styles/estilos.css" />
  <style>
  th {
    background-color: #d8f3dc !important;
  }

  tbody tr {
    background-color: #f1f1f1;
  }

  body {
    background-image: url('../imagenes/ChatGPT\ Image\ 8\ jun\ 2025\,\ 12_13_23\ p.m..png');
  }
</style>

</head>
<body>

<?php include 'componentes/navbar_admin.php'; ?>

<div class="container mt-4">
  <h2 class="mb-4 fw-bold text-center">Gestión de Usuarios</h2>

  <table class="table table-bordered table-hover align-middle text-center">
    <thead >
      <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Correo</th>
        <th>Rol</th>
        <th>Grado</th>
        <th>Fecha de creación</th>
        <th>Estado</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($usuario = $resultado->fetch_assoc()): ?>
        <tr>
          <td><?php echo ($usuario['id']) ?></td>
          <td><?php echo ($usuario['nombre']) ?></td>
          <td><?php echo ($usuario['correo']) ?></td>
          <td><?php echo ($usuario['rol']) ?></td>
          <td><?php echo ($usuario['grado']) ?></td>
          <td><?php echo ($usuario['fecha_creacion']) ?></td>
          <td><span class="badge bg-success">Activo</span></td>
          <td>
            <a href="editar_usuario.php?id=<?= $usuario['id'] ?>" class="btn btn-sm btn-outline-primary">
              <i class="bi bi-pencil"></i> Editar
            </a>
            <a href="eliminar_usuario.php?id=<?= $usuario['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Seguro que deseas eliminar este usuario?');">
              <i class="bi bi-trash"></i> Eliminar
            </a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
    <div class="text-center mt-3">
    <a href="añadir_usuario.php" class="btn btn-success">
      <i class="bi bi-person-plus-fill"></i> Añadir Usuario
    </a>
  </div>

</body>
</html>
