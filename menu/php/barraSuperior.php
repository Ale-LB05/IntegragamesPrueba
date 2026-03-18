<?php
if(!isset($_SESSION)){
    session_start();
}

$usuario = $_SESSION['usuario'];
$rol_usuario = $_SESSION['rol'];
?>

<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Botón para mostrar menú lateral en celular -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Buscador -->
    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">

        <div class="input-group">

            <input type="text" class="form-control bg-light border-0 small"
                placeholder="Buscar..."
                aria-label="Search">

            <div class="input-group-append">

                <button class="btn btn-primary" type="button">
                    <i class="fas fa-search fa-sm"></i>
                </button>

            </div>

        </div>

    </form>

    <!-- Navbar derecha -->
    <ul class="navbar-nav ml-auto">

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Usuario -->
        <li class="nav-item dropdown no-arrow">

            <a class="nav-link dropdown-toggle" href="#" id="userDropdown"
                role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">

                <span class="mr-2 d-none d-lg-inline text-gray-600 small">

                    <?php echo $usuario . " - " . $rol_usuario; ?>

                </span>

                <img class="img-profile rounded-circle"
                    src="../img/undraw_profile.svg">

            </a>

            <!-- Menú usuario -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="userDropdown">

                <a class="dropdown-item" href="#">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Perfil
                </a>

                <a class="dropdown-item" href="#">
                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                    Configuración
                </a>

                <div class="dropdown-divider"></div>

                <a class="dropdown-item" href="../RegistroAdmin/logout.php">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Cerrar sesión
                </a>

            </div>

        </li>

    </ul>

</nav>
