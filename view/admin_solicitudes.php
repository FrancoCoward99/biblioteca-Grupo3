<?php
session_start();
if (!isset($_SESSION['nombre_usuario']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: login.php");
    exit;
}

require_once '../accesoDatos/conexion.php';
$mysqli = abrirConexion();

$resultado = $mysqli->query("SELECT * FROM prestamos ORDER BY fecha_solicitud ASC");

if (!$resultado) {
    die("Error en la consulta: " . $mysqli->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestión de Préstamos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<?php include 'componentes/navbar_admin.php'; ?>

<div class="container mt-4">
  <h2 class="mb-4">Gestión de Préstamos</h2>

  <table class="table table-bordered table-hover align-middle">
    <thead class="table-warning">
      <tr>
        <th>ID</th>
        <th>ID Usuario</th>
        <th>ID Libro</th>
        <th>Estado</th>
        <th>Fecha Solicitud</th>
        <th>Fecha Aprobación</th>
        <th>Acciones</th>
      </tr>
    </thead>

    <tbody>
      <?php while ($p = $resultado->fetch_assoc()): ?>
        <tr>
          <td><?= $p['id'] ?></td>
          <td><?= $p['id_usuario'] ?></td>
          <td><?= $p['id_libro'] ?></td>
          <td>
            <?php
              switch ($p['estado']) {
                case 'pendiente':
                  echo '<span class="badge bg-warning text-dark">Pendiente</span>';
                  break;
                case 'aprobado':
                  echo '<span class="badge bg-success">Aprobado</span>';
                  break;
                case 'rechazado':
                  echo '<span class="badge bg-danger">Rechazado</span>';
                  break;
              }
            ?>
          </td>
          <td><?= $p['fecha_solicitud'] ?></td>
          <td><?= $p['fecha_aprobacion'] ?? '-' ?></td>
          <td>
            <?php if ($p['estado'] === 'pendiente'): ?>
              <a href="aprobar_prestamo.php?id=<?= $p['id'] ?>" class="btn btn-outline-success btn-sm">Aprobar</a>
              <a href="rechazar_prestamo.php?id=<?= $p['id'] ?>" class="btn btn-outline-danger btn-sm">Rechazar</a>
            <?php else: ?>
              <span class="text-muted">Sin acciones</span>
            <?php endif; ?>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

</body>
</html>
