<?php
session_start();
require_once "../config/conexion.php";

/* Verificar sesión */
if (!isset($_SESSION['usuario'])) {
    header("Location: ../RegistroAdmin/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger datos 
    $calificacion = $_POST['calificacion'];
    $comentario = $_POST['comentario'];
    $id_juego = intval($_POST['id_juego']); // Forzamos que sea un número
    $id_participante = $_SESSION['id_usuario'] ?? null;
    $fecha_actual = date("Y-m-d");

    // Validar que el participante existe para evitar el error de FK
    if ($id_participante && $id_juego > 0) {
        // Insertar en la base de datos
        $stmt = $conn->prepare("INSERT INTO satisfaccion (calificacion, comentario, fecha, id_participante, id_juego) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issii", $calificacion, $comentario, $fecha_actual, $id_participante, $id_juego);

        if ($stmt->execute()) {
            // Éxito: Redirigir al menú 
            header("Location: ../menu/menu.php");
            exit();
        } else {
            echo "Error de ejecución: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error: Datos de sesión o juego no encontrados.";
    }
}
