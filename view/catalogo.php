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
    <title>SIBE - Catálogo de Libros</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../styles/estilos.css" />
</head>

<body class="body-inicio">

<?php include 'componentes/navbar_estudiante.php'; ?>

    <main class="d-flex justify-content-center my-5">
        <div class="card shadow px-4 py-4" style="max-width: 1400px; width: 100%;">
            <div class="row">

                <aside class="col-md-3 mb-4">
                    <div class="border rounded p-3 bg-white">
                        <h5 class="text-center mb-3 fs-4"><strong>Filtros de Búsqueda</strong></h5>
                        <div class="form-check"><input class="form-check-input" type="checkbox" checked><label
                                class="form-check-label fs-5">ISBN</label></div>
                        <div class="form-check fs-5"><input class="form-check-input" type="checkbox" checked><label
                                class="form-check-label fs-5">Autor</label></div>
                        <div class="form-check fs-5"><input class="form-check-input" type="checkbox" checked><label
                                class="form-check-label fs-5">Fecha de Publicación</label></div>
                        <div class="form-check fs-5"><input class="form-check-input" type="checkbox" checked><label
                                class="form-check-label fs-5">Categoría</label></div>
                    </div>
                </aside>

                <section class="col-md-9">
                    <h4 class="mb-3  fs-2"><strong>Tecnología:</strong></h4>
                    <div class="d-flex flex-wrap gap-3 justify-content-start">
                        <div class="card" style="width: 200px;">
                            <img src="imagenes/computernetworking.jpg" class="card-img-top" alt="Computer Networking">
                            <div class="card-body text-center">
                                <a href="detalle.php?id=2" class="btn btn-success btn-sm fs-6"><strong>Ver Más Detalles</strong></a>
                            </div>
                        </div>
                        <div class="card" style="width: 200px;">
                            <img src="imagenes/cleancode.jpg" class="card-img-top" alt="Clean Code">
                            <div class="card-body text-center">
                                <a href="detalle.php?id=1" class="btn btn-success btn-sm fs-6"><strong>Ver Más Detalles</strong></a>
                            </div>
                        </div>
                        <div class="card" style="width: 200px;">
                            <img src="imagenes/softwareengineer.jpg" class="card-img-top" alt="Software Engineering">
                            <div class="card-body text-center">
                                <a href="detalle.php?id=3" class="btn btn-success btn-sm fs-6"><strong>Ver Más Detalles</strong></a>
                            </div>
                        </div>
                    </div>

                    <h4 class="mt-5 mb-3 fs-2"><strong>Química:</strong></h4>
                    <div class="d-flex flex-wrap gap-3 justify-content-start">
                        <div class="card" style="width: 200px;">
                            <img src="imagenes/principiosquimica.jpg" class="card-img-top" alt="Principios de Química">
                            <div class="card-body text-center">
                                <a href="detalle.php?id=4" class="btn btn-success btn-sm fs-6"><strong>Ver Más Detalles</strong></a>
                            </div>
                        </div>
                        <div class="card" style="width: 200px;">
                            <img src="imagenes/problemasquimica.png" class="card-img-top"
                                alt="100 Problemas de Química General">
                            <div class="card-body text-center">
                                <a href="#" class="btn btn-success btn-sm fs-6"><strong>Ver Más Detalles</strong></a>
                            </div>
                        </div>
                        <div class="card" style="width: 200px;">
                            <img src="imagenes/quimicaorganica.png" class="card-img-top" alt="Química Orgánica">
                            <div class="card-body text-center">
                                <a href="detalle.php?id=5" class="btn btn-success btn-sm fs-6"><strong>Ver Más Detalles</strong></a>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </main>

</body>

</html>