<?php
session_start();

if (!isset($_SESSION['nombre_usuario']) || $_SESSION['rol'] !== 'estudiante') {
    header("Location: login.php");
    exit;
}

$nombre = $_SESSION['nombre_usuario'];

require_once '../accesoDatos/conexion.php';
$mysqli = abrirConexion();
if (!$mysqli) {
    http_response_code(500);
    echo "Error de conexión a la base de datos.";
    exit;
}

$perPage   = 9;
$page      = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset    = ($page - 1) * $perPage;

$categoria = isset($_GET['categoria']) ? trim($_GET['categoria']) : '';

$where  = [];
$params = [];
$types  = "";

if ($categoria !== "") {
    $where[] = "categoria = ?";
    $params[] = $categoria;
    $types   .= "s";
}

$whereSql = $where ? "WHERE " . implode(" AND ", $where) : "";

$categorias = [];
$resCats = $mysqli->query("SELECT DISTINCT categoria FROM libros WHERE categoria IS NOT NULL AND categoria <> '' ORDER BY categoria ASC");
if ($resCats) {
    while ($row = $resCats->fetch_assoc()) {
        $categorias[] = $row['categoria'];
    }
}


$sqlCount = "SELECT COUNT(*) AS total FROM libros {$whereSql}";
$stmtCount = $mysqli->prepare($sqlCount);
if ($types !== "") { $stmtCount->bind_param($types, ...$params); }
$stmtCount->execute();
$total = (int)$stmtCount->get_result()->fetch_assoc()['total'];
$stmtCount->close();

$totalPages = max(1, (int)ceil($total / $perPage));
if ($page > $totalPages) { $page = $totalPages; $offset = ($page - 1) * $perPage; }

$sql = "
  SELECT id, titulo, autores, categoria, descripcion, imagen, formato, cantidad_disponible
  FROM libros
  {$whereSql}
  ORDER BY id DESC
  LIMIT ? OFFSET ?
";
$stmt = $mysqli->prepare($sql);
if ($types !== "") {
    $typesFull  = $types . "ii";
    $paramsFull = array_merge($params, [$perPage, $offset]);
    $stmt->bind_param($typesFull, ...$paramsFull);
} else {
    $stmt->bind_param("ii", $perPage, $offset);
}
$stmt->execute();
$libros = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

function buildQuerySimple(?int $pageParam = null, ?string $categoriaParam = null) {
    $out = [];
    if ($categoriaParam !== null && $categoriaParam !== '') $out['categoria'] = $categoriaParam;
    if ($pageParam !== null) $out['page'] = $pageParam;
    return http_build_query($out);
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SIBE - Catálogo de Libros</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../styles/estilos.css" />
</head>

<body class="body-inicio">

<?php include 'componentes/navbar_estudiante.php'; ?>

    <main class="d-flex justify-content-center my-5">
        <div class="card shadow px-4 py-4" style="max-width: 1400px; width: 100%;">
            <div class="row">

                <aside class="col-md-3 mb-4">
                    <div class="border rounded p-3 bg-white">
                        <h5 class="text-center mb-3 fs-4"><strong>Filtros de Búsqueda</strong></h5>

                        <form method="get" class="vstack gap-2">
                            <label class="form-label mb-1">Categoría</label>
                            <select name="categoria" class="form-select">
                                <option value="">Todas las categorías</option>
                                <?php foreach ($categorias as $cat): ?>
                                  <option value="<?= htmlspecialchars($cat) ?>" <?= $categoria===$cat?'selected':'' ?>>
                                    <?= htmlspecialchars($cat) ?>
                                  </option>
                                <?php endforeach; ?>
                            </select>

                            <div class="d-grid mt-2">
                                <button class="btn btn-primary">
                                    <i class="bi bi-funnel"></i> Aplicar
                                </button>
                                <?php if ($categoria !== ''): ?>
                                  <a class="btn btn-outline-secondary mt-2" href="?<?= buildQuerySimple(1, '') ?>">
                                      Limpiar filtro
                                  </a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </aside>

                <section class="col-md-9">
                    <h4 class="mb-3 fs-2">
                        <strong><?= $categoria !== '' ? htmlspecialchars($categoria) : 'Resultados' ?></strong>
                    </h4>

                    <?php if ($total === 0): ?>
                        <div class="alert alert-warning">No se encontraron libros con la categoría seleccionada.</div>
                    <?php else: ?>

                        <div class="d-flex flex-wrap gap-3 justify-content-start">
                            <?php foreach ($libros as $libro): ?>
                                <div class="card" style="width: 200px;">
                                    <?php if (!empty($libro['imagen'])): ?>
                                        <img src="imagenes/<?= htmlspecialchars($libro['imagen']) ?>"
                                             class="card-img-top" alt="<?= htmlspecialchars($libro['titulo']) ?>">
                                    <?php else: ?>
                                        <svg class="bd-placeholder-img card-img-top" width="100%" height="200"
                                             xmlns="http://www.w3.org/2000/svg" role="img" preserveAspectRatio="xMidYMid slice">
                                            <rect width="100%" height="100%" fill="#e9ecef"></rect>
                                            <text x="50%" y="50%" fill="#6c757d" dy=".3em" text-anchor="middle">Sin imagen</text>
                                        </svg>
                                    <?php endif; ?>
                                    <div class="card-body text-center">
                                        <div class="small text-muted mb-1">
                                            <?= htmlspecialchars($libro['autores'] ?? '') ?>
                                        </div>
                                        <div class="fw-semibold mb-2" style="min-height: 48px;">
                                            <?= htmlspecialchars($libro['titulo']) ?>
                                        </div>
                                        <a href="detalle.php?id=<?= (int)$libro['id'] ?>"
                                           class="btn btn-success btn-sm fs-6">
                                            <strong>Ver Más Detalles</strong>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <nav class="mt-4">
                          <ul class="pagination justify-content-center">
                            <li class="page-item <?= $page<=1?'disabled':'' ?>">
                              <a class="page-link" href="?<?= buildQuerySimple($page-1, $categoria) ?>" tabindex="-1">« Anterior</a>
                            </li>

                            <?php
                              $window = 2;
                              $start  = max(1, $page - $window);
                              $end    = min($totalPages, $page + $window);

                              if ($start > 1) {
                                  echo '<li class="page-item"><a class="page-link" href="?'.buildQuerySimple(1, $categoria).'">1</a></li>';
                                  if ($start > 2) echo '<li class="page-item disabled"><span class="page-link">…</span></li>';
                              }
                              for ($p = $start; $p <= $end; $p++) {
                                  $active = $p === $page ? 'active' : '';
                                  echo '<li class="page-item '.$active.'"><a class="page-link" href="?'.buildQuerySimple($p, $categoria).'">'.$p.'</a></li>';
                              }
                              if ($end < $totalPages) {
                                  if ($end < $totalPages - 1) echo '<li class="page-item disabled"><span class="page-link">…</span></li>';
                                  echo '<li class="page-item"><a class="page-link" href="?'.buildQuerySimple($totalPages, $categoria).'">'.$totalPages.'</a></li>';
                              }
                            ?>

                            <li class="page-item <?= $page>=$totalPages?'disabled':'' ?>">
                              <a class="page-link" href="?<?= buildQuerySimple($page+1, $categoria) ?>">Siguiente »</a>
                            </li>
                          </ul>
                        </nav>

                    <?php endif; ?>
                </section>
            </div>
        </div>
    </main>

</body>
</html>
