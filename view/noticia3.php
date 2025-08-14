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
  <title>Club de Lectura Juvenil</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php include 'componentes/navbar_estudiante.php'; ?>

<div class="container py-5">
  <h2 class="fw-bold mb-4">Club de Lectura Juvenil</h2>
  <img src="imagenes/Noticia3.png" 
     class="img-fluid rounded shadow mb-4" 
     alt="Colección Ciencia Ficción"
     style="width: 100%; height: 300px; object-fit: cover;">

  <p>Únete a nuestro Club de Lectura Juvenil, un espacio pensado para jóvenes lectores que quieren compartir sus opiniones y descubrir nuevas historias. Cada mes elegimos un libro diferente para debatir y disfrutar en comunidad.</p>
  <p>¡No importa si eres un lector habitual o si apenas estás empezando! Todos son bienvenidos.</p>
  
  <a href="estudiante_homepage.php" class="btn btn-secondary mt-3">Volver a Noticias</a>
</div>

</body>
</html>
