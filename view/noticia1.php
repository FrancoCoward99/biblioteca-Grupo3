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
  <title>Colección de Ciencia Ficción</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php include 'componentes/navbar_estudiante.php'; ?>

<div class="container py-5">
  <h2 class="fw-bold mb-4">Nueva adquisición: Colección de Ciencia Ficción</h2>
  <img src="imagenes/Noticia1.jpg" 
     class="img-fluid rounded shadow mb-4" 
     alt="Colección Ciencia Ficción"
     style="width: 100%; height: 300px; object-fit: cover;">

  <p>La biblioteca ha incorporado más de 50 títulos nuevos de autores reconocidos en el género de ciencia ficción. Estos libros abarcan desde clásicos atemporales hasta las más recientes obras premiadas, ideales para todos los amantes del género.</p>
  <p>Visítanos para explorar esta nueva colección y descubrir nuevas aventuras intergalácticas.</p>
  
  <a href="estudiante_homepage.php" class="btn btn-secondary mt-3">Volver a Noticias</a>
</div>

</body>
</html>
