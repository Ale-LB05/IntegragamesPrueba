<?php
session_start();
include("../config/conexion.php");

/* Verificar sesión */
if (!isset($_SESSION['usuario'])) {
    header("Location: ../RegistroAdmin/login.php");
    exit();
}

$rol = $_SESSION['rol'];

/* ROLES QUE VERÁN EVENTOS */
$rolesPermitidos = ['administrador', 'programador', 'promotor'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <?php include("php/encabezado.php"); ?>
    <link rel="icon" href="../img/logo.png" type="image/png">
    <link href="../css/styles.css" rel="stylesheet">
</head>

<body id="page-top" class="sidebar-toggled">

    <div id="wrapper">

        <!-- MENU -->
        <?php include("php/menuLateral.php"); ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">

                <!-- BARRA -->
                <?php include("php/barraSuperior.php"); ?>

                <div class="container-fluid">

                    <!-- ENCABEZADO -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card shadow">
                                <div class="card-body text-center">
                                    <h4 class="font-weight-bold">IntegraGames</h4>
                                    <p class="mb-0">
                                        Plataforma interactiva para la promoción de TI con contenido educativo y entretenido.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TARJETAS -->
                    <div class="row">

                        <?php
                        /* ADMIN / PROMOTOR / PROGRAMADOR*/
                        if (in_array(strtolower($rol), ['administrador', 'programador', 'promotor'])) {

                            $hoy = date("Y-m-d");

                            $sql = "SELECT nombre_evento, imagen, observaciones, fecha 
                        FROM evento
                        WHERE fecha >= '$hoy'
                        ORDER BY fecha ASC";

                            $resultado = mysqli_query($conn, $sql);

                            if ($resultado && mysqli_num_rows($resultado) > 0) {

                                while ($evento = mysqli_fetch_assoc($resultado)) {
                        ?>

                                    <div class="col-lg-3 col-md-6 mb-4">
                                        <div class="card shadow h-100">

                                            <!-- Imagen -->
                                            <img src="../img/eventos/<?php echo $evento['imagen']; ?>"
                                                class="card-img-top img-uniforme">

                                            <!-- Info -->
                                            <div class="card-body d-flex flex-column">
                                                <h6 class="font-weight-bold">
                                                    <?php echo $evento['nombre_evento']; ?>
                                                </h6>

                                                <p class="text-muted small">
                                                    <?php echo $evento['observaciones']; ?>
                                                </p>

                                            </div>

                                        </div>
                                    </div>

                                <?php
                                }
                            } else {
                                echo "<div class='col-12 text-center'>
                            <p>No hay eventos programados.</p>
                          </div>";
                            }

                            /*PARTICIPANTE*/
                        } else {

                            $juegosInfo = [
                                [
                                    "nombre" => "Tecnología de la información",
                                    "imagen" => "../img/utm2.png",
                                    "descripcion" => "Carrera con muchas oportunidades laborales."
                                ],
                                [
                                    "nombre" => "Por qué estudiar en la UTM",
                                    "imagen" => "../img/imagen3.jpeg",
                                    "descripcion" => "Educación de calidad y profesores capacitados."
                                ],
                                [
                                    "nombre" => "¿Te gustan los videojuegos?",
                                    "imagen" => "../img/imagen3.jpeg",
                                    "descripcion" => "Aprende a crear tus propios videojuegos."
                                ],
                                [
                                    "nombre" => "Día de San Valentín",
                                    "imagen" => "../img/imagen4.jpeg",
                                    "descripcion" => "Descripción del evento."
                                ]
                            ];

                            foreach ($juegosInfo as $juego) {
                                ?>

                                <div class="col-lg-3 col-md-6 mb-4">
                                    <div class="card shadow h-100">

                                        <img src="<?php echo $juego['imagen']; ?>"
                                            class="card-img-top img-uniforme">

                                        <div class="card-body d-flex flex-column">
                                            <h6 class="font-weight-bold">
                                                <?php echo $juego['nombre']; ?>
                                            </h6>

                                            <p class="text-muted small">
                                                <?php echo $juego['descripcion']; ?>
                                            </p>
                                        </div>

                                    </div>
                                </div>

                        <?php
                            }
                        }
                        ?>

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
                                "descripcion" => "Juego de cartas competitivo.",
                                "link" => "../juegos/error404.php"
                            ],
                            [
                                "nombre" => "Code Run",
                                "imagen" => "../img/codeRun/runCode.png",
                                "descripcion" => "Evita enemigos y supera niveles.",
                                "link" => "../juegos/codeRun.php"
                            ],
                            [
                                "nombre" => "Juego 3",
                                "imagen" => "../img/juego3.jpg",
                                "descripcion" => "Reglas básicas del juego.",
                                "link" => "../juegos/juego3.php"
                            ]
                        ];

                        foreach ($juegos as $juego) {
                        ?>

                            <div class="col-12 mb-4">
                                <div class="card shadow">
                                    <div class="card-body">

                                        <div class="row align-items-center">

                                            <div class="col-md-4">
                                                <img src="<?php echo $juego['imagen']; ?>"
                                                    class="img-fluid rounded img-uniforme">
                                            </div>

                                            <div class="col-md-6">
                                                <h5><?php echo $juego['nombre']; ?></h5>
                                                <p class="text-muted">
                                                    <?php echo $juego['descripcion']; ?>
                                                </p>
                                            </div>

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

                </div>
            </div>

            <!-- FOOTER -->
            <?php include("php/piePagina.php"); ?>

        </div>
    </div>

    <!-- SCROLL -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <?php include("php/logoutModal.php"); ?>

    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../js/sb-admin-2.min.js"></script>

</body>

</html>