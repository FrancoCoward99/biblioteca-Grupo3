<?php
session_start();

if (!isset($_SESSION['nombre_usuario']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    echo "No se recibió el ID del libro.";
    exit;
}

$nombre = $_SESSION['nombre_usuario'];
$id = intval($_GET["id"]);

include "../accesoDatos/conexion.php";
$conn = abrirConexion();
if (!$conn) {
    die("Error al conectar con la base de datos.");
}

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

    // Mantener ruta actual de portada y pdf por defecto
    $imagenNombre = $libro['portada_url'];
    $pdfNombre = $libro['pdf_url'];

    // Carpeta de portadas y pdfs
    $carpetaPortadas = "../uploads/portadas/";
    $carpetaPDF = "../uploads/pdf/";

    // Procesar nueva imagen (si se sube)
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['imagen']['tmp_name'];
        $nombreArchivo = time() . '_' . basename($_FILES['imagen']['name']);
        $rutaDestino = $carpetaPortadas . $nombreArchivo;

        if (move_uploaded_file($tmpName, $rutaDestino)) {
            $imagenNombre = "uploads/portadas/" . $nombreArchivo;
        } else {
            echo "Error al mover la imagen.";
            exit;
        }
    }

    // Procesar nuevo PDF (si se sube)
    if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] === UPLOAD_ERR_OK) {
        $tmpNamePDF = $_FILES['pdf']['tmp_name'];
        $nombreArchivoPDF = time() . '_' . basename($_FILES['pdf']['name']);
        $rutaDestinoPDF = $carpetaPDF . $nombreArchivoPDF;

        if (move_uploaded_file($tmpNamePDF, $rutaDestinoPDF)) {
            $pdfNombre = "uploads/pdf/" . $nombreArchivoPDF;
        } else {
            echo "Error al mover el archivo PDF.";
            exit;
        }
    }

    $sql = "UPDATE libros SET titulo=?, autores=?, isbn=?, categoria=?, formato=?, portada_url=?, pdf_url=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error en prepare: " . $conn->error);
    }

    $stmt->bind_param("sssssssi", $titulo, $autor, $isbn, $categoria, $formato, $imagenNombre, $pdfNombre, $id);
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

<?php include "componentes/navbar_admin.php"; ?>

<main class="container mt-5 d-flex justify-content-center">
    <div class="card shadow p-4" style="max-width: 700px; width: 100%;">
        <h2 class="mb-4 text-center">Editar Libro</h2>
        <form method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
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
            <div class="mb-3">
                <label for="imagen" class="form-label">Portada (Imagen)</label>
                <input id="imagen" type="file" name="imagen" class="form-control" accept="image/*" />
                <?php if (!empty($libro['portada_url'])): ?>
                    <img src="../<?= htmlspecialchars($libro['portada_url']) ?>" alt="Portada actual" style="max-height: 150px; margin-top: 10px;">
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label for="pdf" class="form-label">Archivo PDF</label>
                <input id="pdf" type="file" name="pdf" class="form-control" accept="application/pdf" />
                <?php if (!empty($libro['pdf_url'])): ?>
                    <a href="../<?= htmlspecialchars($libro['pdf_url']) ?>" target="_blank" class="btn btn-primary mt-2">Ver PDF actual</a>
                <?php endif; ?>
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


