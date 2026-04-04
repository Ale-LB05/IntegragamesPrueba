<?php
session_start();
require_once "../config/conexion.php";

if (!isset($_SESSION['usuario'])) {
    header("Location: ../RegistroAdmin/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger datos del formulario
    $calificacion = $_POST['calificacion'];
    $comentario = $_POST['comentario'];
    $id_juego = $_POST['id_juego'];

    // Obtener el ID del participante desde la sesión 
    // (Asegúrate de que 'id_usuario' sea la clave correcta en tu sesión)
    $id_participante = $_SESSION['id_usuario'] ?? null;

    // Preparar la consulta para evitar errores de FK y SQL
    $stmt = $conexion->prepare("INSERT INTO satisfaccion (calificacion, comentario, id_participante, id_juego) VALUES (?, ?, ?, ?)");

    $stmt->bind_param("issi", $calificacion, $comentario, $id_participante, $id_juego);

    if ($stmt->execute()) {
        echo "<script>
                alert('¡Gracias por tu opinión!');
                window.location.href='../menu/menu.php';
              </script>";
    } else {
        // Si sale error aquí, es porque el id_juego o id_participante no existen en sus tablas originales
        echo "Error al guardar: " . $stmt->error;
    }

    $stmt->close();
    $conexion->close();
} else {
    header("Location: encuesta.php");
}
