<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- IZQUIERDA -->
    <div class="d-flex align-items-center">

        <!-- Botón sidebar -->
        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-2">
            <i class="fa fa-bars"></i>
        </button>

        <!-- LOGO -->
        <img src="../img/logo.png" style="height: 70px;">
    </div>

    <!-- DERECHA -->
    <ul class="navbar-nav ml-auto">

        <!-- ALERTAS -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                data-toggle="dropdown">
                <i class="fas fa-bell fa-fw"></i>
                <span class="badge badge-danger badge-counter">3+</span>
            </a>

            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in">
                <h6 class="dropdown-header">Centro de Notificaciones</h6>

                <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="mr-3">
                        <div class="icon-circle bg-primary">
                            <i class="fas fa-file-alt text-white"></i>
                        </div>
                    </div>
                    <div>
                        <div class="small text-gray-500">Hoy</div>
                        Nueva actividad registrada
                    </div>
                </a>

                <a class="dropdown-item text-center small text-gray-500" href="#">
                    Ver todas
                </a>
            </div>
        </li>

        <!-- MENSAJES -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                data-toggle="dropdown">
                <i class="fas fa-envelope fa-fw"></i>
                <span class="badge badge-danger badge-counter">7</span>
            </a>

            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in">
                <h6 class="dropdown-header">Mensajes</h6>

                <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="font-weight-bold">
                        <div class="text-truncate">Tienes un nuevo mensaje</div>
                        <div class="small text-gray-500">Hace unos minutos</div>
                    </div>
                </a>

                <a class="dropdown-item text-center small text-gray-500" href="#">
                    Ver más
                </a>
            </div>
        </li>

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- USUARIO -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                data-toggle="dropdown">

                <!-- NOMBRE + ROL -->
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                    <?php echo $_SESSION['usuario'] . " - " . $_SESSION['rol']; ?>
                </span>

                <!-- FOTO -->
                <img class="img-profile rounded-circle"
                    src="../img/undraw_profile.svg">
            </a>

            <!-- DROPDOWN -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in">

                <a class="dropdown-item" href="#">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Perfil
                </a>

                <a class="dropdown-item" href="#">
                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                    Configuración
                </a>

                <a class="dropdown-item" href="#">
                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                    Actividad
                </a>

                <div class="dropdown-divider"></div>

                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Cerrar sesión
                </a>

            </div>
        </li>

    </ul>
</nav>