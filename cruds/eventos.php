<?php
session_start();
include("../config/conexion.php");

/* ACTIVAR ERRORES (opcional pero recomendado) */
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['usuario'])) {
    header("Location: ../RegistroAdmin/login.php");
    exit();
}

$rol = $_SESSION['rol'];

/* VALIDAR CONEXIÓN */
if (!isset($conn)) {
    die("Error: conexión a BD no definida.");
}

/* ELIMINAR */
if (isset($_POST['eliminar'])) {
    $id = intval($_POST['id_evento']);
    $conn->query("DELETE FROM evento WHERE id_evento='$id'");
}

/* CREAR */
if (isset($_POST['crear'])) {

    $nombre = $_POST['nombre_evento'];
    $fecha = $_POST['fecha'];
    $lugar = $_POST['lugar'];
    $observaciones = $_POST['observaciones'];

    $imagenNombre = "";

    // SUBIR IMAGEN
    if (!empty($_FILES['imagen']['name'])) {
        $imagenNombre = time() . "_" . $_FILES['imagen']['name'];
        $ruta = "../img/eventos/" . $imagenNombre;

        move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta);
    }

    $sql = "INSERT INTO evento (nombre_evento, fecha, lugar, observaciones, imagen)
            VALUES ('$nombre','$fecha','$lugar','$observaciones','$imagenNombre')";

    $conn->query($sql);
}

/* EDITAR */
if (isset($_POST['editar'])) {

    $id = intval($_POST['id_evento']);

    if ($id <= 0) {
        die("Error: ID inválido");
    }
    $nombre = $_POST['nombre_evento'];
    $fecha = $_POST['fecha'];
    $lugar = $_POST['lugar'];
    $observaciones = $_POST['observaciones'];

    $imagenNombre = "";

    // OBTENER IMAGEN ACTUAL
    $sqlImg = $conn->query("SELECT imagen FROM evento WHERE id_evento='$id'");

    if ($sqlImg && $sqlImg->num_rows > 0) {
        $fila = $sqlImg->fetch_assoc();
        $imagenNombre = $fila['imagen'];
    }

    // SI SUBE NUEVA IMAGEN
    if (!empty($_FILES['imagen']['name'])) {

        $imagenNombre = time() . "_" . $_FILES['imagen']['name'];
        $ruta = "../img/eventos/" . $imagenNombre;

        move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta);
    }

    $sql = "UPDATE evento SET 
                nombre_evento='$nombre',
                fecha='$fecha',
                lugar='$lugar',
                observaciones='$observaciones',
                imagen='$imagenNombre'
            WHERE id_evento='$id'";

    $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <?php include("../menu/php/encabezado.php"); ?>
    <link rel="icon" href="../img/logo.png">
    <link href="../css/styles.css" rel="stylesheet">

    <style>
        .img-evento {
            width: 220px;
            height: 270px;
            object-fit: cover;
            border-radius: 15px;
            margin-right: 15px;
        }
    </style>
</head>

