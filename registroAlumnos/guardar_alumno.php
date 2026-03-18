<?php

require_once "../config/conexion.php";

$nombre = $_POST['nombre'];
$edad = $_POST['edad'];
$id_evento = $_POST['id_evento'];
$id_escuela = $_POST['id_escuela'];

$sql = "INSERT INTO participante (nombre, edad, id_evento, id_escuela)
        VALUES ('$nombre','$edad','$id_evento','$id_escuela')";

if($conn->query($sql)){

    header("Location: ../menu/menu.php");
    exit();

}else{

    echo "Error al registrar participante";

}

?>
