<?php
session_start();
require_once "../../config/conexion.php";

if (!isset($_SESSION['usuario'])) {
    header("Location: ../../RegistroAdmin/login.php");
    exit();
}

$usuarioActual = $_SESSION['usuario'];

$nuevoNombre = $_POST['nombre'];
$correo = $_POST['correo'];
$password = $_POST['password'];
$actual = $_POST['actual'];

// Obtener contraseña actual de BD
$sqlUser = "SELECT `contraseña` FROM responsable WHERE nombre='$usuarioActual'";
$res = mysqli_query($conn, $sqlUser);
$user = mysqli_fetch_assoc($res);

$passBD = $user['contraseña'];

//  VALIDACIÓN INTELIGENTE (soporta texto plano y encriptado)
$esValida = false;

if (password_verify($actual, $passBD)) {
    $esValida = true; // ya está encriptada
} elseif ($actual === $passBD) {
    $esValida = true; // texto plano
}

//  Si quiere cambiar contraseña pero no coincide
if (!empty($password) && !$esValida) {
    echo "<script>
        alert(' Contraseña actual incorrecta');
        window.history.back();
    </script>";
    exit();
}

//  SI CAMBIA CONTRASEÑA
if (!empty($password)) {

    //  Guardar contraseña en texto plano (SIN HASH)
    $sql = "UPDATE responsable 
            SET nombre='$nuevoNombre', correo='$correo', `contraseña`='$password'
            WHERE nombre='$usuarioActual'";

} else {

    // Solo actualiza datos
    $sql = "UPDATE responsable 
            SET nombre='$nuevoNombre', correo='$correo'
            WHERE nombre='$usuarioActual'";
}

// Ejecutar
if (mysqli_query($conn, $sql)) {

    $_SESSION['usuario'] = $nuevoNombre;

    echo "<script>
        alert(' Perfil actualizado correctamente');
        window.location.href='../perfil.php';
    </script>";

} else {
    echo "Error: " . mysqli_error($conn);
}