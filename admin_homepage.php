<?php
session_start();
if (!isset($_SESSION['nombre_usuario']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: login.php");
    exit;
}
$nombre = $_SESSION['nombre_usuario'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SIBE - Administrador</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <style>
    body {
      background-color: #f8f9fa;
    }
    .card:hover {
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
      transform: scale(1.02);
      transition: 0.3s;
    }
  </style>
</head>
<body>

<header class="bg-white px-4 py-3 d-flex align-items-center justify-content-between shadow-sm">
  <div class="d-flex align-items-center gap-3">
    <img src="Imagenes/logo.png" alt="Logo SIBE" style="height: 40px;" />
    <div class="position-relative" style="max-width: 400px;">
      <input type="text" class="form-control ps-4 pe-5" placeholder="Digite el Título, Autor o ISBN" />
      <i class="bi bi-search position-absolute top-50 end-0 translate-middle-y me-3 text-muted"></i>
    </div>
  </div>
  <nav class="d-flex align-items-center gap-4">
    <a href="#" class="text-dark text-decoration-none">Libros</a>
    <div class="dropdown">
      <a href="#" class="d-flex align-items-center gap-2 text-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
        <span class="fw-semibold">¡Bienvenid@ <?php echo htmlspecialchars($nombre); ?>!</span>
        <i class="bi bi-person-circle fs-4"></i>
      </a>
      <ul class="dropdown-menu dropdown-menu-end">
        <li><a class="dropdown-item" href="logout.php">Cerrar sesión</a></li>
      </ul>
    </div>
  </nav>
</header>


<main class="container text-center py-5">
  <h2 class="fw-bold">¡Te damos la bienvenida <em><?php echo htmlspecialchars($nombre); ?></em>!</h2>
  <p class="lead mb-5">¿Qué desea gestionar hoy?</p>

  <div class="row justify-content-center g-4">
    <!-- Gestión de Usuarios -->
    <div class="col-10 col-sm-6 col-md-4">
      <div class="card p-4 h-100">
        <i class="bi bi-people-fill fs-1 text-success mb-3"></i>
        <h5 class="fw-bold">Gestión de Usuarios</h5>
        <p>Ver y administrar estudiantes registrados</p>
        <a href="gestion_usuarios.php" class="btn btn-outline-success">Ir al Módulo de Usuarios</a>
      </div>
    </div>

    <!-- Gestión de Libros -->
    <div class="col-10 col-sm-6 col-md-4">
      <div class="card p-4 h-100">
        <i class="bi bi-book-fill fs-1 text-primary mb-3"></i>
        <h5 class="fw-bold">Gestión de Libros</h5>
        <p>Ver, agregar y actualizar libros del catálogo</p>
        <a href="gestion_libros.php" class="btn btn-outline-primary">Ir al Módulo de Libros</a>
      </div>
    </div>

    <!-- Gestión de Préstamos -->
    <div class="col-10 col-sm-6 col-md-4">
      <div class="card p-4 h-100">
        <i class="bi bi-arrow-repeat fs-1 text-warning mb-3"></i>
        <h5 class="fw-bold">Gestión de Préstamos</h5>
        <p>Aprobar, rechazar y gestionar solicitudes de préstamo</p>
        <a href="admin_solicitudes.php" class="btn btn-outline-warning">Ir al Módulo de Préstamos</a>
      </div>
    </div>
  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

