<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$nombre = htmlspecialchars($_SESSION['nombre_usuario'] ?? '');
echo '
<header class="bg-white px-4 py-3 d-flex align-items-center justify-content-between shadow-sm">
  <div class="d-flex align-items-center gap-3">
    <img src="imagenes/logo.png" alt="Logo SIBE" style="height: 40px;" />
    <div class="position-relative" style="max-width: 400px;">
      <input type="text" class="form-control ps-4 pe-5" placeholder="Digite el Título, Autor o ISBN" />
      <i class="bi bi-search position-absolute top-50 end-0 translate-middle-y me-3 text-muted"></i>
    </div>
  </div>
  <nav class="d-flex align-items-center gap-4">
  <a href="admin_homepage.php" class="text-dark text-decoration-none">Inicio</a>
    <a href="#" class="text-dark text-decoration-none">Libros</a>
    
    <div class="dropdown">
      <a href="#" class="d-flex align-items-center gap-2 text-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
        <span class="fw-semibold">Admi-' . $nombre . '</span>
        <i class="bi bi-person-circle fs-4"></i>
      </a>
      <ul class="dropdown-menu dropdown-menu-end">
        <li><a class="dropdown-item" href="logout.php">Cerrar sesión</a></li>
      </ul>
    </div>
  </nav>
</header>
';
?>
