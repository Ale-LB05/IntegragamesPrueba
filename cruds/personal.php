<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../RegistroAdmin/login.php");
    exit();
}

$rol = $_SESSION['rol'];
include("../config/conexion.php");
?>

<!DOCTYPE html>

<html lang="es">

<head>
    <?php include("../menu/php/encabezado.php"); ?>
    <link rel="icon" href="../img/control.png">
    <link href="../css/styles.css" rel="stylesheet">
</head>

<body id="page-top">

    <div id="wrapper">
        <!-- Sidebar -->
        <?php include("../menu/php/menuLateral.php"); ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">

                <!-- Topbar -->
                <?php include("../menu/php/barraSuperior.php"); ?>

                <div class="container-fluid">

                    <!-- TÍTULO -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 text-gray-800">Panel de información</h1>
                    </div>

                    <!-- TABLA -->
                    <div class="card shadow mb-4">
                        <div class="card-header">
                            <button class="btn btn-success" data-toggle="modal" data-target="#modalCrear">
                                <i class="fas fa-plus"></i> Nuevo empleado
                            </button>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">

                                <table class="table table-bordered" id="dataTable">

                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre</th>
                                            <th>Usuario</th>
                                            <th>Turno</th>
                                            <th>Email</th>
                                            <th>Rol</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        <?php
                                        $sql = "SELECT * FROM responsable";
                                        $res = $conn->query($sql);

                                        while ($row = $res->fetch_assoc()) {
                                        ?>

                                            <tr>
                                                <td><?= $row["id_responsable"] ?></td>
                                                <td><?= $row["nombre"] ?></td>
                                                <td><?= $row["correo"] ?></td>
                                                <td><?= $row["rol"] ?></td>

                                                <td>
                                                    <button class="btn btn-info btn-sm"
                                                        onclick="editarRegistro(
                                                        '<?= $row['id_responsable'] ?>',
                                                        '<?= $row['nombre'] ?>',
                                                        '<?= $row['correo'] ?>',
                                                        '<?= $row['rol'] ?>'
                                                        )">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                </td>

                                                <td>
                                                    <button class="btn btn-danger btn-sm"
                                                        data-toggle="modal"
                                                        data-target="#modalEliminar"
                                                        onclick="borraRegistro(
                                                        '<?= $row['id_responsable'] ?>',
                                                        '<?= $row['nombre'] ?>'
                                                        )">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>

                                        <?php } ?>

                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <?php include("../menu/php/piePagina.php"); ?>
            ```

        </div>

    </div>

    <!-- MODAL CREAR -->
    <div class="modal fade" id="modalCrear">
        <div class="modal-dialog">
            <form method="POST" class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Nuevo responsable</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <!-- Nombre -->
                    <input type="text" name="nombre" class="form-control mb-2" placeholder="Nombre" required>

                    <!-- Correo -->
                    <input type="email" name="correo" class="form-control mb-2" placeholder="Correo" required>

                    <!-- Contraseña -->
                    <input type="password" name="contrasena" class="form-control mb-2" placeholder="Contraseña" required>

                    <!-- Rol -->
                    <select name="rol" class="form-control mb-2" required>
                        <option value="">Seleccionar rol</option>
                        <option value="administrador">Administrador</option>
                        <option value="promotor">Promotor</option>
                    </select>
                </div>

                <div class="modal-footer">
                    <button type="submit" name="crear" class="btn btn-success">Crear</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>

            </form>
        </div>
    </div>

    <!-- MODAL ELIMINAR -->

    <div class="modal fade" id="modalEliminar">
        <div class="modal-dialog">
            <form method="POST" class="modal-content">

                ```
                <div class="modal-header bg-danger text-white">
                    <h5>Eliminar</h5>
                </div>

                <div class="modal-body">
                    <p id="nombreEmpleado"></p>
                    <input type="hidden" name="id_responsable" id="deleteIdusuario">
                </div>

                <div class="modal-footer">
                    <button name="eliminar" class="btn btn-danger">Eliminar</button>
                </div>
                ```

            </form>
        </div>
    </div>

    <!-- JS -->

    <script src="../vendor/jquery/jquery.min.js"></script>

    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script>
        function editarRegistro(idusuario, usuario, email, idempleado, nombre, turno) {
            $('#modalEditar').modal('show');
        }

        function borraRegistro(id, nombre) {
            document.getElementById('deleteIdusuario').value = id;
            document.getElementById('nombreEmpleado').innerText = nombre;
        }
    </script>

</body>

</html>