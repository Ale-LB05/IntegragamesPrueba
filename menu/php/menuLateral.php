<head>
    <style>
        .text-primary {
            color: #4e73df !important;
        }

        .text-secondary {
            color: #858796 !important;
        }
    </style>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../menu/panel.php">
        <div class="sidebar-brand-icon">
            <i class="fas fa-gamepad"></i>
        </div>
        <div class="sidebar-brand-text mx-2">IntegraGames</div>
    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item active">
        <a class="nav-link" href="../menu/menu.php">
            <i class="fas fa-home"></i>
            <span>Inicio</span>
        </a>
    </li>


    <?php
    $esAdmin = ($rol == "administrador");
    $esProgramador = ($rol == "programador");
    $esPromotor = ($rol == "promotor");
    ?>

    <?php if ($esAdmin || $esProgramador || $esPromotor) { ?>
        <hr class="sidebar-divider">


        <div class="sidebar-heading">
            Administración
        </div>

        <?php if ($esAdmin || $esProgramador) { ?>
            <li class="nav-item">
                <a class="nav-link" href="../cruds/personal.php">
                    <i class="fas fa-user"></i>
                    <span>Personal</span>
                </a>
            </li>
        <?php } ?>

        <?php if ($esAdmin || $esProgramador || $esPromotor) { ?>
            <li class="nav-item">
                <a class="nav-link" href="../cruds/eventos.php">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Eventos</span>
                </a>
            </li>
        <?php } ?>

        <?php if ($esAdmin || $esProgramador || $esPromotor) { ?>
            <li class="nav-item">
                <a class="nav-link" href="../registroEscuela/escuelas.php">
                    <i class="fas fa-school"></i>
                    <span>Escuelas</span>
                </a>
            </li>
        <?php } ?>

        <?php if ($esAdmin || $esProgramador || $esPromotor) { ?>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUsuarios"
                    aria-expanded="false" aria-controls="collapseUsuarios">
                    <i class="fas fa-users"></i>
                    <span>Historial</span>
                </a>

                <div id="collapseUsuarios" class="collapse" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Historial:</h6>

                        <a class="collapse-item" href="../cruds/encargadoEvento.php">
                            <i class="fas fa-user-tie"></i> Eventos
                        </a>

                        <a class="collapse-item" href="../cruds/lista.php">
                            <i class="fas fa-users"></i> Participantes
                        </a>
                    </div>
                </div>
            </li>
        <?php } ?>
    <?php } ?>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Información
    </div>


    <li class="nav-item">
        <a class="nav-link" href="https://share.google/2xNdfPbTZln7FfE9E">
            <i class="fas fa-university"></i>
            <span>UTM</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Contacto
    </div>

    <li class="nav-item">
        <a class="nav-link" href="https://www.facebook.com/share/1JrTM7N5jB/">
            <i class="fab fa-facebook"></i>
            <span>Facebook</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="https://www.instagram.com/utmmorelia?igsh=NzBsNHVlYTRyeTZk">
            <i class="fab fa-instagram"></i>
            <span>Instagram</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="https://www.tiktok.com/@utmorelia?_r=1&_t=ZS-94h24oJcmpk">
            <i class="fab fa-tiktok"></i>
            <span>TikTok</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Sesión
    </div>

    <li class="nav-item">
        <a class="nav-link" href="#" onclick="confirmarSalirSidebar(event)">
            <i class="fas fa-sign-out-alt"></i>
            <span>Salir</span>
        </a>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmarSalirSidebar(event) {
        event.preventDefault(); // Evita la recarga inmediata de la página

        Swal.fire({
            title: '¿Cerrar sesión?',
            text: "¿Estás seguro de que deseas salir del sistema?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74a3b', // Color rojo característico de salida
            cancelButtonColor: '#858796', // Gris para cancelar
            confirmButtonText: '<i class="fas fa-sign-out-alt mr-1"></i> Sí, salir',
            cancelButtonText: 'Cancelar',
            reverseButtons: true,
            customClass: {
                confirmButton: 'rounded-pill px-4 shadow-sm',
                cancelButton: 'rounded-pill px-4'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Mantuve la ruta que tenías originalmente en tu código (../index.php)
                // Si necesitas que cierre la sesión en backend, podrías cambiarlo a '../RegistroAdmin/logout.php'
                window.location.href = '../index.php';
            }
        });
    }
</script>