<?php
session_start();
session_destroy(); // Elimina sesiones previas para evitar conflictos de roles [cite: 2547]
session_start();

require_once "../config/conexion.php";

/* OBTENER DATOS DEL FORMULARIO [cite: 2550-2552] */
$nombre = $_POST['nombre'];
$edad = $_POST['edad'];
$id_evento = $_POST['id_evento'];
$id_escuela = $_POST['id_escuela'];

/* INSERTAR EN LA TABLA PARTICIPANTE [cite: 2553-2554] */
$sql = "INSERT INTO participante (nombre, edad, id_evento, id_escuela)
        VALUES ('$nombre', '$edad', '$id_evento', '$id_escuela')";

if ($conn->query($sql)) {

    /* PASO CLAVE: Capturar el ID autogenerado por la base de datos */
    $id_recien_creado = $conn->insert_id; 

    /* CREAR SESIÓN COMO PARTICIPANTE [cite: 2556-2558] */
    $_SESSION['id_usuario'] = $id_recien_creado; // Este ID es el que usará guardar_encuesta.php
    $_SESSION['usuario'] = $nombre;
    $_SESSION['rol'] = 'participante';

    /* REDIRIGIR AL MENÚ ]0 */
    header("Location: ../menu/menu.php");
    exit();

} else {
    echo "Error al registrar participante: " . $conn->error;
}
?>