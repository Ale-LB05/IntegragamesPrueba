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
    $contacto = $conn->real_escape_string($_POST['contacto']);
    $direccion = $conn->real_escape_string($_POST['direccion']);

    $temp_tel = preg_replace('/[^0-9]/', '', $_POST['telefono']);

    if (strlen($temp_tel) != 10) {
        $mensaje = "El teléfono debe tener exactamente 10 números";
        $tipo = "error";
    } else {
        $telefono = substr($temp_tel, 0, 3) . " " . substr($temp_tel, 3, 3) . " " . substr($temp_tel, 6);

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
    $contacto = $conn->real_escape_string($_POST['contacto']);
    $direccion = $conn->real_escape_string($_POST['direccion']);

    $temp_tel = preg_replace('/[^0-9]/', '', $_POST['telefono']);

    if (strlen($temp_tel) != 10) {
        $mensaje = "El teléfono debe tener exactamente 10 números";
        $tipo = "error";
    } else {
        $telefono = substr($temp_tel, 0, 3) . " " . substr($temp_tel, 3, 3) . " " . substr($temp_tel, 6);

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

/* ELIMINAR (CON VALIDACIÓN DE HISTORIAL) */
if (isset($_POST['eliminar'])) {
    $id = intval($_POST['id']);

    // Primero verificamos si la escuela está siendo usada en la tabla de participantes
    $check = $conn->prepare("SELECT id_participante FROM participante WHERE id_escuela = ? LIMIT 1");
    $check->bind_param("i", $id);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        // Si hay registros, bloqueamos la eliminación
        $mensaje = "No se puede eliminar. La escuela tiene registros en el historial de eventos.";
        $tipo = "error";
    } else {
        // Si no hay registros, procedemos a borrar
        $stmt = $conn->prepare("DELETE FROM escuela WHERE id_escuela = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $mensaje = "Escuela eliminada correctamente.";
            $tipo = "success";
        } else {
            $mensaje = "Error al eliminar la escuela.";
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

    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet">

    <style>
        body {
            background: #eef4ff;
        }

        .text-primary {
            color: #4e73df !important;
        }

        .text-secondary {
            color: #858796 !important;
        }

        .bg-primary {
            background-color: #4e73df !important;
        }

        .card {
            transition: box-shadow 0.4s cubic-bezier(0.25, 0.8, 0.25, 1) !important;
            transform: none !important;
            border: none !important;
            background: #ffffff;
            border-radius: 20px !important;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05) !important;
        }

        .card:hover {
            transform: none !important;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12) !important;
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

        /* Mejoras en la tabla */
        .table thead {
            background: #4e73df;
            color: white;
        }

        .table thead th {
            border-bottom: none !important;
        }

        .table th,
        .table td {
            vertical-align: middle !important;
        }

        .table tbody tr:hover {
            background-color: #f8f9fc;
        }

        /* Ocultar el buscador nativo de DataTables ya que usaremos el personalizado */
        .dataTables_wrapper .dataTables_filter {
            display: none;
        }

        .dataTables_wrapper .dataTables_length select {
            border-radius: 10px;
            border: 1px solid #d1d3e2;
        }

        /* Color Negro para letras de paginación */
        .page-item .page-link {
            color: #333333 !important;
            /* Letras negras/oscuras */
        }

        .page-item.active .page-link {
            background-color: #4e73df !important;
            border-color: #4e73df !important;
            color: #ffffff !important;
            /* Blanco solo para la página seleccionada */
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
                        <h1 class="h3 text-gray-800 mb-2 fw-bold">Gestión de Escuelas</h1>
                        <div class="d-flex align-items-center flex-wrap">
                            <div class="input-group mr-2 mb-2" style="width: 350px;">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white border-right-0"><i class="fas fa-search text-primary"></i></span>
                                </div>
                                <input type="text" id="buscadorPersonalizado" class="form-control border-left-0" placeholder="Buscar escuela...">
                            </div>
                            <button class="btn btn-success mb-2 shadow-sm rounded-pill px-4" data-toggle="modal" data-target="#modalAgregar">
                                <i class="fas fa-plus mr-1"></i> Nueva Escuela
                            </button>
                        </div>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-body p-4">
                            <div class="table-responsive">
                                <table class="table table-bordered text-center w-100" id="tablaEscuelas">
                                    <thead>
                                        <tr>
                                            <th>Nombre de la Escuela</th>
                                            <th>Contacto Directo</th>
                                            <th>Dirección</th>
                                            <th>Teléfono</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = $res->fetch_assoc()) : ?>
                                            <tr>
                                                <td class="align-middle fw-bold text-dark text-left pl-4">
                                                    <?= htmlspecialchars($row['nombre_escuela']) ?>
                                                </td>

                                                <td class="align-middle text-secondary">
                                                    <?= htmlspecialchars($row['contacto']) ?>
                                                </td>

                                                <td class="align-middle text-muted text-left">
                                                    <small><?= htmlspecialchars($row['direccion']) ?></small>
                                                </td>

                                                <td class="align-middle">
                                                    <span class="badge bg-light text-dark border px-3 py-2 rounded-pill" style="font-weight: 500; font-size: 0.85rem;">
                                                        <i class="fas fa-phone-alt mr-1 text-success small"></i> <?= $row['telefono'] ?>
                                                    </span>
                                                </td>

                                                <td class="align-middle">
                                                    <button class="btn btn-info btn-sm shadow-sm rounded-circle"
                                                        data-toggle="modal"
                                                        data-target="#modalEditar"
                                                        title="Editar escuela"
                                                        onclick="editarEscuela(
                                                        '<?= $row['id_escuela'] ?>',
                                                        '<?= htmlspecialchars($row['nombre_escuela'], ENT_QUOTES) ?>',
                                                        '<?= htmlspecialchars($row['contacto'], ENT_QUOTES) ?>',
                                                        '<?= htmlspecialchars($row['direccion'], ENT_QUOTES) ?>',
                                                        '<?= $row['telefono'] ?>'
                                                    )">
                                                        <i class="fas fa-edit"></i>
                                                    </button>

                                                    <button class="btn btn-danger btn-sm shadow-sm rounded-circle ml-1"
                                                        title="Eliminar escuela"
                                                        onclick="eliminarEscuela('<?= $row['id_escuela'] ?>', '<?= htmlspecialchars($row['nombre_escuela'], ENT_QUOTES) ?>')">
                                                        <i class="fas fa-trash"></i>
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

    <div class="modal fade" id="modalAgregar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" class="modal-content shadow-lg border-0 rounded-4">

                <div class="modal-header bg-primary text-white rounded-top-4">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-school mr-2"></i> Nueva Escuela</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">×</button>
                </div>

                <div class="modal-body p-4">
                    <h6 class="fw-bold mb-3 border-bottom pb-2" style="color: #4e73df;">
                        <i class="fas fa-building mr-1"></i> Datos de la Institución
                    </h6>
                    <label class="fw-bold text-muted small">Nombre de la Escuela</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-light border-right-0" style="color: #4e73df;"><i class="fas fa-university"></i></span>
                        <input type="text" name="nombre" class="form-control border-left-0" placeholder="Ej: Preparatoria Benito Juárez" required>
                    </div>

                    <label class="fw-bold text-muted small">Dirección Física</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-light border-right-0" style="color: #4e73df;"><i class="fas fa-map-marker-alt"></i></span>
                        <textarea name="direccion" class="form-control border-left-0" rows="2" placeholder="Calle, Número, Colonia, Municipio" required></textarea>
                    </div>

                    <h6 class="fw-bold mb-3 mt-4 border-bottom pb-2" style="color: #4e73df;">
                        <i class="fas fa-address-book mr-1"></i> Información de Contacto
                    </h6>
                    <label class="fw-bold text-muted small">Nombre del Director o Encargado</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-light border-right-0" style="color: #4e73df;"><i class="fas fa-user-tie"></i></span>
                        <input type="text" name="contacto" class="form-control border-left-0" placeholder="Ej: Mtro. Juan Pérez" required>
                    </div>

                    <label class="fw-bold text-muted small">Teléfono (10 dígitos)</label>
                    <div class="input-group mb-2">
                        <span class="input-group-text bg-light border-right-0" style="color: #4e73df;"><i class="fas fa-phone"></i></span>
                        <input type="text" name="telefono" class="form-control input-telefono border-left-0" maxlength="12" placeholder="Ej: 443 325 2165" required>
                    </div>
                </div>

                <div class="modal-footer bg-light border-top-0">
                    <button type="button" class="btn btn-outline-danger px-4 rounded-pill" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="guardar" class="btn btn-success fw-bold text-white px-4 rounded-pill shadow-sm">
                        <i class="fas fa-save mr-2"></i> Guardar Escuela
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" class="modal-content shadow-lg border-0 rounded-4">

                <div class="modal-header bg-info text-white rounded-top-4">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-edit mr-2"></i> Editar Escuela</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">×</button>
                </div>

                <div class="modal-body p-4">
                    <input type="hidden" name="id" id="editId">

                    <h6 class="fw-bold mb-3 border-bottom pb-2 text-info">
                        <i class="fas fa-building mr-1"></i> Datos de la Institución
                    </h6>
                    <label class="fw-bold text-muted small">Nombre de la Escuela</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-light border-right-0 text-info"><i class="fas fa-university"></i></span>
                        <input type="text" name="nombre" id="editNombre" class="form-control border-left-0" required>
                    </div>

                    <label class="fw-bold text-muted small">Dirección Física</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-light border-right-0 text-info"><i class="fas fa-map-marker-alt"></i></span>
                        <textarea name="direccion" id="editDireccion" class="form-control border-left-0" rows="2" required></textarea>
                    </div>

                    <h6 class="fw-bold mb-3 mt-4 border-bottom pb-2 text-info">
                        <i class="fas fa-address-book mr-1"></i> Información de Contacto
                    </h6>
                    <label class="fw-bold text-muted small">Nombre del Director o Encargado</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-light border-right-0 text-info"><i class="fas fa-user-tie"></i></span>
                        <input type="text" name="contacto" id="editContacto" class="form-control border-left-0" required>
                    </div>

                    <label class="fw-bold text-muted small">Teléfono</label>
                    <div class="input-group mb-2">
                        <span class="input-group-text bg-light border-right-0 text-info"><i class="fas fa-phone"></i></span>
                        <input type="text" name="telefono" id="editTelefono" class="form-control input-telefono border-left-0" maxlength="12" required>
                    </div>
                </div>

                <div class="modal-footer bg-light border-top-0">
                    <button type="button" class="btn btn-outline-danger px-4 rounded-pill" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="actualizar" class="btn btn-info fw-bold text-white px-4 rounded-pill shadow-sm">
                        <i class="fas fa-save mr-2"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../js/sb-admin-2.min.js"></script>

    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

    <script>
        function editarEscuela(id, nombre, contacto, direccion, telefono) {
            document.getElementById('editId').value = id;
            document.getElementById('editNombre').value = nombre;
            document.getElementById('editContacto').value = contacto;
            document.getElementById('editDireccion').value = direccion;
            document.getElementById('editTelefono').value = telefono;
        }

        // FUNCION PARA ELIMINAR CON SWEETALERT
        function eliminarEscuela(id, nombre) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Estás a punto de eliminar a " + nombre + ". Si la escuela tiene historial, se bloqueará la acción.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74a3b', // Rojo
                cancelButtonColor: '#858796', // Gris
                confirmButtonText: '<i class="fas fa-trash"></i> Sí, eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = document.createElement("form");
                    form.method = "POST";
                    form.innerHTML = `<input type="hidden" name="id" value="${id}"><input type="hidden" name="eliminar" value="1">`;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Formateo del teléfono
        document.querySelectorAll('.input-telefono').forEach(input => {
            input.addEventListener('input', function(e) {
                let x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
                e.target.value = !x[2] ? x[1] : x[1] + ' ' + x[2] + (x[3] ? ' ' + x[3] : '');
            });
        });

        $(document).ready(function() {

            // Inicializar DataTables
            var table = $('#tablaEscuelas').DataTable({
                "pageLength": 10,
                "lengthMenu": [
                    [5, 10, 25, 50, -1],
                    [5, 10, 25, 50, "Todas"]
                ],
                "language": {
                    "lengthMenu": "Mostrar _MENU_ escuelas",
                    "zeroRecords": "No se encontraron escuelas registradas",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay datos disponibles",
                    "infoFiltered": "(filtrado de _MAX_ totales)",
                    "paginate": {
                        "first": "Primera",
                        "last": "Última",
                        "next": "Siguiente >",
                        "previous": "< Anterior"
                    }
                }
            });

            // Enlazar el buscador personalizado con DataTables
            $('#buscadorPersonalizado').on('keyup', function() {
                table.search(this.value).draw();
            });

            // Alertas SweetAlert
            <?php if (!empty($mensaje)) : ?>
                Swal.fire({
                    icon: '<?= $tipo ?>',
                    title: '<?= $mensaje ?>',
                    showConfirmButton: false,
                    timer: 2500
                }).then(() => {
                    window.history.replaceState({}, document.title, window.location.pathname);
                });
            <?php endif; ?>

            // Loader de carga al guardar formularios
            $('form:not(.search-form)').on('submit', function() {
                Swal.fire({
                    title: 'Procesando...',
                    text: 'Por favor espera un momento',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                });
            });
        });
    </script>
</body>

</html>