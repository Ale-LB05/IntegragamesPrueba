<?php
session_start();
include("../config/conexion.php");

/* VALIDAR SESIÓN */
if (!isset($_SESSION['usuario'])) {
    header("Location: ../RegistroAdmin/login.php");
    exit();
}

$rol = $_SESSION['rol'];

/* INSERTAR */
if (isset($_POST['guardar'])) {
    $telefono = preg_replace('/[^0-9]/', '', $_POST['telefono']);

    if (strlen($telefono) != 10) {
        echo "<script>alert('El teléfono debe tener exactamente 10 números'); window.location='escuelas.php';</script>";
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO escuela(nombre_escuela, direccion, telefono) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $_POST['nombre'], $_POST['direccion'], $telefono);
    $stmt->execute();
    header("Location: escuelas.php");
}

/* ACTUALIZAR */
if (isset($_POST['actualizar'])) {
    $telefono = preg_replace('/[^0-9]/', '', $_POST['telefono']);

    if (strlen($telefono) != 10) {
        echo "<script>alert('El teléfono debe tener exactamente 10 números'); window.location='escuelas.php';</script>";
        exit;
    }

    $stmt = $conn->prepare("UPDATE escuela SET nombre_escuela=?, direccion=?, telefono=? WHERE id_escuela=?");
    $stmt->bind_param("sssi", $_POST['nombre'], $_POST['direccion'], $telefono, $_POST['id']);
    $stmt->execute();
    header("Location: escuelas.php");
}

/* CONSULTA */
$res = $conn->query("SELECT * FROM escuela");
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <?php include("../menu/php/encabezado.php"); ?>

    <link rel="icon" href="../img/logo.png">
    <link href="../css/styles.css" rel="stylesheet">

    <style>
        body {
            background: #eef4ff;
        }

        .table thead {
            background: #3b82f6;
            color: white;
        }

        .modal-header {
            background: #3b82f6;
            color: white;
        }
    </style>
</head>

<body id="page-top">

    <div id="wrapper">

        <!-- MENU LATERAL -->
        <?php include("../menu/php/menuLateral.php"); ?>

        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                <!-- BARRA SUPERIOR -->
                <?php include("../menu/php/barraSuperior.php"); ?>

                <div class="container-fluid">

                    <div class="d-sm-flex align-items-center justify-content-between mb-4 flex-wrap">

                        <!-- TITULO -->
                        <h1 class="h3 text-gray-800 mb-2">Gestión de Escuelas</h1>

                        <!-- DERECHA -->
                        <div class="d-flex align-items-center flex-wrap">

                            <!-- BUSCADOR -->
                            <div class="input-group mr-2 mb-2" style="width: 550px;">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white">
                                        <i class="fas fa-search"></i>
                                    </span>
                                </div>
                                <input type="text" id="buscador" class="form-control"
                                    placeholder="Buscar evento...">
                                    
                                <select id="orden" class="form-control w-25">
                                    <option value="asc">A - Z</option>
                                    <option value="desc">Z - A</option>
                                </select>
                            </div>


                            <!-- BOTON -->
                            <button class="btn btn-success mb-2"
                                data-toggle="modal"
                                data-target="#modalAgregar">

                                <i class="fas fa-plus"></i> Nueva Escuela
                            </button>

                        </div>
                    </div>

                    <!-- CONTENIDO -->
                    <div class="card shadow">
                        <div class="card-body">

                            <div class="table-responsive">
                                <table class="table table-bordered text-center" id="tablaEscuelas">

                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Dirección</th>
                                            <th>Teléfono</th>
                                            <th>Acciones</th>
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
                                                <td><?= $row['nombre_escuela'] ?></td>
                                                <td><?= $row['direccion'] ?></td>
                                                <td><?= $row['telefono'] ?></td>
                                                <td>
                                                    <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#editar<?= $row['id_escuela'] ?>">
                                                        Editar
                                                    </button>
                                                </td>
                                            </tr>

                                            <!-- MODAL EDITAR -->
                                            <div class="modal fade" id="editar<?= $row['id_escuela'] ?>">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">

                                                        <div class="modal-header">
                                                            <h5>Editar Escuela</h5>
                                                            <button class="close" data-dismiss="modal">&times;</button>
                                                        </div>

                                                        <form method="POST">
                                                            <div class="modal-body">

                                                                <input type="hidden" name="id" value="<?= $row['id_escuela'] ?>">

                                                                <div class="form-group">
                                                                    <label>Nombre</label>
                                                                    <input type="text" name="nombre" class="form-control" value="<?= $row['nombre_escuela'] ?>" required>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label>Dirección</label>
                                                                    <textarea name="direccion" class="form-control" required><?= $row['direccion'] ?></textarea>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label>Teléfono</label>
                                                                    <input type="text" name="telefono" class="form-control"
                                                                        value="<?= $row['telefono'] ?>"
                                                                        pattern="[0-9]{10}" maxlength="10" required>
                                                                </div>

                                                            </div>

                                                            <div class="modal-footer">
                                                                <button name="actualizar" class="btn btn-info">Guardar</button>
                                                            </div>
                                                        </form>

                                                    </div>
                                                </div>
                                            </div>

                                        <?php } ?>

                                    </tbody>

                                </table>
                            </div>

                        </div>
                    </div>

                </div> <!-- container-fluid -->

            </div>

            <!-- FOOTER -->
            <?php include("../menu/php/piePagina.php"); ?>

        </div>

    </div>

    <!-- MODAL AGREGAR -->
    <div class="modal fade" id="modalAgregar">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5>Agregar Escuela</h5>
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>

                <form method="POST">
                    <div class="modal-body">

                        <div class="form-group">
                            <label>Nombre</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Dirección</label>
                            <textarea name="direccion" class="form-control" required></textarea>
                        </div>

                        <div class="form-group">
                            <label>Teléfono</label>
                            <input type="text" name="telefono" class="form-control"
                                pattern="[0-9]{10}" maxlength="10" required>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button name="guardar" class="btn btn-success">Guardar</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script>
        let buscador = document.getElementById("buscador");
        let orden = document.getElementById("orden");

        buscador.addEventListener("keyup", filtrar);
        orden.addEventListener("change", ordenar);

        function filtrar() {
            let texto = buscador.value.toLowerCase();
            let filas = document.querySelectorAll("#tablaEscuelas tbody tr");

            filas.forEach(fila => {
                fila.style.display = fila.textContent.toLowerCase().includes(texto) ? "" : "none";
            });
        }

        function ordenar() {
            let tabla = document.querySelector("#tablaEscuelas tbody");
            let filas = Array.from(tabla.rows);

            filas.sort((a, b) => {
                let A = a.cells[1].innerText.toLowerCase();
                let B = b.cells[1].innerText.toLowerCase();

                return orden.value === "asc" ?
                    A.localeCompare(B) :
                    B.localeCompare(A);
            });

            filas.forEach(fila => tabla.appendChild(fila));
        }
    </script>

    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../js/sb-admin-2.min.js"></script>

</body>

</html>