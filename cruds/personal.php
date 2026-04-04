<?php
session_start();
include("../config/conexion.php");

/* VALIDAR SESIÓN */
if (!isset($_SESSION['usuario'])) {
    header("Location: ../RegistroAdmin/login.php");
    exit();
}

$rol = $_SESSION['rol'];
$mensaje = "";
$tipo = "";

/* --- CREAR --- */
if (isset($_POST['crear'])) {
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $correo = $conn->real_escape_string($_POST['correo']);
    $contraseña = $conn->real_escape_string($_POST['contrasena']);
    $rol_resp = $conn->real_escape_string($_POST['rol']);
    $imagenNombre = "sinFoto.jpg";

    if (!empty($_FILES['imagen']['name'])) {
        $imagenNombre = time() . "_" . basename($_FILES['imagen']['name']);
        move_uploaded_file($_FILES['imagen']['tmp_name'], "../img/responsables/" . $imagenNombre);
    }

    $sql = "INSERT INTO responsable(nombre, correo, contraseña, rol, imagen) VALUES('$nombre','$correo','$contraseña','$rol_resp','$imagenNombre')";

    if ($conn->query($sql)) {
        $mensaje = "Empleado creado correctamente";
        $tipo = "success";
    } else {
        $mensaje = "Error al crear: " . $conn->error;
        $tipo = "error";
    }
}

/* --- EDITAR --- */
if (isset($_POST['editar'])) {
    $id = intval($_POST['id']);
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $correo = $conn->real_escape_string($_POST['correo']);
    $contraseña = $conn->real_escape_string($_POST['contrasena']);
    $rol_resp = $conn->real_escape_string($_POST['rol']);

    $sqlImg = $conn->query("SELECT imagen FROM responsable WHERE id_responsable='$id'");
    $fila = $sqlImg->fetch_assoc();
    $imagenNombre = $fila['imagen'];

    if (!empty($_FILES['imagen']['name'])) {
        $imagenNombre = time() . "_" . basename($_FILES['imagen']['name']);
        move_uploaded_file($_FILES['imagen']['tmp_name'], "../img/responsables/" . $imagenNombre);
    }

    $sql = "UPDATE responsable SET nombre='$nombre', correo='$correo', contraseña='$contraseña', rol='$rol_resp', imagen='$imagenNombre' WHERE id_responsable='$id'";

    if ($conn->query($sql)) {
        $mensaje = "Datos actualizados";
        $tipo = "success";
    } else {
        $mensaje = "Error: " . $conn->error;
        $tipo = "error";
    }
}

