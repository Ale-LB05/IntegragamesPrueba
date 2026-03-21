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
        <a class="nav-link" href="../menu/panel.php">
            <i class="fas fa-home"></i>
            <span>Inicio</span>
        </a>
    </li>

    <!-- PARTICIPANTES -->
    <li class="nav-item">
        <a class="nav-link" href="../registroAlumnos/lista.php">
            <i class="fas fa-user-graduate"></i>
            <span>Participantes</span>
        </a>
    </li>

    <?php if ($rol == "admin") { ?>

        <hr class="sidebar-divider">

        <!-- ADMIN -->
        <div class="sidebar-heading">
            Administración
        </div>

        <li class="nav-item">
            <a class="nav-link" href="../eventos/">
                <i class="fas fa-calendar-alt"></i>
                <span>Eventos</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="../registroEscuela/escuelas.php">
                <i class="fas fa-school"></i>
                <span>Escuelas</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="../resultados/">
                <i class="fas fa-trophy"></i>
                <span>Resultados</span>
            </a>
        </li>

    <?php } ?>

    <hr class="sidebar-divider">

    <!-- INFORMACIÓN -->
    <div class="sidebar-heading">
        Información
    </div>

    <li class="nav-item">
        <a class="nav-link" href="#">
            <i class="fas fa-info-circle"></i>
            <span>Información</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="#">
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
        <a class="nav-link" href="#">
            <i class="fab fa-facebook"></i>
            <span>Facebook</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="#">
            <i class="fab fa-instagram"></i>
            <span>Instagram</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="#">
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
        <a class="nav-link" href="../RegistroAdmin/logout.php">
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