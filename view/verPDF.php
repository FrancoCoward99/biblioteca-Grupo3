<?php
session_start();
include "../accesoDatos/conexion.php";
$conn = abrirConexion();

include "componentes/navbar_admin.php";

$idLibro = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($idLibro <= 0) {
    die("ID de libro inválido.");
}

$sql = "SELECT titulo, pdf_url, portada_url FROM libros WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idLibro);
$stmt->execute();
$result = $stmt->get_result();
$libro = $result->fetch_assoc();
$stmt->close();

if (!$libro || empty($libro['pdf_url']) || !file_exists("../" . $libro['pdf_url'])) {
    die("No se encontró el PDF del libro o no existe el archivo.");
}

$resLibrosPdf = $conn->query("SELECT id, titulo, portada_url FROM libros WHERE pdf_url IS NOT NULL AND pdf_url <> '' ORDER BY titulo ASC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Ver PDF - <?= htmlspecialchars($libro['titulo']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../styles/estilos.css">
    <style>
        body {
            background: #f8f9fa;
        }
        .pdf-viewer {
            width: 100%;
            height: 600px;
            border: 1px solid #ddd;
        }
        .lista-libros {
            max-height: 600px;
            overflow-y: auto;
        }
        .list-group-item {
            display: block !important;
            border-radius: 8px;
            transition: background-color 0.3s;
            width: 120px;
            text-align: center;
            padding: 10px;
            margin-bottom: 10px;
        }
        .list-group-item.active {
            background-color: #0d6efd !important;
            color: white !important;
        }
        .list-group-item img {
            width: 100px;
            height: 130px;
            object-fit: contain;
            border-radius: 4px;
            margin-bottom: 8px;
        }
    </style>
</head>
<body class="body-inicio">

<div class="container">
    <!-- <h3 class="mb-4 text-center"><?= htmlspecialchars($libro['titulo']) ?></h3> -->
    <div class="row">
        <div class="col-md-8 mb-4">
            <embed src="../<?= htmlspecialchars($libro['pdf_url']) ?>" type="application/pdf" class="pdf-viewer" />
        </div>

        <div class="col-md-4 lista-libros">
            <?php if ($resLibrosPdf && $resLibrosPdf->num_rows > 0): ?>
                <div class="list-group d-flex flex-wrap justify-content-center gap-3">
                <?php while ($lib = $resLibrosPdf->fetch_assoc()):
                    $portada = !empty($lib['portada_url']) && file_exists("../" . $lib['portada_url']) 
                               ? "../" . $lib['portada_url'] 
                               : "../imagenes/placeholder.png";
                    $activo = $lib['id'] == $idLibro ? 'active' : '';
                ?>
                    <a href="verPDF.php?id=<?= (int)$lib['id'] ?>" class="list-group-item list-group-item-action <?= $activo ?>">
                        <img src="<?= htmlspecialchars($portada) ?>" alt="Portada <?= htmlspecialchars($lib['titulo']) ?>" />
                        <div style="font-size: 0.9rem; font-weight: 600; color: <?= $activo ? 'white' : '#212529' ?>;">
                            <?= htmlspecialchars($lib['titulo']) ?>
                        </div>
                    </a>
                <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p class="text-center">No hay libros con PDF disponibles.</p>
            <?php endif; ?>
        </div>
    </div>
</div>


</body>
</html>

<?php
cerrarConexion($conn);
?>


