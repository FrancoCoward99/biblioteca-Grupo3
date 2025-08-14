<?php
session_start();
if (!isset($_SESSION['nombre_usuario']) || $_SESSION['rol'] !== 'estudiante') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Semana del Libro</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php include 'componentes/navbar_estudiante.php'; ?>

<div class="container py-5">
  <h2 class="fw-bold mb-4">Semana del Libro</h2>
  <img src="imagenes/Noticia2.jpg" 
     class="img-fluid rounded shadow mb-4" 
     alt="Colección Ciencia Ficción"
     style="width: 100%; height: 300px; object-fit: cover;">

  <p>Del 10 al 14 de septiembre celebraremos la Semana del Libro con actividades para toda la comunidad. Tendremos charlas con autores, talleres de escritura creativa, sesiones de cuentacuentos y presentaciones de libros.</p>
  <p>Consulta nuestra agenda y participa en los eventos que más te interesen.</p>
  
  <a href="estudiante_homepage.php" class="btn btn-secondary mt-3">Volver a Noticias</a>
</div>

</body>
</html>
