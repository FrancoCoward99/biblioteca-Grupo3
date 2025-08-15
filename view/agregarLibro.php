<?php
session_start();
include "../accesoDatos/conexion.php";
$conn = abrirConexion();

if (!isset($_SESSION['nombre_usuario']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: login.php");
    exit;
}

$nombre = $_SESSION['nombre_usuario'];

if (isset($_GET["eliminar"])) {
    $idEliminar = intval($_GET["eliminar"]);

    $res = $conn->query("SELECT portada_url, pdf_url FROM libros WHERE id = $idEliminar");
    if ($res && $res->num_rows > 0) {
        $libro = $res->fetch_assoc();
        if (!empty($libro['portada_url']) && file_exists("../" . $libro['portada_url'])) {
            unlink("../" . $libro['portada_url']);
        }
        if (!empty($libro['pdf_url']) && file_exists("../" . $libro['pdf_url'])) {
            unlink("../" . $libro['pdf_url']);
        }
    }

    $conn->query("DELETE FROM libros WHERE id = $idEliminar");
    header("Location: agregarLibro.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["agregar"])) {
    $titulo = trim($_POST["titulo"]);
    $autor = trim($_POST["autor"]);
    $isbn = trim($_POST["isbn"]);
    $categoria = trim($_POST["categoria"]);

    if (empty($titulo) || empty($autor)) {
        echo "<div class='alert alert-danger'>Los campos Título y Autor son obligatorios.</div>";
    } else if (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', $autor)) {
        echo "<div class='alert alert-danger'>El campo Autor solo puede contener letras y espacios.</div>";
    } else if (!empty($isbn) && !preg_match('/^[0-9\-]+$/', $isbn)) {
        echo "<div class='alert alert-danger'>El campo ISBN solo puede contener números y guiones.</div>";
    } else if (!isset($_FILES["pdf"]) || $_FILES["pdf"]["error"] !== UPLOAD_ERR_OK) {
        echo "<div class='alert alert-danger'>Debe subir un archivo PDF válido.</div>";
    } else if (!isset($_FILES["portada"]) || $_FILES["portada"]["error"] !== UPLOAD_ERR_OK) {
        echo "<div class='alert alert-danger'>Debe subir una imagen de portada válida.</div>";
    } else {
        $carpetaPDF = "uploads/pdf/";
        $carpetaPortadas = "uploads/portadas/";

        if (!is_dir("../" . $carpetaPDF)) mkdir("../" . $carpetaPDF, 0777, true);
        if (!is_dir("../" . $carpetaPortadas)) mkdir("../" . $carpetaPortadas, 0777, true);

        $pdfRuta = null;
        $pdfNombre = time() . "_" . basename($_FILES["pdf"]["name"]);
        $pdfRutaCompleta = "../" . $carpetaPDF . $pdfNombre;
        if (move_uploaded_file($_FILES["pdf"]["tmp_name"], $pdfRutaCompleta)) {
            $pdfRuta = $carpetaPDF . $pdfNombre; 
        } else {
            echo "<div class='alert alert-danger'>Error al subir el archivo PDF.</div>";
            exit;
        }

        $portadaRuta = null;
        $portadaNombre = time() . "_" . basename($_FILES["portada"]["name"]);
        $portadaRutaCompleta = "../" . $carpetaPortadas . $portadaNombre;
        if (move_uploaded_file($_FILES["portada"]["tmp_name"], $portadaRutaCompleta)) {
            $portadaRuta = $carpetaPortadas . $portadaNombre;
        } else {
            echo "<div class='alert alert-danger'>Error al subir la imagen de portada.</div>";
            exit;
        }
        $sql = "INSERT INTO libros (titulo, isbn, categoria, autores, formato, cantidad_disponible, pdf_url, portada_url)
                VALUES (?, ?, ?, ?, 'Fisico', 1, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $titulo, $isbn, $categoria, $autor, $pdfRuta, $portadaRuta);
        $stmt->execute();

        header("Location: agregarLibro.php");
        exit;
    }
}

$libros = $conn->query("SELECT * FROM libros ORDER BY id DESC");

$pdfMostrar = null;
if (isset($_GET['verpdf'])) {
    $idPdf = intval($_GET['verpdf']);
    $resPdf = $conn->query("SELECT pdf_url, titulo FROM libros WHERE id = $idPdf LIMIT 1");
    if ($resPdf && $resPdf->num_rows === 1) {
        $libroPdf = $resPdf->fetch_assoc();
        if (!empty($libroPdf['pdf_url'])) {
            $pdfMostrar = $libroPdf;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Gestión de Libros</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="../styles/estilos.css" />
    <style>
        .btn-accion {
            min-width: 100px;
            margin: 2px 4px;
            white-space: nowrap;
        }
    </style>
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
                <span class="fw-semibold"><?= htmlspecialchars($nombre); ?></span>
                <i class="bi bi-person-circle fs-4"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="logout.php">Cerrar sesión</a></li>
            </ul>
        </div>
    </nav>
</header>

<div class="container my-5">
    <div class="card shadow p-4">
        <h4 class="mb-4 fs-2"><strong>Gestión de Libros</strong></h4>

        <form method="POST" enctype="multipart/form-data" class="row g-3 mb-4 needs-validation" novalidate>
            <div class="col-md-6">
                <label class="form-label">Título <span class="text-danger">*</span></label>
                <input type="text" name="titulo" class="form-control" required />
                <div class="invalid-feedback">El título es obligatorio.</div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Autor <span class="text-danger">*</span></label>
                <input type="text" name="autor" class="form-control" required
                       pattern="^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$"
                       title="Solo letras y espacios" />
                <div class="invalid-feedback">Ingrese solo letras y espacios en el autor.</div>
            </div>
            <div class="col-md-6">
                <label class="form-label">ISBN</label>
                <input type="text" name="isbn" class="form-control" pattern="^[0-9\-]+$"
                       title="Solo números y guiones" />
                <div class="invalid-feedback">Solo números y guiones permitidos en ISBN.</div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Categoría</label>
                <input type="text" name="categoria" class="form-control" />
            </div>
            <div class="col-md-6">
                <label class="form-label">Archivo PDF <span class="text-danger">*</span></label>
                <input type="file" name="pdf" class="form-control" accept="application/pdf" required />
                <div class="invalid-feedback">Debe subir un archivo PDF.</div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Portada (imagen) <span class="text-danger">*</span></label>
                <input type="file" name="portada" class="form-control" accept="image/*" required />
                <div class="invalid-feedback">Debe subir una imagen de portada.</div>
            </div>
            <div class="col-12 d-flex justify-content-end">
                <button type="submit" name="agregar" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> Agregar Libro
                </button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Autor</th>
                        <th>ISBN</th>
                        <th>Categoría</th>
                        <th>Portada</th>
                        <th>PDF</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = $libros->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['titulo']) ?></td>
                        <td><?= htmlspecialchars($row['autores']) ?></td>
                        <td><?= htmlspecialchars($row['isbn']) ?></td>
                        <td><?= htmlspecialchars($row['categoria']) ?></td>
                        <td>
                            <?php
                            if (empty($row['portada_url'])) {
                                $portada = "../imagenes/placeholder.png";
                            } else {
                                $portada = "../" . $row['portada_url'];
                            }
                            ?>
                            <img src="<?= htmlspecialchars($portada) ?>" alt="Portada" style="width:50px; height:auto;">
                        </td>
                        <td>
                            <?php if (!empty($row['pdf_url'])): ?>
                                <a href="verPDF.php?id=<?= $row['id'] ?>" target="_blank" class="btn btn-primary btn-sm btn-accion" title="Abrir PDF en nueva pestaña">
                                    Ver PDF
                                </a>
                                <a href="?verpdf=<?= $row['id'] ?>" class="btn btn-secondary btn-sm btn-accion" title="Ver PDF embebido">
                                    Vista rápida
                                </a>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="?eliminar=<?= $row['id'] ?>" class="btn btn-danger btn-sm btn-accion" onclick="return confirm('¿Eliminar este libro?');">
                                <i class="bi bi-trash"></i> Eliminar
                            </a>
                            <a href="editarLibro.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm btn-accion">
                                <i class="bi bi-pencil"></i> Editar
                            </a>
                        </td>
                    </tr>
                <?php } ?>
                <?php if ($libros->num_rows === 0): ?>
                    <tr><td colspan="8">No hay libros registrados.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if ($pdfMostrar !== null): ?>
            <div class="mt-5">
                <h4>Visualizando PDF: <?= htmlspecialchars($pdfMostrar['titulo']) ?></h4>
                <iframe src="../<?= htmlspecialchars($pdfMostrar['pdf_url']) ?>" width="100%" height="600px" style="border:1px solid #ccc;"></iframe>
                <div class="mt-2">
                    <a href="agregarLibro.php" class="btn btn-secondary">Cerrar visor</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
(() => {
    'use strict';
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();
</script>
</body>
</html>

<?php
cerrarConexion($conn);
?>



