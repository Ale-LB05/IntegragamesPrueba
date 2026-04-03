<?php
session_start();
include("../config/conexion.php");
if (!isset($_SESSION['usuario'])) {
    header("Location: ../RegistroAdmin/login.php");
    exit();
}

$rol = $_SESSION['rol'];

if (!isset($_SESSION['usuario'])) {
    header("Location: ../RegistroAdmin/login.php");
    exit();
}

$mensaje = "";
$tipo = "";

/* --- CREAR --- */
if (isset($_POST['crear'])) {
    // Escapamos los datos para evitar errores de sintaxis y XSS
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $correo = $conn->real_escape_string($_POST['correo']);
    $contraseña = $conn->real_escape_string($_POST['contrasena']);
    $rol = $conn->real_escape_string($_POST['rol']);

    // IMAGEN POR DEFECTO
    $imagenNombre = "sinFoto.jpg";

    $sql = "INSERT INTO responsable(nombre, correo, contraseña, rol, imagen)
            VALUES('$nombre','$correo','$contraseña','$rol','$imagenNombre')";

    // Ejecutamos la consulta UNA SOLA VEZ dentro del condicional
    if ($conn->query($sql)) {
        $mensaje = "Empleado creado correctamente";
        $tipo = "success";
    } else {
        $mensaje = "Error al crear empleado: " . $conn->error;
        $tipo = "error";
    }
}

/* --- EDITAR --- */
if (isset($_POST['editar'])) {
    $id = intval($_POST['id']);
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $correo = $conn->real_escape_string($_POST['correo']);
    $contraseña = $conn->real_escape_string($_POST['contrasena']);
    $rol = $conn->real_escape_string($_POST['rol']);

    // OBTENER IMAGEN ACTUAL
    $sqlImg = $conn->query("SELECT imagen FROM responsable WHERE id_responsable='$id'");
    $imagenNombre = "sinFoto.jpg"; // Por si no hay previa

    if ($sqlImg && $sqlImg->num_rows > 0) {
        $fila = $sqlImg->fetch_assoc();
        $imagenNombre = $fila['imagen'];
    }

    // NUEVA IMAGEN (Si el usuario subió una)
    if (!empty($_FILES['imagen']['name'])) {
        $imagenNombre = time() . "_" . $_FILES['imagen']['name'];
        $ruta = "../img/responsables/" . $imagenNombre;
        move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta);
    }

    $sql = "UPDATE responsable 
            SET nombre='$nombre', 
                correo='$correo', 
                contraseña='$contraseña', 
                rol='$rol',
                imagen='$imagenNombre'
            WHERE id_responsable='$id'";

    // Ejecutamos la consulta UNA SOLA VEZ dentro del condicional
    if ($conn->query($sql)) {
        $mensaje = "Datos actualizados";
        $tipo = "success";
    } else {
        $mensaje = "Error al actualizar: " . $conn->error;
        $tipo = "error";
    }
}

