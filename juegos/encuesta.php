<?php
session_start();
require_once "../config/conexion.php";

/* Verificar si inició sesión [cite: 1322-1323] */
if (!isset($_SESSION['usuario'])) {
    header("Location: ../RegistroAdmin/login.php");
    exit();
}

// Validamos que el ID del juego venga en la URL y sea un número [cite: 1325]
$id_juego = isset($_GET['id_juego']) ? intval($_GET['id_juego']) : 0;

if ($id_juego === 0) {
    die("Error: No se ha seleccionado un juego válido para calificar.");
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Encuesta de Satisfacción</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* [cite: 1336-1349] */
        .stars i {
            font-size: 40px;
            cursor: pointer;
            color: #ccc;
            transition: 0.2s;
        }

        .stars i.active {
            color: gold;
            text-shadow: 0 0 10px rgba(255, 215, 0, 0.5);
        }

        .card {
            max-width: 500px;
            margin: auto;
            border-radius: 20px;
            border: none;
        }

        .btn-success {
            border-radius: 50px;
            padding: 12px;
            font-weight: bold;
        }
    </style>
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="card shadow-lg p-4">
            <h3 class="text-center mb-4">Encuesta de satisfacción</h3>

            <form id="formEncuesta" action="guardar_encuesta.php" method="POST">
                <input type="hidden" name="calificacion" id="calificacion" value="">
                <input type="hidden" name="id_juego" value="<?php echo $id_juego; ?>">

                <div class="text-center mb-4">
                    <label class="h5">¿Qué calificación le das?</label><br>
                    <div class="stars">
                        <i class="fas fa-star" onclick="calificar(1)"></i>
                        <i class="fas fa-star" onclick="calificar(2)"></i>
                        <i class="fas fa-star" onclick="calificar(3)"></i>
                        <i class="fas fa-star" onclick="calificar(4)"></i>
                        <i class="fas fa-star" onclick="calificar(5)"></i>
                    </div>
                    <p id="label-puntos" class="mt-2 text-muted font-weight-bold"></p>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">Comentario (Opcional)</label>
                    <textarea name="comentario" class="form-control" rows="3" placeholder="Cuéntanos tu experiencia..."></textarea>
                </div>

                <button type="button" onclick="procesarEncuesta()" class="btn btn-success btn-block shadow">Enviar mi opinión</button>
            </form>
        </div>
    </div>

    <script>
        let rating = 0;

        function calificar(num) {
            rating = num;
            const puntos = num * 2;
            document.getElementById("calificacion").value = puntos;
            document.getElementById("label-puntos").innerText = puntos + " / 10 puntos";

            let stars = document.querySelectorAll(".stars i");
            stars.forEach((star, index) => {
                star.classList.toggle("active", index < num);
            });
        }

        function procesarEncuesta() {
            if (rating === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: '¡Espera!',
                    text: 'Por favor, selecciona una calificación con las estrellas.',
                    confirmButtonColor: '#28a745'
                });
                return;
            }

            // Evidencia visual de envío exitoso
            Swal.fire({
                icon: 'success',
                title: '¡Enviado con éxito!',
                text: 'Gracias por ayudarnos a mejorar IntegraGames.',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            }).then(() => {
                // Envío manual del formulario después de la alerta
                document.getElementById("formEncuesta").submit();
            });
        }
    </script>
</body>

</html>