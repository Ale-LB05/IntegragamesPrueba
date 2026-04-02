<head>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- BRAND -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../menu/panel.php">
        <div class="sidebar-brand-icon">
            <i class="fas fa-gamepad"></i>
        </div>
        <div class="sidebar-brand-text mx-2">IntegraGames</div>
    </a>

    <hr class="sidebar-divider my-0">

    <!-- INICIO -->
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

        <!-- PERSONAL (solo admin) -->
        <?php if ($esAdmin) { ?>
            <li class="nav-item">
                <a class="nav-link" href="../cruds/personal.php">
                    <i class="fas fa-user"></i>
                    <span>Personal</span>
                </a>
            </li>
        <?php } ?>

        <!-- EVENTOS (admin, programador, promotor) -->
        <?php if ($esAdmin || $esProgramador || $esPromotor) { ?>
            <li class="nav-item">
                <a class="nav-link" href="../cruds/eventos.php">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Eventos</span>
                </a>
            </li>
        <?php } ?>

        <!-- ESCUELAS (admin y programador) -->
        <?php if ($esAdmin || $esProgramador) { ?>
            <li class="nav-item">
                <a class="nav-link" href="../registroEscuela/escuelas.php">
                    <i class="fas fa-school"></i>
                    <span>Escuelas</span>
                </a>
            </li>
        <?php } ?>

        <!-- HISTORIAL (admin y programador) -->
        <?php if ($esAdmin || $esProgramador) { ?>
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

    <!-- INFORMACIÓN -->
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

    <!-- CONTACTO -->
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

    <!-- SESIÓN -->
    <div class="sidebar-heading">
        Sesión
    </div>

    <li class="nav-item">
        <a class="nav-link" href="../index.php">
            <i class="fas fa-sign-out-alt"></i>
            <span>Salir</span>
        </a>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    <!-- TOGGLE (MINIMIZAR) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>


</ul>