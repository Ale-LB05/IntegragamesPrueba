<?php
session_start();
require_once "../config/conexion.php";

$usuario = $_SESSION['usuario'];

// Obtener id_participante
$sqlUser = "SELECT id_participante FROM participante WHERE nombre='$usuario'";
$resUser = mysqli_query($conn, $sqlUser);
$user = mysqli_fetch_assoc($resUser);

$id_participante = $user['id_participante'];

// Datos
$calificacion = $_POST['calificacion'];
$comentario = $_POST['comentario'];
$id_juego = $_POST['id_juego'];

// Insertar
$sql = "INSERT INTO satisfaccion 
(calificacion, comentario, id_participante, id_juego)
VALUES 
('$calificacion','$comentario','$id_participante','$id_juego')";

if (mysqli_query($conn, $sql)) {

    echo "<script>
        alert('Gracias por tu opinión');
        window.location.href='../menu.php';
    </script>";

} else {
    echo "Error: " . mysqli_error($conn);
}