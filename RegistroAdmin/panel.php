<?php
session_start();

if (!isset($_SESSION['id_responsable'])) {
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Panel IntegraGames</title>

    <!-- Bootstrap + SB Admin -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
</head>

<body id="page-top">

    <div id="wrapper">

        <!-- MENU LATERAL -->
        <?php include("../php/menuLateral.php"); ?>

        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                <!-- BARRA SUPERIOR -->
                <?php include("../php/barraSuperior.php"); ?>

                <div class="container-fluid">

                    <!-- ALERTA -->
                    <?php if (isset($_GET['ok'])) { ?>
                        <div class="alert alert-success text-center">
                            <i class="fa-solid fa-circle-check"></i>
                            Entraste correctamente al sistema
                        </div>
                    <?php } ?>

                    <!-- CONTENIDO -->
                    <h1 class="h3 mb-4 text-gray-800">Panel IntegraGames</h1>

                    <p>Bienvenido <b><?php echo $_SESSION['nombre']; ?></b></p>

                    <a href="../index.php" class="back-link text-decoration-none">
                        <i class="fa-solid fa-arrow-left me-1"></i> Volver al inicio
                    </a>

                </div>

            </div>

        </div>

    </div>

    <!-- SCRIPTS (CLAVE PARA EL TOGGLE) -->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../js/sb-admin-2.min.js"></script>

</body>

</html>