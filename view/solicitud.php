<?php 
session_start();

if (!isset($_SESSION['nombre_usuario']) || $_SESSION['rol'] !== 'estudiante') {
    header("Location: login.php");
    exit;
}

$nombre = $_SESSION['nombre_usuario'];

require_once '../accesoDatos/conexion.php';
$mysqli = abrirConexion();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_completo     = $_POST['nombre_completo'];
    $carnet_estudiantil  = $_POST['carnet_estudiantil'];
    $telefono_contacto   = $_POST['telefono_contacto'];
    $correo_electronico  = $_POST['correo_electronico'];
    $titulo_libro        = $_POST['titulo_libro'];
    $inicio_prestamo     = $_POST['inicio_prestamo'];
    $fin_prestamo        = $_POST['fin_prestamo'];

    $query = "INSERT INTO prestamos_libros 
        (nombre_completo, carnet_estudiantil, telefono_contacto, correo_electronico, titulo_libro, inicio_prestamo, fin_prestamo)
        VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("sssssss", $nombre_completo, $carnet_estudiantil, $telefono_contacto, $correo_electronico, $titulo_libro, $inicio_prestamo, $fin_prestamo);

    if ($stmt->execute()) {
        $mensaje = "¡Solicitud de préstamo registrada con éxito!";
        $tipo = "success";
    } else {
        $mensaje = "Error al registrar la solicitud. Por favor, inténtalo de nuevo.";
        $tipo = "danger";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SIBE - Solicitud</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../styles/estilos.css" />
</head>

<body class="body-inicio">

    <header class="bg-white shadow-sm px-4 py-3 d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
            <img src="Imagenes/logo.png" alt="Logo SIBE" style="height: 40px;" />
            <div class="position-relative w-100" style="max-width: 400px;">
                <input type="text" class="form-control ps-4 pe-5 borde-buscador"
                    placeholder="Digite el Título, Autor o ISBN" />
                <i class="bi bi-search position-absolute top-50 end-0 translate-middle-y me-3 text-muted"></i>
            </div>
        </div>
        <nav class="d-flex align-items-center gap-4">
            <a href="#" class="text-dark text-decoration-none">Libros</a>
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center gap-2 text-dark text-decoration-none dropdown-toggle"
                    data-bs-toggle="dropdown">
                    <span class="fw-semibold">
                        <?php echo htmlspecialchars($nombre); ?>
                    </span>
                    <i class="bi bi-person-circle fs-4"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="logout.php">Cerrar sesión</a></li>
                </ul>
            </div>
        </nav>
    </header>


    <div class="container min-vh-100 d-flex flex-column justify-content-center align-items-center">
        <div class="card shadow-lg" style="max-width: 800px; width: 100%; background: #e4fae9;">
            <div class="card-body">
                <h2 class="card-title text-center mb-4 fw-bold">Formulario de préstamo de libros</h2>
                <form method="POST" action="solicitud.php">
                    <div class="mb-3">
                        <label for="nombre_completo" class="form-label fw-bold fs-5">Nombre Completo</label>
                        <input type="text" name="nombre_completo" id="nombre_completo" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="carnet_estudiantil" class="form-label fw-bold fs-5">Número de carnet estudiantil</label>
                        <input type="text" name="carnet_estudiantil" id="carnet_estudiantil" class="form-control"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="telefono_contacto" class="form-label fw-bold fs-5">Teléfono de contacto</label>
                        <input type="text" name="telefono_contacto" id="telefono_contacto" class="form-control"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="correo_electronico" class="form-label fw-bold fs-5">Correo electrónico personal</label>
                        <input type="email" name="correo_electronico" id="correo_electronico" class="form-control"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="titulo_libro" class="form-label fw-bold fs-5">Título del libro</label>
                        <input type="text" name="titulo_libro" id="titulo_libro" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="inicio_prestamo" class="form-label fw-bold fs-5">Inicio del préstamo</label>
                        <input type="date" name="inicio_prestamo" id="inicio_prestamo" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="fin_prestamo" class="form-label fw-bold fs-5">Fin del préstamo</label>
                        <input type="date" name="fin_prestamo" id="fin_prestamo" class="form-control" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-success px-5">Enviar solicitud</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>
    </div>

</body>

</html>