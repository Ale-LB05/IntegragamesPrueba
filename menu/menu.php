<?php
session_start();

/* Verificar si inició sesión */
if (!isset($_SESSION['usuario'])) {
    header("Location: ../RegistroAdmin/login.php");
    exit();
}

$rol = $_SESSION['rol'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <?php include("php/encabezado.php"); ?>
</head>

<body id="page-top">

    <div id="wrapper">

        <!-- MENU LATERAL -->
        <?php include("php/menuLateral.php"); ?>

        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                <!-- BARRA SUPERIOR -->
                <?php include("php/barraSuperior.php"); ?>

                <!-- CONTENIDO -->
                <div class="container-fluid">

                    <!-- ENCABEZADO -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card shadow">
                                <div class="card-body text-center">
                                    <h4 class="font-weight-bold">IntegraGames</h4>
                                    <p class="mb-0">
                                        Plataforma interactiva para la gestión de juegos educativos.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TARJETAS DE JUEGOS -->
                    <div class="row">

                        <?php
                        $juegos = [
                            [
                                "nombre" => "Juego 1",
                                "imagen" => "../img/utm2.png",
                                "descripcion" => "Descripción del 1"
                            ],
                            [
                                "nombre" => "Juego 2",
                                "imagen" => "../img/imagen2.jpeg",
                                "descripcion" => "Descripción del 2"
                            ],
                            [
                                "nombre" => "Juego 3",
                                "imagen" => "../img/imagen3.jpeg",
                                "descripcion" => "Descripción del 3"
                            ],
                            [
                                "nombre" => "Día de San Valentín",
                                "imagen" => "../img/imagen4.jpeg",
                                "descripcion" => "Descripción del 4"
                            ]
                        ];

                        foreach ($juegos as $juego) {
                        ?>

                            <div class="col-lg-3 col-md-6 mb-4">
                                <div class="card shadow h-100">

                                    <img src="<?php echo $juego['imagen']; ?>" class="card-img-top">

                                    <div class="card-body">
                                        <h6 class="font-weight-bold"><?php echo $juego['nombre']; ?></h6>
                                        <p class="text-muted small">
                                            <?php echo $juego['descripcion']; ?>
                                        </p>
                                    </div>

                                </div>
                            </div>

                        <?php } ?>

                    </div>

                    <!-- LISTADO DE JUEGOS -->
                    <div class="row mt-4">

                        <div class="col-12">
                            <h4 class="mb-4">Juegos</h4>
                        </div>

                        <?php for ($i = 1; $i <= 3; $i++) { ?>

                            <div class="col-12 mb-4">
                                <div class="card shadow">
                                    <div class="card-body">

                                        <div class="row align-items-center">

                                            <!-- Imagen -->
                                            <div class="col-md-4">
                                                <img src="https://via.placeholder.com/400x250" class="img-fluid rounded">
                                            </div>

                                            <!-- Info -->
                                            <div class="col-md-6">
                                                <h5>Nombre del juego</h5>
                                                <p class="text-muted">
                                                    Descripción más completa del juego, objetivos, etc.
                                                </p>
                                            </div>

                                            <!-- Botón -->
                                            <div class="col-md-2 text-center">
                                                <a href="#" class="btn btn-primary btn-sm">
                                                    Ver más
                                                </a>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>

                        <?php } ?>

                    </div>

                </div> <!-- FIN container-fluid -->

            </div>

            <!-- FOOTER -->
            <?php include("php/piePagina.php"); ?>

        </div>

    </div>

    <!-- BOTÓN SCROLL -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <?php include("php/logoutModal.php"); ?>

    <!-- SCRIPTS -->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../js/sb-admin-2.min.js"></script>

</body>

</html>