<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | IntegraGames</title>
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

        /* Estilo para el botón de ver contraseña */
        .btn-outline-secondary {
            border-color: #dee2e6;
            border-left: none;
            color: #6c757d;
        }

        .btn-outline-secondary:hover {
            background: transparent;
            color: var(--accent-hover);
            border-color: #dee2e6;
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
                <div class="card shadow-2xl">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4 login-header">
                            <img src="../img/logo.png" alt="Logo" class="mb-3" style="height: 80px; filter: drop-shadow(0 2px 5px rgba(0,0,0,0.2));">
                            <h3>IntegraGames</h3>
                            <p class="text-muted small">Panel de Encargado</p>
                        </div>

                        <form id="loginForm" action="validar_login.php" method="POST" autocomplete="off">
                            <div class="mb-4">
                                <label class="form-label small fw-bold">Correo Electrónico</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control" name="correo" placeholder="ejemplo@utm.mx" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-bold">Contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" id="passwordInput" class="form-control" name="password" placeholder="••••••••" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                                        <i id="toggleIcon" class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 mb-3">
                                <i class="fa-solid fa-right-to-bracket me-2"></i> Iniciar Sesión
                            </button>
                        </form>

                        <hr>
                        <div class="text-center">
                            <a href="../index.php" class="back-link text-decoration-none">
                                <i class="fa-solid fa-house me-1"></i> Volver al inicio
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('passwordInput');
            const toggleIcon = document.getElementById('toggleIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php if (isset($_GET['error'])) { ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Acceso Denegado',
                    text: 'Las credenciales ingresadas no son válidas.',
                    confirmButtonColor: '#1e3c72',
                    background: '#fff',
                    heightAuto: false,
                    customClass: {
                        popup: 'rounded-4'
                    }
                });
            });
        </script>
    <?php } ?>

</body>

</html>