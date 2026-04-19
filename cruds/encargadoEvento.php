<?php
session_start();
include("../config/conexion.php");

/* Verificar si inició sesión */
if (!isset($_SESSION['usuario'])) {
    header("Location: ../RegistroAdmin/login.php");
    exit();
}

$rol = $_SESSION['rol'];

/* CONSULTA */
$sql = "SELECT 
e.id_evento,
e.nombre_evento,
e.lugar,
e.fecha,
GROUP_CONCAT(DISTINCT r.nombre SEPARATOR ', ') AS nombre_responsable,
COUNT(DISTINCT p.id_participante) AS total_personas
FROM evento e
LEFT JOIN evento_responsable er ON e.id_evento = er.id_evento
LEFT JOIN responsable r ON er.id_responsable = r.id_responsable
LEFT JOIN participante p ON e.id_evento = p.id_evento
GROUP BY e.id_evento
ORDER BY e.fecha DESC";

$res = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Historial de Eventos</title>

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
        .text-primary { color: #4e73df !important; }
        .text-secondary { color: #858796 !important; }

        .bg-primary { background-color: #4e73df !important; }
        
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
        .table-responsive { border-radius: 15px; overflow: hidden; }
        
        .table thead {
            background: #4e73df;
            color: white;
            border-bottom: none;
        }
        
        .table thead th {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            border: none !important;
            padding: 15px;
        }

        .table tbody tr {
            border-bottom: 1px solid #f1f3f5;
            transition: background-color 0.2s ease;
        }

        .table tbody tr:hover { background-color: #f8f9fc; }
        
        .table tbody td {
            vertical-align: middle;
            border: none;
            padding: 15px;
            color: #5a5c69;
        }
        
        .rounded-pill { border-radius: 50rem !important; }

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
                            <i class="fas mr-2"></i> Historial de Registros
                        </h1>
                        
                        <div class="d-flex align-items-center flex-wrap">
                            <div class="input-group mr-3 mb-2" style="width: 350px;">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white border-right-0"><i class="fas fa-search text-primary"></i></span>
                                </div>
                                <input type="text" id="buscadorPersonalizado" class="form-control border-left-0" placeholder="Buscar evento, responsable o lugar...">
                            </div>

                            <button onclick="confirmarExportacion()" class="btn btn-success mb-2 rounded-pill px-4 shadow-sm fw-bold">
                                <i class="fas fa-file-excel mr-1"></i> Exportar a Excel
                            </button>
                        </div>
                    </div>

                    <div class="card p-4 shadow-sm mb-5">
                        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                            <h6 class="m-0 font-weight-bold" style="color: #4e73df;">Resumen de Asistencia General</h6>
                            <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">
                                <i class="fas fa-database text-primary mr-1"></i> Total de eventos: <?= $res->num_rows ?>
                            </span>
                        </div>

                        <div class="table-responsive">
                            <table class="table text-left w-100" id="tablaRegistros">
                                <thead>
                                    <tr>
                                        <th class="pl-4">Nombre del Evento</th>
                                        <th>Fecha de Realización</th>
                                        <th>Lugar / Ubicación</th>
                                        <th>Personal a Cargo</th>
                                        <th class="text-center">Total Personas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $res->fetch_assoc()) { ?>
                                        <tr>
                                            <td class="pl-4">
                                                <span class="fw-bold text-dark"><?= htmlspecialchars($row['nombre_evento']) ?></span>
                                            </td>
                                            
                                            <td>
                                                <span class="text-muted small fw-bold">
                                                    <i class="fas fa-calendar-day mr-1" style="color: #4e73df;"></i> 
                                                    <?= $row['fecha'] ? date("d/m/Y", strtotime($row['fecha'])) : '-' ?>
                                                </span>
                                            </td>
                                            
                                            <td>
                                                <span class="text-muted small">
                                                    <i class="fas fa-map-marker-alt mr-1 text-danger"></i> 
                                                    <?= htmlspecialchars($row['lugar']) ?>
                                                </span>
                                            </td>
                                            
                                            <td>
                                                <span class="badge bg-light text-dark border px-3 py-2 rounded-pill" style="font-weight: 500;">
                                                    <i class="fas fa-user-tie text-primary mr-1"></i> 
                                                    <?= htmlspecialchars($row['nombre_responsable'] ?? 'Sin asignar') ?>
                                                </span>
                                            </td>
                                            
                                            <td class="text-center">
                                                <span class="badge px-3 py-2 rounded-pill shadow-sm" style="background-color: #e3f2fd; color: #0288d1; font-size: 0.9rem;">
                                                    <i class="fas fa-users mr-1"></i> <?= $row['total_personas'] ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php } ?>
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
            var table = $('#tablaRegistros').DataTable({
                "pageLength": 10,
                "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]],
                "language": {
                    "lengthMenu": "Mostrar _MENU_ eventos",
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
            $('#buscadorPersonalizado').on('keyup', function () {
                table.search(this.value).draw();
            });
        });

        // VALIDACIÓN Y EXPORTACIÓN (Modales SweetAlert Mejorados)
        function confirmarExportacion() {
            Swal.fire({
                title: '¿Generar Reporte Excel?',
                text: "Se descargará un archivo con la lista actual de asistencia a eventos.",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#1cc88a', // Verde de la plantilla
                cancelButtonColor: '#e74a3b',  // Rojo de la plantilla
                confirmButtonText: '<i class="fas fa-download mr-1"></i> Sí, descargar',
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
                        html: 'Preparando filas y columnas',
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
            // Destruimos la inicialización de DataTables temporalmente para exportar TODAS las filas (y no solo las de la página 1)
            if ($.fn.DataTable.isDataTable('#tablaRegistros')) {
                $('#tablaRegistros').DataTable().destroy();
            }

            // Clonamos la tabla para no afectar la vista original al quitar íconos
            let tablaOriginal = document.getElementById("tablaRegistros");
            let tablaClon = tablaOriginal.cloneNode(true);
            
            // Eliminamos los íconos de FontAwesome del clon para que no se exporten al Excel
            let iconos = tablaClon.querySelectorAll('i');
            iconos.forEach(icono => icono.remove());

            let tableHTML = tablaClon.outerHTML;
            let estilo = "<style>table { font-family: Arial; } th { background-color: #4e73df; color: white; padding: 10px; text-transform: uppercase; font-size: 12px; } td { padding: 8px; border: 1px solid #dddddd; font-size: 14px; }</style>";
            
            // Reemplazar espacios y tildes para evitar errores de codificación
            let uri = 'data:application/vnd.ms-excel;base64,';
            let template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><meta charset="UTF-8">' + estilo + '</head><body><table>{table}</table></body></html>';
            
            let base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) };
            let format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) };

            let ctx = {worksheet: 'Historial_Asistencia', table: tablaClon.innerHTML};
            
            let a = document.createElement('a');
            a.href = uri + base64(format(template, ctx));
            a.download = 'Reporte_IntegraGames_' + new Date().toISOString().slice(0,10) + '.xls';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);

            // Volvemos a inicializar DataTables después de exportar
            var table = $('#tablaRegistros').DataTable({
                "pageLength": 10,
                "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]],
                "language": {
                    "lengthMenu": "Mostrar _MENU_ eventos",
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
                text: 'Tu archivo Excel se ha guardado correctamente.',
                showConfirmButton: false,
                timer: 2000
            });
        }
    </script>
</body>

</html>