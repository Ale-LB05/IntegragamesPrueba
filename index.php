<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IntegraGames - Inicio</title>

    <!-- ICONO -->
    <link rel="icon" href="../img/logo.png" type="image/png">
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <!-- FontAwesome para Iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: url('https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80&w=2070&auto=format&fit=crop') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
        }
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.9) 0%, rgba(0, 0, 0, 0.7) 100%);
            z-index: -1;
        }
        .main-container {
            text-align: center;
            padding: 40px 20px;
            max-width: 800px;
            width: 100%;
        }
        .logo-container {
            margin-bottom: 30px;
            animation: fadeInDown 1s ease-out;
        }
        .logo-icon {
            font-size: 5rem;
            color: #00d2ff;
            text-shadow: 0 0 20px rgba(0, 210, 255, 0.8);
            animation: pulse 2s infinite;
        }
        .main-title {
            font-size: 3.5rem;
            font-weight: 800;
            color: #00d2ff;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 15px;
            text-shadow: 0 0 20px rgba(0, 210, 255, 0.6);
            animation: fadeInUp 1s ease-out 0.3s both;
        }
        .subtitle {
            font-size: 1.3rem;
            color: #ddd;
            margin-bottom: 50px;
            font-weight: 300;
            animation: fadeInUp 1s ease-out 0.6s both;
        }
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 50px;
            animation: fadeInUp 1s ease-out 0.9s both;
        }
        .feature-item {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 25px;
            transition: all 0.3s ease;
        }
        .feature-item:hover {
            background: rgba(0, 210, 255, 0.1);
            border-color: #00d2ff;
            transform: translateY(-5px);
        }
        .feature-icon {
            font-size: 2.5rem;
            color: #00d2ff;
            margin-bottom: 15px;
        }
        .feature-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: white;
            margin-bottom: 10px;
        }
        .feature-desc {
            font-size: 0.9rem;
            color: #aaa;
        }

        .btn-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            animation: fadeInUp 1s ease-out 1.2s both;
        }
        .btn {
            background: linear-gradient(45deg, #00d2ff, #3a7bd5);
            border: none;
            padding: 15px 40px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            color: white;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 20px rgba(0, 210, 255, 0.3);
        }
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 210, 255, 0.5);
        }
        .btn2 {
            background: linear-gradient(45deg, #ff6b6b, #ee5a5a);
            box-shadow: 0 5px 20px rgba(255, 107, 107, 0.3);
        }
        .btn2:hover {
            box-shadow: 0 10px 30px rgba(255, 107, 107, 0.5);
        }
        .footer {
            margin-top: 60px;
            padding-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: #aaa;
            font-size: 0.9rem;
            animation: fadeInUp 1s ease-out 1.5s both;
        }
        .social-links {
            margin-top: 20px;
        }
        .social-links a {
            color: #00d2ff;
            font-size: 1.5rem;
            margin: 0 10px;
            transition: all 0.3s ease;
        }
        .social-links a:hover {
            color: #00d2ff;
            transform: scale(1.2);
        }
        /* Animaciones */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes pulse {
            0%,
            100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }
        /* Responsive */
        @media (max-width: 768px) {
            .main-title {
                font-size: 2.5rem;
            }
            .subtitle {
                font-size: 1.1rem;
            }
            .logo-icon {
                font-size: 3.5rem;
            }
            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>


<body>
    <div class="overlay"></div>

    <div class="main-container">

        <!-- Logo y Título -->
        <div class="logo-container">
            <div class="logo-icon">
                <i class="fa-solid fa-gamepad"></i>
            </div>
            <h1 class="main-title">IntegraGames</h1>
            <p class="subtitle">La integración de la carrera a través del juego</p>
        </div>

        <!-- Características -->
        <div class="features">
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fa-solid fa-trophy"></i>
                </div>
                <div class="feature-title">Eventos</div>
                <div class="feature-desc">Prueba un videojuegos exclusivos de TI</div>
            </div>

            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div class="feature-title">Comunidad</div>
                <div class="feature-desc">Conoce a nuestros integrantes de la carrera</div>
            </div>

            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <div class="feature-title">Juegos</div>
                <div class="feature-desc">Juegos entretenidos</div>
            </div>
        </div>

        <!-- Botones de Acción -->
        <div class="btn-container">
            <a href="registroAlumnos/registro_alumno.php" class="btn">
                <i class="fa-solid fa-user-plus"></i>
                Registrarme
            </a>

            <a href="RegistroAdmin/login.php" class="btn btn2">
                <i class="fa-solid fa-right-to-bracket"></i>
                Encargado
            </a>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; <?php echo date('Y'); ?> IntegraGames. Todos los derechos reservados.</p>
            <p>Desarrollado por alumnos de la Universidad Tecnologica de Morelia de la
                carrera TI</p>

            <div class="social-links">
                <a href="https://www.facebook.com/share/1CTdx2LSvG/" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="https://www.instagram.com/utmmorelia?igsh=NzBsNHVlYTRyeTZk" title="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="https://www.tiktok.com/@utmorelia?_r=1&_t=ZS-94h24oJcmpk" title="Tiktok"><i class="fab fa-tiktok"></i></a>
            </div>
        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>