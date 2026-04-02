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
                                        IntegraGames es una plataforma web interactiva diseñada para promover la carrera de Tecnologías de la Información (TI) de la UTM mediante la gamificación.
                                        El sistema combina el aprendizaje con el entretenimiento para que futuros estudiantes conozcan el plan de estudios y conceptos clave de programación de forma dinámica.
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

                            $sql = "SELECT nombre_evento, imagen, observaciones, fecha, hora, lugar, ubicacion
                                    FROM evento
                                    WHERE fecha >= '$hoy'
                                    ORDER BY fecha ASC";

                            $resultado = mysqli_query($conn, $sql);

                            if ($resultado && mysqli_num_rows($resultado) > 0) {

                                while ($evento = mysqli_fetch_assoc($resultado)) {
                        ?>

                                    <div class="col-lg-3 col-md-6 mb-4">
                                        <div class="card shadow h-100">
                                            <img src="../img/eventos/<?php echo $evento['imagen']; ?>"
                                                class="card-img-top img-uniforme" alt="Imagen del evento">

                                            <div class="card-body d-flex flex-column">
                                                <h6 class="font-weight-bold text-black mb-2">
                                                    <?php echo htmlspecialchars($evento['nombre_evento']); ?>
                                                </h6>

                                                <p class="text-muted small mb-1">
                                                    <i class="fas fa-calendar-alt fa-fw mr-1"></i>
                                                    <strong>Fecha:</strong> <?php echo date("d/m/Y", strtotime($evento['fecha'])); ?>
                                                </p>

                                                <?php if (!empty($evento['hora'])): ?>
                                                    <p class="text-muted small mb-1">
                                                        <i class="fas fa-clock fa-fw mr-1"></i>
                                                        <strong>Hora:</strong> <?php echo date("h:i A", strtotime($evento['hora'])); ?>
                                                    </p>
                                                <?php endif; ?>

                                                <p class="text-muted small mb-1">
                                                    <i class="fas fa-map-marker-alt fa-fw mr-1"></i>
                                                    <strong>Lugar:</strong> <?php echo htmlspecialchars($evento['lugar'] ?? 'Ubicación no disponible'); ?>
                                                </p>

                                                <p class="text-muted small mb-1">
                                                    <i class="fas fa-map-marker-alt fa-fw mr-1"></i>
                                                    <strong>Ubicación:</strong> <?php echo htmlspecialchars($evento['ubicacion'] ?? 'No especificado'); ?>

                                                    <?php if (!empty($evento['ubicacion'])): ?>
                                                        <a href="https://www.google.com/maps/search/?api=1&query=<?php echo $evento['ubicacion']; ?>"
                                                            target="_blank"
                                                            class="btn btn-sm btn-outline-primary p-0 px-1 ml-1 ms-2"
                                                            title="Ver en el mapa"
                                                            style="font-size: 0.7rem; border-radius: 10px;">
                                                            <i class="fas fa-map-marker-alt"></i> Ver Mapa
                                                        </a>
                                                    <?php endif; ?>
                                                </p>

                                                <p class="text-dark small mb-0">
                                                    <i class="fas fa-info-circle fa-fw mr-1"></i>
                                                    <?php echo htmlspecialchars($evento['observaciones']); ?>
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
                                                    class="btn btn-primary btn-sm text-white"> Ver más
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

            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <?php if (isset($_GET['login'])) { ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Bienvenido',
                            text: 'Inicio de sesión exitoso'
                        });
                    });
                </script>
            <?php } ?>

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