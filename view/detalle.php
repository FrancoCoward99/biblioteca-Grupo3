<?php
session_start();

if (!isset($_SESSION['nombre_usuario']) || $_SESSION['rol'] !== 'estudiante') {
    header("Location: login.php");
    exit;
}

$nombre = $_SESSION['nombre_usuario'];

require_once '../accesoDatos/conexion.php';
$mysqli = abrirConexion();

if(!isset($_GET['id'])){
    echo "ID de libro no proporcionado.";
    exit;
}

$id = intval($_GET['id']);

$query = "SELECT * FROM libros WHERE id = ?";
$stmt = $mysqli-> prepare($query);
$stmt -> bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();

if($resultado->num_rows === 0){
    echo "Libro no encontrado, lo sentimos.";
    exit;
}

$libro = $resultado->fetch_assoc();
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Detalle de Libros</title>
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

    <main class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="card shadow mx-aut w-100" style="max-width: 1500px; min-height: 700px;">
            <div class="row g-0">


                <aside class="col-md-4 text-center p-5">
                    <img src="imagenes/<?php echo htmlspecialchars($libro['imagen']); ?>" alt="Portada del libro"
                        class="img-fluid rounded" style="max-height: 400px;" />
                </aside>


                <section class="col-md-8 p-4 fs-4">
                    <h4 class="fw-bold text-success fs-2">
                        <?php echo htmlspecialchars($libro['titulo']); ?>
                    </h4>
                    <p><strong style="color: #52b788;">ISBN:</strong>
                        <?php echo htmlspecialchars($libro['isbn']); ?>
                    </p>
                    <p><strong style="color: #52b788;">Categoría:</strong>
                        <?php echo htmlspecialchars($libro['categoria']); ?>
                    </p>
                    <p><strong style="color: #52b788;">Autores:</strong>
                        <?php echo htmlspecialchars($libro['autores']); ?>
                    </p>
                    <p><strong style="color: #52b788;">Formato:</strong>
                        <?php echo htmlspecialchars($libro['formato']); ?>
                    </p>
                    <p><strong style="color: #52b788;">Disponibles:</strong>
                        <?php echo htmlspecialchars($libro['cantidad_disponible']); ?>
                    </p>

                    <hr>
                    <h6 class="fs-4"><strong>Descripción:</strong></h6>
                    <p class="text-justify">
                        <?php echo nl2br(htmlspecialchars($libro['descripcion'])); ?>
                    </p>

                    <div class="mt-3 d-flex gap-2">
                        <a href="catalogo.php" class="btn btn-outline-secondary fs-4">← Volver al catálogo</a>
                        <a href="solicitud.php" class="btn btn-success fs-4">Solicitar Préstamo</a>
                    </div>
                </section>

            </div>
        </div>
    </main>
</body>

</html>