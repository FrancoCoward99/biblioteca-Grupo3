<?php
session_start();
if (!isset($_SESSION['nombre_usuario']) || $_SESSION['rol'] !== 'estudiante') {
    header("Location: login.php");
    exit;
}

require_once '../accesoDatos/conexion.php';
$conn = abrirConexion();
if (!$conn) { http_response_code(500); echo "Error de conexión a la base de datos."; exit; }

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { die("ID de libro inválido."); }

$sql = "SELECT id, titulo, autores, categoria, descripcion, formato,
 cantidad_disponible, imagen, portada_url, pdf_url
 FROM libros WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$libro = $res->fetch_assoc();
$stmt->close();

if (!$libro) { die("Libro no encontrado."); }

function isUrl(string $path): bool {
    return stripos($path, 'http://') === 0 || stripos($path, 'https://') === 0;
}
function portadaSrc(array $libro): ?string {
    if (!empty($libro['portada_url'])) return $libro['portada_url']; 
    if (!empty($libro['imagen']))      return 'imagenes/' . $libro['imagen'];
    return null;
}
function pdfDisponible(array $libro): bool {
    if (empty($libro['pdf_url'])) return false;
    if (isUrl($libro['pdf_url'])) return true;
    $localPath = "../" . ltrim($libro['pdf_url'], '/');
    return file_exists($localPath);
}

$hayPdf  = pdfDisponible($libro);
$disp    = (int)$libro['cantidad_disponible'];
$msg     = isset($_GET['msg']) ? $_GET['msg'] : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Detalle — <?= htmlspecialchars($libro['titulo']) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../styles/estilos.css" />
</head>
<body class="body-inicio">

<?php include 'componentes/navbar_estudiante.php'; ?>

<main class="container my-4">
  <?php if ($msg === 'pdfnotfound'): ?>
    <div class="alert alert-danger">El archivo PDF no se encontró en el servidor.</div>
  <?php elseif ($msg === 'nopdf'): ?>
    <div class="alert alert-warning">Este libro no tiene PDF disponible.</div>
  <?php endif; ?>

  <div class="card shadow-sm">
    <div class="row g-0">
      <div class="col-md-4 p-4 d-flex align-items-center justify-content-center">
        <?php $src = portadaSrc($libro); ?>
        <?php if ($src): ?>
          <img src="<?= htmlspecialchars($src) ?>" alt="Portada"
               class="img-fluid rounded" style="max-height:360px; object-fit:contain;">
        <?php else: ?>
          <svg class="bd-placeholder-img" width="100%" height="320" xmlns="http://www.w3.org/2000/svg">
            <rect width="100%" height="100%" fill="#e9ecef"></rect>
            <text x="50%" y="50%" text-anchor="middle" fill="#6c757d">Sin imagen</text>
          </svg>
        <?php endif; ?>
      </div>

      <div class="col-md-8 p-4">
        <h1 class="h4 mb-2"><?= htmlspecialchars($libro['titulo']) ?></h1>
        <div class="text-muted mb-3">
          <?= htmlspecialchars($libro['autores'] ?? '') ?>
          <?= !empty($libro['categoria']) ? ' · '.htmlspecialchars($libro['categoria']) : '' ?>
          <?= !empty($libro['formato'])   ? ' · '.htmlspecialchars($libro['formato'])   : '' ?>
        </div>

        <p class="mb-3"><?= nl2br(htmlspecialchars($libro['descripcion'] ?? '')) ?></p>

        <div class="mb-3">
          <span class="badge bg-<?= $disp > 0 ? 'success' : 'secondary' ?>">Disponibles: <?= $disp ?></span>
        </div>

        <div class="d-flex flex-wrap gap-2">
          <?php if ($hayPdf): ?>
            <a href="verPDF.php?id=<?= (int)$libro['id'] ?>" class="btn btn-primary">
              Ver PDF
            </a>
          <?php endif; ?>

          <?php if ($disp > 0): ?>
            <a href="solicitud.php?id=<?= (int)$libro['id'] ?>" class="btn btn-success">
              Solicitar préstamo
            </a>
          <?php endif; ?>

          <a href="catalogo.php" class="btn btn-outline-secondary">Volver al catálogo</a>
        </div>
      </div>
    </div>
  </div>
</main>

</body>
</html>

<?php cerrarConexion($conn); ?>
