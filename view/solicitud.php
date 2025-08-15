<?php
session_start();

if (!isset($_SESSION['nombre_usuario']) || $_SESSION['rol'] !== 'estudiante') {
    header("Location: login.php");
    exit;
}

$nombre = $_SESSION['nombre_usuario'];
$correo = $_SESSION['correo_usuario'] ?? '';
$id_usuario = $_SESSION['id_usuario'] ?? 0;

require_once '../accesoDatos/conexion.php';
$mysqli = abrirConexion();

if (!isset($_GET['id'])) {
    echo "ID de libro no proporcionado.";
    exit;
}

$id_libro = intval($_GET['id']);
$query = "SELECT titulo FROM libros WHERE id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $id_libro);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Libro no encontrado.";
    exit;
}

$libro = $result->fetch_assoc();
$titulo_libro = $libro['titulo'];

$mensaje = '';
$tipo = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $query = "INSERT INTO prestamos 
        (id_usuario, id_libro, estado, fecha_solicitud) 
        VALUES (?, ?, 'pendiente', NOW())";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ii", $id_usuario, $id_libro);

    if ($stmt->execute()) {
        $mensaje = "¡Solicitud de préstamo registrada con éxito!";
        $tipo = "success";
        $mostrarBoton = true;
    } else {
        $mensaje = "Error al registrar la solicitud. Por favor, inténtalo de nuevo.";
        $tipo = "danger";
        $mostrarBoton = false;
    }
}


?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>SIBE - Solicitud de Préstamo</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">

<?php include 'componentes/navbar_estudiante.php'; ?>
  <div class="container min-vh-100 d-flex flex-column justify-content-center align-items-center">
    <div class="card shadow" style="max-width: 700px; width: 100%;">
      <div class="card-body">
        <h2 class="text-center fw-bold mb-4">Formulario de Préstamo</h2>

        
 <?php if ($mensaje) { ?>
  <div class="alert alert-<?= $tipo ?> text-center fs-5">
    <?= $mensaje ?>
    <?php if (!empty($mostrarBoton)) { ?>
      <div class="mt-3">
        <a href="mis_solicitudes.php" class="btn btn-success px-4">Ver mis solicitudes</a>
      </div>
    <?php } ?>
  </div>
<?php } ?>
        <form method="POST">
          <div class="mb-3">
            <label class="form-label fw-bold">Nombre del estudiante</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($nombre) ?>" readonly>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold">Título del libro</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($titulo_libro) ?>" readonly>
          </div>

          <div class="mb-3">
            <div class="alert alert-info mb-0">
              Recuerda que el préstamo es por un máximo de <strong>2 semanas</strong>. Entrega oportuna es responsabilidad del estudiante.
            </div>
          </div>

          <div class="text-center">
            <button type="submit" class="btn btn-success px-5">Enviar solicitud</button>
            <a href="catalogo.php" class="btn btn-outline-secondary">Volver al catálogo</a>
          </div>
        </form>
      </div>
    </div>
  </div>

</body>
</html>
