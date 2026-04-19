<?php
session_start();
session_destroy();
require_once "../config/conexion.php";
$escuelas = $conn->query("SELECT * FROM escuela");
// Consulta filtrada: Solo eventos con la fecha de HOY
$eventos = $conn->query("SELECT * FROM evento WHERE fecha = CURDATE()");
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro Alumno | IntegraGames</title>
    <link rel="icon" href="../img/logo.png" type="image/png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #1e3c72, #2a5298);
            --accent-color: #00d2ff;
            --accent-hover: #0072ff;
        }

        body {
            background: var(--primary-gradient);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 20px;
        }

        /* Elemento decorativo de fondo */
        body::before {
            content: "";
            position: absolute;
            width: 350px;
            height: 350px;
            background: rgba(0, 210, 255, 0.15);
            border-radius: 50%;
            top: -50px;
            right: -50px;
            z-index: -1;
            filter: blur(80px);
        }

        .card {
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.98);
            transition: box-shadow 0.3s ease, border-color 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25) !important;
            border-color: var(--accent-color);
        }

        .header-title h3 {
            font-weight: 700;
            color: #1e3c72;
            margin-top: 10px;
        }

        .form-label {
            font-size: 0.85rem;
            color: #495057;
        }

        /* Estilo de inputs con iconos */
        .input-group-text {
            background-color: transparent;
            color: #2a5298;
            border-right: none;
        }

        .form-control,
        .form-select {
            border-left: none;
            padding: 10px;
        }

        .form-control:focus,
        .form-select:focus {
            box-shadow: none;
            border-color: var(--accent-color);
        }

        .input-group:focus-within .input-group-text,
        .input-group:focus-within .form-control,
        .input-group:focus-within .form-select {
            border-color: var(--accent-color);
        }

        /* CORRECCIÓN PARA MANTENER SELECT2 EN LA MISMA LÍNEA */
        .input-group > .select2-container {
            flex: 1 1 auto;
            width: 1% !important;
        }

        .select2-container--bootstrap-5 .select2-selection {
            border-left: none !important;
            border-top-left-radius: 0 !important;
            border-bottom-left-radius: 0 !important;
            min-height: calc(1.5em + 1rem + 2px);
            padding-top: 5px;
            padding-bottom: 5px;
            border-color: #dee2e6;
        }

        .input-group:focus-within .select2-container--bootstrap-5 .select2-selection {
            border-color: var(--accent-color) !important;
            box-shadow: none !important;
        }

        .btn-primary {
            background: linear-gradient(to right, var(--accent-color), var(--accent-hover));
            border: none;
            padding: 12px;
            border-radius: 12px;
            font-weight: 600;
            margin-top: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 210, 255, 0.3);
        }

        .btn-primary:hover {
            transform: scale(1.02);
            box-shadow: 0 6px 20px rgba(0, 114, 255, 0.4);
        }

        .back-link {
            color: #6c757d;
            font-size: 0.9rem;
            transition: color 0.3s;
        }

        .back-link:hover {
            color: var(--accent-hover);
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-lg">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4 header-title">
                            <img src="../img/logo.png" alt="Logo" style="height: 70px; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));">
                            <h3>Registro de Alumno</h3>
                            <p class="text-muted small">¡Bienvenido a IntegraGames!</p>
                        </div>

                        <form action="guardar_alumno.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nombre Completo</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" name="nombre" placeholder="Tu nombre" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Edad</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-calendar-day"></i></span>
                                    <input type="number" class="form-control" name="edad" placeholder="¿Cuántos años tienes?" min="1" onkeypress="return event.charCode >= 48 && event.charCode <= 57" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Escuela de Procedencia</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-school"></i></span>
                                    <select class="form-select" name="id_escuela" id="selectEscuela" required>
                                        <option value="">Buscar escuela...</option>
                                        <?php while ($escuela = $escuelas->fetch_assoc()) { ?>
                                            <option value="<?php echo $escuela['id_escuela']; ?>">
                                                <?php echo $escuela['nombre_escuela']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Evento del Día</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-trophy"></i></span>
                                    <select class="form-select" name="id_evento" required>
                                        <?php if ($eventos->num_rows > 0) { ?>
                                            <option value="">Selecciona el evento</option>
                                            <?php while ($evento = $eventos->fetch_assoc()) { ?>
                                                <option value="<?php echo $evento['id_evento']; ?>">
                                                    <?php echo $evento['nombre_evento']; ?>
                                                </option>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <option value="">No hay eventos programados hoy</option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 mb-3">
                                <i class="fa-solid fa-user-plus me-2"></i> Ingresar
                            </button>
                        </form>

                        <hr class="opacity-25">
                        <div class="text-center">
                            <a href="../index.php" class="back-link text-decoration-none">
                                <i class="fa-solid fa-arrow-left me-1"></i> Volver al inicio
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#selectEscuela').select2({
                theme: 'bootstrap-5', 
                placeholder: "Buscar escuela...",
                width: '100%',
                language: {
                    noResults: function() {
                        return "No se encontró ninguna escuela";
                    }
                }
            }).on('select2:open', function () {
                // Esto pone el texto gris de pista dentro del buscador de texto cuando haces clic
                document.querySelector('.select2-search__field').placeholder = 'Buscar escuela...';
            });
        });
    </script>
</body>

</html>