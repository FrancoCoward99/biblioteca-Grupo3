<?php

session_start();

include "../accesoDatos/conexion.php";
$conn = abrirConexion();


if (!isset($_SESSION['nombre_usuario']) || $_SESSION['rol'] !== 'estudiante') {
    header("Location: login.php");
    exit;
}

$nombre = $_SESSION['nombre_usuario'];


if (isset($_GET["eliminar"])) {
    $idEliminar = intval($_GET["eliminar"]);
    $conn->query("DELETE FROM libros WHERE id = $idEliminar");
    header("Location: agregarLibro.php");
    exit();
}


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["agregar"])) {
    $titulo = $_POST["titulo"];
    $autor = $_POST["autor"];
    $isbn = $_POST["isbn"];
    $categoria = $_POST["categoria"];

    $sql = "INSERT INTO libros (titulo, isbn, categoria, autores, formato, cantidad_disponible)
            VALUES (?, ?, ?, ?, 'Fisico', 1)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $titulo, $isbn, $categoria, $autor);
    $stmt->execute();
}

$nombre = $_SESSION['nombre_usuario'];

$libros = $conn->query("SELECT * FROM libros");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Gestión de Libros</title>
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

        <form method="POST" class="row g-3 mb-4 needs-validation" novalidate>
            <div class="col-md-6">
                <label class="form-label">Título</label>
                <input type="text" name="titulo" class="form-control" required />
            </div>
            <div class="col-md-6">
                <label class="form-label">Autor</label>
                <input type="text" name="autor" class="form-control" required />
            </div>
            <div class="col-md-6">
                <label class="form-label">ISBN</label>
                <input type="text" name="isbn" class="form-control" />
            </div>
            <div class="col-md-6">
                <label class="form-label">Categoría</label>
                <input type="text" name="categoria" class="form-control" />
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
                            <a href="?eliminar=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este libro?');">
                                <i class="bi bi-trash"></i> Eliminar
                            </a>
                        </td>
                    </tr>
                <?php } ?>
                <?php if ($libros->num_rows === 0): ?>
                    <tr><td colspan="6">No hay libros registrados.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
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
