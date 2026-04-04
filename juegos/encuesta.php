<?php
session_start();
require_once "../config/conexion.php";

if (!isset($_SESSION['usuario'])) {
    header("Location: ../RegistroAdmin/login.php");
    exit();
}

// Validamos que el ID del juego venga en la URL y sea un número
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
    <style>
        .stars i {
            font-size: 35px;
            cursor: pointer;
            color: #ccc;
            transition: 0.2s;
        }

        .stars i.active {
            color: gold;
        }

        .card {
            max-width: 500px;
            margin: auto;
            border-radius: 15px;
        }
    </style>
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="card shadow p-4">
            <h3 class="text-center mb-4">Encuesta de satisfacción</h3>

            <form action="guardar_encuesta.php" method="POST" onsubmit="return validar();">
                <input type="hidden" name="calificacion" id="calificacion" value="">
                <input type="hidden" name="id_juego" value="<?php echo $id_juego; ?>">

                <div class="text-center mb-4">
                    <label>¿Qué calificación le das?</label><br>
                    <div class="stars">
                        <i class="fas fa-star" onclick="calificar(1)"></i>
                        <i class="fas fa-star" onclick="calificar(2)"></i>
                        <i class="fas fa-star" onclick="calificar(3)"></i>
                        <i class="fas fa-star" onclick="calificar(4)"></i>
                        <i class="fas fa-star" onclick="calificar(5)"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label>Comentario (Opcional)</label>
                    <textarea name="comentario" class="form-control" rows="3" placeholder="Escribe tu opinión..."></textarea>
                </div>

                <button type="submit" class="btn btn-success btn-block">Enviar encuesta</button>
                <a href="../menu/menu.php" class="btn btn-link btn-block text-muted">Volver al menú</a>
            </form>
        </div>
    </div>

    <script>
        let rating = 0;

        function calificar(num) {
            rating = num;
            document.getElementById("calificacion").value = num;
            let stars = document.querySelectorAll(".stars i");
            stars.forEach((star, index) => {
                star.classList.toggle("active", index < num);
            });
        }

        function validar() {
            if (rating === 0) {
                alert("Por favor, selecciona una calificación con las estrellas.");
                return false;
            }
            return true;
        }
    </script>
</body>

</html>