<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../RegistroAdmin/login.php");
    exit();
}

$rol = $_SESSION['rol'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <?php include("../menu/php/encabezado.php"); ?>
    <link rel="icon" href="../img/control.png" type="image/png">
    <link href="../css/styles.css" rel="stylesheet">
</head>

<body id="page-top" class="sidebar-toggled">

    <div id="wrapper">

        <!-- MENU -->
        <?php include("../menu/php/menuLateral.php"); ?>

        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                <!-- TOPBAR -->
                <?php include("../menu/php/barraSuperior.php"); ?>

                <!-- CONTENIDO -->
                <div class="container-fluid">

                    <?php
                    $juego = [
                        "nombre" => "'Error 404'",
                        "imagenes" => [
                            "../img/uno/uno.png",
                            "../img/uno/uno.2.png",
                            "../img/uno/uno.3.png",
                            "../img/uno/uno.4.png"
                        ],
                        "descripcion" => [
                            "Error 404 es un juego de cartas.",
                            "Debes ganar cada partida.",
                            "Pon a prueba tu lógica.",
                            "Ideal para aprender jugando."
                        ],
                        "link" => "juego1.php"
                    ];
                    ?>

                    <div class="row justify-content-center">
                        <div class="col-lg-10">

                            <div class="card shadow p-4">

                                <!-- TITULO -->
                                <h3 class="mb-3"><?php echo $juego['nombre']; ?></h3>

                                <!-- CARRUSEL -->
                                <div id="carouselJuego" class="carousel slide mb-4" data-ride="carousel">

                                    <ol class="carousel-indicators">
                                        <?php foreach ($juego['imagenes'] as $index => $img) { ?>
                                            <li data-target="#carouselJuego" data-slide-to="<?php echo $index; ?>"
                                                class="<?php echo $index == 0 ? 'active' : ''; ?>"></li>
                                        <?php } ?>
                                    </ol>

                                    <div class="carousel-inner">
                                        <?php foreach ($juego['imagenes'] as $index => $img) { ?>
                                            <div class="carousel-item <?php echo $index == 0 ? 'active' : ''; ?>">
                                                <img src="<?php echo $img; ?>"
                                                    class="rounded"
                                                    style="max-height: 100%; max-width: 100%; object-fit: contain;">
                                            </div>
                                        <?php } ?>
                                    </div>

                                    <a class="carousel-control-prev" href="#carouselJuego" role="button" data-slide="prev">
                                        <span class="carousel-control-prev-icon"></span>
                                    </a>

                                    <a class="carousel-control-next" href="#carouselJuego" role="button" data-slide="next">
                                        <span class="carousel-control-next-icon"></span>
                                    </a>

                                </div>

                                <!-- DESCRIPCIÓN -->
                                <div class="mb-4">
                                    <?php foreach ($juego['descripcion'] as $linea) { ?>
                                        <p class="mb-2"><?php echo $linea; ?></p>
                                    <?php } ?>
                                </div>

                                <!-- BOTÓN -->
                                <div class="text-center">
                                    <a href="<?php echo $juego['link']; ?>" class="btn btn-primary px-5">
                                        Jugar
                                    </a>
                                </div>

                            </div>

                        </div>
                    </div>

                </div>

            </div>

            <!-- FOOTER -->
            <?php include("../menu/php/piePagina.php"); ?>

        </div>

    </div>

    <!-- SCROLL -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <?php include("../menu/php/logoutModal.php"); ?>

    <!-- SCRIPTS (IGUAL QUE EL INDEX) -->
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Easing -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>

    <!-- SB ADMIN -->
    <script src="https://cdn.jsdelivr.net/npm/startbootstrap-sb-admin-2@4.1.4/js/sb-admin-2.min.js"></script>


</body>

</html>