<?php
session_start();
include("../config/conexion.php");

/* ERRORES */
error_reporting(E_ALL);
ini_set('display_errors', 1);

/* VALIDAR SESIÓN */
if (!isset($_SESSION['usuario'])) {
    header("Location: ../RegistroAdmin/login.php");
    exit();
}
$rol = $_SESSION['rol'];

if (!isset($conn)) {
    die("Error: conexión a BD no definida.");
}

$hoy = date("Y-m-d");
$mensaje = "";
$tipo = "";

/* 1. OBTENER TODOS LOS RESPONSABLES */
$res_responsables = $conn->query("SELECT id_responsable, nombre FROM responsable ORDER BY nombre ASC");
$responsables = [];
if ($res_responsables) {
    while ($r = $res_responsables->fetch_assoc()) {
        $responsables[] = $r;
    }
}
$json_responsables = json_encode($responsables);

/* 2. OBTENER LAS OCUPACIONES FUTURAS (Para la validación dinámica) */
$sql_ocupaciones = "SELECT er.id_responsable, e.fecha, e.nombre_evento, e.id_evento 
                    FROM evento_responsable er 
                    JOIN evento e ON er.id_evento = e.id_evento 
                    WHERE e.fecha >= '$hoy'";
$res_ocupaciones = $conn->query($sql_ocupaciones);
$ocupaciones = [];
if ($res_ocupaciones) {
    while ($row = $res_ocupaciones->fetch_assoc()) {
        $fecha = $row['fecha'];
        if (!isset($ocupaciones[$fecha])) {
            $ocupaciones[$fecha] = [];
        }
        $ocupaciones[$fecha][] = [
            'id_responsable' => $row['id_responsable'],
            'nombre_evento' => $row['nombre_evento'],
            'id_evento' => $row['id_evento']
        ];
    }
}
$json_ocupaciones = json_encode($ocupaciones);

/* ACCIONES (CREAR, EDITAR, ELIMINAR, ASIGNAR) */

/* CREAR EVENTO */
if (isset($_POST['crear'])) {
    $nombre = $conn->real_escape_string($_POST['nombre_evento']);
    $fecha = $conn->real_escape_string($_POST['fecha']);
    $hora = !empty($_POST['hora']) ? $conn->real_escape_string($_POST['hora']) : "00:00:00";
    $lugar = $conn->real_escape_string($_POST['lugar']);
    $ubicacion = $conn->real_escape_string($_POST['ubicacion']);
    $observaciones = $conn->real_escape_string($_POST['observaciones']);

    $imagenNombre = "";
    if (!empty($_FILES['imagen']['name'])) {
        $directorio = "../img/eventos/";
        if (!is_dir($directorio)) {
            mkdir($directorio, 0777, true);
        }
        $imagenNombre = time() . "_" . basename($_FILES['imagen']['name']);
        move_uploaded_file($_FILES['imagen']['tmp_name'], $directorio . $imagenNombre);
    }

    $sql = "INSERT INTO evento (nombre_evento, fecha, hora, lugar, ubicacion, observaciones, imagen)
            VALUES ('$nombre', '$fecha', '$hora', '$lugar', '$ubicacion', '$observaciones', '$imagenNombre')";

    if ($conn->query($sql)) {
        $mensaje = "¡Evento creado con éxito!";
        $tipo = "success";
    } else {
        $mensaje = "Error al guardar: " . $conn->error;
        $tipo = "error";
    }
}

/* EDITAR EVENTO */
if (isset($_POST['editar'])) {
    $id = intval($_POST['id_evento']);
    $verificar = $conn->query("SELECT fecha, imagen FROM evento WHERE id_evento='$id'");
    $dato = $verificar->fetch_assoc();

    if ($dato['fecha'] < $hoy) {
        $mensaje = "No se pueden editar eventos pasados";
        $tipo = "error";
    } else {
        $nombre = $_POST['nombre_evento'];
        $fecha = $_POST['fecha'];
        $hora = $_POST['hora'];
        $lugar = $_POST['lugar'];
        $ubicacion = $_POST['ubicacion'];
        $observaciones = $_POST['observaciones'];

        $imagenNombre = $dato['imagen'];

        if (!empty($_FILES['imagen']['name'])) {
            $imagenNombre = time() . "_" . $_FILES['imagen']['name'];
            move_uploaded_file($_FILES['imagen']['tmp_name'], "../img/eventos/" . $imagenNombre);
        }

        $sql = "UPDATE evento SET 
                nombre_evento='$nombre',
                fecha='$fecha',
                hora='$hora',
                lugar='$lugar',
                ubicacion='$ubicacion',
                observaciones='$observaciones',
                imagen='$imagenNombre'
                WHERE id_evento='$id'";

        if ($conn->query($sql)) {
            $mensaje = "Evento actualizado correctamente";
            $tipo = "success";
        } else {
            $mensaje = "Error al editar el evento";
            $tipo = "error";
        }
    }
}

