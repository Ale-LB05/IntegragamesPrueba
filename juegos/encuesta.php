<?php
session_start();
require_once "../config/conexion.php";

if (!isset($_SESSION['usuario'])) {
    header("Location: ../RegistroAdmin/login.php");
    exit();
}

$id_juego = $_GET['id_juego'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Encuesta</title>

<link href="../css/styles.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<style>
.stars i {
    font-size: 35px;
    cursor: pointer;
    color: #ccc;
}
.stars i.active {
    color: gold;
}
</style>
</head>

<body class="bg-light">

<div class="container mt-5">
<div class="card shadow p-4">

<h3 class="text-center mb-4">Encuesta de satisfacción</h3>

<form action="guardar_encuesta.php" method="POST" onsubmit="return validar();">

<input type="hidden" name="calificacion" id="calificacion">
<input type="hidden" name="id_juego" value="<?php echo $id_juego; ?>">

<!--  ESTRELLAS -->
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

<!-- COMENTARIO -->
<div class="form-group">
    <label>Comentario</label>
    <textarea name="comentario" class="form-control" placeholder="Escribe tu opinión..."></textarea>
</div>

<button class="btn btn-success btn-block">Enviar encuesta</button>

</form>

</div>
</div>

<script>
let rating = 0;

function calificar(num){
    rating = num;
    document.getElementById("calificacion").value = num;

    let stars = document.querySelectorAll(".stars i");
    stars.forEach((star, index)=>{
        star.classList.toggle("active", index < num);
    });
}

function validar(){
    if(rating === 0){
        alert("Selecciona una calificación ");
        return false;
    }
    return true;
}
</script>

</body>
</html>