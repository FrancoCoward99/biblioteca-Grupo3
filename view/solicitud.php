<?php 
session_start();

if (!isset($_SESSION['nombre_usuario']) || $_SESSION['rol'] !== 'estudiante') {
    header("Location: login.php");
    exit;
}

$nombre = $_SESSION['nombre_usuario'];
?>

<html>
    <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SIBE - Solicitud de Préstamo</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../styles/estilos.css" />
</head>
    <body>
        


    </body>
</html>