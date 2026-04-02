<?php
session_start();
include("../config/conexion.php");

/* ERRORES */
error_reporting(E_ALL);
ini_set('display_errors', 1);

/* VALIDAR SESIÓN */
if (!isset($_SESSION['usuario'])) {
    header("Location: ../RegistroAdmin/login.php");
    exit();
}
$rol = $_SESSION['rol'];

if (!isset($conn)) {
    die("Error: conexión a BD no definida.");
}

$hoy = date("Y-m-d");

$mensaje = "";
$tipo = "";

/* ACCIONES */

/* CREAR EVENTO */
if (isset($_POST['crear'])) {

    // Limpiamos los datos para evitar errores de comillas
    $nombre = $conn->real_escape_string($_POST['nombre_evento']);
    $fecha = $conn->real_escape_string($_POST['fecha']);
    $hora = !empty($_POST['hora']) ? $conn->real_escape_string($_POST['hora']) : "00:00:00";
    $lugar = $conn->real_escape_string($_POST['lugar']);
    $ubicacion = $conn->real_escape_string($_POST['ubicacion']);
    $observaciones = $conn->real_escape_string($_POST['observaciones']);

    $imagenNombre = "";

    // Manejo de la imagen
    if (!empty($_FILES['imagen']['name'])) {
        $directorio = "../img/eventos/";
        // Si el directorio no existe, lo creamos
        if (!is_dir($directorio)) {
            mkdir($directorio, 0777, true);
        }

        $imagenNombre = time() . "_" . basename($_FILES['imagen']['name']);
        move_uploaded_file($_FILES['imagen']['tmp_name'], $directorio . $imagenNombre);
    }

    // La consulta ahora solo lleva los campos que realmente necesitas
    $sql = "INSERT INTO evento (nombre_evento, fecha, hora, lugar, ubicacion, observaciones, imagen)
            VALUES ('$nombre', '$fecha', '$hora', '$lugar', '$ubicacion', '$observaciones', '$imagenNombre')";

    if ($conn->query($sql)) {
        $mensaje = "¡Evento creado con éxito!";
        $tipo = "success";
    } else {
        // Si sale error aquí, es por un nombre de columna mal escrito
        $mensaje = "Error al guardar: " . $conn->error;
        $tipo = "error";
    }
}

/* EDITAR */
if (isset($_POST['editar'])) {

    $id = intval($_POST['id_evento']);

    $verificar = $conn->query("SELECT fecha, imagen FROM evento WHERE id_evento='$id'");
    $dato = $verificar->fetch_assoc();

    if ($dato['fecha'] < $hoy) {
        $mensaje = "No se pueden editar eventos pasados";
        $tipo = "error";
    } else {

        $nombre = $_POST['nombre_evento'];
        $fecha = $_POST['fecha'];
        $hora = $_POST['hora'];
        $lugar = $_POST['lugar'];
        $ubicacion = $_POST['ubicacion'];
        $observaciones = $_POST['observaciones'];

        $imagenNombre = $dato['imagen'];

        if (!empty($_FILES['imagen']['name'])) {
            $imagenNombre = time() . "_" . $_FILES['imagen']['name'];
            move_uploaded_file($_FILES['imagen']['tmp_name'], "../img/eventos/" . $imagenNombre);
        }

        $sql = "UPDATE evento SET 
                nombre_evento='$nombre',
                fecha='$fecha',
                hora='$hora',
                lugar='$lugar',
                ubicacion='$ubicacion',
                observaciones='$observaciones',
                imagen='$imagenNombre'
                WHERE id_evento='$id'";

        if ($conn->query($sql)) {
            $mensaje = "Evento actualizado correctamente";
            $tipo = "success";
        } else {
            $mensaje = "Error al editar el evento";
            $tipo = "error";
        }
    }
}

/* ELIMINAR */
if (isset($_POST['eliminar'])) {

    $id = intval($_POST['id_evento']);

    $verificar = $conn->query("SELECT fecha FROM evento WHERE id_evento='$id'");
    $dato = $verificar->fetch_assoc();

    if ($dato['fecha'] >= $hoy) {

        if ($conn->query("DELETE FROM evento WHERE id_evento='$id'")) {
            $mensaje = "Evento eliminado correctamente";
            $tipo = "success";
        } else {
            $mensaje = "Error al eliminar";
            $tipo = "error";
        }
    } else {
        $mensaje = "No puedes eliminar eventos pasados";
        $tipo = "error";
    }
}

