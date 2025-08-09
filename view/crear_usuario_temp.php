<?php
require_once '../accesoDatos/conexion.php';
session_start();

$mensaje = '';
$estadoConexion = '';

$mysqli = abrirConexion();

if (!$mysqli) {
    $estadoConexion = "❌ Error al conectar con la base de datos.";
} else {
    $estadoConexion = "✅ Conexión a la base de datos establecida.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $contrasena = $_POST['contrasena'] ?? '';
    $rol = $_POST['rol'] ?? '';

    if ($nombre && $correo && $contrasena && $rol) {
        $contrasenaHash = password_hash($contrasena, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuarios (nombre, correo, contrasena, rol) VALUES (?, ?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ssss", $nombre, $correo, $contrasenaHash, $rol);

        if ($stmt->execute()) {
            $mensaje = "✅ Usuario creado correctamente.";
        } else {
            $mensaje = "❌ Error al insertar: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $mensaje = "Todos los campos son obligatorios.";
    }

    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Crear Usuario Temporal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-5">
    <div class="card shadow">
      <div class="card-header bg-primary text-white">
        <h4>Crear Usuario Temporal</h4>
      </div>
      <div class="card-body">
        <?php if ($estadoConexion): ?>
          <div class="alert alert-<?php echo strpos($estadoConexion, '✅') !== false ? 'success' : 'danger'; ?>">
            <?php echo $estadoConexion; ?>
          </div>
        <?php endif; ?>

        <?php if ($mensaje): ?>
          <div class="alert alert-info"><?php echo htmlspecialchars($mensaje); ?></div>
        <?php endif; ?>

        <?php if ($mysqli): ?>
          <form method="POST">
            <div class="mb-3">
              <label class="form-label">Nombre completo</label>
              <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Correo institucional</label>
              <input type="email" name="correo" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Contraseña</label>
              <input type="text" name="contrasena" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Rol</label>
              <select name="rol" class="form-select" required>
                <option value="estudiante">Estudiante</option>
                <option value="administrador">Administrador</option>
              </select>
            </div>
            <button type="submit" class="btn btn-success">Crear Usuario</button>
          </form>
        <?php endif; ?>
      </div>
    </div>
  </div>
</body>
</html>
