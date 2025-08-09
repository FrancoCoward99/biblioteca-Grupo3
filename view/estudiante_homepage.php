<?php
session_start();

if (!isset($_SESSION['nombre_usuario']) || $_SESSION['rol'] !== 'estudiante') {
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
  <title>SIBE - Inicio</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="../styles/estilos.css" />
</head>
<body class="body-inicio">


<?php include 'componentes/navbar_estudiante.php'; ?>

  <section class="text-center py-5 ">
    <h1 class="fw-bold">¡Bienvenido/a al SIBE!</h1>
    <p class="lead mt-4 fw-bold">Una plataforma digital creada para facilitar el acceso a<br>materiales educativos promoviendo su aprendizaje dentro y fuera del aula</p>
    <a href="catalogo.php" class="btn btn-success px-4 mt-3">Comenzar</a>
  </section>

  <!-- los mas prestados -->
<section class="container pb-5">
  <h4 class="mb-4 fw-bold">Más prestados del mes</h4>
  <div class="row g-4 justify-content-center">

<div class="col-6 col-sm-4 col-md-2 text-center">
  <img src="Imagenes/Libro1.jpg" alt="Libro 1" class="img-fluid rounded shadow-sm mb-2" />
  <span class="badge bg-success">Introduction to the Theory of Computation</span>
</div>


  </div>
</section>


</body>
</html>