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
p.nombre,
p.edad,
e.nombre_evento,
esc.nombre_escuela,
r.tipo_juego,
r.puntaje,
r.fecha,
s.calificacion,
s.comentario
FROM participante p
LEFT JOIN evento e ON p.id_evento = e.id_evento
LEFT JOIN escuela esc ON p.id_escuela = esc.id_escuela
LEFT JOIN resultado r ON p.id_participante = r.id_participante
LEFT JOIN satisfaccion s ON p.id_participante = s.id_participante";

$res = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Participantes</title>

    <?php include("../menu/php/encabezado.php"); ?>
    <link rel="icon" href="../img/control.png" type="image/png">
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">

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
            border-radius: 18px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
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

<body id="page-top" class="sidebar-toggled">

    <div id="wrapper">

        <!-- MENU LATERAL -->
        <?php include("../menu/php/menuLateral.php"); ?>

        <!-- CONTENIDO -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">

                <!-- ENCABEZADO SUPERIOR -->
                <?php include("../menu/php/barraSuperior.php"); ?>

                <!-- CONTENIDO PRINCIPAL -->
                <div class="container-fluid mt-4">

                    <h2>Registros Completos</h2>

                    <div class="card p-3">

                        <!-- BUSCADOR -->
                        <input type="text" id="buscador" class="form-control mb-3" placeholder="Buscar participante...">

                        <!-- TOTAL -->
                        <p><strong>Total de registros:</strong> <?= $res->num_rows ?></p>

                        <!-- EXPORTAR -->
                        <button onclick="exportTableToExcel()" class="btn btn-success mb-3">
                            Exportar a Excel
                        </button>

                        <div class="table-responsive">
                            <table class="table table-bordered text-center">

                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Edad</th>
                                        <th>Evento</th>
                                        <th>Escuela</th>
                                        <th>Juego</th>
                                        <th>Puntaje</th>
                                        <th>Fecha</th>
                                        <th>Calificación</th>
                                        <th>Comentario</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php if ($res->num_rows == 0) { ?>
                                        <tr>
                                            <td colspan="9">No hay registros</td>
                                        </tr>
                                    <?php } ?>

                                    <?php while ($row = $res->fetch_assoc()) { ?>
                                        <tr>
                                            <td><?= $row['nombre'] ?></td>
                                            <td><?= $row['edad'] ?></td>
                                            <td><?= $row['nombre_evento'] ?? '-' ?></td>
                                            <td><?= $row['nombre_escuela'] ?? '-' ?></td>
                                            <td><?= $row['tipo_juego'] ?? '-' ?></td>
                                            <td><?= $row['puntaje'] ?? '-' ?></td>

                                            <td>
                                                <?= $row['fecha'] ? date("d/m/Y", strtotime($row['fecha'])) : '-' ?>
                                            </td>

                                            <td>
                                                <?php
                                                $cal = $row['calificacion'];
                                                if ($cal >= 8) {
                                                    echo "<span class='badge badge-success'>$cal</span>";
                                                } elseif ($cal >= 5) {
                                                    echo "<span class='badge badge-warning'>$cal</span>";
                                                } elseif ($cal !== null) {
                                                    echo "<span class='badge badge-danger'>$cal</span>";
                                                } else {
                                                    echo "-";
                                                }
                                                ?>
                                            </td>

                                            <td><?= $row['comentario'] ?? '-' ?></td>
                                        </tr>
                                    <?php } ?>

                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

            </div>

            <!-- FOOTER -->
            <?php include("../menu/php/piePagina.php"); ?>

        </div>
    </div>
    <?php include("../menu/php/logoutModal.php"); ?>

    <!-- JS -->
    <script>
        document.getElementById("buscador").addEventListener("keyup", function() {
            let filtro = this.value.toLowerCase();
            let filas = document.querySelectorAll("tbody tr");

            filas.forEach(fila => {
                let texto = fila.textContent.toLowerCase();
                fila.style.display = texto.includes(filtro) ? "" : "none";
            });
        });

        function exportTableToExcel() {
            let table = document.querySelector("table").outerHTML;
            let url = 'data:application/vnd.ms-excel,' + escape(table);
            let a = document.createElement('a');
            a.href = url;
            a.download = 'registros.xls';
            a.click();
        }
    </script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/startbootstrap-sb-admin-2@4.1.4/js/sb-admin-2.min.js"></script>

</body>

</html>