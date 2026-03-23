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

/* CREAR */
if (isset($_POST['crear'])) {

    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $contraseña = $_POST['contrasena'];
    $rol = $_POST['rol'];

    // IMAGEN POR DEFECTO
    $imagenNombre = "sinFoto.jpg";

    $sql = "INSERT INTO responsable(nombre, correo, contraseña, rol, imagen)
            VALUES('$nombre','$correo','$contraseña','$rol','$imagenNombre')";
    $conn->query($sql);
}

/* EDITAR */
if (isset($_POST['editar'])) {

    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $contraseña = $_POST['contrasena'];
    $rol = $_POST['rol'];

    // OBTENER IMAGEN ACTUAL
    $sqlImg = $conn->query("SELECT imagen FROM responsable WHERE id_responsable='$id'");
    $imagenNombre = "";

    if ($sqlImg && $sqlImg->num_rows > 0) {
        $fila = $sqlImg->fetch_assoc();
        $imagenNombre = $fila['imagen'];
    }

    // NUEVA IMAGEN
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
    $conn->query($sql);
}

/* ELIMINAR */
if (isset($_POST['eliminar'])) {
    $id = $_POST['id_responsable'];

    $sql = "DELETE FROM responsable WHERE id_responsable='$id'";
    $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <?php include("../menu/php/encabezado.php"); ?>
    <link rel="icon" href="../img/control.png">
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

                    <!-- TÍTULO -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 text-gray-800">Panel de personal</h1>
                    </div>

                    <!-- CARD PRINCIPAL -->
                    <div class="card shadow mb-4">

                        <div class="card-header">
                            <button class="btn btn-success" data-toggle="modal" data-target="#modalCrear">
                                <i class="fas fa-plus"></i> Nuevo empleado
                            </button>
                        </div>

                        <div class="card-body">

                            <div class="row">
                                <?php
                                $sql = "SELECT * FROM responsable";
                                $res = $conn->query($sql);

                                while ($row = $res->fetch_assoc()) {
                                ?>

                                    <div class="col-12 mb-3">
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

                                                <!-- EDITAR 
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
                                                </button> -->

                                                <!-- ELIMINAR -->
                                                <button class="btn btn-danger btn-sm"
                                                    data-toggle="modal"
                                                    data-target="#modalEliminar"
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

    <!-- ===================== MODALES ===================== -->

    <!-- CREAR -->
    <div class="modal fade" id="modalCrear">
        <div class="modal-dialog">
            <form method="POST" enctype="multipart/form-data" class="modal-content">

                <div class="modal-header">
                    <h5>Nuevo responsable</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <input type="text" name="nombre" class="form-control mb-2" placeholder="Nombre" required>
                    <input type="email" name="correo" class="form-control mb-2" placeholder="Correo" required>
                    <div class="position-relative mb-2">
                        <input type="password" name="contrasena" id="crearContrasena"
                            class="form-control pr-5" placeholder="Nueva contraseña">

                        <span onclick="togglePassword('crearContrasena', this)"
                            style="position:absolute; right:15px; top:50%; transform:translateY(-50%); cursor:pointer;">
                            <i class="fa fa-eye"></i>
                        </span>
                    </div>

                    <select name="rol" class="form-control">
                        <option value="">Seleccionar rol</option>
                        <option value="administrador">Administrador</option>
                        <option value="programador">Programador</option>
                        <option value="promotor">Promotor</option>
                    </select>
                </div>

                <div class="modal-footer">
                    <button name="crear" class="btn btn-success">Crear</button>
                </div>

            </form>
        </div>
    </div>

    <!-- EDITAR -->
    <div class="modal fade" id="modalEditar">
        <div class="modal-dialog">
            <form method="POST" enctype="multipart/form-data" class="modal-content">

                <div class="modal-header bg-info text-white">
                    <h5>Editar</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">

                    <input type="hidden" name="id" id="editId">
                    <div class="text-center mb-2">
                        <img id="previewImagen"
                            src=""
                            style="width:70px; height:70px; object-fit:cover; border-radius:50%;">
                    </div>

                    <input type="file" name="imagen" class="form-control">

                    <input type="text" name="nombre" id="editNombre" class="form-control mb-2">
                    <input type="email" name="correo" id="editCorreo" class="form-control mb-2">
                    <div class="position-relative mb-2">
                        <input type="password" name="contrasena" id="editContrasena"
                            class="form-control pr-5" placeholder="Nueva contraseña">

                        <span onclick="togglePassword('editContrasena', this)"
                            style="position:absolute; right:15px; top:50%; transform:translateY(-50%); cursor:pointer;">
                            <i class="fa fa-eye"></i>
                        </span>
                    </div>

                    <select name="rol" id="editRol" class="form-control">
                        <option value="administrador">Administrador</option>
                        <option value="programador">Programador</option>
                        <option value="promotor">Promotor</option>
                    </select>

                </div>

                <div class="modal-footer">
                    <button name="editar" class="btn btn-info">Guardar cambios</button>
                </div>

            </form>
        </div>
    </div>

    <!-- ELIMINAR -->
    <div class="modal fade" id="modalEliminar">
        <div class="modal-dialog">
            <form method="POST" class="modal-content">

                <div class="modal-header bg-danger text-white">
                    <h5>¿Eliminar registro?</h5>
                </div>

                <div class="modal-body text-center">
                    <p>¿Seguro que deseas eliminar a:</p>
                    <strong id="nombreEmpleado"></strong>

                    <input type="hidden" name="id_responsable" id="deleteIdusuario">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button name="eliminar" class="btn btn-danger">Sí, eliminar</button>
                </div>

            </form>
        </div>
    </div>

    <!-- JS -->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../js/sb-admin-2.min.js"></script>

    <script>
        function editarRegistro(id, nombre, correo, contrasena, rol, imagen) {

            document.getElementById('editId').value = id;
            document.getElementById('editNombre').value = nombre;
            document.getElementById('editCorreo').value = correo;
            document.getElementById('editContrasena').value = contrasena;
            document.getElementById('editRol').value = rol;

            document.getElementById('previewImagen').src = imagen;
        }

        function borraRegistro(id, nombre) {
            document.getElementById('deleteIdusuario').value = id;
            document.getElementById('nombreEmpleado').innerText = nombre;
        }

        function togglePassword(id, icono) {
            let input = document.getElementById(id);
            let icon = icono.querySelector("i");

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
    </script>

</body>

</html>