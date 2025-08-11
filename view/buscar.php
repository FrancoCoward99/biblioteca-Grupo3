<?php
session_start();

if (!isset($_SESSION['nombre_usuario']) || $_SESSION['rol'] !== 'estudiante') {
    header("Location: login.php");
    exit;
}

require_once '../accesoDatos/conexion.php';
$mysqli = abrirConexion();
if (!$mysqli) {
    http_response_code(500);
    echo "Error de conexión a la base de datos.";
    exit;
}

$perPage = 9;
$page    = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset  = ($page - 1) * $perPage;
$q       = isset($_GET['q']) ? trim($_GET['q']) : '';

function buildQuery(array $extra) {
    $merged = array_merge($_GET, $extra);
    return http_build_query($merged);
}

$showPrompt = ($q === '' || mb_strlen($q) < 1);

$total = 0;
$libros = [];

if (!$showPrompt) {

    $sqlCount = "SELECT COUNT(*) AS total FROM libros WHERE titulo LIKE ?";
    $stmtC = $mysqli->prepare($sqlCount);
    $like  = "%{$q}%";
    $stmtC->bind_param("s", $like);
    $stmtC->execute();
    $total = (int)$stmtC->get_result()->fetch_assoc()['total'];
    $stmtC->close();

    $totalPages = max(1, (int)ceil($total / $perPage));
    if ($page > $totalPages) { $page = $totalPages; $offset = ($page - 1) * $perPage; }

    $sql = "
      SELECT id, titulo, autores, categoria, descripcion, imagen, portada_url, formato, cantidad_disponible
      FROM libros
      WHERE titulo LIKE ?
      ORDER BY titulo ASC, id ASC
      LIMIT ? OFFSET ?
    ";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("sii", $like, $perPage, $offset);
    $stmt->execute();
    $libros = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    $totalPages = 1;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SIBE - Resultados de búsqueda</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="../styles/estilos.css" />
</head>
<body class="body-inicio">

<?php include 'componentes/navbar_estudiante.php'; ?>

<main class="d-flex justify-content-center my-5">
  <div class="card shadow px-4 py-4" style="max-width: 1400px; width: 100%;">
    <h4 class="mb-3 fs-2"><strong>Resultados de búsqueda</strong></h4>

    <?php if ($showPrompt): ?>
      <div class="alert alert-info">Escriba un título en la barra de búsqueda.</div>

    <?php elseif ($total === 0): ?>
      <div class="alert alert-warning">No hay coincidencias</div>

    <?php else: ?>
      <div class="mb-2 text-muted">
        <?= (int)$total ?> resultado<?= $total === 1 ? '' : 's' ?> para “<?= htmlspecialchars($q) ?>”
      </div>

      <div class="d-flex flex-wrap gap-3 justify-content-start">
        <?php foreach ($libros as $libro): ?>
          <div class="card" style="width: 200px;">
            <?php
              $src = '';
              if (!empty($libro['portada_url'])) {
                  $src = htmlspecialchars($libro['portada_url']);
              } elseif (!empty($libro['imagen'])) {
                  $src = 'imagenes/' . htmlspecialchars($libro['imagen']);
              }
            ?>
            <?php if ($src): ?>
              <img src="<?= $src ?>" class="card-img-top" alt="<?= htmlspecialchars($libro['titulo']) ?>">
            <?php else: ?>
              <svg class="bd-placeholder-img card-img-top" width="100%" height="200"
                   xmlns="http://www.w3.org/2000/svg" role="img" preserveAspectRatio="xMidYMid slice">
                <rect width="100%" height="100%" fill="#e9ecef"></rect>
                <text x="50%" y="50%" fill="#6c757d" dy=".3em" text-anchor="middle">Sin imagen</text>
              </svg>
            <?php endif; ?>

            <div class="card-body text-center">
              <div class="small text-muted mb-1"><?= htmlspecialchars($libro['autores'] ?? '') ?></div>
              <div class="fw-semibold mb-2" style="min-height: 48px;"><?= htmlspecialchars($libro['titulo']) ?></div>
              <a href="detalle.php?id=<?= (int)$libro['id'] ?>" class="btn btn-success btn-sm fs-6">
                <strong>Ver Más Detalles</strong>
              </a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <nav class="mt-4">
        <ul class="pagination justify-content-center">
          <li class="page-item <?= $page<=1?'disabled':'' ?>">
            <a class="page-link" href="?<?= buildQuery(['page'=>$page-1]) ?>" tabindex="-1">« Anterior</a>
          </li>

          <?php
            $window = 2;
            $start  = max(1, $page - $window);
            $end    = min($totalPages, $page + $window);

            if ($start > 1) {
              echo '<li class="page-item"><a class="page-link" href="?'.buildQuery(['page'=>1]).'">1</a></li>';
              if ($start > 2) echo '<li class="page-item disabled"><span class="page-link">…</span></li>';
            }
            for ($p = $start; $p <= $end; $p++) {
              $active = $p === $page ? 'active' : '';
              echo '<li class="page-item '.$active.'"><a class="page-link" href="?'.buildQuery(['page'=>$p]).'">'.$p.'</a></li>';
            }
            if ($end < $totalPages) {
              if ($end < $totalPages - 1) echo '<li class="page-item disabled"><span class="page-link">…</span></li>';
              echo '<li class="page-item"><a class="page-link" href="?'.buildQuery(['page'=>$totalPages]).'">'.$totalPages.'</a></li>';
            }
          ?>

          <li class="page-item <?= $page>=$totalPages?'disabled':'' ?>">
            <a class="page-link" href="?<?= buildQuery(['page'=>$page+1]) ?>">Siguiente »</a>
          </li>
        </ul>
      </nav>
    <?php endif; ?>

  </div>
</main>

</body>
</html>
