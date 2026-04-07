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
GROUP BY e.id_evento";

$res = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Eventos Registrados</title>

    <?php include("../menu/php/encabezado.php"); ?>
    <link rel="icon" href="../img/logo.png" type="image/png">
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../css/styles.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background: #eef4ff;
            font-family: 'Segoe UI';
        }

        h2 {
            color: #1e3a8a;
            font-weight: bold;
        }
        .card {
            transition: box-shadow 0.4s cubic-bezier(0.25, 0.8, 0.25, 1) !important;
            transform: none !important;
            border: none !important;
            background: #ffffff;
            border-radius: 20px !important; /* Más redondeado para verse moderno */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05) !important;
        }

        .card:hover {
            transform: none !important;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12) !important;
        }

        .table thead {
            background: #3b82f6;
            color: white;
        }

        .table tbody tr:hover {
            background: #e0ecff;
        }

        table {
            border-radius: 10px;
            overflow: hidden;
        }

        thead th {
            text-transform: uppercase;
            font-size: 13px;
        }

        tbody td {
            vertical-align: middle;
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
                        <h1 class="h3 text-gray-800 mb-2">Eventos Registrados</h1>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="input-group mr-2 mb-2" style="width: 400px;">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                                </div>
                                <input type="text" id="buscador" class="form-control" placeholder="Buscar evento...">
                            </div>

                            <button onclick="confirmarExportacion()" class="btn btn-success mb-2">
                                <i class="fas fa-file-excel"></i> Exportar a Excel
                            </button>
                        </div>
                    </div>

                    <div class="card p-3 shadow">
                        <p><strong>Total de registros:</strong> <?= $res->num_rows ?></p>

                        <div class="table-responsive">
                            <table class="table table-bordered text-center" id="tablaRegistros">
                                <thead>
                                    <tr>
                                        <th>Evento</th>
                                        <th>Lugar</th>
                                        <th>Fecha</th>
                                        <th>Responsable</th>
                                        <th>Total Personas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($res->num_rows == 0) { ?>
                                        <tr>
                                            <td colspan="5">No hay registros</td>
                                        </tr>
                                    <?php } ?>
                                    <?php while ($row = $res->fetch_assoc()) { ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['nombre_evento']) ?></td>
                                            <td><?= htmlspecialchars($row['lugar']) ?></td>
                                            <td><?= $row['fecha'] ? date("d/m/Y", strtotime($row['fecha'])) : '-' ?></td>
                                            <td><?= htmlspecialchars($row['nombre_responsable'] ?? 'Sin asignar') ?></td>
                                            <td><?= $row['total_personas'] ?></td>
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

    <script>
        // Buscador en tiempo real
        document.getElementById("buscador").addEventListener("keyup", function() {
            let filtro = this.value.toLowerCase();
            let filas = document.querySelectorAll("#tablaRegistros tbody tr");

            filas.forEach(fila => {
                let textoFila = fila.textContent.toLowerCase();
                fila.style.display = textoFila.includes(filtro) ? "" : "none";
            });
        });

        // VALIDACIÓN Y EXPORTACIÓN
        function confirmarExportacion() {
            Swal.fire({
                title: '¿Exportar registros?',
                text: "Se generará un archivo Excel con la información actual de la tabla.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, descargar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Simulación de carga
                    Swal.fire({
                        title: 'Generando archivo...',
                        timer: 1500,
                        timerProgressBar: true,
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
            let table = document.getElementById("tablaRegistros").outerHTML;
            // Estilo básico para que el Excel reconozca bordes
            let estilo = "<style>table, th, td { border: 1px solid black; border-collapse: collapse; }</style>";
            let url = 'data:application/vnd.ms-excel;charset=utf-8,' + encodeURIComponent(estilo + table);

            let a = document.createElement('a');
            a.href = url;
            a.download = 'Reporte_Eventos_IntegraGames.xls';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);

            Swal.fire({
                icon: 'success',
                title: '¡Descarga completada!',
                showConfirmButton: false,
                timer: 1500
            });
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/startbootstrap-sb-admin-2@4.1.4/js/sb-admin-2.min.js"></script>
</body>

</html>