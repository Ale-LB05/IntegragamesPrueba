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
    <link rel="icon" href="../img/logo.png" type="image/png">
    <link href="../css/styles.css" rel="stylesheet">
    <style>
        body { background: #eef4ff; }

        .rounded-4 { border-radius: 1.5rem !important; }
        .rounded-3 { border-radius: 1rem !important; }
        
        .card {
            transition: box-shadow 0.4s cubic-bezier(0.25, 0.8, 0.25, 1) !important;
            transform: none !important;
            border: none !important;
            background: #ffffff;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08) !important;
        }

        /* Estilos del Carrusel para que parezca de videojuego */
        .carousel-inner {
            border-radius: 1rem;
            background-color: #1a1c23; /* Fondo oscuro para resaltar las cartas */
            box-shadow: inset 0 0 20px rgba(0,0,0,0.5);
        }

        .carousel-item {
            height: 400px; /* Altura fija para que no brinque la página */
        }

        .carousel-item img {
            height: 100%;
            width: 100%;
            object-fit: contain; /* Ajusta la imagen sin deformarla */
            padding: 10px;
        }

        .carousel-indicators li {
            background-color: #4e73df;
            border-radius: 50%;
            width: 10px;
            height: 10px;
            margin: 0 5px;
        }

        .game-title {
            color: #2e384d;
            font-weight: 800;
            font-size: 2.5rem;
            letter-spacing: -0.5px;
        }

        .btn-play {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            border: none;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-play:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(78, 115, 223, 0.4);
            color: white;
        }
    </style>
</head>

<body id="page-top" class="sidebar-toggled">

    <div id="wrapper">

        <?php include("../menu/php/menuLateral.php"); ?>

        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                <?php include("../menu/php/barraSuperior.php"); ?>

                <div class="container-fluid mb-5">

                    <?php
                    $juego = [
                        "nombre" => "Error 404",
                        "imagenes" => [
                            "../img/uno/uno.png",
                            "../img/uno/uno.2.png",
                            "../img/uno/uno.3.png",
                            "../img/uno/uno.4.png"
                        ],
                        "parrafo" => "Error404 transforma la diversión de UNO en una experiencia educativa sobre la carrera de Tecnologías de la Información. Cada partida te reta a pensar, adaptarte y aprender conceptos clave de programación, bases de datos, redes, ciberseguridad y soporte técnico mediante cartas temáticas, efectos especiales y mensajes contextuales. No solo juegas para ganar: juegas para descubrir cómo funciona el mundo de TI.",
                        "descripcion" => [
                            "Basado en las divertidas reglas clásicas de UNO.",
                            "Cartas temáticas con conceptos clave de TI.",
                            "Mecánicas con efectos especiales y mensajes contextuales.",
                            "Pon a prueba tu lógica y capacidad de adaptación."
                        ],
                        "link" => "../juegos/error404/index.html"
                    ];
                    ?>

                    <div class="mb-4">
                        <a href="../menu/menu.php" class="btn btn-light border shadow-sm rounded-pill text-muted fw-bold px-4">
                            <i class="fas fa-arrow-left mr-2"></i> Volver a Juegos
                        </a>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-xl-10 col-lg-11">

                            <div class="card p-4 p-md-5 rounded-4">
                                <div class="row align-items-center">
                                    
                                    <div class="col-lg-7 mb-4 mb-lg-0">
                                        <div id="carouselJuego" class="carousel slide shadow-sm rounded-3" data-ride="carousel">
                                            
                                            <ol class="carousel-indicators mb-2">
                                                <?php foreach ($juego['imagenes'] as $index => $img) { ?>
                                                    <li data-target="#carouselJuego" data-slide-to="<?php echo $index; ?>" class="<?php echo $index == 0 ? 'active' : ''; ?>"></li>
                                                <?php } ?>
                                            </ol>

                                            <div class="carousel-inner">
                                                <?php foreach ($juego['imagenes'] as $index => $img) { ?>
                                                    <div class="carousel-item <?php echo $index == 0 ? 'active' : ''; ?>">
                                                        <img src="<?php echo $img; ?>" alt="Captura del juego">
                                                    </div>
                                                <?php } ?>
                                            </div>

                                            <a class="carousel-control-prev" href="#carouselJuego" role="button" data-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="sr-only">Anterior</span>
                                            </a>
                                            <a class="carousel-control-next" href="#carouselJuego" role="button" data-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="sr-only">Siguiente</span>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="col-lg-5 pl-lg-5 d-flex flex-column justify-content-center">
                                        
                                        <div class="mb-3">
                                            <span class="badge bg-danger text-white px-3 py-2 rounded-pill shadow-sm"><i class="fas fa-layer-group mr-1"></i> Cartas</span>
                                            <span class="badge bg-light text-dark border px-3 py-2 rounded-pill shadow-sm"><i class="fas fa-brain mr-1"></i> Estrategia</span>
                                        </div>

                                        <h1 class="game-title mb-3"><?php echo $juego['nombre']; ?></h1>

                                        <p class="text-secondary mb-4" style="font-size: 0.95rem; line-height: 1.6; text-align: justify;">
                                            <?php echo $juego['parrafo']; ?>
                                        </p>

                                        <div class="mb-4">
                                            <?php foreach ($juego['descripcion'] as $linea) { ?>
                                                <p class="text-muted mb-2" style="line-height: 1.7; font-size: 1.05rem;">
                                                    <i class="fas fa-check-circle text-success mr-2" style="font-size: 0.9rem;"></i> <?php echo $linea; ?>
                                                </p>
                                            <?php } ?>
                                        </div>

                                        <hr class="mb-4 border-light">

                                        <div>
                                            <a href="<?php echo $juego['link']; ?>" class="btn btn-play btn-lg rounded-pill text-white w-100 fw-bold py-3 shadow">
                                                <i class="fas fa-play-circle fa-lg mr-2"></i> Jugar Ahora
                                            </a>
                                        </div>

                                    </div>
                                    
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

            </div>

            <?php include("../menu/php/piePagina.php"); ?>

        </div>

    </div>

    <a class="scroll-to-top rounded-circle shadow" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <?php include("../menu/php/logoutModal.php"); ?>

    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../js/sb-admin-2.min.js"></script>

</body>

</html>