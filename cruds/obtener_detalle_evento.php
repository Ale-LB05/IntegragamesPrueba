<?php
include("../config/conexion.php");
$id = intval($_POST['id_evento']);

// 1. Datos del evento
$sql = "SELECT e.*, r.nombre as resp 
        FROM evento e 
        LEFT JOIN evento_responsable er ON e.id_evento = er.id_evento 
        LEFT JOIN responsable r ON er.id_responsable = r.id_responsable 
        WHERE e.id_evento = $id";
$evento = $conn->query($sql)->fetch_assoc();

// 2. Conteo de participantes
$participantes = $conn->query("SELECT COUNT(*) as total FROM participante WHERE id_evento = $id")->fetch_assoc()['total'];

// 3. Juegos más jugados (Basado en la tabla satisfacción)
$sqlJuegos = "SELECT j.nombre_juego, COUNT(s.id_satisfaccion) as veces 
              FROM satisfaccion s 
              JOIN juego j ON s.id_juego = j.id_juego 
              JOIN participante p ON s.id_participante = p.id_participante 
              WHERE p.id_evento = $id 
              GROUP BY j.id_juego ORDER BY veces DESC LIMIT 3";
$resJuegos = $conn->query($sqlJuegos);
?>

<style>
    /* Estilos personalizados solo para este modal */
    .detalle-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #858796;
        font-weight: 700;
    }

    .detalle-valor {
        font-size: 1.05rem;
        color: #3a3b45;
        font-weight: 600;
    }

    .caja-juegos {
        background: #f8f9fc;
        border-left: 4px solid #f6c23e;
        border-radius: 12px;
    }

    .icon-box {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        border-radius: 10px;
        margin-right: 12px;
        font-size: 1.1rem;
    }

    .game-pill {
        background: #ffffff;
        border: 1px solid #e3e6f0;
        border-radius: 8px;
        padding: 8px 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 100%;
    }
</style>

<div class="px-2 pb-2">
    <div class="text-center mb-4 pb-3 border-bottom">
        <h4 class="fw-bold text-primary mb-2" style="font-weight: 800;"><?= htmlspecialchars($evento['nombre_evento']) ?></h4>
        <span class="badge badge-light text-muted border px-4 py-2 rounded-pill shadow-sm" style="font-size: 0.9rem;">
            <i class="fas fa-calendar-alt mr-2 text-primary"></i> <?= date("d / m / Y", strtotime($evento['fecha'])) ?>
        </span>
    </div>

    <div class="row mb-4">
        <div class="col-12 col-sm-6 mb-4">
            <div class="d-flex align-items-center mb-1">
                <div class="icon-box bg-light text-primary shadow-sm"><i class="fas fa-clock"></i></div>
                <span class="detalle-label">Horario</span>
            </div>
            <div class="detalle-valor pl-5" style="margin-top: -8px; white-space: nowrap;">
                <?= date("h:i A", strtotime($evento['hora_inicio'])) ?> - <?= date("h:i A", strtotime($evento['hora_fin'])) ?>
            </div>
        </div>

        <div class="col-12 col-sm-6 mb-4">
            <div class="d-flex align-items-center mb-1">
                <div class="icon-box bg-light text-success shadow-sm"><i class="fas fa-users"></i></div>
                <span class="detalle-label">Asistencia</span>
            </div>
            <div class="detalle-valor pl-5" style="margin-top: -8px;">
                <?= $participantes ?> <span class="text-muted font-weight-normal small">personas</span>
            </div>
        </div>

        <div class="col-12 mb-4">
            <div class="d-flex align-items-center mb-1">
                <div class="icon-box bg-light text-danger shadow-sm"><i class="fas fa-map-marker-alt"></i></div>
                <span class="detalle-label">Lugar y Ubicación</span>
            </div>
            <div class="detalle-valor pl-5 d-flex align-items-center justify-content-between flex-wrap" style="margin-top: -8px;">
                <span class="mr-2 mb-2 mb-sm-0"><?= htmlspecialchars($evento['lugar']) ?></span>

                <?php if (!empty($evento['ubicacion'])): ?>
                    <a href="https://www.google.com/maps/search/?api=1&query=<?= htmlspecialchars($evento['ubicacion']) ?>" target="_blank" class="btn btn-sm btn-outline-danger rounded-pill px-3 shadow-sm" style="font-size: 0.8rem; font-weight: 600;">
                        <i class="fas fa-map-marked-alt mr-1"></i> Ver en Maps
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-12">
            <div class="d-flex align-items-center mb-1">
                <div class="icon-box bg-light text-info shadow-sm"><i class="fas fa-user-tie"></i></div>
                <span class="detalle-label">Responsable Asignado</span>
            </div>
            <div class="detalle-valor pl-5" style="margin-top: -8px;">
                <?= htmlspecialchars($evento['resp'] ?? 'No asignado') ?>
            </div>
        </div>
    </div>

    <div class="caja-juegos p-4 mt-3 shadow-sm border-right border-bottom border-top">
        <h6 class="font-weight-bold text-dark mb-3 text-uppercase" style="font-size: 0.85rem; letter-spacing: 0.5px;">
            <i class="fas fa-trophy text-warning mr-2 fa-lg"></i> Top Juegos Populares
        </h6>

        <div class="row">
            <?php if ($resJuegos->num_rows > 0):
                while ($j = $resJuegos->fetch_assoc()): ?>
                    <div class="col-12 col-md-auto mb-2">
                        <div class="game-pill shadow-sm">
                            <span class="small font-weight-bold text-secondary mr-3" style="white-space: normal; word-break: break-word;">
                                <i class="fas fa-gamepad mr-1 text-primary"></i> <?= htmlspecialchars($j['nombre_juego']) ?>
                            </span>
                            <span class="badge badge-primary rounded-pill px-2 py-1"><?= $j['veces'] ?></span>
                        </div>
                    </div>
                <?php endwhile;
            else: ?>
                <div class="col-12 text-center py-3 bg-white rounded border border-light">
                    <span class="text-muted small">
                        <i class="fas fa-folder-open fa-2x mb-2 text-gray-300 d-block"></i>
                        No hay registros de juegos jugados en este evento.
                    </span>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>