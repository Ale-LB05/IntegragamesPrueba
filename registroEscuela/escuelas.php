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

/* INSERTAR */
if (isset($_POST['guardar'])) {
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $contacto = $conn->real_escape_string($_POST['contacto']); // Nuevo campo
    $direccion = $conn->real_escape_string($_POST['direccion']);

    $temp_tel = preg_replace('/[^0-9]/', '', $_POST['telefono']);

    if (strlen($temp_tel) != 10) {
        $mensaje = "El teléfono debe tener exactamente 10 números";
        $tipo = "error";
    } else {
        $telefono = substr($temp_tel, 0, 3) . " " . substr($temp_tel, 3, 3) . " " . substr($temp_tel, 6);

        // Ajuste: agregamos 'contacto' a la consulta
        $stmt = $conn->prepare("INSERT INTO escuela(nombre_escuela, contacto, direccion, telefono) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nombre, $contacto, $direccion, $telefono);
        if ($stmt->execute()) {
            $mensaje = "¡Escuela agregada con éxito!";
            $tipo = "success";
        } else {
            $mensaje = "Error al guardar";
            $tipo = "error";
        }
    }
}

/* ACTUALIZAR */
if (isset($_POST['actualizar'])) {
    $id = intval($_POST['id']);
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $contacto = $conn->real_escape_string($_POST['contacto']); // Nuevo campo
    $direccion = $conn->real_escape_string($_POST['direccion']);

    $temp_tel = preg_replace('/[^0-9]/', '', $_POST['telefono']);

    if (strlen($temp_tel) != 10) {
        $mensaje = "El teléfono debe tener exactamente 10 números";
        $tipo = "error";
    } else {
        $telefono = substr($temp_tel, 0, 3) . " " . substr($temp_tel, 3, 3) . " " . substr($temp_tel, 6);

        // Ajuste: actualizamos también el campo 'contacto'
        $stmt = $conn->prepare("UPDATE escuela SET nombre_escuela=?, contacto=?, direccion=?, telefono=? WHERE id_escuela=?");
        $stmt->bind_param("ssssi", $nombre, $contacto, $direccion, $telefono, $id);
        if ($stmt->execute()) {
            $mensaje = "Escuela actualizada correctamente";
            $tipo = "success";
        } else {
            $mensaje = "Error al actualizar";
            $tipo = "error";
        }
    }
}

/* CONSULTA */
$res = $conn->query("SELECT * FROM escuela ORDER BY nombre_escuela ASC");
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <?php include("../menu/php/encabezado.php"); ?>
    <link rel="icon" href="../img/logo.png">
    <link href="../css/styles.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .card {
            transition: box-shadow 0.4s cubic-bezier(0.25, 0.8, 0.25, 1) !important;
            transform: none !important;
            border: none !important;
            background: #ffffff;
            border-radius: 20px !important;
            /* Más redondeado para verse moderno */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05) !important;
        }

        .card:hover {
            transform: none !important;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12) !important;
        }

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

        .table thead {
            background: #3b82f6;
            color: white;
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
                        <h1 class="h3 text-gray-800 mb-2">Gestión de Escuelas</h1>
                        <div class="d-flex align-items-center flex-wrap">
                            <div class="input-group mr-2 mb-2" style="width: 400px;">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                                </div>
                                <input type="text" id="buscador" class="form-control" placeholder="Buscar escuela...">
                            </div>
                            <button class="btn btn-success mb-2" data-toggle="modal" data-target="#modalAgregar">
                                <i class="fas fa-plus"></i> Nueva Escuela
                            </button>
                        </div>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-center" id="tablaEscuelas">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Contacto</th>
                                            <th>Dirección</th>
                                            <th>Teléfono</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($res->num_rows == 0) : ?>
                                            <tr>
                                                <td colspan="5">No hay registros</td>
                                            </tr>
                                        <?php endif; ?>
                                        <?php while ($row = $res->fetch_assoc()) : ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row['nombre_escuela']) ?></td>
                                                <td><?= htmlspecialchars($row['contacto']) ?></td>
                                                <td><?= htmlspecialchars($row['direccion']) ?></td>
                                                <td><?= $row['telefono'] ?></td>
                                                <td>
                                                    <button class="btn btn-info btn-sm"
                                                        data-toggle="modal"
                                                        data-target="#modalEditar"
                                                        onclick="editarEscuela(
                                                            '<?= $row['id_escuela'] ?>',
                                                            '<?= htmlspecialchars($row['nombre_escuela'], ENT_QUOTES) ?>',
                                                            '<?= htmlspecialchars($row['contacto'], ENT_QUOTES) ?>',
                                                            '<?= htmlspecialchars($row['direccion'], ENT_QUOTES) ?>',
                                                            '<?= $row['telefono'] ?>'
                                                        )">
                                                        <i class="fas fa-edit"></i> Editar
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php include("../menu/php/piePagina.php"); ?>
        </div>
    </div>

    <div class="modal fade" id="modalAgregar">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" class="modal-content shadow-lg border-0 rounded-4">
                <div class="modal-header bg-primary text-white rounded-top-4">
                    <h5 class="mb-0"><i class="fas fa-school"></i> Nueva Escuela</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body">
                    <label class="fw-bold">Nombre de la Escuela</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fas fa-university"></i></span>
                        <input type="text" name="nombre" class="form-control" placeholder="Ej: Escuela Benito Juárez" required>
                    </div>

                    <label class="fw-bold">Persona de Contacto</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" name="contacto" class="form-control" placeholder="Nombre del director o encargado" required>
                    </div>

                    <label class="fw-bold">Dirección</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                        <textarea name="direccion" class="form-control" rows="2" placeholder="Calle, Número, Colonia" required></textarea>
                    </div>

                    <label class="fw-bold">Teléfono (10 dígitos)</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                        <input type="text" name="telefono" class="form-control input-telefono" maxlength="12" placeholder="443 325 2165" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="guardar" class="btn btn-success px-4 rounded-pill">
                        <i class="fas fa-save"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modalEditar">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" class="modal-content shadow-lg border-0 rounded-4">
                <div class="modal-header bg-primary text-white rounded-top-4">
                    <h5 class="mb-0"><i class="fas fa-edit"></i> Editar Escuela</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="editId">

                    <label class="fw-bold">Nombre de la Escuela</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fas fa-university"></i></span>
                        <input type="text" name="nombre" id="editNombre" class="form-control" required>
                    </div>

                    <label class="fw-bold">Persona de Contacto</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" name="contacto" id="editContacto" class="form-control" required>
                    </div>

                    <label class="fw-bold">Dirección</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                        <textarea name="direccion" id="editDireccion" class="form-control" rows="2" required></textarea>
                    </div>

                    <label class="fw-bold">Teléfono</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                        <input type="text" name="telefono" id="editTelefono" class="form-control input-telefono" maxlength="12" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="actualizar" class="btn btn-info px-4 rounded-pill">
                        <i class="fas fa-save"></i> Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../js/sb-admin-2.min.js"></script>

    <script>
        // Actualizada para recibir 5 parámetros
        function editarEscuela(id, nombre, contacto, direccion, telefono) {
            document.getElementById('editId').value = id;
            document.getElementById('editNombre').value = nombre;
            document.getElementById('editContacto').value = contacto;
            document.getElementById('editDireccion').value = direccion;
            document.getElementById('editTelefono').value = telefono;
        }

        document.querySelectorAll('.input-telefono').forEach(input => {
            input.addEventListener('input', function(e) {
                let x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
                e.target.value = !x[2] ? x[1] : x[1] + ' ' + x[2] + (x[3] ? ' ' + x[3] : '');
            });
        });

        $(document).ready(function() {
            <?php if (!empty($mensaje)) : ?>
                Swal.fire({
                    icon: '<?= $tipo ?>',
                    title: '<?= $mensaje ?>',
                    showConfirmButton: false,
                    timer: 2000
                });
            <?php endif; ?>

            $('form').on('submit', function() {
                Swal.fire({
                    title: 'Procesando...',
                    text: 'Por favor espera un momento',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                });
            });

            $("#buscador").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#tablaEscuelas tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>
</body>

</html>