/*CONSULTAS  */
$sqlActivos = "SELECT * FROM evento WHERE fecha >= '$hoy' ORDER BY fecha ASC";
$sqlPasados = "SELECT * FROM evento WHERE fecha < '$hoy' ORDER BY fecha DESC";
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <?php include("../menu/php/encabezado.php"); ?>
    <link rel="icon" href="../img/logo.png">
    <link href="../css/styles.css" rel="stylesheet">

    <style>
        .img-evento {
            width: 120px;
            height: 170px;
            object-fit: cover;
            border-radius: 15px;
            margin-right: 15px;
        }

        .bg-primary {
            background-color: #3b82f6 !important;
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

                        <!-- TITULO -->
                        <h1 class="h3 text-gray-800 mb-2">Eventos</h1>

                        <!-- DERECHA -->
                        <div class="d-flex align-items-center flex-wrap">

                            <!-- BUSCADOR -->
                            <div class="input-group mr-2 mb-2" style="width: 400px;">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white">
                                        <i class="fas fa-search"></i>
                                    </span>
                                </div>
                                <input type="text" id="buscador" class="form-control"
                                    placeholder="Buscar evento...">
                            </div>

                            <!-- BOTON -->
                            <button class="btn btn-success mb-2"
                                data-toggle="modal"
                                data-target="#modalCrear">

                                <i class="fas fa-plus"></i> Nuevo Evento
                            </button>

                        </div>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-body">

                            <!--ACTIVOS-->
                            <h4 class="text">Eventos Activos</h4>
                            <div class="row">

                                <?php
                                $res = $conn->query($sqlActivos);

                                if ($res && $res->num_rows > 0) {
                                    while ($row = $res->fetch_assoc()) {

                                        $imagen = (!empty($row['imagen']) && file_exists("../img/eventos/" . $row['imagen']))
                                            ? "../img/eventos/" . $row['imagen']
                                            : "../img/default.png";
                                ?>

                                        <div class="col-12 mb-3 evento-item"
                                            data-nombre="<?= strtolower($row['nombre_evento']) ?>"
                                            data-fecha="<?= strtolower($row['fecha']) ?>"
                                            data-lugar="<?= strtolower($row['lugar']) ?>">

                                            <div class="card shadow-sm p-3 d-flex flex-row justify-content-between align-items-center" style="border-radius:15px;">

                                                <div class="d-flex align-items-center">
                                                    <img src="<?= $imagen ?>" class="img-evento">

                                                    <div>
                                                        <h6 class="mb-1"><?= htmlspecialchars($row["nombre_evento"] ?? '') ?></h6>
                                                        <small class="text-muted">

                                                            <strong class="text-dark">Fecha:</strong>
                                                            <?= date("d/m/Y", strtotime($row["fecha"])) ?><br>
                                                            <strong class="text-dark">Hora:</strong>
                                                            <?= date("h:i A", strtotime($row["hora"])) ?><br>
                                                            <strong class="text-dark">Lugar:</strong>
                                                            <?= htmlspecialchars($row["lugar"] ?? '') ?><br>
                                                            <strong class="text-dark">Ubicación:</strong>
                                                            <?= htmlspecialchars($row["ubicacion"] ?? '') ?>
                                                            <a href="https://www.google.com/maps/search/?api=1&query=<?= urlencode($row['ubicacion'] ?? '') ?>"
                                                                target="_blank"
                                                                class="btn btn-sm btn-outline-primary ms-2"
                                                                style="border-radius:20px;">
                                                                <i class="fas fa-map-marker-alt"></i>
                                                            </a><br>
                                                            <strong class="text-dark">Observaciones:</strong>
                                                            <?= htmlspecialchars($row["observaciones"] ?? '') ?>

                                                        </small>
                                                    </div>
                                                </div>

                                                <div>
                                                    <button class="btn btn-info btn-sm"
                                                        data-toggle="modal"
                                                        data-target="#modalEditar"
                                                        onclick="editarEvento(
                                                        '<?= $row['id_evento'] ?>',
                                                        '<?= htmlspecialchars($row['nombre_evento'] ?? '', ENT_QUOTES) ?>',
                                                        '<?= $row['fecha'] ?>',
                                                        '<?= $row['hora'] ?>',
                                                        '<?= htmlspecialchars($row['lugar'] ?? '', ENT_QUOTES) ?>',
                                                        '<?= htmlspecialchars($row['ubicacion'] ?? '', ENT_QUOTES) ?>',
                                                        '<?= htmlspecialchars($row['observaciones'] ?? '', ENT_QUOTES) ?>',
                                                        '<?= $imagen ?>'
                                                    )">
                                                        <i class="fas fa-edit"></i>
                                                    </button>

                                                    <button class="btn btn-danger btn-sm"
                                                        data-toggle="modal"
                                                        onclick="eliminarEvento('<?= $row['id_evento'] ?>','<?= htmlspecialchars($row['nombre_evento'], ENT_QUOTES) ?>')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>

                                            </div>
                                        </div>

                                <?php }
                                } else {
                                    echo "<p>No hay eventos activos</p>";
                                } ?>

                            </div>

                            <!-- PASADOS-->
                            <hr>
                            <h4 class="text">Eventos Pasados</h4>

                            <div class="row">

                                <?php
                                $res = $conn->query($sqlPasados);

                                if ($res && $res->num_rows > 0) {
                                    while ($row = $res->fetch_assoc()) {

                                        $imagen = (!empty($row['imagen']) && file_exists("../img/eventos/" . $row['imagen']))
                                            ? "../img/eventos/" . $row['imagen']
                                            : "../img/default.png";
                                ?>

                                        <div class="col-12 mb-3 evento-item"
                                            data-nombre="<?= strtolower($row['nombre_evento']) ?>"
                                            data-fecha="<?= strtolower($row['fecha']) ?>"
                                            data-lugar="<?= strtolower($row['lugar']) ?>">

                                            <!-- <div class="card shadow-sm p-3 d-flex flex-row align-items-center" style="border-radius:15px; opacity:0.6;">-->
                                            <div class="d-flex align-items-center p-2 mb-2" style="border-radius:15px; background:#f8f9fa; opacity:0.75;">
                                                <img src="<?= $imagen ?>" class="img-evento">

                                                <div class="flex-grow-1">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <h6 class="mb-1 text-red" style="color:#dc3545;">
                                                            <?= htmlspecialchars($row["nombre_evento"] ?? '') ?>
                                                        </h6>

                                                        <span class="badge bg-danger text-white">Vencido</span>
                                                    </div>
                                                    <small class="text-muted">
                                                        <strong class="text-dark">Fecha:</strong>
                                                        <?= date("d/m/Y", strtotime($row["fecha"])) ?><br>
                                                        <strong class="text-dark">Hora:</strong>
                                                        <?= date("h:i A", strtotime($row["hora"])) ?><br>
                                                        <strong class="text-dark">Lugar:</strong>
                                                        <?= htmlspecialchars($row["lugar"] ?? '') ?><br>
                                                        <strong class="text-dark">Ubicación:</strong>
                                                        <?= htmlspecialchars($row["ubicacion"] ?? '') ?><br>
                                                        <strong class="text-dark">Observaciones:</strong>
                                                        <?= htmlspecialchars($row["observaciones"] ?? '') ?>
                                                    </small>

                                                </div>
                                            </div>
                                        </div>

                                <?php }
                                } else {
                                    echo "<p>No hay eventos pasados</p>";
                                } ?>

                            </div>

                        </div>
                    </div>

                </div>
            </div>

            <?php include("../menu/php/piePagina.php"); ?>

        </div>
    </div>
    <!-- Modal Crear y Editar -->
    <div class="modal fade" id="modalCrear">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form method="POST" enctype="multipart/form-data" class="modal-content shadow-lg border-0 rounded-4">

                <div class="modal-header bg-primary text-white rounded-top-4">
                    <h5 class="mb-0"><i class="fas fa-calendar-plus"></i> Nuevo Evento</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">

                    <label class="fw-bold">Nombre del Evento</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fas fa-heading"></i></span>
                        <input type="text" name="nombre_evento" class="form-control" placeholder="Ej: Feria de las ciencias" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="fw-bold">Fecha</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                <input type="date" name="fecha" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Hora</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                <input type="time" name="hora" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <label class="fw-bold">Lugar</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fas fa-map"></i></span>
                        <input type="text" name="lugar" class="form-control" placeholder="Ej: Cancha municipal" required>
                    </div>
                    <label class="fw-bold">Ubicación (Google Maps)</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                        <input type="text" name="ubicacion" class="form-control" placeholder="Pega link o dirección" required>
                    </div>
                    <label class="fw-bold">Observaciones</label>
                    <textarea name="observaciones" class="form-control mb-3" rows="3" placeholder="Detalles del evento"></textarea>
                    <label class="fw-bold">Imagen</label>
                    <input type="file" name="imagen" class="form-control">

                </div>

                <div class="modal-footer">
                    <button type="submit" name="crear" class="btn btn-success px-4 rounded-pill">
                        <i class="fas fa-save"></i> Guardar
                    </button>
                </div>

            </form>
        </div>
    </div>


    <div class="modal fade" id="modalEditar">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form method="POST" enctype="multipart/form-data" class="modal-content shadow-lg border-0 rounded-4">

                <div class="modal-header bg-primary text-white rounded-top-4">
                    <h5 class="mb-0"><i class="fas fa-edit"></i> Editar Evento</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">

                    <input type="hidden" name="id_evento" id="editIdEvento">

                    <label class="fw-bold">Nombre del Evento</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fas fa-heading"></i></span>
                        <input type="text" name="nombre_evento" id="editNombre" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="fw-bold">Fecha</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                <input type="date" name="fecha" id="editFecha" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Hora</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                <input type="time" name="hora" id="editHora" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <label class="fw-bold">Lugar</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fas fa-map"></i></span>
                        <input type="text" name="lugar" id="editLugar" class="form-control" required>
                    </div>
                    <label class="fw-bold">Ubicación</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                        <input type="text" name="ubicacion" id="editUbicacion" class="form-control" required>
                    </div>
                    <label class="fw-bold">Observaciones</label>
                    <textarea name="observaciones" id="editObservaciones" class="form-control mb-3"></textarea>

                    <label class="fw-bold">Imagen actual</label>
                    <div class="text-center mb-3">
                        <img id="previewImagen"
                            style="width:100px; height:100px; object-fit:cover; border-radius:12px; border:2px solid #ddd;">
                    </div>

                    <label class="fw-bold">Cambiar imagen</label>
                    <input type="file" name="imagen" class="form-control">
                </div>

                <div class="modal-footer">
                    <button type="submit" name="editar" class="btn btn-info px-4 rounded-pill">
                        <i class="fas fa-save"></i> Guardar cambios
                    </button>
                </div>

            </form>
        </div>
    </div>
    <!-- Final Modal Crear y Editar -->
    <!-- JS -->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../js/sb-admin-2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        <?php if (!empty($mensaje)) : ?>
            Swal.fire({
                icon: '<?= $tipo ?>',
                title: '<?= $mensaje ?>',
                showConfirmButton: false,
                timer: 2000
            });
        <?php endif; ?>
    </script>

    <script>
        function editarEvento(id, nombre, fecha, hora, lugar, ubicacion, observaciones, imagen) {
            document.getElementById('editIdEvento').value = id;
            document.getElementById('editNombre').value = nombre;
            document.getElementById('editFecha').value = fecha;
            document.getElementById('editHora').value = hora;
            document.getElementById('editLugar').value = lugar;
            document.getElementById('editUbicacion').value = ubicacion;
            document.getElementById('editObservaciones').value = observaciones;
            document.getElementById('previewImagen').src = imagen;
        }

        function eliminarEvento(id, nombre) {

            Swal.fire({
                title: '¿Eliminar evento?',
                text: "No podrás recuperar este evento",
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

                    let input = document.createElement("input");
                    input.type = "hidden";
                    input.name = "id_evento";
                    input.value = id;

                    let accion = document.createElement("input");
                    accion.type = "hidden";
                    accion.name = "eliminar";
                    accion.value = "1";

                    form.appendChild(input);
                    form.appendChild(accion);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        let buscador = document.getElementById("buscador");

        buscador.addEventListener("keyup", function() {

            let texto = buscador.value.toLowerCase();
            let eventos = document.querySelectorAll(".evento-item");

            eventos.forEach(evento => {

                let nombre = evento.getAttribute("data-nombre");
                let fecha = evento.getAttribute("data-fecha");
                let lugar = evento.getAttribute("data-lugar");

                if (
                    nombre.includes(texto) ||
                    fecha.includes(texto) ||
                    lugar.includes(texto)
                ) {
                    evento.style.display = "";
                } else {
                    evento.style.display = "none";
                }

            });
        });
    </script>

</body>

</html>