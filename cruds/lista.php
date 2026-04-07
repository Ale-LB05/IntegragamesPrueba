<?php
session_start();
include("../config/conexion.php");

/* Verificar si inició sesión */
if (!isset($_SESSION['usuario'])) {
    header("Location: ../RegistroAdmin/login.php");
    exit();
}

$rol = $_SESSION['rol'];

/* CONSULTA ACTUALIZADA:*/
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

    <style>
        body {
            background: #eef4ff;
            font-family: 'Segoe UI';
        }

        h1 {
            color: #1e3a8a;
            font-weight: bold;
        }

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
            font-size: 11px;
            vertical-align: middle !important;
        }

        tbody td {
            vertical-align: middle;
            font-size: 14px;
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
                        <h1 class="h3 text-gray-800 mb-2">Registros de Participantes</h1>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="input-group mr-2 mb-2" style="width: 400px;">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                                </div>
                                <input type="text" id="buscador" class="form-control" placeholder="Buscar participante...">
                            </div>
                            <button onclick="confirmarExportacion()" class="btn btn-success mb-2">
                                <i class="fas fa-file-excel"></i> Exportar a Excel
                            </button>
                        </div>
                    </div>

                    <div class="card p-3 shadow">
                        <p><strong>Total de registros:</strong> <?= $res->num_rows ?></p>
                        <div class="table-responsive">
                            <table class="table table-bordered text-center" id="tablaParticipantes">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Edad</th>
                                        <th>Evento</th>
                                        <th>Escuela</th>
                                        <th>Juego</th>
                                        <th>Fecha Encuesta</th>
                                        <th>Calificación</th>
                                        <th>Comentario</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($res->num_rows == 0) : ?>
                                        <tr>
                                            <td colspan="8">No hay registros</td>
                                        </tr>
                                    <?php endif; ?>

                                    <?php while ($row = $res->fetch_assoc()) : ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['nombre']) ?></td>
                                            <td><?= $row['edad'] ?></td>
                                            <td><?= htmlspecialchars($row['nombre_evento'] ?? '-') ?></td>
                                            <td><?= htmlspecialchars($row['nombre_escuela'] ?? '-') ?></td>
                                            <td><?= htmlspecialchars($row['juego'] ?? '-') ?></td>
                                            <td><?= $row['fecha'] ? date("d/m/Y", strtotime($row['fecha'])) : '-' ?></td>
                                            <td>
                                                <?php
                                                $cal = $row['calificacion'];
                                                if ($cal >= 4) echo "<span class='badge badge-success'>$cal ★</span>";
                                                elseif ($cal == 3) echo "<span class='badge badge-warning'>$cal ★</span>";
                                                elseif ($cal !== null) echo "<span class='badge badge-danger'>$cal ★</span>";
                                                else echo "-";
                                                ?>
                                            </td>
                                            <td><small><?= htmlspecialchars($row['comentario'] ?? '-') ?></small></td>
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

    <script>
        // Buscador
        document.getElementById("buscador").addEventListener("keyup", function() {
            let filtro = this.value.toLowerCase();
            let filas = document.querySelectorAll("#tablaParticipantes tbody tr");
            filas.forEach(fila => {
                fila.style.display = fila.textContent.toLowerCase().includes(filtro) ? "" : "none";
            });
        });

        // VALIDACIÓN Y EXPORTACIÓN
        function confirmarExportacion() {
            Swal.fire({
                title: '¿Exportar a Excel?',
                text: "Se generará un reporte con la satisfacción de los alumnos.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, exportar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Generando archivo...',
                        timer: 2000,
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
            let table = document.getElementById("tablaParticipantes").outerHTML;
            let estilo = "<style>table, th, td { border: 1px solid #000; border-collapse: collapse; text-align: center; }</style>";
            let url = 'data:application/vnd.ms-excel;charset=utf-8,' + encodeURIComponent(estilo + table);
            let a = document.createElement('a');
            a.href = url;
            a.download = 'Reporte_Satisfaccion_IntegraGames.xls';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/startbootstrap-sb-admin-2@4.1.4/js/sb-admin-2.min.js"></script>
</body>

</html>