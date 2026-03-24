<?php
session_start();   
require_once "../config/conexion.php";

$correo = trim($_POST['correo']);
$password = trim($_POST['password']);

/* BUSCAR EN RESPONSABLE */
$sql = "SELECT * FROM responsable 
        WHERE correo='$correo' 
        AND contraseña='$password'";

$res = $conn->query($sql);

if ($res->num_rows > 0) {

    $datos = $res->fetch_assoc();

    $_SESSION['usuario'] = $datos['nombre'];
    $_SESSION['rol'] = strtolower($datos['rol']); // CLAVE

    header("Location: ../menu/menu.php");
    exit();
}

/* SI NO EXISTE */
header("Location: login.php?error=1");
exit();
?>