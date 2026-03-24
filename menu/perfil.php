<?php
session_start();
require_once "../config/conexion.php";

/* VALIDAR SESIÓN */
if (!isset($_SESSION['usuario'])) {
    header("Location: ../RegistroAdmin/login.php");
    exit();
}

$usuarioActual = $_SESSION['usuario'];
$rol = $_SESSION['rol'];

/* OBTENER USUARIO */
$sql = "SELECT * FROM responsable WHERE nombre='$usuarioActual'";
$res = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($res);

if (!$user) {
    die("Usuario no encontrado");
}

/* IMAGEN */
$imagen = (!empty($user['imagen']) && file_exists("../img/responsables/" . $user['imagen']))
    ? "../img/responsables/" . $user['imagen']
    : "../img/responsables/sinFoto.jpg";
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <?php include("../menu/php/encabezado.php"); ?>
    <link rel="icon" href="../img/logo.png" type="image/png">

    <style>
        .perfil-container {
            max-width: 1000px;
            margin: auto;
        }

        .perfil-box {
            display: flex;
            gap: 50px;
            flex-wrap: wrap;
        }

        .foto-container {
            position: relative;
            width: 150px;
        }

        .perfil-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
        }

        .btn-foto {
            position: absolute;
            bottom: 5px;
            right: 5px;
        }

        .info-box {
            flex: 1;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }

        .info-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-text {
            max-width: 250px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .evento-card {
            display: flex;
            align-items: center;
            background: #fff;
            border-radius: 15px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        .evento-img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 15px;
            margin-right: 15px;
        }

        .evento-info {
            flex: 1;
        }

        .modal-dialog {
            max-width: 500px;
        }

        .modal-body {
            overflow-x: hidden;
        }

        .toggle-pass {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            z-index: 10;
        }

        .modal-content {
            border-radius: 10px;
        }

        .form-control {
            box-sizing: border-box;
        }

        .pr-5 {
            padding-right: 40px;
            /* espacio para el ojo */
        }
    </style>
</head>

<body>

    <div id="wrapper">

        <?php include("../menu/php/menuLateral.php"); ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">

                <?php include("../menu/php/barraSuperior.php"); ?>

                <div class="container-fluid perfil-container">

                    <h4 class="mb-4">Perfil</h4>

                    <div class="perfil-box">

                        <!-- FOTO -->
                        <div class="text-center">
                            <div class="foto-container">
                                <img src="<?= $imagen ?>" class="perfil-img">

                                <button class="btn btn-info btn-sm btn-foto"
                                    data-toggle="modal"
                                    data-target="#modalFoto">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>

                            <div class="mt-2">
                                <strong><?= htmlspecialchars($user['nombre']) ?></strong>
                            </div>
                        </div>

                        <!-- INFO -->
                        <div class="info-box">

                            <h5>Información</h5>

                            <div class="info-item">
                                <span>Correo</span>

                                <div class="info-right">
                                    <span class="info-text"><?= htmlspecialchars($user['correo']) ?></span>

                                    <button class="btn btn-info btn-sm"
                                        data-toggle="modal"
                                        data-target="#modalCorreo">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="info-item">
                                <span>Rol</span>
                                <span><?= $rol ?></span>
                            </div>

                            <div class="info-item">
                                <span>Contraseña</span>

                                <div class="info-right">
                                    <span class="info-text">********</span>

                                    <button class="btn btn-info btn-sm"
                                        data-toggle="modal"
                                        data-target="#modalPass">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- EVENTOS -->
                    <h4 class="mt-4 mb-3">Eventos Asistidos</h4>

                    <?php
                    $sqlEventos = "SELECT * FROM evento ORDER BY fecha DESC LIMIT 3";
                    $resEventos = $conn->query($sqlEventos);

                    while ($row = $resEventos->fetch_assoc()) {

                        $imgEvento = (!empty($row['imagen']) && file_exists("../img/eventos/" . $row['imagen']))
                            ? "../img/eventos/" . $row['imagen']
                            : "../img/default.png";
                    ?>

                        <div class="evento-card">
                            <img src="<?= $imgEvento ?>" class="evento-img">

                            <div class="evento-info">
                                <h6><?= htmlspecialchars($row["nombre_evento"]) ?></h6>

                                <small class="text-muted">
                                    Fecha: <?= $row["fecha"] ?><br>
                                    Lugar: <?= htmlspecialchars($row["lugar"]) ?><br>
                                    Observaciones: <?= htmlspecialchars($row["observaciones"]) ?>
                                </small>
                            </div>
                        </div>

                    <?php } ?>

                </div>
            </div>

            <?php include("../menu/php/piePagina.php"); ?>

        </div>
    </div>

    <!--  MODALES  -->

    <!-- FOTO -->
    <div class="modal fade" id="modalFoto">
        <div class="modal-dialog">
            <form method="POST" action="php/actualizar_perfil.php" enctype="multipart/form-data" class="modal-content">

                <div class="modal-header bg-success text-white">
                    <h5>Cambiar foto</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body text-center">

                    <img id="previewFoto" src="<?= $imagen ?>" style="width:120px; height:120px; border-radius:50%; margin-bottom:10px;">

                    <input type="file" name="imagen" class="form-control" onchange="previewImagen(event)" required>

                    <input type="hidden" name="nombre" value="<?= $user['nombre'] ?>">
                    <input type="hidden" name="correo" value="<?= $user['correo'] ?>">

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button class="btn btn-success">Guardar</button>
                </div>

            </form>
        </div>
    </div>

    <!-- CORREO -->
    <div class="modal fade" id="modalCorreo">
        <div class="modal-dialog">
            <form method="POST" action="php/actualizar_perfil.php" class="modal-content">

                <div class="modal-header bg-info text-white">
                    <h5>Editar correo</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">

                    <label>Correo actual</label>
                    <input type="text" class="form-control mb-2" value="<?= $user['correo'] ?>" disabled>

                    <label>Nuevo correo</label>
                    <input type="email" name="correo" class="form-control" required>

                    <input type="hidden" name="nombre" value="<?= $user['nombre'] ?>">

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button class="btn btn-info">Guardar</button>
                </div>

            </form>
        </div>
    </div>

    <!-- PASSWORD -->
    <div class="modal fade" id="modalPass">
        <div class="modal-dialog">
            <form method="POST" action="php/actualizar_perfil.php" class="modal-content">

                <div class="modal-header bg-info text-white">
                    <h5>Cambiar contraseña</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="position-relative mb-3">
                        <input type="password" name="actual" class="form-control pr-5" placeholder="Contraseña actual" required>

                        <span class="toggle-pass">
                            <i class="fa fa-eye"></i>
                        </span>
                    </div>

                    <div class="position-relative mb-3">
                        <input type="password" name="password" class="form-control pr-5" placeholder="Nueva contraseña">

                        <span class="toggle-pass">
                            <i class="fa fa-eye"></i>
                        </span>
                    </div>

                    <input type="hidden" name="nombre" value="<?= $user['nombre'] ?>">
                    <input type="hidden" name="correo" value="<?= $user['correo'] ?>">

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button class="btn btn-info">Guardar</button>
                </div>

            </form>
        </div>
    </div>

    <!-- SCRIPTS -->
    <!-- SCRIPTS -->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../js/sb-admin-2.min.js"></script>

    <script>
        function previewImagen(event) {
            const reader = new FileReader();
            reader.onload = e => document.getElementById('previewFoto').src = e.target.result;
            reader.readAsDataURL(event.target.files[0]);
        }

        document.querySelectorAll(".toggle-pass").forEach(icon => {
            icon.addEventListener("click", function() {
                let input = this.parentElement.querySelector("input");
                let i = this.querySelector("i");

                if (input.type === "password") {
                    input.type = "text";
                    i.classList.remove("fa-eye");
                    i.classList.add("fa-eye-slash");
                } else {
                    input.type = "password";
                    i.classList.remove("fa-eye-slash");
                    i.classList.add("fa-eye");
                }
            });
        });
    </script>

</body>

</html>