/* --- ELIMINAR --- */
if (isset($_POST['eliminar'])) {
    $id = intval($_POST['id_responsable']);

    if ($conn->query("DELETE FROM responsable WHERE id_responsable='$id'")) {
        $mensaje = "Empleado eliminado";
        $tipo = "success";
    } else {
        $mensaje = "No se pudo eliminar";
        $tipo = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <?php include("../menu/php/encabezado.php"); ?>
    <link rel="icon" href="../img/logo.png">
    <link href="../css/styles.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body id="page-top">

    <div id="wrapper">

        <!-- Sidebar -->
        <?php include("../menu/php/menuLateral.php"); ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">

                <!-- Topbar -->
                <?php include("../menu/php/barraSuperior.php"); ?>

                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4 flex-wrap">

                        <!-- TITULO -->
                        <h1 class="h3 text-gray-800 mb-2">Panel de personal</h1>

                        <!-- DERECHA -->
                        <div class="d-flex align-items-center flex-wrap">
                            <!-- BUSCADOR -->
                            <div class="input-group mr-2 mb-2" style="width: 400px;">
                                <span class="input-group-text bg-white">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" id="buscador" class="form-control"
                                    placeholder="Buscar por nombre o rol...">
                            </div>
                            <!-- BOTON -->
                            <button class="btn btn-success mb-2"
                                data-toggle="modal"
                                data-target="#modalCrear">
                                <i class="fas fa-plus"></i> Nuevo Empleado
                            </button>

                        </div>
                    </div>

                    <!-- CARD PRINCIPAL -->
                    <div class="card shadow mb-4">
                        <div class="card-body">

                            <div class="row">
                                <?php
                                $sql = "SELECT * FROM responsable";
                                $res = $conn->query($sql);

                                while ($row = $res->fetch_assoc()) {
                                ?>

                                    <div class="col-12 mb-3 empleado-item"
                                        data-nombre="<?= strtolower($row['nombre']) ?>"
                                        data-rol="<?= strtolower($row['rol']) ?>">

                                        <div class="card shadow-sm p-3 d-flex flex-row justify-content-between align-items-center" style="border-radius:15px;">

                                            <!-- IZQUIERDA -->
                                            <div class="d-flex align-items-center">

                                                <!-- IMAGEN -->
                                                <?php
                                                $imagen = (!empty($row['imagen']) && file_exists("../img/responsables/" . $row['imagen']))
                                                    ? "../img/responsables/" . $row['imagen']
                                                    : "../img/responsables/sinFoto.jpg";
                                                ?>

                                                <img src="<?= $imagen ?>"
                                                    style="width:60px; height:60px; object-fit:cover; border-radius:50%; margin-right:15px;">

                                                <div>
                                                    <h6 class="mb-1"><?= $row["nombre"] ?></h6>
                                                    <small class="text-muted">
                                                        <h7>Correo: "<?= $row["correo"] ?>"</h7><br>
                                                        <h7>Contraseña: "<?= $row["contraseña"] ?>"</h7><br>
                                                        <h7>Rol: "<?= $row["rol"] ?>"</h7>
                                                    </small>
                                                </div>

                                            </div>

                                            <!-- BOTONES -->
                                            <div>

                                                <!-- EDITAR -->
                                                <button class="btn btn-info btn-sm"
                                                    data-toggle="modal"
                                                    data-target="#modalEditar"
                                                    onclick="editarRegistro(
                                                        '<?= $row['id_responsable'] ?>',
                                                        '<?= $row['nombre'] ?>',
                                                        '<?= $row['correo'] ?>',
                                                        '<?= htmlspecialchars($row['contraseña'], ENT_QUOTES) ?>',
                                                        '<?= $row['rol'] ?>',
                                                        '<?= $imagen ?>'
                                                        )">
                                                    <i class="fas fa-edit"></i>
                                                </button>

                                                <!-- ELIMINAR -->
                                                <button class="btn btn-danger btn-sm"
                                                    onclick="borraRegistro('<?= $row['id_responsable'] ?>','<?= $row['nombre'] ?>')">
                                                    <i class="fas fa-trash"></i>
                                                </button>

                                            </div>

                                        </div>
                                    </div>

                                <?php } ?>
                            </div>

                        </div>
                    </div>

                </div>

            </div>

            <!-- FOOTER -->
            <?php include("../menu/php/piePagina.php"); ?>

        </div>
    </div>

    <!--MODALES -->

    <!-- CREAR -->
    <div class="modal fade" id="modalCrear">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" enctype="multipart/form-data" class="modal-content shadow-lg border-0 rounded-4">

                <div class="modal-header bg-success text-white rounded-top-4">
                    <h5 class="mb-0">Nuevo Responsable</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">

                    <label class="fw-bold">Nombre Completo</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" name="nombre" class="form-control" placeholder="Ej: Juan Pérez" required>
                    </div>

                    <label class="fw-bold">Correo Electrónico</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" name="correo" class="form-control" placeholder="correo@ejemplo.com" required autocomplete="off">
                    </div>

                    <label class="fw-bold">Contraseña</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" name="contrasena" id="crearContrasena" class="form-control" placeholder="Asignar contraseña" required autocomplete="new-password">
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('crearContrasena', this)">
                            <i class="fa fa-eye"></i>
                        </button>
                    </div>

                    <label class="fw-bold">Rol del Responsable</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                        <select name="rol" class="form-control" required>
                            <option value="">Seleccionar rol...</option>
                            <option value="administrador">Administrador</option>
                            <option value="programador">Programador</option>
                            <option value="promotor">Promotor</option>
                        </select>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" name="crear" class="btn btn-success px-4 rounded-pill">
                        <i class="fas fa-save"></i> Crear Responsable
                    </button>
                </div>

            </form>
        </div>
    </div>

    <!-- EDITAR -->
    <div class="modal fade" id="modalEditar">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" enctype="multipart/form-data" class="modal-content shadow-lg border-0 rounded-4">

                <div class="modal-header bg-info text-white rounded-top-4">
                    <h5 class="mb-0"><i class="fas fa-user-edit"></i> Editar Responsable</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="id" id="editId">

                    <label class="fw-bold d-block text-center">Imagen actual</label>
                    <div class="text-center mb-3">
                        <img id="previewImagen" src=""
                            style="width:90px; height:90px; object-fit:cover; border-radius:50%; border:2px solid #17a2b8;">
                    </div>

                    <label class="fw-bold">Cambiar Foto</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fas fa-image"></i></span>
                        <input type="file" name="imagen" class="form-control">
                    </div>

                    <label class="fw-bold">Nombre Completo</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" name="nombre" id="editNombre" class="form-control" required autocomplete="off">
                    </div>

                    <label class="fw-bold">Correo Electrónico</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" name="correo" id="editCorreo" class="form-control" required autocomplete="off">
                    </div>

                    <label class="fw-bold">Contraseña (Dejar vacío para no cambiar)</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" name="contrasena" id="editContrasena" class="form-control" placeholder="Nueva contraseña" autocomplete="new-password">
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('editContrasena', this)">
                            <i class="fa fa-eye"></i>
                        </button>
                    </div>

                    <label class="fw-bold">Rol del Responsable</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                        <select name="rol" id="editRol" class="form-control" required>
                            <option value="administrador">Administrador</option>
                            <option value="programador">Programador</option>
                            <option value="promotor">Promotor</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" name="editar" class="btn btn-info px-4 rounded-pill text-white">
                        <i class="fas fa-save"></i> Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JS -->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../js/sb-admin-2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // 1. Mostrar alerta si hay mensaje de PHP
            <?php if (!empty($mensaje)) : ?>
                Swal.fire({
                    icon: <?php echo json_encode($tipo); ?>,
                    title: <?php echo json_encode($mensaje); ?>,
                    showConfirmButton: false,
                    timer: 2000
                });
            <?php endif; ?>

            // 2. Efecto de "Guardando..." en todos los formularios
            $('form').on('submit', function() {
                // Solo si el formulario es de Crear o Editar (no el de eliminar que manejamos aparte)
                if (!$(this).find('button[name="eliminar"]').length) {
                    Swal.fire({
                        title: 'Procesando...',
                        text: 'Guardando información del personal',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    });
                }
            });
        });
        // --- ESTA ES LA FUNCIÓN QUE TE FALTABA ---
        function togglePassword(id, icono) {
            let input = document.getElementById(id);
            // Buscamos el icono dentro del botón o el elemento clickeado
            let icon = icono.querySelector("i") || icono;

            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }

        // Lógica para llenar el modal de editar
        function editarRegistro(id, nombre, correo, contrasena, rol, imagen) {
            document.getElementById('editId').value = id;
            document.getElementById('editNombre').value = nombre; // Esto busca id="editNombre"
            document.getElementById('editCorreo').value = correo; // Esto busca id="editCorreo"
            document.getElementById('editContrasena').value = contrasena;
            document.getElementById('editRol').value = rol;
            document.getElementById('previewImagen').src = imagen;
        }

        // 3. Reemplazar el modal de eliminar de Bootstrap por SweetAlert2
        function borraRegistro(id, nombre) {
            Swal.fire({
                title: '¿Eliminar a ' + nombre + '?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Creamos un formulario dinámico para enviar el POST
                    let form = document.createElement("form");
                    form.method = "POST";

                    let inputId = document.createElement("input");
                    inputId.type = "hidden";
                    inputId.name = "id_responsable";
                    inputId.value = id;

                    let inputAccion = document.createElement("input");
                    inputAccion.type = "hidden";
                    inputAccion.name = "eliminar";
                    inputAccion.value = "1";

                    form.appendChild(inputId);
                    form.appendChild(inputAccion);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>

</body>

</html>