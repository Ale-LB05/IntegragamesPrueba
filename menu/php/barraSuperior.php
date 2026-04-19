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

    <div class="d-flex align-items-center">

        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-2">
            <i class="fa fa-bars"></i>
        </button>

        <img src="../img/logo.png" style="height: 70px;">
    </div>

    <ul class="navbar-nav ml-auto">

        <div class="topbar-divider d-none d-sm-block"></div>

        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" data-toggle="dropdown">

                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                    <?= $_SESSION['usuario'] . " - " . $_SESSION['rol']; ?>
                </span>

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
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in">

                    <a class="dropdown-item" href="../menu/perfil.php">
                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                        Perfil
                    </a>

                    <a class="dropdown-item" href="#" onclick="confirmarCerrarSesion(event)">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        Cerrar sesión
                    </a>

                </div>
            <?php } ?>
        </li>

    </ul>

</nav>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Función para confirmar el cierre de sesión
    function confirmarCerrarSesion(event) {
        event.preventDefault(); // Evita que el enlace intente navegar a "#"

        Swal.fire({
            title: '¿Cerrar sesión?',
            text: "¿Estás seguro de que deseas salir de tu cuenta?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74a3b', // Color rojo para acción destructiva/salir
            cancelButtonColor: '#858796', // Color gris para cancelar
            confirmButtonText: '<i class="fas fa-sign-out-alt mr-1"></i> Sí, salir',
            cancelButtonText: 'Cancelar',
            reverseButtons: true,
            customClass: {
                confirmButton: 'rounded-pill px-4 shadow-sm',
                cancelButton: 'rounded-pill px-4'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Si el usuario confirma, lo redirigimos al script que destruye la sesión
                window.location.href = '../RegistroAdmin/logout.php';
            }
        });
    }
</script>