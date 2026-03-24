<?php
session_start();
session_destroy(); //elimina cualquier sesión previa (admin, etc.)
session_start();

require_once "../config/conexion.php";

/* OBTENER DATOS */
$nombre = $_POST['nombre'];
$edad = $_POST['edad'];
$id_evento = $_POST['id_evento'];
$id_escuela = $_POST['id_escuela'];

/* INSERTAR */
$sql = "INSERT INTO participante (nombre, edad, id_evento, id_escuela)
        VALUES ('$nombre','$edad','$id_evento','$id_escuela')";

if ($conn->query($sql)) {

    /*CREAR SESIÓN COMO PARTICIPANTE */
    $_SESSION['usuario'] = $nombre;
    $_SESSION['rol'] = 'participante';

    /* REDIRIGIR AL MENÚ */
    header("Location: ../menu/menu.php");
    exit();

} else {

    echo "Error al registrar participante: " . $conn->error;

}
?>