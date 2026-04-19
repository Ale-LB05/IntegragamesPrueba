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
    <style>
        body {
            background: #eef4ff;
        }

        .text-primary {
            color: #4e73df !important;
        }

        .text-secondary {
            color: #858796 !important;
        }

        .rounded-4 {
            border-radius: 1rem !important;
        }

        /* Efecto de levitación para las tarjetas */
        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease !important;
            border: none !important;
            background: #ffffff;
            border-radius: 1rem !important;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05) !important;
        }

        .hover-lift:hover {
            transform: translateY(-7px) !important;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1) !important;
        }

        /* Imágenes del mismo tamaño para que no se deforme el diseño */
        .img-uniforme {
            height: 200px;
            width: 100%;
            object-fit: cover;
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
        }

        /* Banner Principal Moderno */
        .hero-banner {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            color: white;
            position: relative;
            overflow: hidden;
            border-radius: 1.5rem !important;
        }

        .hero-icon-bg {
            position: absolute;
            font-size: 15rem;
            right: -20px;
            bottom: -50px;
            opacity: 0.1;
            transform: rotate(-15deg);
            pointer-events: none;
        }

        .badge-soft-primary {
            background-color: #e3f2fd;
            color: #4e73df;
        }
    </style>
</head>

<body id="page-top" class="sidebar-toggled">

    <div id="wrapper">

        <?php include("php/menuLateral.php"); ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">

                <?php include("php/barraSuperior.php"); ?>
                <div class="container-fluid mb-5">

                    <div class="row mb-5 mt-2">
                        <div class="col-12">
                            <div class="card shadow-lg border-0 hero-banner">
                                <div class="card-body p-5 position-relative z-index-1">
                                    <div class="row align-items-center">
                                        <div class="col-lg-8">
                                            <span class="badge bg-light text-primary px-3 py-2 rounded-pill mb-3 fw-bold">
                                                <i class="fas fa-user-circle mr-1"></i> Hola, <?= htmlspecialchars($_SESSION['usuario']) ?>
                                            </span>
                                            <h2 class="font-weight-bold mb-3 text-white">¡Bienvenido a IntegraGames!</h2>
                                            <p class="lead mb-0" style="opacity: 0.9; font-size: 1.1rem; line-height: 1.6;">
                                                IntegraGames es una plataforma web interactiva diseñada para promover la carrera de <strong>Tecnologías de la Información (TI)</strong> de la UTM mediante la gamificación. Combina el aprendizaje con el entretenimiento para conocer el plan de estudios de forma dinámica.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <i class="fas fa-gamepad hero-icon-bg"></i>
                            </div>
                        </div>
                    </div>

                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h4 class="mb-0 fw-bold text-gray-800">
                            <?php if (in_array(strtolower($rol), $rolesPermitidos)) { ?>
                                <i class="fas fa-calendar-alt mr-2" style="color: #4e73df;"></i> Próximos Eventos
                            <?php } else { ?>
                                <i class="fas fa-rocket mr-2" style="color: #4e73df;"></i> Descubre la Carrera
                            <?php } ?>
                        </h4>
                    </div>

                    <div class="row mb-5">
                        <?php
                        /* ADMIN / PROMOTOR / PROGRAMADOR - VEN EVENTOS */
                        if (in_array(strtolower($rol), $rolesPermitidos)) {

                            $hoy = date("Y-m-d");
                            /* MODIFICACIÓN: Se cambiaron las columnas de hora por hora_inicio y hora_fin */
                            $sql = "SELECT nombre_evento, imagen, observaciones, fecha, hora_inicio, hora_fin, lugar, ubicacion
                                    FROM evento
                                    WHERE fecha >= '$hoy'
                                    ORDER BY fecha ASC";

                            $resultado = mysqli_query($conn, $sql);

                            if ($resultado && mysqli_num_rows($resultado) > 0) {
                                while ($evento = mysqli_fetch_assoc($resultado)) {
                        ?>
                                    <div class="col-lg-4 col-md-6 mb-4">
                                        <div class="card hover-lift h-100">
                                            <img src="../img/eventos/<?php echo $evento['imagen']; ?>"
                                                class="card-img-top img-uniforme" alt="Imagen del evento">

                                            <div class="card-body d-flex flex-column p-4">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <h5 class="font-weight-bold text-dark mb-0">
                                                        <?php echo htmlspecialchars($evento['nombre_evento']); ?>
                                                    </h5>
                                                    <span class="badge badge-soft-primary px-2 py-1 rounded-pill">Próximo</span>
                                                </div>

                                                <div class="mt-3">
                                                    <p class="text-muted small mb-2">
                                                        <i class="fas fa-calendar-day fa-fw mr-2" style="color: #4e73df;"></i>
                                                        <strong>Fecha:</strong> <?php echo date("d/m/Y", strtotime($evento['fecha'])); ?>
                                                    </p>

                                                    <?php if (!empty($evento['hora_inicio']) && !empty($evento['hora_fin'])): ?>
                                                        <p class="text-muted small mb-2">
                                                            <i class="fas fa-clock fa-fw mr-2" style="color: #4e73df;"></i>
                                                            <strong>Hora:</strong> <?php echo date("h:i A", strtotime($evento['hora_inicio'])) . " - " . date("h:i A", strtotime($evento['hora_fin'])); ?>
                                                        </p>
                                                    <?php endif; ?>

                                                    <p class="text-muted small mb-2">
                                                        <i class="fas fa-map-marker-alt fa-fw mr-2" style="color: #e74a3b;"></i>
                                                        <strong>Lugar:</strong> <?php echo htmlspecialchars($evento['lugar'] ?? 'Ubicación no disponible'); ?>
                                                    </p>
                                                </div>

                                                <div class="mt-auto pt-3 border-top">
                                                    <?php if (!empty($evento['ubicacion'])): ?>
                                                        <a href="https://www.google.com/maps/search/?api=1&query=<?php echo $evento['ubicacion']; ?>"
                                                            target="_blank"
                                                            class="btn btn-sm btn-outline-primary rounded-pill w-100">
                                                            <i class="fas fa-map-marked-alt mr-1"></i> Ver en Google Maps
                                                        </a>
                                                    <?php else: ?>
                                                        <p class="text-muted small mb-0 text-center"><i class="fas fa-info-circle"></i> <?php echo htmlspecialchars($evento['observaciones']); ?></p>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                            } else {
                                echo "<div class='col-12'>
                                        <div class='alert alert-light border text-center py-5 rounded-4 shadow-sm'>
                                            <i class='fas fa-calendar-times fa-3x text-muted mb-3'></i>
                                            <h5 class='text-muted fw-bold'>No hay eventos programados por el momento.</h5>
                                        </div>
                                      </div>";
                            }

                            /* PARTICIPANTE - VE TARJETAS INFORMATIVAS */
                        } else {
                            $juegosInfo = [
                                [
                                    "nombre" => "Domina el Futuro Digital",
                                    "imagen" => "../img/utm2.png",
                                    "descripcion" => "Convierte tu pasión por la tecnología en soluciones reales. En TI, no solo usas el futuro, ¡tú lo programas!"
                                ],
                                [
                                    "nombre" => "Experiencia UTM",
                                    "imagen" => "../img/imagen3.jpeg",
                                    "descripcion" => "Aprende con proyectos prácticos y laboratorios de vanguardia. Formamos los líderes tecnológicos que el mundo necesita."
                                ],
                                [
                                    "nombre" => "De Gamer a Desarrollador",
                                    "imagen" => "../img/imagen3.jpeg",
                                    "descripcion" => "Lleva tu nivel al siguiente paso. Aprende lógica de programación creando mundos y mecánicas de juego increíbles."
                                ],
                                [
                                    "nombre" => "¡Únete a la Comunidad!",
                                    "imagen" => "../img/imagen4.jpeg",
                                    "descripcion" => "Participa en eventos, torneos y desafíos. IntegraGames es solo el inicio de tu viaje en las Tecnologías de la Información."
                                ]
                            ];

                            foreach ($juegosInfo as $info) {
                                ?>
                                <div class="col-lg-3 col-md-6 mb-4">
                                    <div class="card hover-lift h-100">
                                        <img src="<?php echo $info['imagen']; ?>" class="card-img-top img-uniforme">
                                        <div class="card-body text-center p-4">
                                            <h6 class="font-weight-bold text-dark mb-3">
                                                <?php echo $info['nombre']; ?>
                                            </h6>
                                            <p class="text-muted small mb-0">
                                                <?php echo $info['descripcion']; ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                        <?php
                            }
                        }
                        ?>
                    </div>

                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h4 class="mb-0 fw-bold text-gray-800"><i class="fas fa-gamepad mr-2" style="color: #1cc88a;"></i> Zona Arcade</h4>
                    </div>

                    <div class="row">
                        <?php
                        $juegos = [
                            [
                                "nombre" => "Error 404",
                                "imagen" => "../img/uno/uno.png",
                                "descripcion" => "El clásico juego de cartas reinventado. Aplica tu agilidad mental para dejar a tus oponentes sin conexión.",
                                "link" => "../juegos/error404.php",
                                "color" => "info"
                            ],
                            [
                                "nombre" => "Code Run",
                                "imagen" => "../img/codeRun/runCode.png",
                                "descripcion" => "Supera niveles en este mundo pixelado. Esquiva amenazas digitales mientras compilas tu camino al éxito.",
                                "link" => "../juegos/codeRun.php",
                                "color" => "info"
                            ],
                            [
                                "nombre" => "Desafío Tech",
                                "imagen" => "../img/desafioTech/desafioTech.png",
                                "descripcion" => "Supera las preguntas del Maestro Byte. Una trivia llena de ritmo, comodines y aprendizaje tecnológico.",
                                "link" => "../juegos/desafioTech.php",
                                "color" => "info"
                            ]
                        ];

                        foreach ($juegos as $juego) {
                        ?>
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card hover-lift h-100 text-center">
                                    <div class="card-body p-4">
                                        <div class="mb-3">
                                            <img src="<?php echo $juego['imagen']; ?>" class="img-fluid shadow-sm" style="width: 100%; height: 180px; object-fit: cover; border-radius: 15px; border: 4px solid #fff;">
                                        </div>
                                        <h4 class="font-weight-bold text-dark"><?php echo $juego['nombre']; ?></h4>
                                        <p class="text-muted small mb-4">
                                            <?php echo $juego['descripcion']; ?>
                                        </p>
                                        <a href="<?php echo $juego['link']; ?>" class="btn btn-<?php echo $juego['color']; ?> rounded-pill px-4 shadow-sm w-100 fw-bold">
                                            <i class="fas fa-play-circle mr-1"></i> Jugar Ahora
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>

                </div>
            </div>

            <?php include("php/piePagina.php"); ?>

            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <?php if (isset($_GET['login'])) { ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Bienvenido!',
                            text: 'Inicio de sesión exitoso',
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            window.history.replaceState({}, document.title, window.location.pathname);
                        });
                    });
                </script>
            <?php } ?>

        </div>
    </div>

    <a class="scroll-to-top rounded-circle shadow" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <?php include("php/logoutModal.php"); ?>

    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../js/sb-admin-2.min.js"></script>

</body>

</html>