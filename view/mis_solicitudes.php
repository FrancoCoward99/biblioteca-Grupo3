<?php
session_start();

if (!isset($_SESSION['nombre_usuario']) || $_SESSION['rol'] !== 'estudiante') {
    header("Location: login.php");
    exit;
}

$nombre = $_SESSION['nombre_usuario'];
$id_usuario = $_SESSION['id_usuario'] ?? 0;

require_once '../accesoDatos/conexion.php';
$mysqli = abrirConexion();

$sql = "SELECT l.titulo, p.fecha_solicitud, p.estado, p.comentario_rechazo 
        FROM prestamos p 
        INNER JOIN libros l ON p.id_libro = l.id
        WHERE p.id_usuario = ?
        ORDER BY p.fecha_solicitud DESC";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Mis Solicitudes</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="../styles/estilos.css" />
</head>
<body class="body-inicio">

<?php include 'componentes/navbar_estudiante.php'; ?>

<main class="container py-5">
  <div class="card shadow">
    <div class="card-header bg-success text-white">
      <h5 class="mb-0">Historial de Solicitudes de Préstamo</h5>
    </div>
    <div class="card-body">
      <?php if ($resultado->num_rows > 0): ?>
        <div class="table-responsive">
          <table class="table table-bordered align-middle">
            <thead class="table-light">
              <tr>
                <th>Título del Libro</th>
                <th>Fecha de Solicitud</th>
                <th>Estado</th>
                <th>Comentario</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($fila = $resultado->fetch_assoc()): ?>
                <tr>
                  <td><?= htmlspecialchars($fila['titulo']) ?></td>
                  <td><?= htmlspecialchars(date('d-m-Y', strtotime($fila['fecha_solicitud']))) ?></td>
                  <td>
                    <?php
                      $estado = $fila['estado'];
                      $badge = match ($estado) {
                          'aprobado' => 'success',
                          'rechazado' => 'danger',
                          default => 'warning'
                      };
                    ?>
                    <span class="badge bg-<?= $badge ?> text-uppercase"><?= $estado ?></span>
                  </td>
                  <td>
                    <?php 
                      if ($estado === 'rechazado') {
                          echo !empty($fila['comentario_rechazo']) 
                              ? htmlspecialchars($fila['comentario_rechazo']) 
                              : 'Sin comentario';
                      } else {
                          echo '-';
                      }
                    ?>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <p class="text-center">No has realizado solicitudes de préstamo todavía.</p>
      <?php endif; ?>
    </div>
  </div>
</main>

</body>
</html>
