<?php 
include("../config/conexion.php");

/* INSERTAR */
if(isset($_POST['guardar'])){
    $telefono = preg_replace('/[^0-9]/', '', $_POST['telefono']);

    if(strlen($telefono) != 10){
        echo "<script>alert('El teléfono debe tener exactamente 10 números'); window.location='escuelas.php';</script>";
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO escuela(nombre_escuela, direccion, telefono) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $_POST['nombre'], $_POST['direccion'], $telefono);
    $stmt->execute();
    header("Location: escuelas.php");
}

/* ACTUALIZAR */
if(isset($_POST['actualizar'])){
    $telefono = preg_replace('/[^0-9]/', '', $_POST['telefono']);

    if(strlen($telefono) != 10){
        echo "<script>alert('El teléfono debe tener exactamente 10 números'); window.location='escuelas.php';</script>";
        exit;
    }

    $stmt = $conn->prepare("UPDATE escuela SET nombre_escuela=?, direccion=?, telefono=? WHERE id_escuela=?");
    $stmt->bind_param("sssi", $_POST['nombre'], $_POST['direccion'], $telefono, $_POST['id']);
    $stmt->execute();
    header("Location: escuelas.php");
}

/* CONSULTA */
$res = $conn->query("SELECT * FROM escuela");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Escuelas</title>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<style>
body{background:#eef4ff;font-family:'Segoe UI';}
.table thead{background:#3b82f6;color:white;}
.modal-header{background:#17a2b8;color:white;}

.label-form{
    font-weight:600;
    color:#6c757d;
    margin-bottom:5px;
}

.input-form{
    border-radius:8px;
    padding:10px;
    border:1px solid #ddd;
}

.input-form:focus{
    border-color:#3b82f6;
    box-shadow:0 0 5px rgba(59,130,246,0.3);
}

input:invalid{
    border-color:red;
}
</style>
</head>

<body>

<div class="container mt-4">

<h2>Registro de Escuelas</h2>

<div class="d-flex mb-3">
    <input type="text" id="buscador" class="form-control mr-2" placeholder="Buscar escuela...">

    <select id="orden" class="form-control w-25">
        <option value="asc">A - Z</option>
        <option value="desc">Z - A</option>
    </select>

    <button class="btn btn-success ml-2" data-toggle="modal" data-target="#modalAgregar">
        + Nueva
    </button>
</div>

<div class="table-responsive">
<table class="table table-bordered text-center" id="tablaEscuelas">

<thead>
<tr>
<th>ID</th>
<th>Nombre</th>
<th>Dirección</th>
<th>Teléfono</th>
<th>Acciones</th>
</tr>
</thead>

<tbody>

<?php if($res->num_rows == 0){ ?>
<tr><td colspan="5">No hay registros</td></tr>
<?php } ?>

<?php while($row = $res->fetch_assoc()){ ?>
<tr>
<td><?= $row['id_escuela'] ?></td>
<td><?= $row['nombre_escuela'] ?></td>
<td><?= $row['direccion'] ?></td>
<td><?= $row['telefono'] ?></td>

<td>
<button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editar<?= $row['id_escuela'] ?>">
Editar
</button>
</td>
</tr>

<!-- MODAL EDITAR -->
<div class="modal fade" id="editar<?= $row['id_escuela'] ?>">
<div class="modal-dialog">
<div class="modal-content">

<div class="modal-header">
<h5>Editar Escuela</h5>
<button class="close" data-dismiss="modal">&times;</button>
</div>

<form method="POST">
<div class="modal-body">

<input type="hidden" name="id" value="<?= $row['id_escuela'] ?>">

<div class="form-group">
<label class="label-form">Nombre</label>
<input type="text" name="nombre" class="form-control input-form"
value="<?= $row['nombre_escuela'] ?>" required>
</div>

<div class="form-group">
<label class="label-form">Dirección</label>
<textarea name="direccion" class="form-control input-form" rows="2" required><?= $row['direccion'] ?></textarea>
</div>

<div class="form-group">
<label class="label-form">Teléfono</label>
<input type="text" name="telefono" class="form-control input-form"
value="<?= $row['telefono'] ?>"
required pattern="[0-9]{10}" inputmode="numeric" maxlength="10"
title="Debe tener exactamente 10 números">
</div>

</div>

<div class="modal-footer">
<button name="actualizar" class="btn btn-info">Guardar cambios</button>
</div>
</form>

</div>
</div>
</div>

<?php } ?>

</tbody>
</table>
</div>

</div>

<!-- MODAL AGREGAR -->
<div class="modal fade" id="modalAgregar">
<div class="modal-dialog">
<div class="modal-content">

<div class="modal-header">
<h5>Agregar Escuela</h5>
<button class="close" data-dismiss="modal">&times;</button>
</div>

<form method="POST">
<div class="modal-body">

<div class="form-group">
<label class="label-form">Nombre</label>
<input type="text" name="nombre" class="form-control input-form" required>
</div>

<div class="form-group">
<label class="label-form">Dirección</label>
<textarea name="direccion" class="form-control input-form" rows="2" required></textarea>
</div>

<div class="form-group">
<label class="label-form">Teléfono</label>
<input type="text" name="telefono" class="form-control input-form"
required pattern="[0-9]{10}" inputmode="numeric" maxlength="10"
title="Debe tener exactamente 10 números">
</div>

</div>

<div class="modal-footer">
<button name="guardar" class="btn btn-success">Guardar</button>
</div>
</form>

</div>
</div>
</div>

<script>
let buscador = document.getElementById("buscador");
let orden = document.getElementById("orden");

buscador.addEventListener("keyup", filtrar);
orden.addEventListener("change", ordenar);

function filtrar(){
    let texto = buscador.value.toLowerCase();
    let filas = document.querySelectorAll("#tablaEscuelas tbody tr");

    filas.forEach(fila => {
        fila.style.display = fila.textContent.toLowerCase().includes(texto) ? "" : "none";
    });
}

function ordenar(){
    let tabla = document.querySelector("#tablaEscuelas tbody");
    let filas = Array.from(tabla.rows);

    filas.sort((a, b) => {
        let A = a.cells[1].innerText.toLowerCase();
        let B = b.cells[1].innerText.toLowerCase();

        return orden.value === "asc"
            ? A.localeCompare(B)
            : B.localeCompare(A);
    });

    filas.forEach(fila => tabla.appendChild(fila));
}

/* BLOQUEAR LETRAS EN TIEMPO REAL */
document.querySelectorAll('input[name="telefono"]').forEach(input => {
    input.addEventListener('input', function () {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
});
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>