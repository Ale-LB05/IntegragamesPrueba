<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login IntegraGames</title>

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
            <div class="col-md-5">
                <div class="card shadow-lg">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h3>IntegraGames</h3>
                            <p class="text-muted">Panel de encargado</p>

                        </div>
                        <form id="loginForm" action="validar_login.php" method="POST" autocomplete="off">
                            <div class="mb-3">
                                <label class="form-label">Correo</label>
                                <input
                                    type="email"
                                    class="form-control"
                                    name="correo"
                                    placeholder="Ingresa tu correo"
                                    required>

                            </div>
                            <div class="mb-3">
                                <label class="form-label">Contraseña</label>
                                <input
                                    type="password"
                                    class="form-control"
                                    name="password"
                                    placeholder="Ingresa tu contraseña"
                                    required>
                            </div>
                            <?php if (isset($_GET['error'])) { ?>

                                <div class="alert alert-danger text-center">
                                    Correo o contraseña incorrectos
                                </div>
                            <?php } ?>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa-solid fa-right-to-bracket"></i>
                                Iniciar sesión
                            </button>
                        </form>
                        <hr>
                        <div class="text-center">
                            <a href="../index.php" class="text-decoration-none">
                                <i class="fa-solid fa-arrow-left"></i>
                                Volver al inicio
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../js/login.js"></script>

</body>

</html>