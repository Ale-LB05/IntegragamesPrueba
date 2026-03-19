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
    <!-- ICONO -->
    <link rel="icon" href="../img/control.png" type="image/png">

    <!-- CSS -->
    <link href="../css/styles.css" rel="stylesheet">
</head>

<body id="page-top" class="sidebar-toggled">

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
                                        Es una plataforma interactiva para la promocion de la carrera de Tecnoloía de la informacion, con contenido educativo y entretenido, para los amantes de los videojuegos.
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
                                "nombre" => "Tecologia de la informacion",
                                "imagen" => "../img/utm2.png",
                                "descripcion" => "Es una carrera muy interesante, con muchas oportunidades laborales"
                            ],
                            [
                                "nombre" => "Por que estudiar en la UTM",
                                "imagen" => "../img/imagen3.jpeg",
                                "descripcion" => "La UTM ofrece una educacion de calidad, con profesores altamente capacitados y una amplia variendad de conocimientes"
                            ],
                            [
                                "nombre" => "Te gustan los videojuegos?",
                                "imagen" => "../img/imagen3.jpeg",
                                "descripcion" => "Si te gustan los videojuegos, la carrera de TI es para ti, ya que podras aprender a crear y programar tus propios juegos  "
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

                                    <!-- Imagen -->
                                    <img src="<?php echo $juego['imagen']; ?>"
                                        class="card-img-top img-uniforme">

                                    <!-- Contenido -->
                                    <div class="card-body d-flex flex-column">
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

                        <?php
                        $juegos = [
                            [
                                "nombre" => "Error 404",
                                "imagen" => "../img/uno/uno.png",
                                "descripcion" => "Error 404 es un juego donde los jugadores tendran que ganar una partida de cartas.",
                                "link" => "../juegos/error404.php"
                            ],
                            [
                                "nombre" => "Code Run",
                                "imagen" => "../img/codeRun/runCode.png",
                                "descripcion" => "Code Run es un juego donde tendras que pasar cada nivel sin que seas derivado por las carpetas enemigas .",
                                "link" => "../juegos/codeRun.php"
                            ],
                            [
                                "nombre" => "Juego 3",
                                "imagen" => "../img/juego3.jpg",
                                "descripcion" => "Descripción del juego 3, reglas básicas.",
                                "link" => "../juegos/juego3.php"
                            ]
                        ];

                        foreach ($juegos as $juego) {
                        ?>

                            <div class="col-12 mb-4">
                                <div class="card shadow">
                                    <div class="card-body">

                                        <div class="row align-items-center">

                                            <!-- Imagen -->
                                            <div class="col-md-4">
                                                <img src="<?php echo $juego['imagen']; ?>"
                                                    class="img-fluid rounded img-uniforme">
                                            </div>

                                            <!-- Info -->
                                            <div class="col-md-6">
                                                <h5><?php echo $juego['nombre']; ?></h5>
                                                <p class="text-muted">
                                                    <?php echo $juego['descripcion']; ?>
                                                </p>
                                            </div>

                                            <!-- Botón -->
                                            <div class="col-md-2 text-center">
                                                <a href="<?php echo $juego['link']; ?>"
                                                    class="btn btn-primary btn-sm">
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
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>