<?php
session_start();
include "../accesoDatos/conexion.php";
$conn = abrirConexion();

if (!isset($_SESSION['nombre_usuario']) || $_SESSION['rol'] !== 'estudiante') {
    header("Location: login.php");
    exit;
}

$nombre = $_SESSION['nombre_usuario'];

$id = intval($_GET["id"]);
$result = $conn->query("SELECT * FROM libros WHERE id = $id");

if ($result->num_rows === 0) {
    echo "Libro no encontrado.";
    exit;
}

$libro = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titulo = $_POST["titulo"];
    $autor = $_POST["autor"];
    $isbn = $_POST["isbn"];
    $categoria = $_POST["categoria"];
    $formato = $_POST["formato"];

    $sql = "UPDATE libros SET titulo=?, autores=?, isbn=?, categoria=?, formato=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $titulo, $autor, $isbn, $categoria, $formato, $id);
    $stmt->execute();

    header("Location: agregarLibro.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Edición de libros</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
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


                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="logout.php">Cerrar sesión</a></li>
                </ul>
            </div>
        </nav>
</header>

<main class="container mt-5 d-flex justify-content-center">
    <div class="card shadow p-4" style="max-width: 700px; width: 100%;">
        <h2 class="mb-4 text-center">Editar Libro</h2>
        <form method="POST" class="needs-validation" novalidate>
            <div class="mb-3">
                <label for="titulo" class="form-label">Título</label>
                <input id="titulo" type="text" class="form-control" name="titulo" value="<?= htmlspecialchars($libro['titulo']) ?>" required />
            </div>
            <div class="mb-3">
                <label for="autor" class="form-label">Autor</label>
                <input id="autor" type="text" class="form-control" name="autor" value="<?= htmlspecialchars($libro['autores']) ?>" required />
            </div>
            <div class="mb-3">
                <label for="isbn" class="form-label">ISBN</label>
                <input id="isbn" type="text" class="form-control" name="isbn" value="<?= htmlspecialchars($libro['isbn']) ?>" />
            </div>
            <div class="mb-3">
                <label for="categoria" class="form-label">Categoría</label>
                <input id="categoria" type="text" class="form-control" name="categoria" value="<?= htmlspecialchars($libro['categoria']) ?>" />
            </div>
            <div class="mb-4">
                <label for="formato" class="form-label">Formato</label>
                <select id="formato" name="formato" class="form-select" required>
                    <option value="Fisico" <?= $libro['formato'] === 'Fisico' ? 'selected' : '' ?>>Físico</option>
                    <option value="Digital" <?= $libro['formato'] === 'Digital' ? 'selected' : '' ?>>Digital</option>
                </select>
            </div>
            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save"></i> Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</main>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php cerrarConexion($conn); ?>