/* ELIMINAR EVENTO */
if (isset($_POST['eliminar'])) {
    $id = intval($_POST['id_evento']);
    $verificar = $conn->query("SELECT fecha FROM evento WHERE id_evento='$id'");
    $dato = $verificar->fetch_assoc();

    if ($dato['fecha'] >= $hoy) {
        $conn->query("DELETE FROM evento_responsable WHERE id_evento='$id'");

        if ($conn->query("DELETE FROM evento WHERE id_evento='$id'")) {
            $mensaje = "Evento eliminado correctamente";
            $tipo = "success";
        } else {
            $mensaje = "Error al eliminar";
            $tipo = "error";
        }
    } else {
        $mensaje = "No puedes eliminar eventos pasados";
        $tipo = "error";
    }
}

if (isset($_POST['asignar_responsable'])) {
    $id_evento = intval($_POST['id_evento']);
    $id_resp = intval($_POST['id_responsable']);

    $conn->query("DELETE FROM evento_responsable WHERE id_evento='$id_evento'");

    if ($id_resp > 0) {
        $conn->query("INSERT INTO evento_responsable (id_evento, id_responsable) VALUES ('$id_evento', '$id_resp')");
        $mensaje = "Responsable asignado correctamente";
    } else {
        $mensaje = "Se ha quitado al responsable del evento";
    }

    $tipo = "success";
}

$sqlActivos = "SELECT e.*, er.id_responsable, r.nombre AS nombre_responsable 
               FROM evento e 
               LEFT JOIN evento_responsable er ON e.id_evento = er.id_evento 
               LEFT JOIN responsable r ON er.id_responsable = r.id_responsable 
               WHERE e.fecha >= '$hoy' ORDER BY e.fecha ASC";

