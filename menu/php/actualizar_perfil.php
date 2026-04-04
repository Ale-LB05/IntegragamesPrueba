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
    header("Location: ../perfil.php?msg=Usuario no encontrado&tipo=error");
    exit();
}

/* CONSERVAR DATOS */
$nuevoNombre = !empty($_POST['nombre']) ? mysqli_real_escape_string($conn, $_POST['nombre']) : $user['nombre'];
$correo = !empty($_POST['correo']) ? mysqli_real_escape_string($conn, $_POST['correo']) : $user['correo'];
$passwordNueva = !empty($_POST['password']) ? $_POST['password'] : null;
$actual = $_POST['actual'] ?? '';
$imagenNombre = $user['imagen'];

/* VALIDAR CONTRASEÑA (Solo si se intenta cambiar) */
if (!empty($passwordNueva)) {
    // Validamos contra hash o texto plano (según tu DB actual)
    if (!(password_verify($actual, $user['contraseña']) || $actual === $user['contraseña'])) {
        header("Location: ../perfil.php?msg=La contraseña actual es incorrecta&tipo=error");
        exit();
    }
}

/* SUBIR IMAGEN */
if (!empty($_FILES['imagen']['name'])) {
    $directorio = "../../img/responsables/";

    // Crear carpeta si no existe
    if (!is_dir($directorio)) {
        mkdir($directorio, 0777, true);
    }

    $nombreArchivo = time() . "_" . basename($_FILES['imagen']['name']);
    $ruta = $directorio . $nombreArchivo;

    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta)) {
        // Borrar imagen anterior si no es la de por defecto
        if (!empty($user['imagen']) && $user['imagen'] !== 'sinFoto.jpg' && file_exists($directorio . $user['imagen'])) {
            unlink($directorio . $user['imagen']);
        }
        $imagenNombre = $nombreArchivo;
    }
}

/* CONSTRUIR UPDATE */
$sql = "UPDATE responsable SET 
            nombre='$nuevoNombre',
            correo='$correo',
            imagen='$imagenNombre'";

if (!empty($passwordNueva)) {
    // Si usas hashing en el login, aquí deberías usar password_hash()
    $sql .= ", contraseña='$passwordNueva'";
}

$sql .= " WHERE nombre='$usuarioActual'";

/* EJECUTAR */
if (mysqli_query($conn, $sql)) {
    // Actualizamos la sesión con el nuevo nombre si cambió
    $_SESSION['usuario'] = $nuevoNombre;

    header("Location: ../perfil.php?msg=Perfil actualizado correctamente&tipo=success");
    exit();
} else {
    $error = mysqli_error($conn);
    header("Location: ../perfil.php?msg=Error al actualizar: $error&tipo=error");
    exit();
}