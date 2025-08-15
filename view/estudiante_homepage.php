<?php
session_start();

if (!isset($_SESSION['nombre_usuario']) || $_SESSION['rol'] !== 'estudiante') {
    header("Location: login.php");
    exit;
}

$nombre = $_SESSION['nombre_usuario'];

require_once '../accesoDatos/conexion.php';
$mysqli = abrirConexion();


$query = "SELECT id, titulo, imagen FROM libros";
$result = $mysqli->query($query);
$libros = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SIBE - Inicio</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="../styles/estilos.css" />
</head>
<body class="body-inicio" style="
    background-image: url('imagenes/ChatGPT\ Image\ 8\ jun\ 2025\,\ 12_13_23\ p.m..png');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
">

<?php include 'componentes/navbar_estudiante.php'; ?>

<section class="text-center py-5">
  <h1 class="fw-bold">¡Bienvenido/a al SIBE!</h1>
  <p class="lead mt-4 fw-bold">
    Una plataforma digital creada para facilitar el acceso a<br>
    materiales educativos promoviendo su aprendizaje dentro y fuera del aula
  </p>
  <a href="catalogo.php" class="btn btn-success px-4 mt-3">Comenzar</a>
</section>

<div class="container mb-5">
  <h4 class="fw-bold text-center mb-4">Todos los libros</h4>
  <?php if (count($libros) > 0): ?>
  <div id="carruselLibros" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">

      <?php 
      $totalLibros = count($libros);
      for ($i = 0; $i < $totalLibros; $i += 3): 
      ?>
        <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
          <div class="row justify-content-center">
            <?php for ($j = $i; $j < $i + 3 && $j < $totalLibros; $j++): ?>
              <div class="col-md-4 text-center">
                <img src="imagenes/<?= htmlspecialchars($libros[$j]['imagen']) ?>" 
                     class="img-fluid rounded shadow-sm mb-2" 
                     style="max-height: 250px; object-fit: cover;">
                <div class="fw-bold"><?= htmlspecialchars($libros[$j]['titulo']) ?></div>
              </div>
            <?php endfor; ?>
          </div>
        </div>
      <?php endfor; ?>

    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carruselLibros" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Anterior</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carruselLibros" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Siguiente</span>
    </button>
  </div>
  <?php else: ?>
    <p class="text-center">No hay libros registrados en la base de datos.</p>
  <?php endif; ?>
</div>
>
<section class="container pb-5">
  <h4 class="mb-4 fw-bold">Noticias y novedades</h4>
  <div class="row g-4">

    <div class="col-md-4">
      <div class="card h-100 shadow-sm border-0">
        <img src="imagenes/Noticia1.jpg" class="card-img-top" alt="Noticia 1" style="height: 200px; object-fit: cover;">
        <div class="card-body">
          <h5 class="card-title">Nueva adquisición: Colección de Ciencia Ficción</h5>
          <p class="card-text">La biblioteca ha incorporado más de 50 títulos nuevos de autores reconocidos en el género de ciencia ficción.</p>
        </div>
        <div class="card-footer bg-white border-0 text-center">
          <a href="noticia1.php" class="btn btn-success btn-sm">Leer más</a>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card h-100 shadow-sm border-0">
        <img src="imagenes/Noticia2.jpg" class="card-img-top" alt="Noticia 2" style="height: 200px; object-fit: cover;">
        <div class="card-body">
          <h5 class="card-title">Semana del Libro</h5>
          <p class="card-text">Del 10 al 14 de septiembre, participa en nuestras charlas, talleres y presentaciones de autores locales.</p>
        </div>
        <div class="card-footer bg-white border-0 text-center">
          <a href="noticia2.php" class="btn btn-success btn-sm">Ver agenda</a>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card h-100 shadow-sm border-0">
        <img src="imagenes/Noticia3.png" class="card-img-top" alt="Noticia 3" style="height: 200px; object-fit: cover;">
        <div class="card-body">
          <h5 class="card-title">Club de Lectura Juvenil</h5>
          <p class="card-text">Únete a nuestro club de lectura para jóvenes, donde cada mes debatimos un libro seleccionado por los miembros.</p>
        </div>
        <div class="card-footer bg-white border-0 text-center">
          <a href="noticia3.php" class="btn btn-success btn-sm">Unirme</a>
        </div>
      </div>
    </div>

  </div>
</section>


</body>
</html>
