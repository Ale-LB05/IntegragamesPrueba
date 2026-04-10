<?php
session_start();
include("../config/conexion.php");

$mensaje = "";
$tipo = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['correo'])) {
    $correo = $conn->real_escape_string($_POST['correo']);

    $sql = "SELECT id_responsable, nombre FROM responsable WHERE correo = '$correo'";
    $res = $conn->query($sql);

    $mensaje = "Si el correo coincide con una cuenta activa, te hemos enviado las instrucciones para restablecer tu contraseña.";
    $tipo = "success";

    if ($res && $res->num_rows > 0) {
        $usuario = $res->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recuperar Contraseña | IntegraGames</title>
    <link rel="icon" href="../img/logo.png" type="image/png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #1e3c72, #2a5298);
            --accent-color: #00d2ff;
            --accent-hover: #0072ff;
        }

        body {
            background: var(--primary-gradient);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            overflow: hidden;
        }

        body::before {
            content: "";
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(0, 210, 255, 0.1);
            border-radius: 50%;
            top: 10%;
            left: 10%;
            z-index: -1;
            filter: blur(80px);
        }

        .card {
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            transition: box-shadow 0.3s ease, border-color 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2) !important;
            border-color: var(--accent-color);
        }

        .login-header h3 {
            font-weight: 700;
            color: #1e3c72;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .input-group-text {
            background-color: transparent;
            color: #2a5298;
            border-color: #dee2e6;
        }

        .form-control {
            padding: 12px;
            border-color: #dee2e6;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: var(--accent-color);
        }

        .btn-primary {
            background: linear-gradient(to right, var(--accent-color), var(--accent-hover));
            border: none;
            padding: 12px;
            border-radius: 12px;
            font-weight: 600;
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
            <div class="col-md-5 col-lg-4">
                <div class="card shadow-lg">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4 login-header">
                            <i class="fas fa-user-lock fa-3x mb-3 text-primary" style="color: #2a5298 !important;"></i>
                            <h3>Recuperación</h3>
                            <p class="text-muted small">Ingresa tu correo para restablecer tu contraseña.</p>
                        </div>

                        <form action="" method="POST" autocomplete="off">
                            <div class="mb-4">
                                <label class="form-label small fw-bold">Correo Electrónico Registrado</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control" name="correo" placeholder="ejemplo@utm.mx" required>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 mb-3 text-white">
                                <i class="fas fa-paper-plane me-2"></i> Enviar Enlace
                            </button>
                        </form>

                        <hr>
                        <div class="text-center">
                            <a href="login.php" class="back-link text-decoration-none">
                                <i class="fas fa-arrow-left me-1"></i> Volver al Login
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php if (!empty($mensaje)) { ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: '<?= $tipo ?>',
                    title: 'Proceso completado',
                    text: '<?= $mensaje ?>',
                    confirmButtonColor: '#1e3c72',
                    background: '#fff',
                    heightAuto: false,
                    customClass: {
                        popup: 'rounded-4'
                    }
                }).then(() => {
                    // Redirigir de vuelta al login después de leer el mensaje
                    window.location.href = 'login.php';
                });
            });
        </script>
    <?php } ?>

</body>

</html>