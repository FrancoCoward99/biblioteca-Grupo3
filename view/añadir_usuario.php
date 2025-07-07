<?php 
session_start();

if (!isset($_SESSION['nombre_usuario']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: login.php");
    exit;
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../accesoDatos/conexion.php';

try {
    $mysqli = abrirConexion();
} catch (Exception $e) {
    die('Error al conectar a la base de datos: ' . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['txtNombre'] ?? '';
    $correo = $_POST['txtCorreo'] ?? '';
    $contrasena = $_POST['txtContrasena'] ?? '';
    $rol = $_POST['selRol'] ?? '';
    $grado = $_POST['txtGrado'] ?? '';

    if ($nombre === '' || $correo === '' || $contrasena === '' || $rol === '') {
        echo 'Error: Todos los campos obligatorios deben completarse.';
        exit;
    }

    $contrasenaHash = password_hash($contrasena, PASSWORD_DEFAULT);
    $sql = "INSERT INTO usuarios (nombre, correo, contrasena, rol, grado) VALUES (?, ?, ?, ?, ?)";

    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("sssss", $nombre, $correo, $contrasenaHash, $rol, $grado);
        if ($stmt->execute()) {
            $mensaje = "Usuario registrado correctamente.";
            $tipo = "success"; 
        } else {
            $mensaje = "Error al registrar el usuario.";
            $tipo = "danger"; 
        }
        $stmt->close();
    } else {
         $mensaje = "Error en la preparación de la consulta.";
         $tipo = "danger";
    }

    cerrarConexion($mysqli);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Añadir Usuario</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="../styles/estilos.css" />
</head>
<body>

<?php include 'componentes/navbar_admin.php'; ?>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow rounded">
        <div class="card-header  text-center">
          <h4>Registrar Usuario</h4>
        </div>
        <div class="card-body">
          <form method="POST" action="añadir_usuario.php">
            <div class="mb-3">
              <label for="txtNombre" class="form-label">Nombre</label>
              <input type="text" name="txtNombre" id="txtNombre" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="txtCorreo" class="form-label">Correo</label>
              <input type="email" name="txtCorreo" id="txtCorreo" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="txtContrasena" class="form-label">Contraseña</label>
              <input type="password" name="txtContrasena" id="txtContrasena" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="selRol" class="form-label">Rol</label>
              <select name="selRol" id="selRol" class="form-select" required>
                <option value="">Seleccione</option>
                <option value="administrador">Administrador</option>
                <option value="estudiante">Estudiante</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="txtGrado" class="form-label">Grado</label>
              <input type="text" name="txtGrado" id="txtGrado" class="form-control">
            </div>

            <?php if (isset($mensaje)): ?>
              <div class="alert alert-<?= $tipo ?> alert-dismissible fade show mt-3" role="alert">
                <?= $mensaje ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
              </div>
            <?php endif; ?>

            <div class="text-center">
              <button type="submit" class="btn btn-success">Registrar</button>
              <a href="gestion_usuarios.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>
