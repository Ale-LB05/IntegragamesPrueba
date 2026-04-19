<?php
session_start();
include("../config/conexion.php");

/* Verificar si inició sesión */
if (!isset($_SESSION['usuario'])) {
    header("Location: ../RegistroAdmin/login.php");
    exit();
}

$rol = $_SESSION['rol'];

/* CONSULTA ACTUALIZADA */
$sql = "SELECT 
    p.nombre,
    p.edad,
    e.nombre_evento,
    esc.nombre_escuela,
    j.nombre_juego AS juego,
    s.fecha,
    s.calificacion,
    s.comentario
FROM participante p
LEFT JOIN evento e ON p.id_evento = e.id_evento
LEFT JOIN escuela esc ON p.id_escuela = esc.id_escuela
LEFT JOIN satisfaccion s ON p.id_participante = s.id_participante
LEFT JOIN juego j ON s.id_juego = j.id_juego
ORDER BY s.fecha DESC";

$res = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Participantes</title>

    <?php include("../menu/php/encabezado.php"); ?>
    <link rel="icon" href="../img/logo.png" type="image/png">
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../css/styles.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet">

    <style>
        body {
            background: #eef4ff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05) !important;
        }

        .card:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
        }

        /* Estilos de Tabla Premium */
        .table-responsive {
            border-radius: 15px;
            overflow: hidden;
        }

        .table thead {
            background: #4e73df;
            color: white;
            border-bottom: none;
        }

        .table thead th {
            font-size: 0.80rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            border: none !important;
            padding: 15px;
            vertical-align: middle;
        }

        .table tbody tr {
            border-bottom: 1px solid #f1f3f5;
            transition: background-color 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: #f8f9fc;
        }

        .table tbody td {
            vertical-align: middle;
            border: none;
            padding: 12px 15px;
            color: #5a5c69;
            font-size: 0.9rem;
        }

        .rounded-pill {
            border-radius: 50rem !important;
        }

        /* Controles de DataTables (Buscador oculto) */
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
        }

        .page-item.active .page-link {
            background-color: #4e73df !important;
            border-color: #4e73df !important;
            color: #ffffff !important;
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
                        <h1 class="h3 text-gray-800 mb-2 fw-bold">
                            <i class="fas fa-users mr-2"></i> Registros de Participantes
                        </h1>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="input-group mr-3 mb-2" style="width: 350px;">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white border-right-0"><i class="fas fa-search text-primary"></i></span>
                                </div>
                                <input type="text" id="buscadorPersonalizado" class="form-control border-left-0" placeholder="Buscar participante, evento o escuela...">
                            </div>

                            <button onclick="confirmarExportacion()" class="btn btn-success mb-2 rounded-pill px-4 shadow-sm fw-bold">
                                <i class="fas fa-file-excel mr-1"></i> Exportar a Excel
                            </button>
                        </div>
                    </div>

                    <div class="card p-4 shadow-sm mb-5">
                        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                            <h6 class="m-0 font-weight-bold" style="color: #4e73df;">Feedback y Satisfacción</h6>
                            <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">
                                <i class="fas fa-database text-primary mr-1"></i> Total registrados: <?= $res->num_rows ?>
                            </span>
                        </div>

                        <div class="table-responsive">
                            <table class="table text-left w-100" id="tablaParticipantes">
                                <thead>
                                    <tr>
                                        <th class="pl-4">Nombre</th>
                                        <th class="text-center">Edad</th>
                                        <th>Evento</th>
                                        <th>Escuela</th>
                                        <th>Juego</th>
                                        <th>Fecha</th>
                                        <th class="text-center">Calificación</th>
                                        <th>Comentario</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $res->fetch_assoc()) : ?>
                                        <tr>
                                            <td class="pl-4 fw-bold text-dark">
                                                <?= htmlspecialchars($row['nombre']) ?>
                                            </td>

                                            <td class="text-center">
                                                <span class="badge bg-light text-dark border rounded-circle" style="padding: 8px 10px;">
                                                    <?= $row['edad'] ?>
                                                </span>
                                            </td>

                                            <td class="text-secondary"><?= htmlspecialchars($row['nombre_evento'] ?? '-') ?></td>
                                            <td class="text-muted"><small><?= htmlspecialchars($row['nombre_escuela'] ?? '-') ?></small></td>

                                            <td>
                                                <?php if ($row['juego']): ?>
                                                    <span class="badge px-3 py-1 rounded-pill" style="background-color: #e3f2fd; color: #0288d1; font-weight: 600;">
                                                        <i class="fas fa-gamepad mr-1"></i> <?= htmlspecialchars($row['juego']) ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>

                                            <td class="text-muted small">
                                                <?= $row['fecha'] ? date("d/m/Y", strtotime($row['fecha'])) : '-' ?>
                                            </td>

                                            <td class="text-center">
                                                <?php
                                                $cal = $row['calificacion'];

                                                if ($cal !== null) {
                                                    // VERDE: 8 a 10
                                                    if ($cal >= 8) {
                                                        echo "<span class='badge px-3 py-2 rounded-pill shadow-sm' style='background-color: #1cc88a; color: white; font-size: 0.85rem;'>$cal <i class='fas fa-star ml-1'></i></span>";
                                                    }
                                                    // AMARILLO: 3 a 7
                                                    elseif ($cal >= 3 && $cal <= 7) {
                                                        echo "<span class='badge px-3 py-2 rounded-pill shadow-sm' style='background-color: #f6c23e; color: white; font-size: 0.85rem;'>$cal <i class='fas fa-star ml-1'></i></span>";
                                                    }
                                                    // ROJO: 0 a 2
                                                    else {
                                                        echo "<span class='badge px-3 py-2 rounded-pill shadow-sm' style='background-color: #e74a3b; color: white; font-size: 0.85rem;'>$cal <i class='fas fa-star ml-1'></i></span>";
                                                    }
                                                } else {
                                                    echo '<span class="text-muted">-</span>';
                                                }
                                                ?>
                                            </td>

                                            <td class="text-muted" style="max-width: 250px; font-style: italic;">
                                                <small><?= htmlspecialchars($row['comentario'] ?? '-') ?></small>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php include("../menu/php/piePagina.php"); ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/startbootstrap-sb-admin-2@4.1.4/js/sb-admin-2.min.js"></script>

    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            // Inicializar DataTables
            var table = $('#tablaParticipantes').DataTable({
                "pageLength": 10,
                "lengthMenu": [
                    [5, 10, 25, 50, -1],
                    [5, 10, 25, 50, "Todos"]
                ],
                "language": {
                    "lengthMenu": "Mostrar _MENU_ registros",
                    "zeroRecords": "No se encontraron registros",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay datos disponibles",
                    "infoFiltered": "(filtrado de _MAX_ totales)",
                    "search": "",
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
        });

        // VALIDACIÓN Y EXPORTACIÓN MEJORADA
        function confirmarExportacion() {
            Swal.fire({
                title: '¿Exportar a Excel?',
                text: "Se generará un reporte con la satisfacción de los alumnos.",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#1cc88a',
                cancelButtonColor: '#e74a3b',
                confirmButtonText: '<i class="fas fa-download mr-1"></i> Sí, exportar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'rounded-pill px-4 shadow-sm',
                    cancelButton: 'rounded-pill px-4'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Procesando Documento...',
                        html: 'Preparando calificaciones',
                        timer: 1500,
                        timerProgressBar: true,
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                        willClose: () => {
                            ejecutarExportacion();
                        }
                    });
                }
            });
        }

        function ejecutarExportacion() {
            // Destruimos la inicialización de DataTables temporalmente para exportar TODAS las filas
            if ($.fn.DataTable.isDataTable('#tablaParticipantes')) {
                $('#tablaParticipantes').DataTable().destroy();
            }

            // Clonamos la tabla para limpiarla antes de exportar
            let tablaOriginal = document.getElementById("tablaParticipantes");
            let tablaClon = tablaOriginal.cloneNode(true);

            // Eliminamos los íconos de FontAwesome para que no se exporten
            let iconos = tablaClon.querySelectorAll('i');
            iconos.forEach(icono => icono.remove());

            let tableHTML = tablaClon.outerHTML;
            let estilo = "<style>table { font-family: Arial; } th { background-color: #4e73df; color: white; padding: 10px; } td { padding: 8px; border: 1px solid #dddddd; }</style>";

            let uri = 'data:application/vnd.ms-excel;base64,';
            let template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><meta charset="UTF-8">' + estilo + '</head><body><table>{table}</table></body></html>';

            let base64 = function(s) {
                return window.btoa(unescape(encodeURIComponent(s)))
            };
            let format = function(s, c) {
                return s.replace(/{(\w+)}/g, function(m, p) {
                    return c[p];
                })
            };

            let ctx = {
                worksheet: 'Reporte_Satisfaccion',
                table: tablaClon.innerHTML
            };

            let a = document.createElement('a');
            a.href = uri + base64(format(template, ctx));
            a.download = 'Reporte_Satisfaccion_' + new Date().toISOString().slice(0, 10) + '.xls';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);

            // Volvemos a inicializar DataTables después de exportar
            var table = $('#tablaParticipantes').DataTable({
                "pageLength": 10,
                "lengthMenu": [
                    [5, 10, 25, 50, -1],
                    [5, 10, 25, 50, "Todos"]
                ],
                "language": {
                    "lengthMenu": "Mostrar _MENU_ registros",
                    "zeroRecords": "No se encontraron registros",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay datos disponibles",
                    "infoFiltered": "(filtrado de _MAX_ totales)",
                    "search": "",
                    "paginate": {
                        "first": "Primera",
                        "last": "Última",
                        "next": "Siguiente >",
                        "previous": "< Anterior"
                    }
                }
            });

            Swal.fire({
                icon: 'success',
                title: '¡Descarga Exitosa!',
                showConfirmButton: false,
                timer: 2000
            });
        }
    </script>
</body>

</html>