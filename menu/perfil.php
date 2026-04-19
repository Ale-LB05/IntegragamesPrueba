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

/* CAPTURAR MENSAJES DE LA URL */
$mensaje = isset($_GET['msg']) ? $_GET['msg'] : "";
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : "";

/* OBTENER USUARIO ACTUAL */
$sql = "SELECT * FROM responsable WHERE nombre='$usuarioActual'";
$res = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($res);

if (!$user) {
    die("Usuario no encontrado");
}

$id_responsable_actual = $user['id_responsable'];

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
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border: none;
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
            border: 4px solid #4e73df;
        }

        .btn-foto {
            position: absolute;
            bottom: 5px;
            right: 5px;
            border-radius: 50%;
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #4e73df;
            border: 2px solid white;
        }

        .info-box {
            flex: 1;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #f1f3f5;
        }

        .info-text {
            font-weight: 600;
            color: #4e73df;
        }

        .bg-primary {
            background-color: #4e73df !important;
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

        .evento-card {
            display: flex;
            align-items: center;
            background: #fff;
            border-radius: 15px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            border-left: 5px solid #4e73df;
            transition: transform 0.2s ease;
        }

        .evento-card:hover {
            transform: translateX(5px);
        }

        .evento-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 12px;
            margin-right: 15px;
        }

        .text-primary {
            color: #4e73df !important;
        }

        .text-secondary {
            color: #858796 !important;
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
                    <h4 class="mb-4 fw-bold text-gray-800"><i class="fas fa-user-circle mr-2" style="color: #4e73df;"></i> Mi Perfil</h4>

                    <div class="perfil-box shadow-sm mb-5">
                        <div class="text-center">
                            <div class="foto-container">
                                <img src="<?= $imagen ?>" class="perfil-img shadow-sm">
                                <button class="btn btn-primary btn-foto shadow-sm text-white" data-toggle="modal" data-target="#modalFoto" title="Cambiar foto">
                                    <i class="fas fa-camera"></i>
                                </button>
                            </div>
                            <div class="mt-3">
                                <div class="d-flex justify-content-center align-items-center">
                                    <h5 class="fw-bold mb-0 text-dark mr-2"><?= htmlspecialchars($user['nombre']) ?></h5>
                                    <button class="btn btn-outline-primary btn-sm rounded-circle" data-toggle="modal" data-target="#modalNombre" title="Editar nombre">
                                        <i class="fas fa-pen" style="font-size: 0.7rem;"></i>
                                    </button>
                                </div>
                                <span class="badge px-3 py-2 mt-2 rounded-pill bg-light text-primary border"><i class="fas fa-user-tag mr-1"></i> <?= ucfirst($rol) ?></span>
                            </div>
                        </div>

                        <div class="info-box pl-md-4">
                            <h5 class="fw-bold text-gray-800 mb-3"><i class="fas fa-info-circle mr-2" style="color: #4e73df;"></i> Información de cuenta</h5>

                            <div class="info-item">
                                <span class="text-muted"><i class="fas fa-envelope mr-2" style="color: #4e73df;"></i> Correo</span>
                                <div class="d-flex align-items-center">
                                    <span class="info-text mr-3"><?= htmlspecialchars($user['correo']) ?></span>
                                    <button class="btn btn-outline-primary btn-sm rounded-circle" data-toggle="modal" data-target="#modalCorreo" title="Editar correo">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="info-item border-0">
                                <span class="text-muted"><i class="fas fa-lock mr-2" style="color: #4e73df;"></i> Contraseña</span>
                                <div class="d-flex align-items-center">
                                    <span class="info-text mr-3">••••••••</span>
                                    <button class="btn btn-outline-primary btn-sm rounded-circle" data-toggle="modal" data-target="#modalPass" title="Cambiar contraseña">
                                        <i class="fas fa-key"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h4 class="mb-3 fw-bold text-gray-800"><i class="fas fa-calendar-check mr-2" style="color: #4e73df;"></i> Mis Eventos Asignados</h4>
                    <?php
                    // CONSULTA MODIFICADA: Se quitó 'hora' y ahora trae todas las columnas de 'evento' incluyendo hora_inicio y hora_fin
                    $sqlEventos = "SELECT e.* FROM evento e 
                                   INNER JOIN evento_responsable er ON e.id_evento = er.id_evento 
                                   WHERE er.id_responsable = '$id_responsable_actual' 
                                   ORDER BY e.fecha ASC";
                    $resEventos = $conn->query($sqlEventos);

                    if ($resEventos && $resEventos->num_rows > 0) {
                        while ($row = $resEventos->fetch_assoc()) {
                            $imgEvento = (!empty($row['imagen']) && file_exists("../img/eventos/" . $row['imagen']))
                                ? "../img/eventos/" . $row['imagen'] : "../img/default.png";

                            // Verificar si el evento ya pasó para cambiarle el color
                            $esPasado = ($row['fecha'] < date("Y-m-d"));
                            $bordeColor = $esPasado ? "border-left: 5px solid #6c757d; opacity: 0.8;" : "border-left: 5px solid #1cc88a;";
                    ?>
                            <div class="evento-card" style="<?= $bordeColor ?>">
                                <img src="<?= $imgEvento ?>" class="evento-img shadow-sm">
                                <div class="evento-info flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="fw-bold mb-1 text-dark"><?= htmlspecialchars($row["nombre_evento"]) ?></h6>
                                        <?php if ($esPasado): ?>
                                            <span class="badge bg-secondary text-white">Finalizado</span>
                                        <?php else: ?>
                                            <span class="badge bg-success text-white">Próximo</span>
                                        <?php endif; ?>
                                    </div>
                                    <small class="text-muted d-block mt-1">
                                        <i class="fas fa-calendar-day mr-1" style="color: #4e73df;"></i> <?= date("d/m/Y", strtotime($row["fecha"])) ?> &nbsp;|&nbsp;
                                        <i class="fas fa-clock mr-1" style="color: #4e73df;"></i> 
                                        <?php 
                                            if(!empty($row["hora_inicio"]) && !empty($row["hora_fin"])){
                                                echo date("h:i A", strtotime($row["hora_inicio"])) . " - " . date("h:i A", strtotime($row["hora_fin"])); 
                                            } else {
                                                echo "Horario no definido";
                                            }
                                        ?>
                                    </small>
                                    <small class="text-muted d-block mt-1">
                                        <i class="fas fa-map-marker-alt mr-1" style="color: #e74a3b;"></i> <?= htmlspecialchars($row["lugar"]) ?>
                                    </small>
                                </div>
                            </div>
                    <?php
                        }
                    } else {
                        // Mensaje si no tiene eventos asignados
                        echo '<div class="alert alert-light border shadow-sm text-center py-4 rounded-4 text-muted">
                                <i class="fas fa-calendar-times fa-3x mb-3" style="color: #cbd5e1;"></i><br>
                                <h6 class="fw-bold">No tienes eventos asignados</h6>
                                <p class="small mb-0">Cuando un administrador te asigne a un evento, aparecerá aquí.</p>
                              </div>';
                    }
                    ?>
                </div>
            </div>
            <?php include("../menu/php/piePagina.php"); ?>
        </div>
    </div>

    <div class="modal fade" id="modalNombre" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="php/actualizar_perfil.php" class="modal-content shadow-lg border-0 rounded-4">
                <div class="modal-header bg-primary text-white rounded-top-4">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-user-edit mr-2"></i> Editar Nombre</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body p-4">
                    <h6 class="fw-bold mb-3 border-bottom pb-2" style="color: #4e73df;">
                        <i class="fas fa-id-card mr-1"></i> Actualización de Identidad
                    </h6>
                    <label class="fw-bold text-muted small">Nuevo Nombre de Usuario</label>
                    <div class="input-group mb-2">
                        <span class="input-group-text bg-light border-right-0" style="color: #4e73df;"><i class="fas fa-user"></i></span>
                        <input type="text" name="nombre" class="form-control border-left-0" placeholder="Ej: JuanPerez" value="<?= htmlspecialchars($user['nombre']) ?>" required>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top-0">
                    <button type="button" class="btn btn-outline-danger px-4 rounded-pill" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-info px-4 rounded-pill shadow-sm text-white fw-bold">
                        <i class="fas fa-save mr-2"></i> Actualizar Nombre
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modalFoto" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="php/actualizar_perfil.php" enctype="multipart/form-data" class="modal-content shadow-lg border-0 rounded-4">
                <div class="modal-header bg-primary text-white rounded-top-4">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-camera mr-2"></i> Actualizar Foto</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body p-4 text-center">
                    <h6 class="fw-bold mb-3 border-bottom pb-2 text-left" style="color: #4e73df;">
                        <i class="fas fa-image mr-1"></i> Nueva Imagen de Perfil
                    </h6>
                    <img id="previewFoto" src="<?= $imagen ?>" style="width:150px; height:150px; border-radius:50%; object-fit:cover; border:4px solid #4e73df;" class="mb-4 shadow-sm mt-2">

                    <div class="input-group mb-2">
                        <span class="input-group-text bg-light border-right-0" style="color: #4e73df;"><i class="fas fa-upload"></i></span>
                        <input type="file" name="imagen" class="form-control border-left-0" accept="image/*" onchange="previewImagen(event)" required>
                    </div>
                    <small class="text-muted"><i class="fas fa-info-circle"></i> Se recomienda una imagen cuadrada. Máximo 2MB.</small>

                    <input type="hidden" name="nombre" value="<?= htmlspecialchars($user['nombre']) ?>">
                </div>
                <div class="modal-footer bg-light border-top-0">
                    <button type="button" class="btn btn-outline-danger px-4 rounded-pill" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-info px-4 rounded-pill shadow-sm text-white fw-bold">
                        <i class="fas fa-save mr-2"></i> Guardar Foto
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modalCorreo" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="php/actualizar_perfil.php" class="modal-content shadow-lg border-0 rounded-4">
                <div class="modal-header bg-primary text-white rounded-top-4">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-envelope mr-2"></i> Editar Correo</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body p-4">
                    <h6 class="fw-bold mb-3 border-bottom pb-2" style="color: #4e73df;">
                        <i class="fas fa-at mr-1"></i> Actualización de Contacto
                    </h6>
                    <label class="fw-bold text-muted small">Nuevo Correo Electrónico</label>
                    <div class="input-group mb-2">
                        <span class="input-group-text bg-light border-right-0" style="color: #4e73df;"><i class="fas fa-envelope"></i></span>
                        <input type="email" name="correo" class="form-control border-left-0" placeholder="Ej: nuevo@correo.com" value="<?= htmlspecialchars($user['correo']) ?>" required>
                    </div>

                    <input type="hidden" name="nombre" value="<?= htmlspecialchars($user['nombre']) ?>">
                </div>
                <div class="modal-footer bg-light border-top-0">
                    <button type="button" class="btn btn-outline-danger px-4 rounded-pill" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-info px-4 rounded-pill shadow-sm text-white fw-bold">
                        <i class="fas fa-save mr-2"></i> Actualizar Correo
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modalPass" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="php/actualizar_perfil.php" id="formPass" class="modal-content shadow-lg border-0 rounded-4">
                <div class="modal-header bg-primary text-white rounded-top-4">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-lock mr-2"></i> Seguridad</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body p-4">
                    <h6 class="fw-bold mb-4 border-bottom pb-2" style="color: #4e73df;">
                        <i class="fas fa-key mr-1"></i> Cambio de Contraseña
                    </h6>

                    <label class="fw-bold text-muted small">Contraseña Actual</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-light border-right-0" style="color: #4e73df;"><i class="fas fa-unlock"></i></span>
                        <input type="password" name="actual" id="passActual" class="form-control border-left-0" placeholder="Ingresa tu clave actual" required>
                        <button class="btn btn-outline-secondary border-left-0" type="button" onclick="togglePassword('passActual', this)"><i class="fa fa-eye"></i></button>
                    </div>

                    <label class="fw-bold text-muted small mt-2">Nueva Contraseña</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-light border-right-0" style="color: #1cc88a;"><i class="fas fa-lock"></i></span>
                        <input type="password" name="password" id="newPass" class="form-control border-left-0" placeholder="Mínimo 6 caracteres" required>
                        <button class="btn btn-outline-secondary border-left-0" type="button" onclick="togglePassword('newPass', this)"><i class="fa fa-eye"></i></button>
                    </div>

                    <label class="fw-bold text-muted small">Confirmar Nueva Contraseña</label>
                    <div class="input-group mb-2">
                        <span class="input-group-text bg-light border-right-0" style="color: #1cc88a;"><i class="fas fa-check-circle"></i></span>
                        <input type="password" id="confirmPass" class="form-control border-left-0" placeholder="Repite la nueva clave" required>
                        <button class="btn btn-outline-secondary border-left-0" type="button" onclick="togglePassword('confirmPass', this)"><i class="fa fa-eye"></i></button>
                    </div>

                    <input type="hidden" name="nombre" value="<?= htmlspecialchars($user['nombre']) ?>">
                </div>
                <div class="modal-footer bg-light border-top-0">
                    <button type="button" class="btn btn-outline-danger px-4 rounded-pill" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-info px-4 rounded-pill shadow-sm text-white fw-bold">
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
            // MOSTRAR ALERTA DE ÉXITO O ERROR
            <?php if (!empty($mensaje)) : ?>
                Swal.fire({
                    icon: '<?= $tipo ?>',
                    title: '<?= $mensaje ?>',
                    showConfirmButton: false,
                    timer: 2000
                }).then(() => {
                    // Limpiar la URL para que no se vuelva a mostrar la alerta al recargar
                    window.history.replaceState({}, document.title, window.location.pathname);
                });
            <?php endif; ?>

            // VALIDACIÓN DE CONTRASEÑAS ANTES DE ENVIAR
            $('#formPass').on('submit', function(e) {
                const p1 = $('#newPass').val();
                const p2 = $('#confirmPass').val();

                if (p1 !== p2) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Las contraseñas nuevas no coinciden.'
                    });
                    return false;
                }

                if (p1.length < 6) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Muy corta',
                        text: 'La nueva contraseña debe tener al menos 6 caracteres.'
                    });
                    return false;
                }
            });

            // Cargar estado al enviar formularios (excepto foto para que no estorbe)
            $('form:not([enctype="multipart/form-data"])').on('submit', function() {
                Swal.fire({
                    title: 'Guardando...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                });
            });
        });

        // PREVISUALIZAR FOTO DE PERFIL
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

        // MOSTRAR U OCULTAR CONTRASEÑA
        function togglePassword(id, btn) {
            let input = document.getElementById(id);
            let icon = btn.querySelector("i");
            input.type = input.type === "password" ? "text" : "password";
            icon.classList.toggle("fa-eye");
            icon.classList.toggle("fa-eye-slash");
        }
    </script>
</body>

</html>