<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../accesoDatos/conexion.php';

$mysqli = abrirConexion();
if (!$mysqli) {
    die('Error al conectar a la base de datos.');
}

$mensaje = '';
$tipoAlerta = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo'] ?? '');
    $nuevaContrasena = trim($_POST['nueva_contrasena'] ?? '');

    if ($correo !== '' && $nuevaContrasena !== '') {
        $sql = "SELECT id FROM usuarios WHERE correo = ? LIMIT 1";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param('s', $correo);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows === 1) {
                $hash = password_hash($nuevaContrasena, PASSWORD_DEFAULT);
                $updateSql = "UPDATE usuarios SET contrasena = ? WHERE correo = ?";
                if ($updateStmt = $mysqli->prepare($updateSql)) {
                    $updateStmt->bind_param('ss', $hash, $correo);
                    if ($updateStmt->execute()) {
                        $mensaje = "Contraseña actualizada correctamente. Ahora puede iniciar sesión.";
                        $tipoAlerta = "success";
                    } else {
                        $mensaje = "Error al actualizar la contraseña.";
                        $tipoAlerta = "danger";
                    }
                    $updateStmt->close();
                }
            } else {
                $mensaje = "El correo ingresado no está registrado.";
                $tipoAlerta = "warning";
            }
            $stmt->close();
        }
    } else {
        $mensaje = "Debe completar todos los campos.";
        $tipoAlerta = "warning";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Recuperar Contraseña</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../styles/estilos.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
</head>
<body>
  <div class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="row shadow rounded overflow-hidden login-cuadro">

      <div class="col-md-6 d-none d-md-block p-0">
        <img src="../view/imagenes/ArmariodeLibros-Login.jpg" 
             alt="Imagen de recuperar contraseña" 
             class="img-fluid w-100 h-100" 
             style="object-fit: cover;">
      </div>
      <div class="col-md-6 login-form">
        <div class="text-center mb-4">
          <i class="bi bi-key-fill" style="font-size: 3rem;"></i>
          <h2 class="fw-bold mt-2">Recuperar Contraseña</h2>
        </div>

        <?php if (!empty($mensaje)): ?>
          <div class="alert alert-<?php echo $tipoAlerta; ?> alert-dismissible fade show" role="alert">
            <?php echo ($mensaje); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
          </div>
        <?php endif; ?>

        <form method="POST" action="">
          <div class="mb-3">
            <label for="correo" class="form-label">Correo registrado</label>
            <input type="email" class="form-control" name="correo" id="correo" required>
          </div>
          <div class="mb-3">
            <label for="nueva_contrasena" class="form-label">Nueva contraseña</label>
            <input type="password" class="form-control" name="nueva_contrasena" id="nueva_contrasena" required>
          </div>
          <button type="submit" class="btn btn-success w-100">Actualizar Contraseña</button>
        </form>

        <div class="text-center mt-3">
          <a href="login.php">Volver al inicio de sesión</a>
        </div>
      </div>

    </div>
  </div>
  
</body>
</html>
