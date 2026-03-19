<?php

session_start();
require_once "../config/conexion.php";

$correo = trim($_POST['correo']);
$password = trim($_POST['password']);

$sql = "SELECT * FROM responsable 
        WHERE correo='$correo' 
        AND contraseña='$password'";

$resultado = $conn->query($sql);

if($resultado->num_rows > 0){

    $datos = $resultado->fetch_assoc();

    $_SESSION['usuario'] = $datos['nombre'];
    $_SESSION['rol'] = $datos['rol'];

    header("Location: ../menu/menu.php");
    exit();

}else{

    header("Location: login.php?error=1");
    exit();

}

?>
