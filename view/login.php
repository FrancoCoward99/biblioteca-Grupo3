<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../accesoDatos/conexion.php';

$mysqli = abrirConexion();
if (!$mysqli) {
    $fecha = date('Y-m-d H:i:s');
    $linea = "|Fecha:" . $fecha . "|Error: Error al conectar a la base de datos | Pantalla: login.php | Usuario: No autenticado\n";
    file_put_contents("../logs/errores.txt", $linea, FILE_APPEND);
    die('Error al conectar a la base de datos.');
}

$mensaje = '';
$tipoAlerta = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correoUsuario = trim($_POST['correo'] ?? '');
    $contrasenna = trim($_POST['contrasena'] ?? '');

    if ($correoUsuario !== '' && $contrasenna !== '') {
        $sql = "SELECT id, nombre, contrasena, rol FROM usuarios WHERE correo = ? LIMIT 1";

        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param('s', $correoUsuario);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows === 1) {
                $stmt->bind_result($idUsuario, $nombreUsuario, $contrasenaBase, $rolUsuario);
                $stmt->fetch();

                if (password_verify($contrasenna, $contrasenaBase)) {
                    $_SESSION['id_usuario'] = $idUsuario;
                    $_SESSION['nombre_usuario'] = $nombreUsuario;
                    $_SESSION['rol'] = $rolUsuario;

                    $stmt->close();
                    cerrarConexion($mysqli);

                    if ($rolUsuario === 'administrador') {
                        header("Location: admin_homepage.php");
                    } else {
                        header("Location: estudiante_homepage.php");
                    }
                    exit;
                } else {
                    $mensaje = "Contraseña incorrecta.";
                    $tipoAlerta = "danger";
                    $fecha = date('Y-m-d H:i:s');
                    $linea = "|Fecha:" . $fecha . "|Error: Intento de login con contraseña incorrecta | Pantalla: login.php | Usuario: " . $correoUsuario . "\n";
                    file_put_contents("../logs/errores.txt", $linea, FILE_APPEND);
                }
            } else {
                $mensaje = "El correo indicado no existe.";
                $tipoAlerta = "danger";
                $fecha = date('Y-m-d H:i:s');
                $linea = "|Fecha:" . $fecha . "|Error: Intento de login con correo no registrado | Pantalla: login.php | Usuario: " . $correoUsuario . "\n";
                file_put_contents("../logs/errores.txt", $linea, FILE_APPEND);
            }

            $stmt->close();
        } else {
            $mensaje = "Error al preparar la consulta.";
            $tipoAlerta = "danger";
            $fecha = date('Y-m-d H:i:s');
            $linea = "|Fecha:" . $fecha . "|Error: Error al preparar la consulta SQL | Pantalla: login.php | Usuario: " . $correoUsuario . "\n";
            file_put_contents("../logs/errores.txt", $linea, FILE_APPEND);
        }
    } else {
        $mensaje = "Debe ingresar usuario y contraseña.";
        $tipoAlerta = "warning";
        $fecha = date('Y-m-d H:i:s');
        $linea = "|Fecha:" . $fecha . "|Error: Intento de login sin proporcionar credenciales | Pantalla: login.php | Usuario: No proporcionado\n";
        file_put_contents("../logs/errores.txt", $linea, FILE_APPEND);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Inicio de Sesión</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../styles/estilos.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
</head>
<body>
  <div class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="row shadow rounded overflow-hidden login-cuadro">

      <div class="col-md-6 d-none d-md-block p-0">
        <img src="../view/imagenes/ArmariodeLibros-Login.jpg" 
             alt="Imagen de inicio de sesión" 
             class="img-fluid w-100 h-100" 
             style="object-fit: cover;">
      </div>

      <div class="col-md-6 login-form">
        <div class="text-center mb-4">
          <i class="bi bi-person-circle" style="font-size: 3rem;"></i>
          <h2 class="fw-bold mt-2">Inicio de Sesión</h2>
        </div>

        <?php if (!empty($mensaje)): ?>
          <div class="alert alert-<?php echo $tipoAlerta; ?> alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($mensaje); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
          </div>
        <?php endif; ?>

        <form method="POST" action="">
          <div class="mb-3">
            <input type="email" class="form-control" name="correo" placeholder="Correo estudiantil" required>
          </div>
          <div class="mb-3">
            <input type="password" class="form-control" name="contrasena" placeholder="Contraseña" required>
          </div>
          <div class="mb-3 text-end">
            <a  href="recuperar_contrasena.php">¿Olvidó su contraseña?</a>
          </div>
          <button type="submit" class="btn btn-success w-100">Iniciar</button>
        </form>
      </div>

    </div>
  </div>
</body>
</html>
