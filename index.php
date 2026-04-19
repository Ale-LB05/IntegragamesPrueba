<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IntegraGames - Inicio</title>

    <link rel="icon" href="img/logo.png" type="image/png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
            position: fixed; /* Cambiado a fixed para que cubra todo al hacer scroll */
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
            z-index: 1; /* Asegura que esté por encima del overlay */
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
            cursor: pointer; /* Cambia el cursor a una mano */
        }

        .feature-item:hover {
            background: rgba(0, 210, 255, 0.1);
            border-color: #00d2ff;
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 210, 255, 0.2);
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
            color: white;
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
            color: #ffffff;
            transform: scale(1.2);
            text-shadow: 0 0 10px rgba(0, 210, 255, 0.8);
        }

        /* Contenedor de logos superiores */
        .top-logos {
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            display: flex;
            justify-content: space-between;
            padding: 0 40px;
            z-index: 10;
        }

        .logo-superior {
            height: 60px;
            width: auto;
            filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.2));
            transition: transform 0.3s ease;
        }

        .logo-superior:hover {
            transform: scale(1.05);
        }

        /* Estilos del Modal Oscuro */
        .modal-content.dark-theme {
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(0, 210, 255, 0.2);
            border-radius: 20px;
            color: white;
            box-shadow: 0 15px 35px rgba(0,0,0,0.5);
        }

        .modal-header.dark-theme {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .modal-footer.dark-theme {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .btn-close-white {
            filter: invert(1) grayscale(100%) brightness(200%);
        }

        /* Animaciones */
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-title { font-size: 2.5rem; }
            .subtitle { font-size: 1.1rem; }
            .logo-icon { font-size: 3.5rem; }
            .btn { width: 100%; justify-content: center; }
            .logo-superior { height: 40px; }
            .top-logos { padding: 0 15px; }
        }
    </style>
</head>


<body>
    <div class="overlay"></div>

    <div class="top-logos">
        <img src="img/logoUtm.png" alt="Logo UTM" class="logo-superior" style="height: 70px;">
    </div>

    <div class="main-container">

        <div class="logo-container">
            <div class="logo-icon">
                <img src="img/logo.png" class="img-logo-main" style="height: 170px;" alt="Logo IntegraGames">
            </div>
            <h1 class="main-title">IntegraGames</h1>
            <p class="subtitle">La integración de la carrera a través del juego</p>
        </div>

        <div class="features">
            <div class="feature-item" onclick="abrirModal('eventos')">
                <div class="feature-icon">
                    <i class="fa-solid fa-trophy"></i>
                </div>
                <div class="feature-title">Eventos</div>
                <div class="feature-desc">Prueba videojuegos exclusivos de TI</div>
                <span class="badge rounded-pill bg-primary mt-2 opacity-75" style="font-size: 0.7rem;">Saber más <i class="fas fa-arrow-right ms-1"></i></span>
            </div>

            <div class="feature-item" onclick="abrirModal('comunidad')">
                <div class="feature-icon">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div class="feature-title">Comunidad</div>
                <div class="feature-desc">Conoce a nuestros integrantes de la carrera</div>
                <span class="badge rounded-pill bg-primary mt-2 opacity-75" style="font-size: 0.7rem;">Saber más <i class="fas fa-arrow-right ms-1"></i></span>
            </div>

            <div class="feature-item" onclick="abrirModal('juegos')">
                <div class="feature-icon">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <div class="feature-title">Juegos</div>
                <div class="feature-desc">Descubre la tecnología detrás de ellos</div>
                <span class="badge rounded-pill bg-primary mt-2 opacity-75" style="font-size: 0.7rem;">Saber más <i class="fas fa-arrow-right ms-1"></i></span>
            </div>
        </div>

        <div class="btn-container">
            <a href="registroAlumnos/registro_alumno.php" class="btn">
                <i class="fa-solid fa-user-plus"></i> Registrarme
            </a>

            <a href="RegistroAdmin/login.php" class="btn btn2">
                <i class="fa-solid fa-right-to-bracket"></i> Encargado
            </a>
        </div>

        <div class="footer">
            <p>&copy; <?php echo date('Y'); ?> IntegraGames. Todos los derechos reservados.</p>
            <p>Desarrollado por alumnos de la Universidad Tecnológica de Morelia de la carrera TI</p>

            <div class="social-links">
                <a href="https://www.facebook.com/share/1CTdx2LSvG/" title="Facebook" target="_blank"><i class="fab fa-facebook-f"></i></a>
                <a href="https://www.instagram.com/utmmorelia?igsh=NzBsNHVlYTRyeTZk" title="Instagram" target="_blank"><i class="fab fa-instagram"></i></a>
                <a href="https://www.tiktok.com/@utmorelia?_r=1&_t=ZS-94h24oJcmpk" title="Tiktok" target="_blank"><i class="fab fa-tiktok"></i></a>
            </div>
        </div>

    </div>

    <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content dark-theme">
                <div class="modal-header dark-theme">
                    <h5 class="modal-title fw-bold" id="infoModalLabel">
                        <i id="modalIcon" class="me-2" style="color: #00d2ff;"></i>
                        <span id="modalTitle">Título</span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalBody">
                    </div>
                <div class="modal-footer dark-theme border-0 justify-content-center">
                    <button type="button" class="btn btn-outline-light rounded-pill px-4" data-bs-dismiss="modal">Entendido</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const informacion = {
            'eventos': {
                titulo: 'Acerca de los Eventos',
                icono: 'fa-solid fa-trophy',
                contenido: `
                    <p class="mb-3 text-light opacity-75">Nuestros eventos están diseñados para poner a prueba tus habilidades de lógica y programación mediante juegos competitivos y colaborativos.</p>
                    <h6 class="fw-bold" style="color: #00d2ff;">Lo que encontrarás:</h6>
                    <ul class="text-start text-light opacity-75">
                        <li>Torneos exclusivos para alumnos y visitantes.</li>
                        <li>Retos de programación contrarreloj.</li>
                        <li>Exhibición de proyectos finales de la carrera de TI.</li>
                    </ul>
                `
            },
            'comunidad': {
                titulo: 'Nuestra Comunidad',
                icono: 'fa-solid fa-users',
                contenido: `
                    <p class="mb-3 text-light opacity-75">IntegraGames fue desarrollado orgullosamente por alumnos de la Universidad Tecnológica de Morelia (UTM).</p>
                    <h6 class="fw-bold" style="color: #00d2ff;">Tecnologías utilizadas:</h6>
                    <div class="d-flex justify-content-center gap-3 mt-3 fs-3">
                        <i class="fab fa-html5" title="HTML5" style="color: #E34F26;"></i>
                        <i class="fab fa-css3-alt" title="CSS3" style="color: #1572B6;"></i>
                        <i class="fab fa-js" title="JavaScript" style="color: #F7DF1E;"></i>
                        <i class="fab fa-php" title="PHP" style="color: #777BB4;"></i>
                        <i class="fas fa-database" title="MySQL" style="color: #4479A1;"></i>
                        <i class="fab fa-bootstrap" title="Bootstrap" style="color: #7952B3;"></i>
                    </div>
                    <p class="mt-4 mb-0 small opacity-50">Trabajamos bajo metodologías ágiles (Scrum) para garantizar la calidad de la plataforma.</p>
                `
            },
            'juegos': {
                titulo: 'Nuestros Juegos',
                icono: 'fa-solid fa-shield-halved',
                contenido: `
                    <p class="mb-3 text-light opacity-75">En la Zona Arcade encontrarás juegos desarrollados para enseñar conceptos fundamentales de programación de forma divertida.</p>
                    <h6 class="fw-bold" style="color: #00d2ff;">Catálogo actual:</h6>
                    <div class="text-start mt-3">
                        <div class="mb-2"><i class="fas fa-check-circle me-2 text-success"></i> <strong>Error 404:</strong> Juego de agilidad mental.</div>
                        <div class="mb-2"><i class="fas fa-check-circle me-2 text-success"></i> <strong>Code Run:</strong> Esquiva bugs y compila código.</div>
                        <div class="mb-2"><i class="fas fa-check-circle me-2 text-success"></i> <strong>DesafioTech:</strong> Preguntas y respuestas de TI.</div>
                    </div>
                `
            }
        };

        function abrirModal(seccion) {
            // Obtenemos los datos según la sección que presionó el usuario
            const datos = informacion[seccion];

            // Inyectamos los datos en el modal
            document.getElementById('modalTitle').innerText = datos.titulo;
            document.getElementById('modalIcon').className = datos.icono + " me-2";
            document.getElementById('modalBody').innerHTML = datos.contenido;

            // Mostramos el modal usando Bootstrap
            const modal = new bootstrap.Modal(document.getElementById('infoModal'));
            modal.show();
        }
    </script>
</body>

</html>