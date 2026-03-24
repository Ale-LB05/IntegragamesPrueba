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

                    <h2>Eventos Registrados</h2>

                    <div class="card p-3">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <!-- BUSCADOR -->
                            <input type="text" id="buscador" class="form-control w-50" placeholder="Buscar evento...">

                            <!-- BOTÓN -->
                            <button onclick="exportTableToExcel()" class="btn btn-success">
                                Exportar a Excel
                            </button>
                        </div>
                        <!-- TOTAL -->
                        <p><strong>Total de registros:</strong> <?= $res->num_rows ?></p>

                        <div class="table-responsive">
                            <table class="table table-bordered text-center">

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
                                            <td colspan="4">No hay registros</td>
                                        </tr>
                                    <?php } ?>

                                    <?php while ($row = $res->fetch_assoc()) { ?>
                                        <tr>
                                            <td><?= $row['nombre_evento'] ?></td>
                                            <td><?= $row['lugar'] ?></td>

                                            <td>
                                                <?= $row['fecha'] ? date("d/m/Y", strtotime($row['fecha'])) : '-' ?>
                                            </td>

                                            <td><?= $row['nombre_responsable'] ?? 'Sin asignar' ?></td>
                                            <td><?= $row['total_personas'] ?></td>
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
                let evento = fila.children[0].textContent.toLowerCase();
                let lugar = fila.children[1].textContent.toLowerCase();
                let fecha = fila.children[2].textContent.toLowerCase();
                let responsable = fila.children[3].textContent.toLowerCase();

                if (
                    evento.includes(filtro) ||
                    lugar.includes(filtro) ||
                    fecha.includes(filtro) ||
                    responsable.includes(filtro)
                ) {
                    fila.style.display = "";
                } else {
                    fila.style.display = "none";
                }
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