$sqlPasados = "SELECT e.*, er.id_responsable, r.nombre AS nombre_responsable 
               FROM evento e 
               LEFT JOIN evento_responsable er ON e.id_evento = er.id_evento 
               LEFT JOIN responsable r ON er.id_responsable = r.id_responsable 
               WHERE e.fecha < '$hoy' ORDER BY e.fecha DESC";
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <?php include("../menu/php/encabezado.php"); ?>
    <link rel="icon" href="../img/logo.png">
    <link href="../css/styles.css" rel="stylesheet">
    <style>
        body { background: #eef4ff; }

        .text-primary { color: #4e73df !important; }
        .text-secondary { color: #858796 !important; }
        
        .bg-primary { background-color: #4e73df !important; }

        .rounded-4 { border-radius: 1rem !important; }
        .rounded-top-4 { border-top-left-radius: 1rem !important; border-top-right-radius: 1rem !important; }
        
        .evento-card {
            display: flex;
            background: #ffffff;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            border-left: 6px solid #4e73df;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            position: relative;
        }

        .evento-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        .evento-card.pasado {
            border-left-color: #858796;
            background: #f8f9fc;
            opacity: 0.9;
        }

        .evento-card.pasado .img-evento {
            filter: grayscale(100%);
            opacity: 0.8;
        }

        .img-evento {
            width: 160px; /* Aumentado desde 110px */
            height: 160px; /* Aumentado desde 110px */
            object-fit: cover;
            border-radius: 12px;
            border: 1px solid #e3e6f0;
            flex-shrink: 0;
        }

        .acciones-evento {
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 8px;
            border-left: 1px solid #e3e6f0;
            padding-left: 20px;
            min-width: 60px;
        }

        @media (max-width: 768px) {
            .evento-card {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
            .img-evento {
                width: 100%;
                max-width: 250px;
                height: 200px;
                margin-bottom: 15px;
            }
            .flex-grow-1.mx-3 {
                margin-left: 0 !important;
                margin-right: 0 !important;
            }
            .d-flex.flex-wrap {
                justify-content: center;
            }
            .acciones-evento {
                flex-direction: row;
                border-left: none;
                border-top: 1px solid #e3e6f0;
                padding-left: 0;
                padding-top: 15px;
                margin-top: 15px;
                width: 100%;
            }
        }

        /* Estilos Select Dinámico */
        optgroup[label="DISPONIBLES"] { color: #1cc88a; font-weight: bold; }
        optgroup[label="OCUPADOS EN OTROS EVENTOS"] { color: #e74a3b; font-weight: bold; }
        option { color: #333; font-weight: normal; font-style: normal; }
    </style>
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include("../menu/php/menuLateral.php"); ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include("../menu/php/barraSuperior.php"); ?>
                <div class="container-fluid">
                    
                    <div class="d-sm-flex align-items-center justify-content-between mb-4 flex-wrap">
                        <h1 class="h3 text-gray-800 mb-2 fw-bold">Eventos</h1>
                        <div class="d-flex align-items-center flex-wrap">
                            <div class="input-group mr-2 mb-2" style="width: 350px;">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white border-right-0"><i class="fas fa-search text-primary"></i></span>
                                </div>
                                <input type="text" id="buscador" class="form-control border-left-0" placeholder="Buscar evento...">
                            </div>
                            <button class="btn btn-success mb-2" data-toggle="modal" data-target="#modalCrear">
                                <i class="fas fa-plus"></i> Nuevo Evento
                            </button>
                        </div>
                    </div>

                    <ul class="nav nav-tabs mb-4" id="eventosTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active fw-bold text-primary" id="activos-tab" data-toggle="tab" href="#activos" role="tab">
                                <i class="fas fa-calendar-check mr-1"></i> Eventos Activos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-bold text-secondary" id="pasados-tab" data-toggle="tab" href="#pasados" role="tab">
                                <i class="fas fa-history mr-1"></i> Historial Pasados
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content" id="eventosTabContent">
                        <div class="tab-pane fade show active" id="activos" role="tabpanel">
                            <div class="row">
                                <?php
                                $res = $conn->query($sqlActivos);
                                if ($res && $res->num_rows > 0) {
                                    while ($row = $res->fetch_assoc()) {
                                        $imagen = (!empty($row['imagen']) && file_exists("../img/eventos/" . $row['imagen']))
                                            ? "../img/eventos/" . $row['imagen'] : "../img/default.png";
                                ?>
                                        <div class="col-12 evento-item"
                                            data-nombre="<?= strtolower($row['nombre_evento']) ?>"
                                            data-fecha="<?= strtolower($row['fecha']) ?>"
                                            data-lugar="<?= strtolower($row['lugar']) ?>">
                                            
                                            <div class="evento-card">
                                                <img src="<?= $imagen ?>" class="img-evento shadow-sm">
                                                
                                                <div class="flex-grow-1 mx-3 d-flex flex-column justify-content-center">
                                                    <h5 class="fw-bold text-dark mb-2"><?= htmlspecialchars($row["nombre_evento"] ?? '') ?></h5>
                                                    
                                                    <div class="d-flex flex-wrap align-items-center mb-2" style="gap: 15px;">
                                                        <span class="text-muted small">
                                                            <i class="fas fa-calendar-day mr-1" style="color: #4e73df;"></i> <?= date("d/m/Y", strtotime($row["fecha"])) ?>
                                                        </span>
                                                        <span class="text-muted small">
                                                            <i class="fas fa-clock mr-1" style="color: #4e73df;"></i> <?= date("h:i A", strtotime($row["hora"])) ?>
                                                        </span>
                                                        <span class="text-muted small">
                                                            <i class="fas fa-map-marker-alt mr-1" style="color: #e74a3b;"></i> <?= htmlspecialchars($row["lugar"] ?? '') ?>
                                                        </span>
                                                    </div>

                                                    <div class="d-flex flex-wrap align-items-center" style="gap: 10px;">
                                                        <span class="badge bg-light text-dark border px-3 py-2 rounded-pill" style="font-weight: 500;">
                                                            <i class="fas fa-user-tie text-primary mr-1"></i> Responsable: <?= htmlspecialchars($row["nombre_responsable"] ?? 'Sin asignar') ?>
                                                        </span>
                                                        <?php if(!empty($row["ubicacion"])): ?>
                                                            <a href="https://www.google.com/maps/search/?api=1&query=<?= urlencode($row['ubicacion']) ?>" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1">
                                                                <i class="fas fa-map mr-1"></i> Ver Mapa
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>

                                                <div class="acciones-evento">
                                                    <button class="btn btn-warning btn-sm text-dark font-weight-bold rounded-circle shadow-sm"
                                                        data-toggle="modal" data-target="#modalAsignarResp" title="Asignar Personal"
                                                        onclick="abrirAsignacion('<?= $row['id_evento'] ?>', '<?= htmlspecialchars($row['nombre_evento'] ?? '', ENT_QUOTES) ?>', '<?= $row['id_responsable'] ?? '' ?>', '<?= $row['fecha'] ?>')">
                                                        <i class="fas fa-user-cog"></i>
                                                    </button>

                                                    <button class="btn btn-info btn-sm text-white rounded-circle shadow-sm"
                                                        data-toggle="modal" data-target="#modalEditar" title="Editar Evento"
                                                        onclick="editarEvento('<?= $row['id_evento'] ?>','<?= htmlspecialchars($row['nombre_evento'] ?? '', ENT_QUOTES) ?>','<?= $row['fecha'] ?>','<?= $row['hora'] ?>','<?= htmlspecialchars($row['lugar'] ?? '', ENT_QUOTES) ?>','<?= htmlspecialchars($row['ubicacion'] ?? '', ENT_QUOTES) ?>','<?= htmlspecialchars($row['observaciones'] ?? '', ENT_QUOTES) ?>','<?= $imagen ?>')">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    
                                                    <button class="btn btn-danger btn-sm rounded-circle shadow-sm" title="Eliminar"
                                                        onclick="eliminarEvento('<?= $row['id_evento'] ?>','<?= htmlspecialchars($row['nombre_evento'], ENT_QUOTES) ?>')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                <?php }
                                } else {
                                    echo '<div class="col-12"><div class="alert alert-light border text-center py-4 rounded-4"><i class="fas fa-calendar-times fa-3x text-muted mb-3"></i><h5 class="text-muted fw-bold">No hay eventos activos</h5></div></div>';
                                } ?>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="pasados" role="tabpanel">
                            <div class="row">
                                <?php
                                $res = $conn->query($sqlPasados);
                                if ($res && $res->num_rows > 0) {
                                    while ($row = $res->fetch_assoc()) {
                                        $imagen = (!empty($row['imagen']) && file_exists("../img/eventos/" . $row['imagen']))
                                            ? "../img/eventos/" . $row['imagen'] : "../img/default.png";
                                ?>
                                        <div class="col-12 evento-item"
                                            data-nombre="<?= strtolower($row['nombre_evento']) ?>"
                                            data-fecha="<?= strtolower($row['fecha']) ?>"
                                            data-lugar="<?= strtolower($row['lugar']) ?>">
                                            
                                            <div class="evento-card pasado">
                                                <img src="<?= $imagen ?>" class="img-evento shadow-sm">
                                                
                                                <div class="flex-grow-1 mx-3 d-flex flex-column justify-content-center">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <h5 class="fw-bold text-secondary mb-0 mr-3"><?= htmlspecialchars($row["nombre_evento"] ?? '') ?></h5>
                                                        <span class="badge bg-secondary text-white px-2 py-1">Finalizado</span>
                                                    </div>
                                                    
                                                    <div class="d-flex flex-wrap align-items-center mb-2" style="gap: 15px;">
                                                        <span class="text-muted small"><i class="fas fa-calendar-day mr-1"></i> <?= date("d/m/Y", strtotime($row["fecha"])) ?></span>
                                                        <span class="text-muted small"><i class="fas fa-clock mr-1"></i> <?= date("h:i A", strtotime($row["hora"])) ?></span>
                                                        <span class="text-muted small"><i class="fas fa-map-marker-alt mr-1"></i> <?= htmlspecialchars($row["lugar"] ?? '') ?></span>
                                                    </div>

                                                    <div>
                                                        <span class="badge bg-white text-secondary border px-3 py-1 rounded-pill">
                                                            <i class="fas fa-user-check mr-1"></i> Asignado a: <?= htmlspecialchars($row["nombre_responsable"] ?? 'Nadie') ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                <?php }
                                } else {
                                    echo '<div class="col-12"><div class="alert alert-light border text-center py-4 rounded-4"><i class="fas fa-history fa-3x text-muted mb-3"></i><h5 class="text-muted fw-bold">No hay historial de eventos</h5></div></div>';
                                } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php include("../menu/php/piePagina.php"); ?>
        </div>
    </div>

    <div class="modal fade" id="modalCrear" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form method="POST" enctype="multipart/form-data" class="modal-content shadow-lg border-0 rounded-4">
                <div class="modal-header bg-primary text-white rounded-top-4">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-calendar-plus mr-2"></i> Nuevo Evento</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body p-4">
                    <h6 class="fw-bold mb-3 border-bottom pb-2" style="color: #4e73df;">
                        <i class="fas fa-info-circle mr-1"></i> Información General
                    </h6>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-light border-right-0" style="color: #4e73df;"><i class="fas fa-heading"></i></span>
                        <input type="text" name="nombre_evento" class="form-control border-left-0" placeholder="Ej: Feria de las ciencias" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-right-0" style="color: #4e73df;"><i class="fas fa-calendar-day"></i></span>
                                <input type="date" name="fecha" class="form-control border-left-0" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-right-0" style="color: #4e73df;"><i class="fas fa-clock"></i></span>
                                <input type="time" name="hora" class="form-control border-left-0" required>
                            </div>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3 mt-2 border-bottom pb-2" style="color: #4e73df;">
                        <i class="fas fa-map-marked-alt mr-1"></i> Ubicación
                    </h6>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-light border-right-0" style="color: #4e73df;"><i class="fas fa-building"></i></span>
                        <input type="text" name="lugar" class="form-control border-left-0" placeholder="Ej: Cancha municipal" required>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-light border-right-0" style="color: #4e73df;"><i class="fas fa-map-pin"></i></span>
                        <input type="text" name="ubicacion" class="form-control border-left-0" placeholder="Pega link de Google Maps o dirección" required>
                    </div>

                    <h6 class="fw-bold mb-3 mt-2 border-bottom pb-2" style="color: #4e73df;">
                        <i class="fas fa-image mr-1"></i> Detalles y Multimedia
                    </h6>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-light border-right-0" style="color: #4e73df;"><i class="fas fa-align-left"></i></span>
                        <textarea name="observaciones" class="form-control border-left-0" rows="2" placeholder="Detalles del evento..."></textarea>
                    </div>
                    <div class="row align-items-center bg-light p-3 rounded-3 mx-0 border">
                        <div class="col-md-4 text-center border-right">
                            <p class="mb-2 small fw-bold text-uppercase" style="color: #4e73df;">Vista Previa</p>
                            <img id="previewNuevo" src="../img/default.png" style="width: 100px; height: 100px; object-fit: cover; border-radius: 12px; border: 3px solid #fff; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                        </div>
                        <div class="col-md-8 pl-md-4 mt-3 mt-md-0">
                            <p class="mb-2 small fw-bold text-uppercase" style="color: #4e73df;">Subir Imagen del Cartel</p>
                            <input type="file" name="imagen" class="form-control form-control-sm" accept="image/*" onchange="document.getElementById('previewNuevo').src = window.URL.createObjectURL(this.files[0])">
                            <small class="text-muted mt-1 d-block" style="font-size: 0.75rem;">
                                <i class="fas fa-info-circle"></i> Sube un póster o logotipo. Se recomienda cuadrada.
                            </small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top-0">
                    <button type="button" class="btn btn-outline-danger rounded-pill px-4" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="crear" class="btn btn-success text-white fw-bold rounded-pill px-4 shadow-sm">
                        <i class="fas fa-save mr-1"></i> Guardar Evento
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form method="POST" enctype="multipart/form-data" class="modal-content shadow-lg border-0 rounded-4">
                <div class="modal-header bg-info text-white rounded-top-4">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-edit mr-2"></i> Editar Evento</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" name="id_evento" id="editIdEvento">

                    <h6 class="text-info fw-bold mb-3 border-bottom pb-2">
                        <i class="fas fa-info-circle mr-1"></i> Información General
                    </h6>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-light text-info border-right-0"><i class="fas fa-heading"></i></span>
                        <input type="text" name="nombre_evento" id="editNombre" class="form-control border-left-0" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="input-group">
                                <span class="input-group-text bg-light text-info border-right-0"><i class="fas fa-calendar-day"></i></span>
                                <input type="date" name="fecha" id="editFecha" class="form-control border-left-0" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="input-group">
                                <span class="input-group-text bg-light text-info border-right-0"><i class="fas fa-clock"></i></span>
                                <input type="time" name="hora" id="editHora" class="form-control border-left-0" required>
                            </div>
                        </div>
                    </div>

                    <h6 class="text-info fw-bold mb-3 mt-2 border-bottom pb-2">
                        <i class="fas fa-map-marked-alt mr-1"></i> Ubicación
                    </h6>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-light text-info border-right-0"><i class="fas fa-building"></i></span>
                        <input type="text" name="lugar" id="editLugar" class="form-control border-left-0" required>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-light text-info border-right-0"><i class="fas fa-map-pin"></i></span>
                        <input type="text" name="ubicacion" id="editUbicacion" class="form-control border-left-0" required>
                    </div>

                    <h6 class="text-info fw-bold mb-3 mt-2 border-bottom pb-2">
                        <i class="fas fa-image mr-1"></i> Detalles y Multimedia
                    </h6>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-light text-info border-right-0"><i class="fas fa-align-left"></i></span>
                        <textarea name="observaciones" id="editObservaciones" class="form-control border-left-0" rows="2"></textarea>
                    </div>
                    <div class="row align-items-center bg-light p-3 rounded-3 mx-0 border">
                        <div class="col-md-4 text-center border-right">
                            <p class="mb-2 small fw-bold text-muted text-uppercase">Imagen Actual</p>
                            <img id="previewImagen" style="width: 100px; height: 100px; object-fit: cover; border-radius: 12px; border: 3px solid #fff; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                        </div>
                        <div class="col-md-8 pl-md-4 mt-3 mt-md-0">
                            <p class="mb-2 small fw-bold text-info text-uppercase">Subir Nueva Imagen</p>
                            <input type="file" name="imagen" class="form-control form-control-sm" accept="image/*" onchange="document.getElementById('previewImagen').src = window.URL.createObjectURL(this.files[0])">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top-0">
                    <button type="button" class="btn btn-outline-danger rounded-pill px-4" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="editar" class="btn btn-info text-white fw-bold rounded-pill px-4 shadow-sm">
                        <i class="fas fa-save mr-1"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modalAsignarResp" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form method="POST" class="modal-content shadow-lg border-0 rounded-4">
                <div class="modal-header bg-warning text-dark rounded-top-4">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-user-tag mr-2"></i> Gestión de Personal</h5>
                    <button type="button" class="close text-dark" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" name="id_evento" id="asignarIdEvento">

                    <div class="text-center mb-4">
                        <h6 class="text-muted text-uppercase small font-weight-bold">Evento Seleccionado</h6>
                        <h4 class="text-black font-weight-bold" id="asignarNombreEvento"></h4>
                        <span id="badgeFecha" class="badge px-3 py-2 rounded-pill mt-2" style="background-color: #ffc107; color: black; font-size: 0.9rem;"></span>
                    </div>

                    <label class="fw-bold text-dark">Encargado del Evento</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-right-0"><i class="fas fa-user-tie text-warning"></i></span>
                        <select name="id_responsable" id="asignarSelectResp" class="form-control border-left-0"></select>
                    </div>

                    <div id="warningOcupado" class="alert alert-danger mt-3 py-2 px-3 small font-weight-bold" style="display:none; border-radius: 10px;">
                    </div>
                </div>
                <div class="modal-footer bg-light border-top-0">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="asignar_responsable" class="btn btn-warning text-dark font-weight-bold rounded-pill px-4 shadow-sm">
                        <i class="fas fa-check-circle mr-1"></i> Confirmar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../js/sb-admin-2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        <?php if (!empty($mensaje)) : ?>
            Swal.fire({
                icon: '<?= $tipo ?>',
                title: '<?= $mensaje ?>',
                showConfirmButton: false,
                timer: 2000
            }).then(() => {
                window.history.replaceState({}, document.title, window.location.pathname);
            });
        <?php endif; ?>

        const responsablesArray = <?= $json_responsables ?>;
        const ocupacionesPorFecha = <?= $json_ocupaciones ?>;

        function abrirAsignacion(id_evento, nombre_evento, id_responsable_actual, fecha_evento) {
            document.getElementById('asignarIdEvento').value = id_evento;
            document.getElementById('asignarNombreEvento').innerText = nombre_evento;

            let fechaParts = fecha_evento.split('-');
            document.getElementById('badgeFecha').innerHTML = `<i class="fas fa-calendar-day mr-1"></i> ${fechaParts[2]}/${fechaParts[1]}/${fechaParts[0]}`;

            const select = document.getElementById('asignarSelectResp');
            select.innerHTML = '<option value="" style="color: #6c757d;">-- Dejar sin asignar --</option>';

            let ocupadosEsteDia = ocupacionesPorFecha[fecha_evento] || [];

            let optLibres = document.createElement('optgroup');
            optLibres.label = 'DISPONIBLES';
            optLibres.style.color = '#1cc88a';

            let optOcupados = document.createElement('optgroup');
            optOcupados.label = 'OCUPADOS EN OTROS EVENTOS';
            optOcupados.style.color = '#e74a3b';

            responsablesArray.forEach(resp => {
                let option = document.createElement('option');
                option.value = resp.id_responsable;
                option.style.color = '#333333';

                let ocupacion = ocupadosEsteDia.find(o => o.id_responsable === resp.id_responsable && o.id_evento !== id_evento);

                if (ocupacion) {
                    option.text = `${resp.nombre} (Ocupado en: ${ocupacion.nombre_evento})`;
                    option.dataset.eventoOcupado = ocupacion.nombre_evento;
                    optOcupados.appendChild(option);
                } else {
                    option.text = resp.nombre;
                    optLibres.appendChild(option);
                }
            });

            if (optLibres.children.length > 0) select.appendChild(optLibres);
            if (optOcupados.children.length > 0) select.appendChild(optOcupados);

            select.value = id_responsable_actual;
            verificarAlertaOcupado();
        }

        document.getElementById('asignarSelectResp').addEventListener('change', verificarAlertaOcupado);

        function verificarAlertaOcupado() {
            const select = document.getElementById('asignarSelectResp');
            const warning = document.getElementById('warningOcupado');
            const opcionSeleccionada = select.options[select.selectedIndex];

            if (opcionSeleccionada && opcionSeleccionada.dataset.eventoOcupado) {
                warning.innerHTML = `<i class="fas fa-exclamation-triangle mr-1"></i> <b>${opcionSeleccionada.text.split(' (')[0]}</b> ya tiene a su cargo el evento "<b>${opcionSeleccionada.dataset.eventoOcupado}</b>".`;
                warning.style.display = 'block';
            } else {
                warning.style.display = 'none';
            }
        }

        function editarEvento(id, nombre, fecha, hora, lugar, ubicacion, observaciones, imagen) {
            document.getElementById('editIdEvento').value = id;
            document.getElementById('editNombre').value = nombre;
            document.getElementById('editFecha').value = fecha;
            document.getElementById('editHora').value = hora;
            document.getElementById('editLugar').value = lugar;
            document.getElementById('editUbicacion').value = ubicacion;
            document.getElementById('editObservaciones').value = observaciones;
            document.getElementById('previewImagen').src = imagen;
        }

        function eliminarEvento(id, nombre) {
            Swal.fire({
                title: '¿Eliminar evento?',
                text: "No podrás recuperar este evento",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#858796',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = document.createElement("form");
                    form.method = "POST";
                    form.innerHTML = `<input type="hidden" name="id_evento" value="${id}"><input type="hidden" name="eliminar" value="1">`;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        $("#buscador").on("keyup", function() {
            let texto = $(this).val().toLowerCase();
            $(".evento-item").each(function() {
                let nombre = $(this).data("nombre");
                let fecha = $(this).data("fecha");
                let lugar = $(this).data("lugar");
                $(this).toggle(nombre.includes(texto) || fecha.includes(texto) || lugar.includes(texto));
            });
        });

        $('form').on('submit', function() {
            Swal.fire({
                title: 'Guardando...',
                text: 'Por favor espera un momento',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
        });
    </script>
</body>
</html>