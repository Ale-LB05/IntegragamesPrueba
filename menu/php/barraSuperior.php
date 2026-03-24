<?php
if (!isset($conn)) {
    include("../config/conexion.php");
}

/* OBTENER IMAGEN DEL USUARIO (NO PARTICIPANTES) */
$fotoUsuario = "../img/responsables/sinFoto.jpg";

if ($_SESSION['rol'] != "participante") {

    $nombreSesion = $_SESSION['usuario'];

    $sqlImg = "SELECT imagen FROM responsable WHERE nombre='$nombreSesion'";
    $resImg = mysqli_query($conn, $sqlImg);

    if ($resImg && mysqli_num_rows($resImg) > 0) {
        $dataImg = mysqli_fetch_assoc($resImg);

        if (!empty($dataImg['imagen']) && file_exists("../img/responsables/" . $dataImg['imagen'])) {
            $fotoUsuario = "../img/responsables/" . $dataImg['imagen'];
        }
    }
}
?>

<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- IZQUIERDA -->
    <div class="d-flex align-items-center">

        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-2">
            <i class="fa fa-bars"></i>
        </button>

        <img src="../img/logo.png" style="height: 70px;">
    </div>
    
    <!-- DERECHA -->
    <ul class="navbar-nav ml-auto">

        <!-- ALERTAS -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                <i class="fas fa-bell fa-fw"></i>
                <span class="badge badge-danger badge-counter">3+</span>
            </a>
        </li>

        <!-- MENSAJES -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                <i class="fas fa-envelope fa-fw"></i>
                <span class="badge badge-danger badge-counter">7</span>
            </a>
        </li>

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- USUARIO -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" data-toggle="dropdown">

                <!-- NOMBRE -->
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                    <?= $_SESSION['usuario'] . " - " . $_SESSION['rol']; ?>
                </span>

                <!-- FOTO / ICONO -->
                <?php if ($_SESSION['rol'] != "participante") { ?>

                    <img src="<?= $fotoUsuario ?>"
                        style="width:45px; height:45px; border-radius:50%; object-fit:cover;">

                <?php } else { ?>

                    <div style="width:45px; height:45px; border-radius:50%; background:#eaeaea; display:flex; align-items:center; justify-content:center;">
                        <i class="fas fa-user"></i>
                    </div>

                <?php } ?>

            </a>
            <?php if ($esAdmin || $esProgramador || $esPromotor) { ?>
            <!-- DROPDOWN -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in">

                <a class="dropdown-item" href="../menu/perfil.php">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Perfil
                </a>

                <a class="dropdown-item" href="../RegistroAdmin/logout.php">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Cerrar sesión
                </a>

            </div>
            <?php } ?>
        </li>

    </ul>
    
</nav>