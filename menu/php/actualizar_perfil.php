<?php
session_start();
require_once "../../config/conexion.php";

/* VALIDAR SESIÓN */
if (!isset($_SESSION['usuario'])) {
    header("Location: ../../RegistroAdmin/login.php");
    exit();
}

/* VALIDAR MÉTODO */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../perfil.php");
    exit();
}

$usuarioActual = $_SESSION['usuario'];

/* OBTENER USUARIO */
$sqlUser = "SELECT * FROM responsable WHERE nombre='$usuarioActual'";
$res = mysqli_query($conn, $sqlUser);
$user = mysqli_fetch_assoc($res);

if (!$user) {
    die("Usuario no encontrado");
}

/* CONSERVAR DATOS */
$nuevoNombre = !empty($_POST['nombre']) ? $_POST['nombre'] : $user['nombre'];
$correo = !empty($_POST['correo']) ? $_POST['correo'] : $user['correo'];
$passwordNueva = !empty($_POST['password']) ? $_POST['password'] : null;
$actual = $_POST['actual'] ?? '';
$imagenNombre = $user['imagen'];

/* VALIDAR CONTRASEÑA */
if (!empty($passwordNueva)) {
    if (!(password_verify($actual, $user['contraseña']) || $actual === $user['contraseña'])) {
        echo "<script>
            alert('Contraseña incorrecta');
            window.history.back();
        </script>";
        exit();
    }
}

/* SUBIR IMAGEN */
if (!empty($_FILES['imagen']['name'])) {

    $nombreArchivo = time() . "_" . basename($_FILES['imagen']['name']);
    $ruta = "../../img/responsables/" . $nombreArchivo;

    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta)) {
        $imagenNombre = $nombreArchivo;
    }
}

/* UPDATE */
$sql = "UPDATE responsable SET 
            nombre='$nuevoNombre',
            correo='$correo',
            imagen='$imagenNombre'";

if (!empty($passwordNueva)) {
    $sql .= ", contraseña='$passwordNueva'";
}

$sql .= " WHERE nombre='$usuarioActual'";

/* EJECUTAR */
if (mysqli_query($conn, $sql)) {

    $_SESSION['usuario'] = $nuevoNombre;

    header("Location: ../perfil.php?ok=1");
    exit();

} else {
    echo "Error: " . mysqli_error($conn);
}