/* --- ELIMINAR (CORREGIDO PARA LLAVES FORÁNEAS) --- */
if (isset($_POST['eliminar'])) {
    $id = intval($_POST['id_responsable']);

    // 1. Primero eliminamos su relación con los eventos para evitar el error de Foreign Key
    $conn->query("DELETE FROM evento_responsable WHERE id_responsable='$id'");

    // 2. Ahora sí eliminamos al responsable
    if ($conn->query("DELETE FROM responsable WHERE id_responsable='$id'")) {
        $mensaje = "Empleado eliminado correctamente";
        $tipo = "success";
    } else {
        $mensaje = "Error al eliminar: " . $conn->error;
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background: #eef4ff;
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

        .perfil-img-preview {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #3b82f6;
        }
    </style>
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include("../menu/php/menuLateral.php"); ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include("../menu/php/barraSuperior.php"); ?>
                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4 flex-wrap">
                        <h1 class="h3 text-gray-800 mb-2">Panel de Personal</h1>
                        <div class="d-flex align-items-center flex-wrap">
                            <div class="input-group mr-2 mb-2" style="width: 400px;">
                                <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                                <input type="text" id="buscador" class="form-control" placeholder="Buscar por nombre o rol...">
                            </div>
                            <button class="btn btn-success mb-2" data-toggle="modal" data-target="#modalCrear">
                                <i class="fas fa-plus"></i> Nuevo Empleado
                            </button>
                        </div>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="row">
                                <?php
                                $res_list = $conn->query("SELECT * FROM responsable");
                                while ($row = $res_list->fetch_assoc()) {
                                    $img_lista = (!empty($row['imagen']) && file_exists("../img/responsables/" . $row['imagen'])) ? "../img/responsables/" . $row['imagen'] : "../img/responsables/sinFoto.jpg";
                                ?>
                                    <div class="col-12 mb-3 empleado-item" data-nombre="<?= strtolower($row['nombre']) ?>" data-rol="<?= strtolower($row['rol']) ?>">
                                        <div class="card shadow-sm p-3 d-flex flex-row justify-content-between align-items-center" style="border-radius:15px;">
                                            <div class="d-flex align-items-center">
                                                <img src="<?= $img_lista ?>" style="width:60px; height:60px; object-fit:cover; border-radius:50%; margin-right:15px;">
                                                <div>
                                                    <h6 class="mb-1 fw-bold"><?= $row["nombre"] ?></h6>
                                                    <small class="text-muted">
                                                        <i class="fas fa-envelope mr-1"></i> <?= $row["correo"] ?><br>
                                                        <i class="fas fa-user-tag mr-1"></i> <?= ucfirst($row["rol"]) ?>
                                                    </small>
                                                </div>
                                            </div>
                                            <div>
                                                <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalEditar" onclick="editarRegistro('<?= $row['id_responsable'] ?>','<?= $row['nombre'] ?>','<?= $row['correo'] ?>','<?= htmlspecialchars($row['contraseña'], ENT_QUOTES) ?>','<?= $row['rol'] ?>','<?= $img_lista ?>')">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-danger btn-sm" onclick="borraRegistro('<?= $row['id_responsable'] ?>','<?= $row['nombre'] ?>')">
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
            <?php include("../menu/php/piePagina.php"); ?>
        </div>
    </div>

    <div class="modal fade" id="modalCrear">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" enctype="multipart/form-data" class="modal-content shadow-lg border-0 rounded-4">
                <div class="modal-header bg-success text-white rounded-top-4">
                    <h5 class="mb-0"><i class="fas fa-user-plus mr-2"></i> Nuevo Empleado</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    
                    <label class="fw-bold">Nombre Completo</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <label class="fw-bold">Correo Electrónico</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" name="correo" class="form-control" required>
                    </div>
                    <label class="fw-bold">Contraseña</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" name="contrasena" id="passCrear" class="form-control" required>
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('passCrear', this)"><i class="fa fa-eye"></i></button>
                    </div>
                    <label class="fw-bold">Rol</label>
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
                        <i class="fas fa-save mr-2"></i> Guardar Empleado
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modalEditar">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" enctype="multipart/form-data" class="modal-content shadow-lg border-0 rounded-4">
                <div class="modal-header bg-info text-white rounded-top-4">
                    <h5 class="mb-0"><i class="fas fa-user-edit mr-2"></i> Editar Empleado</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="editId">
                    <div class="text-center mb-3">
                        <img id="previewEditar" src="" class="perfil-img-preview shadow-sm">
                    </div>
                    <label class="fw-bold">Cambiar Foto</label>
                    <div class="custom-file mb-3">
                        <input type="file" name="imagen" class="custom-file-input" id="imgEditar" accept="image/*" onchange="previewImagen(event, 'previewEditar')">
                        <label class="custom-file-label" for="imgEditar">Seleccionar nueva...</label>
                    </div>
                    <label class="fw-bold">Nombre Completo</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" name="nombre" id="editNombre" class="form-control" required>
                    </div>
                    <label class="fw-bold">Correo Electrónico</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" name="correo" id="editCorreo" class="form-control" required>
                    </div>
                    <label class="fw-bold">Contraseña</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" name="contrasena" id="editContrasena" class="form-control" placeholder="Dejar vacío para no cambiar">
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('editContrasena', this)"><i class="fa fa-eye"></i></button>
                    </div>
                    <label class="fw-bold">Rol</label>
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
                    <button type="submit" name="editar" class="btn btn-success px-4 rounded-pill">
                        <i class="fas fa-save mr-2"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../js/sb-admin-2.min.js"></script>

    <script>
        function previewImagen(event, idPreview) {
            const input = event.target;
            const fileName = input.files[0].name;
            $(input).next('.custom-file-label').html(fileName);

            const reader = new FileReader();
            reader.onload = e => document.getElementById(idPreview).src = e.target.result;
            reader.readAsDataURL(input.files[0]);
        }

        function togglePassword(id, btn) {
            let input = document.getElementById(id);
            let icon = btn.querySelector("i");
            input.type = input.type === "password" ? "text" : "password";
            icon.classList.toggle("fa-eye");
            icon.classList.toggle("fa-eye-slash");
        }

        function editarRegistro(id, nombre, correo, contrasena, rol, imagen) {
            document.getElementById('editId').value = id;
            document.getElementById('editNombre').value = nombre;
            document.getElementById('editCorreo').value = correo;
            document.getElementById('editContrasena').value = contrasena;
            document.getElementById('editRol').value = rol;
            document.getElementById('previewEditar').src = imagen;
            $('#modalEditar .custom-file-label').html('Seleccionar nueva...');
        }

        $(document).ready(function() {
            <?php if (!empty($mensaje)) : ?>
                Swal.fire({
                    icon: '<?= $tipo ?>',
                    title: '<?= $mensaje ?>',
                    showConfirmButton: false,
                    timer: 2000
                });
            <?php endif; ?>

            $("#buscador").on("keyup", function() {
                let value = $(this).val().toLowerCase();
                $(".empleado-item").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });

        function borraRegistro(id, nombre) {
            Swal.fire({
                title: '¿Eliminar a ' + nombre + '?',
                text: "Esta acción también quitará al empleado de los eventos asignados.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = document.createElement("form");
                    form.method = "POST";
                    form.innerHTML = `<input type="hidden" name="id_responsable" value="${id}"><input type="hidden" name="eliminar" value="1">`;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
</body>

</html>