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

/* --- NUEVO: CAPTURAR MENSAJES DE LA URL --- */
$mensaje = isset($_GET['msg']) ? $_GET['msg'] : "";
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : "";

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
    <link href="../css/styles.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background: #eef4ff;
        }

        .perfil-container {
            max-width: 1000px;
            margin: auto;
        }

        .perfil-box {
            display: flex;
            gap: 50px;
            flex-wrap: wrap;
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .foto-container {
            position: relative;
            width: 150px;
            margin: auto;
        }

        .perfil-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #3b82f6;
        }

        .btn-foto {
            position: absolute;
            bottom: 5px;
            right: 5px;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .info-box {
            flex: 1;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }

        .info-text {
            font-weight: 600;
            color: #4e73df;
        }

        .bg-primary {
            background-color: #3b82f6 !important;
        }

        .rounded-4 {
            border-radius: 1rem !important;
        }

        .rounded-top-4 {
            border-top-left-radius: 1rem !important;
            border-top-right-radius: 1rem !important;
        }

        .fw-bold {
            font-weight: bold;
        }

        .toggle-pass {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            z-index: 10;
            color: #ccc;
        }

        .evento-card {
            display: flex;
            align-items: center;
            background: #fff;
            border-radius: 15px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        }

        .evento-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 12px;
            margin-right: 15px;
        }
    </style>
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include("../menu/php/menuLateral.php"); ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include("../menu/php/barraSuperior.php"); ?>

                <div class="container-fluid perfil-container mt-4">
                    <h4 class="mb-4 fw-bold text-gray-800">Mi Perfil</h4>

                    <div class="perfil-box shadow-sm">
                        <div class="text-center">
                            <div class="foto-container">
                                <img src="<?= $imagen ?>" class="perfil-img shadow">
                                <button class="btn btn-primary btn-foto shadow" data-toggle="modal" data-target="#modalFoto">
                                    <i class="fas fa-camera"></i>
                                </button>
                            </div>
                            <div class="mt-3">
                                <h5 class="fw-bold mb-0"><?= htmlspecialchars($user['nombre']) ?></h5>
                                <span class="badge px-3 rounded-pill" style="background-color: #b1cfff; color: black;"><?= $rol ?></span>
                            </div>
                        </div>

                        <div class="info-box">
                            <h5 class="fw-bold text-gray-800 mb-3"><i class="fas fa-info-circle mr-2"></i>Información de cuenta</h5>
                            <div class="info-item">
                                <span><i class="fas fa-envelope mr-2"></i> Correo</span>
                                <div class="d-flex align-items-center">
                                    <span class="info-text mr-3"><?= htmlspecialchars($user['correo']) ?></span>
                                    <button class="btn btn-outline-primary btn-sm rounded-circle" data-toggle="modal" data-target="#modalCorreo">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="info-item">
                                <span><i class="fas fa-lock mr-2"></i> Contraseña</span>
                                <div class="d-flex align-items-center">
                                    <span class="info-text mr-3">********</span>
                                    <button class="btn btn-outline-primary btn-sm rounded-circle" data-toggle="modal" data-target="#modalPass">
                                        <i class="fas fa-key"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h4 class="mt-5 mb-3 fw-bold text-gray-800">Actividad Reciente</h4>
                    <?php
                    $sqlEventos = "SELECT * FROM evento ORDER BY fecha DESC LIMIT 3";
                    $resEventos = $conn->query($sqlEventos);
                    while ($row = $resEventos->fetch_assoc()) {
                        $imgEvento = (!empty($row['imagen']) && file_exists("../img/eventos/" . $row['imagen']))
                            ? "../img/eventos/" . $row['imagen'] : "../img/default.png";
                    ?>
                        <div class="evento-card border-left-primary">
                            <img src="<?= $imgEvento ?>" class="evento-img shadow-sm">
                            <div class="evento-info">
                                <h6 class="fw-bold mb-1"><?= htmlspecialchars($row["nombre_evento"]) ?></h6>
                                <small class="text-muted">
                                    <i class="fas fa-calendar-alt mr-1"></i> <?= date("d/m/Y", strtotime($row["fecha"])) ?>
                                </small>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <?php include("../menu/php/piePagina.php"); ?>
        </div>
    </div>

    <div class="modal fade" id="modalFoto">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="php/actualizar_perfil.php" enctype="multipart/form-data" class="modal-content shadow-lg border-0 rounded-4">
                <div class="modal-header bg-primary text-white rounded-top-4">
                    <h5 class="mb-0"><i class="fas fa-image mr-2"></i> Actualizar Foto</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body text-center">
                    <img id="previewFoto" src="<?= $imagen ?>" style="width:150px; height:150px; border-radius:50%; object-fit:cover; border:3px solid #eee;" class="mb-3 shadow-sm">
                    <div class="custom-file text-left">
                        <input type="file" name="imagen" class="custom-file-input" id="inputFoto" onchange="previewImagen(event)" accept="image/*" required>
                        <label class="custom-file-label" for="inputFoto">Elegir nueva imagen...</label>
                    </div>
                    <input type="hidden" name="nombre" value="<?= $user['nombre'] ?>">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success px-4 rounded-pill shadow-sm">
                        <i class="fas fa-save mr-2"></i> Guardar Foto
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modalCorreo">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="php/actualizar_perfil.php" class="modal-content shadow-lg border-0 rounded-4">
                <div class="modal-header bg-primary text-white rounded-top-4">
                    <h5 class="mb-0"><i class="fas fa-envelope mr-2"></i> Editar Correo</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <label class="fw-bold">Nuevo Correo Electrónico</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-at"></i></span>
                        </div>
                        <input type="email" name="correo" class="form-control" placeholder="nuevo@correo.com" required>
                    </div>
                    <input type="hidden" name="nombre" value="<?= $user['nombre'] ?>">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success px-4 rounded-pill shadow-sm">
                        <i class="fas fa-save mr-2"></i> Actualizar Correo
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modalPass">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="php/actualizar_perfil.php" id="formPass" class="modal-content shadow-lg border-0 rounded-4">
                <div class="modal-header bg-primary text-white rounded-top-4">
                    <h5 class="mb-0"><i class="fas fa-lock mr-2"></i> Seguridad de la Cuenta</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <label class="fw-bold">Contraseña Actual</label>
                    <div class="position-relative mb-3">
                        <input type="password" name="actual" class="form-control pr-5" placeholder="Escribe tu contraseña actual" required>
                        <span class="toggle-pass"><i class="fa fa-eye"></i></span>
                    </div>
                    <label class="fw-bold">Nueva Contraseña</label>
                    <div class="position-relative mb-3">
                        <input type="password" name="password" id="newPass" class="form-control pr-5" placeholder="Mínimo 6 caracteres" required>
                        <span class="toggle-pass"><i class="fa fa-eye"></i></span>
                    </div>
                    <label class="fw-bold">Confirmar Nueva Contraseña</label>
                    <div class="position-relative">
                        <input type="password" id="confirmPass" class="form-control pr-5" placeholder="Repite la nueva contraseña" required>
                        <span class="toggle-pass"><i class="fa fa-eye"></i></span>
                    </div>
                    <input type="hidden" name="nombre" value="<?= $user['nombre'] ?>">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success px-4 rounded-pill shadow-sm">
                        <i class="fas fa-save mr-2"></i> Cambiar Contraseña
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../js/sb-admin-2.min.js"></script>

    <script>
        $(document).ready(function() {
            // MOSTRAR CONFIRMACIÓN
            <?php if (!empty($mensaje)) : ?>
                Swal.fire({
                    icon: '<?= $tipo ?>',
                    title: '<?= $mensaje ?>',
                    showConfirmButton: false,
                    timer: 2000
                }).then(() => {
                    // Limpia la URL
                    window.history.replaceState({}, document.title, window.location.pathname);
                });
            <?php endif; ?>

            // VALIDACIÓN ANTES DE ENVIAR
            $('form').on('submit', function(e) {
                if (this.id === 'formPass') {
                    const p1 = $('#newPass').val();
                    const p2 = $('#confirmPass').val();

                    if (p1 !== p2) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'Las contraseñas no coinciden',
                            text: 'Por favor, verifica que ambos campos sean iguales.'
                        });
                        return false;
                    }

                    if (p1.length < 6) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'warning',
                            title: 'Contraseña muy corta',
                            text: 'La nueva contraseña debe tener al menos 6 caracteres.'
                        });
                        return false;
                    }
                }
            });
        });

        // Previsualizar Imagen
        function previewImagen(event) {
            const file = event.target.files[0];
            if (file && file.size > 2000000) {
                Swal.fire('Error', 'La imagen es muy pesada (máx 2MB)', 'error');
                event.target.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = e => document.getElementById('previewFoto').src = e.target.result;
            reader.readAsDataURL(file);
        }

        // Mostrar/Ocultar Pass
        document.querySelectorAll(".toggle-pass").forEach(icon => {
            icon.addEventListener("click", function() {
                let input = this.parentElement.querySelector("input");
                let i = this.querySelector("i");
                input.type = input.type === "password" ? "text" : "password";
                i.classList.toggle("fa-eye");
                i.classList.toggle("fa-eye-slash");
            });
        });
    </script>
</body>

</html>