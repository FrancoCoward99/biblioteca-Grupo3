<?php
session_start();

if (!isset($_SESSION['nombre_usuario']) || $_SESSION['rol'] !== 'estudiante') {
    header("Location: login.php");
    exit;
}

$nombre = $_SESSION['nombre_usuario'];
?>

<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SIBE - Catálogo de Libros</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="../styles/estilos.css" />
</head>

<body>
  <header class="bg-white shadow-sm px-4 py-3 d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center gap-3">
      <img src="Imagenes/logo.png" alt="Logo SIBE" style="height: 40px;" />
      <div class="position-relative w-100" style="max-width: 400px;">
        <input type="text" class="form-control ps-4 pe-5 borde-buscador" placeholder="Digite el Título, Autor o ISBN" />
        <i class="bi bi-search position-absolute top-50 end-0 translate-middle-y me-3 text-muted"></i>
      </div>
    </div>
    <nav class="d-flex align-items-center gap-4">
      <a href="#" class="text-dark text-decoration-none">Libros</a>
      <div class="dropdown">
        <a href="#" class="d-flex align-items-center gap-2 text-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
          <span class="fw-semibold"><?php echo htmlspecialchars($nombre); ?></span>
          <i class="bi bi-person-circle fs-4"></i>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="logout.php">Cerrar sesión</a></li>
        </ul>
      </div>
    </nav>
  </header>

  <div class="container my-5">
    <div class="row justify-content-center">
      <div class="col-lg-10 d-flex">
        
        <aside class="me-4" style="min-width: 220px;">
          <div class="border rounded p-3 bg-white filtros-caja">
            <h5 class="mb-3">Filtros de Búsqueda</h5>
            <div class="form-check"><input class="form-check-input" type="checkbox" checked><label class="form-check-label">ISBN</label></div>
            <div class="form-check"><input class="form-check-input" type="checkbox" checked><label class="form-check-label">Autor</label></div>
            <div class="form-check"><input class="form-check-input" type="checkbox" checked><label class="form-check-label">Fecha de Publicación</label></div>
            <div class="form-check"><input class="form-check-input" type="checkbox" checked><label class="form-check-label">Categoría</label></div>
          </div>
        </aside>

        <section class="flex-grow-1">

          <h4 class="mb-3">Tecnología:</h4>
          <div class="d-flex flex-wrap justify-content-center gap-3 mb-5">
            <div class="card" style="width: 200px;">
              <img src="imagenes/computernetworking.jpg" class="card-img-top libro-img" alt="Computer Networking">
              <div class="card-body text-center">
                <a href="detalle.php?id=2" class="btn btn-success btn-sm">Ver Más Detalles</a>
              </div>
            </div>
            <div class="card" style="width: 200px;">
              <img src="imagenes/cleancode.jpg" class="card-img-top libro-img" alt="Clean Code">
              <div class="card-body text-center">
                <a href="detalle.php?id=1" class="btn btn-success btn-sm">Ver Más Detalles</a>
              </div>
            </div>
            <div class="card" style="width: 200px;">
              <img src="imagenes/softwareengineer.jpg" class="card-img-top libro-img" alt="Software Engineering">
              <div class="card-body text-center">
                <a href="detalle.php?id=3" class="btn btn-success btn-sm">Ver Más Detalles</a>
              </div>
            </div>
          </div>

          <h4 class="mb-3">Química:</h4>
          <div class="d-flex flex-wrap justify-content-center gap-3">
            <div class="card" style="width: 200px;">
              <img src="imagenes/principiosquimica.jpg" class="card-img-top" alt="Principios de Química">
              <div class="card-body text-center">
                <a href="detalle.php?id=4" class="btn btn-success btn-sm">Ver Más Detalles</a>
              </div>
            </div>
            <div class="card" style="width: 200px;">
              <img src="imagenes/problemasquimica.png" class="card-img-top" alt="100 Problemas de Química General">
              <div class="card-body text-center">
                <a href="#" class="btn btn-success btn-sm">Ver Más Detalles</a>
              </div>
            </div>
            <div class="card" style="width: 200px;">
              <img src="imagenes/quimicaorganica.png" class="card-img-top" alt="Química Orgánica">
              <div class="card-body text-center">
                <a href="detalle.php?id=2" class="btn btn-success btn-sm">Ver Más Detalles</a>
              </div>
            </div>
          </div>
        </section>
        
      </div>
    </div>
  </div>
</body>
</html>
