<?php
require_once "../config/conexion.php";
$escuelas = $conn->query("SELECT * FROM escuela");
$eventos = $conn->query("SELECT * FROM evento");
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro Alumno - IntegraGames</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }

        .card {
            border: none;
            border-radius: 15px;
        }
        .btn-primary {
            background: #00c6ff;
            border: none;
        }

        .btn-primary:hover {
            background: #0072ff;
        }
    </style>

</head>

<body>

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-md-6">

                <div class="card shadow-lg">

                    <div class="card-body p-5">

                        <div class="text-center mb-4">
                            <h3 class="mt-2">Registro de Alumno</h3>

                            <p class="text-muted">IntegraGames</p>

                        </div>
                        <form action="guardar_alumno.php" method="POST">

                            <!-- NOMBRE -->
                            <div class="mb-3">
                                <label class="form-label">Nombre</label>
                                <input type="text"
                                    class="form-control"
                                    name="nombre"
                                    required>
                            </div>

                            <!-- EDAD -->
                            <div class="mb-3">
                                <label class="form-label">Edad</label>
                                <input type="number"
                                    class="form-control"
                                    name="edad"
                                    required>

                            </div>

                            <!-- ESCUELA -->
                            <div class="mb-3">
                                <label class="form-label">Escuela</label>
                                <select class="form-control" name="id_escuela" required>
                                    <option value="">Selecciona una escuela</option>
                                    <?php while ($escuela = $escuelas->fetch_assoc()) { ?>
                                        <option value="<?php echo $escuela['id_escuela']; ?>">
                                            <?php echo $escuela['nombre_escuela']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <!-- EVENTO -->
                            <div class="mb-3">
                                <label class="form-label">Evento</label>
                                <select class="form-control" name="id_evento" required>
                                    <option value="">Selecciona un evento</option>
                                    <?php while ($evento = $eventos->fetch_assoc()) { ?>
                                        <option value="<?php echo $evento['id_evento']; ?>">
                                            <?php echo $evento['nombre_evento']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">

                                <i class="fa-solid fa-user-plus"></i> Registrarme
                            </button>
                        </form>
                        <hr>
                        <div class="text-center">
                            <a href="../index.php">
                                <i class="fa-solid fa-arrow-left"></i> Volver al inicio
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>