<?php
session_start();
if (!isset($_SESSION['nombre_usuario']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: login.php");
    exit;
}

require_once '../accesoDatos/conexion.php';
$mysqli = abrirConexion();

$resultado = $mysqli->query("
    SELECT 
        p.*, 
        u.nombre AS nombre_estudiante,
        l.titulo AS titulo_libro,
        l.cantidad_disponible
    FROM prestamos p
    INNER JOIN usuarios u ON p.id_usuario = u.id
    INNER JOIN libros l ON p.id_libro = l.id
    ORDER BY p.fecha_solicitud ASC
");

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
        <th>Estudiante</th>
        <th>Libro</th>
        <th>Disponibles</th>
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
        <td><?= htmlspecialchars($p['nombre_estudiante']) ?></td>
        <td><?= htmlspecialchars($p['titulo_libro']) ?></td>
        <td><?= $p['cantidad_disponible'] ?></td>
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
        <td><?= $p['fecha_respuesta'] ?? '-' ?></td>

        <td>
          <?php if ($p['estado'] === 'pendiente'): ?>
            <a href="aprobar_prestamo.php?id=<?= $p['id'] ?>" class="btn btn-outline-success btn-sm">Aprobar</a>
            <button 
              class="btn btn-outline-danger btn-sm" 
              data-bs-toggle="modal" 
              data-bs-target="#modalRechazo<?= $p['id'] ?>">
              Rechazar
            </button>
          <?php else: ?>
            <span class="text-muted">Sin acciones</span>
          <?php endif; ?>
        </td>
      </tr>

      <!-- Modal individual de rechazo por préstamo -->
      <div class="modal fade" id="modalRechazo<?= $p['id'] ?>" tabindex="-1" aria-labelledby="rechazoLabel<?= $p['id'] ?>" aria-hidden="true">
        <div class="modal-dialog">
          <form method="POST" action="rechazar_prestamo.php">
            <input type="hidden" name="id_prestamo" value="<?= $p['id'] ?>">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="rechazoLabel<?= $p['id'] ?>">Rechazar Solicitud #<?= $p['id'] ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
              </div>
              <div class="modal-body">
                <div class="mb-3">
                  <label for="comentario<?= $p['id'] ?>" class="form-label">Motivo de rechazo:</label>
                  <textarea class="form-control" name="comentario" id="comentario<?= $p['id'] ?>" required rows="3"></textarea>
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-danger">Confirmar Rechazo</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    <?php endwhile; ?>
    </tbody>
  </table>
</div>

</body>
</html>
