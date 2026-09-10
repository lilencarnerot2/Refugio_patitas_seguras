<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mascotas - Patitas Seguras</title>
    <script src="../assets/js/mascotas.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body class="page-mascotas has-mobile-nav">

    <header>
        <a class="logo-container" href="index.php">
            <div class="logo-icon">
                <i class="fa-solid fa-paw"></i>
            </div>
            <div class="logo-text">
                <h1>PATITAS SEGURAS</h1>
                <span>Refugio de animales</span>
            </div>
        </a>
        <input type="checkbox" id="nav-toggle" class="nav-toggle">
        <nav>
            <ul>
                <li><a href="index.php">INICIO</a></li>
                <li><a href="index.php#nosotros">NOSOTROS</a></li>
                <li class="nav-dropdown">
                    <details>
                        <summary>SUMATE</summary>
                        <ul class="dropdown-menu">
                            <li><a href="mascotas.php#adopcion">ADOPTÁ</a></li>
                            <li><a href="mascotas.php#transito">HOGAR DE TRÁNSITO</a></li>
                            <li><a href="index.php#modal-voluntariado">SE VOLUNTARIO</a></li>
                        </ul>
                    </details>
                </li>
                <li class="nav-dropdown">
                    <details>
                        <summary>CÓMO AYUDAR</summary>
                        <ul class="dropdown-menu">
                            <li><a href="index.php#modal-castracion">CASTRACIÓN</a></li>
                            <li><a href="index.php#modal-maltrato">DENUNCIA MALTRATO</a></li>
                        </ul>
                    </details>
                </li>
                <li><a href="index.php#contacto">CONTACTO</a></li>
            </ul>
        </nav>
        <div class="header-actions">
            <a class="btn-donate" href="index.php#modal-donaciones">
                <i class="fa-solid fa-heart"></i> DONAR
            </a>
            <label for="nav-toggle" class="menu-toggle" aria-label="Menú">
                <i class="fa-solid fa-bars menu-icon-bars"></i>
                <i class="fa-solid fa-xmark menu-icon-close"></i>
            </label>
        </div>
    </header>

    <section>
        <div class="page-intro">
            <h2>Encontrá a tu compañero ideal</h2>
            <p>Conocé a nuestros rescatados y elegí cómo querés ayudarlos: con una adopción responsable, ofreciendo un hogar de tránsito o revisando el detalle de cada mascota.</p>
        </div>

        <div class="info-cards">
            <div class="info-card" id="adopcion">
                <h3><i class="fa-solid fa-house"></i> Adopción responsable</h3>
                <p>Adoptar es un compromiso a largo plazo. Te acompañamos en todo el proceso para asegurar el bienestar del animal y de tu familia.</p>
                <ul>
                    <li>Entrevista y evaluación del hogar.</li>
                    <li>Mascotas con control veterinario y desparasitación.</li>
                    <li>Seguimiento posterior a la adopción.</li>
                </ul>
            </div>
            <div class="info-card" id="transito">
                <h3><i class="fa-solid fa-house-chimney"></i> Hogar de tránsito</h3>
                <p>Un hogar temporal permite que un rescatado se recupere en un ambiente cálido mientras encontramos su familia definitiva.</p>
                <ul>
                    <li>Colaboración por un tiempo acordado.</li>
                    <li>El refugio cubre gastos veterinarios acordados.</li>
                    <li>Ideal si no podés adoptar de forma permanente.</li>
                </ul>
            </div>
        </div>
    </section>

    <section id="catalogo">
        <div class="catalog-header">
            <h3><i class="fa-solid fa-paw"></i> Mascotas disponibles</h3>
            <span id="catalogo-count">Cargando...</span>
        </div>

        <div class="catalog-filters">
            <input type="radio" name="filtro-especie" id="filtro-todos" checked>
            <label for="filtro-todos">Todos</label>
        </div>

        <div id="contenedor-mascotas" class="pet-catalog"></div>
    </section>

    <section class="requisitos-section">
        <div class="page-intro">
            <h2>Requisitos para adoptar</h2>
            <p>El formulario lo evalúa el refugio. La decisión la toma el personal autorizado.</p>
        </div>

        <div class="requisitos-grid">
            <div class="requisito-item"><span class="requisito-mark" aria-hidden="true"></span>Usuario registrado.</div>
            <div class="requisito-item"><span class="requisito-mark" aria-hidden="true"></span>La solicitud va asociada a una mascota.</div>
            <div class="requisito-item"><span class="requisito-mark" aria-hidden="true"></span>Completar vivienda, patio y hogar.</div>
            <div class="requisito-item"><span class="requisito-mark" aria-hidden="true"></span>Integrantes, menores y horas sola.</div>
            <div class="requisito-item"><span class="requisito-mark" aria-hidden="true"></span>Otras mascotas, experiencia y cuidado.</div>
            <div class="requisito-item"><span class="requisito-mark" aria-hidden="true"></span>Compromisos y seguimiento posterior.</div>
        </div>

        <details class="proceso-adopcion">
            <summary>Proceso de adopción</summary>
            <ol>
                <li>Ingresá con un usuario registrado.</li>
                <li>Elegí la mascota y completá el formulario.</li>
                <li>El refugio evalúa. La compatibilidad es orientativa.</li>
            </ol>
        </details>
    </section>

    <footer>
        <div class="footer-inner">
            <div>
                <h3>PATITAS SEGURAS</h3>
                <p class="footer-tagline">Refugio de animales</p>
            </div>
            <div class="footer-contact">
                <p><i class="fa-solid fa-envelope"></i> hola@patitaseguras.org</p>
                <p><i class="fa-solid fa-location-dot"></i> Merlo, Buenos Aires, Argentina</p>
            </div>
        </div>
    </footer>

<script src="config.php"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/app.js"></script>
<script src="../assets/js/mascotas.js"></script>

</body>
</html>