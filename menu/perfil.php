<?php
session_start();
require_once "../config/conexion.php";

if (!isset($_SESSION['usuario'])) {
    header("Location: ../RegistroAdmin/login.php");
    exit();
}

$usuario = $_SESSION['usuario'];

$sql = "SELECT nombre, correo FROM responsable WHERE nombre='$usuario'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Perfil</title>

<link href="../css/styles.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<style>
body {
    background: #eef4ff;
}

/* Card */
.card {
    border-radius: 15px;
    border: none;
}

/* Título */
h3 {
    color: #3b82f6;
    font-weight: bold;
}

/* Inputs */
.form-control {
    border-radius: 10px;
    border: 1px solid #d1d5db;
}

/* Botón principal */
.btn-primary {
    background: #3b82f6;
    border: none;
}

.btn-primary:hover {
    background: #2563eb;
}

/* Botón secundario */
.btn-secondary {
    background: #6c757d;
    border: none;
}

.btn-secondary:hover {
    background: #5a6268;
}

/* Icono ojo */
.eye {
    position:absolute;
    right:10px;
    top:35px;
    cursor:pointer;
    color: #3b82f6;
}

/* Sombra elegante */
.card.shadow {
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
}
</style>

</head>

<body>

<div class="container mt-5">
<div class="card shadow">
<div class="card-body">

<h3 class="mb-4">👤 Perfil del Responsable</h3>

<form action="php/actualizar_perfil.php" method="POST" onsubmit="return validar();">

<!-- NOMBRE -->
<div class="form-group mb-3">
<label>Nombre</label>
<input type="text" name="nombre" class="form-control"
value="<?php echo $user['nombre']; ?>" required>
</div>

<!-- CORREO -->
<div class="form-group mb-3">
<label>Correo</label>
<input type="email" name="correo" class="form-control"
value="<?php echo $user['correo']; ?>" required>
</div>

<!-- PASSWORD ACTUAL -->
<div class="form-group position-relative mb-3">
<label>Contraseña actual</label>
<input type="password" id="actual" name="actual" class="form-control">
<i class="fas fa-eye eye" onclick="toggle('actual', this)"></i>
</div>

<!-- NUEVA PASSWORD -->
<div class="form-group position-relative mb-3">
<label>Nueva contraseña</label>
<input type="password" id="nueva" name="password" class="form-control">
<i class="fas fa-eye eye" onclick="toggle('nueva', this)"></i>
</div>

<button class="btn btn-primary w-100 mb-2">Actualizar perfil</button>
<a href="../menu.php" class="btn btn-secondary w-100">Volver</a>

</form>

</div>
</div>
</div>

<script>
// Mostrar contraseña
function toggle(id, icon){
    let input = document.getElementById(id);
    if(input.type === "password"){
        input.type = "text";
        icon.classList.replace("fa-eye","fa-eye-slash");
    } else {
        input.type = "password";
        icon.classList.replace("fa-eye-slash","fa-eye");
    }
}

// Validación
function validar(){
    let nueva = document.getElementById("nueva").value;
    let actual = document.getElementById("actual").value;

    if(nueva !== "" && actual === ""){
        alert("Debes ingresar tu contraseña actual");
        return false;
    }
    return true;
}
</script>

</body>
</html>