<body id="page-top">

    <div id="wrapper">

        <!-- MENU -->
        <?php include("../menu/php/menuLateral.php"); ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">

                <!-- TOPBAR -->
                <?php include("../menu/php/barraSuperior.php"); ?>

                <div class="container-fluid">

                    <!-- TITULO -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 text-gray-800">Eventos</h1>

                        <button class="btn btn-success" data-toggle="modal" data-target="#modalCrear">
                            <i class="fas fa-plus"></i> Nuevo Evento
                        </button>
                    </div>

                    <!-- CARD PRINCIPAL -->
                    <div class="card shadow mb-4">
                        <div class="card-body">

                            <div class="row">

                                <?php
                                $sql = "SELECT * FROM evento ORDER BY fecha DESC";
                                $res = $conn->query($sql);

                                if ($res && $res->num_rows > 0) {

                                    while ($row = $res->fetch_assoc()) {

                                        // IMAGEN (fallback si no hay)
                                        $imagen = (!empty($row['imagen']) && file_exists("../img/eventos/" . $row['imagen']))
                                            ? "../img/eventos/" . $row['imagen']
                                            : "../img/default.png";
                                ?>

                                        <div class="col-12 mb-3">
                                            <div class="card shadow-sm p-3 d-flex flex-row justify-content-between align-items-center" style="border-radius:15px;">

                                                <!-- IZQUIERDA -->
                                                <div class="d-flex align-items-center">

                                                    <!-- IMAGEN -->
                                                    <img src="<?= $imagen ?>" class="img-evento">

                                                    <div>
                                                        <h6 class="mb-1"><?= htmlspecialchars($row["nombre_evento"]) ?></h6>

                                                        <small class="text-muted">
                                                            Fecha: <?= $row["fecha"] ?><br>
                                                            Lugar: <?= htmlspecialchars($row["lugar"]) ?><br>
                                                            Observaciones: <?= htmlspecialchars($row["observaciones"]) ?>
                                                        </small>
                                                    </div>

                                                </div>

                                                <!-- BOTONES -->
                                                <div>

                                                    <!-- EDITAR -->
                                                    <button class="btn btn-info btn-sm"
                                                        data-toggle="modal"
                                                        data-target="#modalEditar"
                                                        onclick="editarEvento(
                                                        '<?= $row['id_evento'] ?>',
                                                        '<?= htmlspecialchars($row['nombre_evento'], ENT_QUOTES) ?>',
                                                        '<?= $row['fecha'] ?>',
                                                        '<?= htmlspecialchars($row['lugar'], ENT_QUOTES) ?>',
                                                        '<?= htmlspecialchars($row['observaciones'], ENT_QUOTES) ?>',
                                                        '<?= $imagen ?>'
                                                    )">
                                                        <i class="fas fa-edit"></i>
                                                    </button>

                                                    <!-- ELIMINAR -->
                                                    <button class="btn btn-danger btn-sm"
                                                        data-toggle="modal"
                                                        data-target="#modalEliminar"
                                                        onclick="eliminarEvento('<?= $row['id_evento'] ?>','<?= htmlspecialchars($row['nombre_evento'], ENT_QUOTES) ?>')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>

                                                </div>

                                            </div>
                                        </div>

                                <?php
                                    }
                                } else {
                                    echo "<p class='text-center'>No hay eventos registrados</p>";
                                }
                                ?>

                            </div>

                        </div>
                    </div>

                </div>
            </div>

            <?php include("../menu/php/piePagina.php"); ?>

        </div>
    </div>
    <!-- ================= MODAL CREAR ================= -->
    <div class="modal fade" id="modalCrear">
        <div class="modal-dialog">
            <form method="POST" enctype="multipart/form-data" class="modal-content">

                <div class="modal-header bg-success text-white">
                    <h5>Nuevo Evento</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">

                    <input type="text" name="nombre_evento" class="form-control mb-2" placeholder="Nombre del evento" required>

                    <input type="date" name="fecha" class="form-control mb-2" required>

                    <input type="text" name="lugar" class="form-control mb-2" placeholder="Lugar" required>

                    <textarea name="observaciones" class="form-control mb-2" placeholder="Observaciones"></textarea>

                    <!-- IMAGEN -->
                    <input type="file" name="imagen" class="form-control">

                </div>

                <div class="modal-footer">
                    <button name="crear" class="btn btn-success">Guardar</button>
                </div>

            </form>
        </div>
    </div>

    <!-- ================= MODAL EDITAR ================= -->
    <div class="modal fade" id="modalEditar">
        <div class="modal-dialog">
            <form method="POST" enctype="multipart/form-data" class="modal-content">

                <div class="modal-header bg-info text-white">
                    <h5>Editar Evento</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">

                    <!-- ID OCULTO -->
                    <input type="hidden" name="id_evento" id="editIdEvento">

                    <input type="text" name="nombre_evento" id="editNombre" class="form-control mb-2" placeholder="Nombre" required>

                    <input type="date" name="fecha" id="editFecha" class="form-control mb-2" required>

                    <input type="text" name="lugar" id="editLugar" class="form-control mb-2" placeholder="Lugar" required>

                    <textarea name="observaciones" id="editObservaciones" class="form-control mb-2" placeholder="Observaciones"></textarea>

                    <!-- IMAGEN ACTUAL -->
                    <div class="mb-2 text-center">
                        <img id="previewImagen" src="" style="width:80px; height:80px; object-fit:cover; border-radius:10px;">
                    </div>

                    <!-- NUEVA IMAGEN -->
                    <input type="file" name="imagen" class="form-control">

                </div>

                <div class="modal-footer">
                    <button name="editar" class="btn btn-info">Guardar cambios</button>
                </div>

            </form>
        </div>
    </div>

    <!-- ================= MODAL ELIMINAR ================= -->

    <div class="modal fade" id="modalEliminar">
        <div class="modal-dialog">
            <form method="POST" class="modal-content">

                <div class="modal-header bg-danger text-white">
                    <h5>¿Eliminar evento?</h5>
                </div>

                <div class="modal-body text-center">
                    <p>¿Seguro que deseas eliminar:</p>
                    <strong id="nombreEvento"></strong>

                    <input type="hidden" name="id_evento" id="deleteIdEvento">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button name="eliminar" class="btn btn-danger">Eliminar</button>
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
        function eliminarEvento(id, nombre) {
            document.getElementById('deleteIdEvento').value = id;
            document.getElementById('nombreEvento').innerText = nombre;
        }

        function editarEvento(id, nombre, fecha, lugar, observaciones, imagen) {

            document.getElementById('editIdEvento').value = id;
            document.getElementById('editNombre').value = nombre;
            document.getElementById('editFecha').value = fecha;
            document.getElementById('editLugar').value = lugar;
            document.getElementById('editObservaciones').value = observaciones;

            document.getElementById('previewImagen').src = imagen;
        }
    </script>

</body>

</html>