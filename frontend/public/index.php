<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patitas Seguras - Refugio de animales</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css?v=menu4">
</head>
<body>

    <!-- HEADER -->
    <header>
        <a class="logo-container" href="#inicio">
            <div style="background: var(--primary-color); border-radius: 50%; padding: 8px; display: flex; align-items: center; justify-content: center; color: white; font-size: 20px;">
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
                <li><a href="#inicio">INICIO</a></li>
                <li><a href="#nosotros">NOSOTROS</a></li>
                <li class="nav-dropdown">
                    <a tabindex="0">SUMATE</a>
                    <ul class="dropdown-menu">
                        <li><a href="mascotas.php#adopcion">ADOPTÁ</a></li>
                        <li><a href="mascotas.php#transito">HOGAR DE TRÁNSITO</a></li>
                        <li><a href="#modal-voluntariado">SE VOLUNTARIO</a></li>
                    </ul>
                </li>
                <li class="nav-dropdown">
                    <a tabindex="0">CÓMO AYUDAR</a>
                    <ul class="dropdown-menu">
                        <li><a href="#modal-castracion">CASTRACIÓN</a></li>
                        <li><a href="#modal-maltrato">DENUNCIA MALTRATO</a></li>
                    </ul>
                </li>
                <li><a href="#contacto">CONTACTO</a></li>
            </ul>
        </nav>
        <div class="header-actions">
            <a class="btn-donate" href="#modal-donaciones">
                <i class="fa-solid fa-heart"></i> DONAR
            </a>
            <label for="nav-toggle" class="menu-toggle" aria-label="Menú">
                <i class="fa-solid fa-bars menu-icon-bars"></i>
                <i class="fa-solid fa-xmark menu-icon-close"></i>
            </label>
        </div>
    </header>

    <!-- INICIO / HERO -->
    <section id="inicio" class="hero">
        <div class="hero-content">
            <h2>CAMBIÁ UNA VIDA, <span>GANÁ UN AMIGO.</span></h2>
            <p>En Patitas Seguras rescatamos, cuidamos y buscamos un hogar lleno de amor para cada patita que lo necesita.</p>
        </div>
        <div class="hero-images">
            <img src="https://images.unsplash.com/photo-1543466835-00a7907e9de1" alt="Perro y gato felices">
        </div>
    </section>

    <!-- TARJETAS PRINCIPALES -->
    <section id="adopta" class="action-cards">
        <a class="card" href="mascotas.php#adopcion">
            <div class="card-body">
                <h3><i class="fa-solid fa-house"></i> ADOPTÁ</h3>
                <p>Dale a un animalito una segunda oportunidad de ser feliz.</p>
            </div>
            <img src="https://images.unsplash.com/photo-1583511655857-d19b40a7a54e" alt="Adoptar cachorro">
            <span class="card-btn">CONOCÉ A LOS RESCATADOS</span>
        </a>
        <a class="card" href="#modal-voluntariado">
            <div class="card-body">
                <h3><i class="fa-solid fa-hand-holding-heart"></i> SE VOLUNTARIO</h3>
                <p>Tu tiempo y tu amor pueden cambiarles la vida.</p>
            </div>
            <img src="https://images.unsplash.com/photo-1601758228041-f3b2795255f1" alt="Voluntariado">
            <span class="card-btn">SUMATE COMO VOLUNTARIO</span>
        </a>
        <a class="card" href="mascotas.php#transito">
            <div class="card-body">
                <h3><i class="fa-solid fa-house-chimney"></i> HOGAR DE TRÁNSITO</h3>
                <p>Abri tu hogar por un tiempo y ayudanos a salvar más vidas.</p>
            </div>
            <img src="https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba" alt="Hogar de transito">
            <span class="card-btn">MÁS INFO</span>
        </a>
    </section>

    <!-- ESTADÍSTICAS -->
    <section>
        <div class="stats-container">
            <div class="stat-item">
                <h4><i class="fa-solid fa-paw" style="font-size: 16px;"></i> +1200</h4>
                <p>Rescates realizados</p>
            </div>
            <div class="stat-item">
                <h4><i class="fa-solid fa-heart" style="font-size: 16px;"></i> +980</h4>
                <p>Adopciones felices</p>
            </div>
            <div class="stat-item">
                <h4><i class="fa-solid fa-house" style="font-size: 16px;"></i> +150</h4>
                <p>Hogares de tránsito</p>
            </div>
            <div class="stat-item">
                <h4><i class="fa-solid fa-users" style="font-size: 16px;"></i> +200</h4>
                <p>Voluntarios activos</p>
            </div>
        </div>
    </section>

    <!-- NOSOTROS -->
    <section id="nosotros">
        <div style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
            <h3 style="color: var(--primary-color); margin-bottom: 15px; font-size: 24px;">SOBRE NOSOTROS</h3>
            <p style="color: #555; line-height: 1.6; font-size: 14px;">Somos un refugio independiente sin fines de lucro. Trabajamos con compromiso y amor para rescatar animales en situación de calle o abandono, brindarles atención veterinaria, alimento y contención, y encontrarles el hogar que merecen.</p>
        </div>
    </section>

    <!-- OTRAS FORMAS DE AYUDAR -->
    <section id="ayudar">
        <h3 style="color: var(--primary-color); margin-bottom: 20px; text-align: center; font-size: 22px;">OTRAS FORMAS DE AYUDAR</h3>
        <div class="other-ways">
            <a class="way-card" href="#modal-alimentos">
                <i class="fa-solid fa-bowl-food"></i>
                <div>
                    <h5>DONÁ ALIMENTOS</h5>
                    <p>Alimento, mantas, camitas y todo lo que podamos necesitar.</p>
                </div>
            </a>
            <a class="way-card" href="#modal-apadrina">
                <i class="fa-solid fa-circle-plus"></i>
                <div>
                    <h5>APADRINÁ</h5>
                    <p>Con tu aporte mensual nos ayudás a cubrir tratamientos y cuidados.</p>
                </div>
            </a>
            <a class="way-card" href="#modal-donaciones">
                <i class="fa-solid fa-circle-dollar-to-slot"></i>
                <div>
                    <h5>HACÉ UNA DONACIÓN</h5>
                    <p>Tu donación nos permite seguir rescatando y cuidando más patitas.</p>
                </div>
            </a>
            <a class="way-card" href="#modal-difundi">
                <i class="fa-solid fa-share-nodes"></i>
                <div>
                    <h5>DIFUNDÍ</h5>
                    <p>Compartí nuestras historias y ayudanos a encontrarles un hogar.</p>
                </div>
            </a>
        </div>
    </section>

    <!-- FOOTER / CONTACTO -->
    <footer id="contacto">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
            <div>
                <h3>PATITAS SEGURAS</h3>
                <p style="font-size: 12px; color: #ccc;">Refugio de animales</p>
                <div class="social-icons">
                    <a href="https://facebook.com" target="_blank" title="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="https://instagram.com" target="_blank" title="Instagram"><i class="fa-brands fa-instagram"></i></a>
                    <a href="https://tiktok.com" target="_blank" title="TikTok"><i class="fa-brands fa-tiktok"></i></a>
                </div>
            </div>
            <div>
                <p style="font-size: 13px;"><i class="fa-solid fa-phone"></i> 11 2345 6789</p>
                <p style="font-size: 13px; margin-top: 5px;"><i class="fa-solid fa-envelope"></i> hola@patitaseguras.org</p>
                <p style="font-size: 13px; margin-top: 5px;"><i class="fa-solid fa-location-dot"></i> Merlo, Buenos Aires, Argentina</p>
                <p style="font-size: 12px; margin-top: 10px; color: #ccc;">Amor que se nota, huellas que quedan. 🐾</p>
            </div>
        </div>
    </footer>

    <div id="modal-voluntariado" class="modal-overlay">
        <div class="modal-content">
            <h3>Voluntariado</h3>
            <p>¡Gracias por querer sumarte! Dejanos tu mensaje y nos pondremos en contacto para coordinar las actividades.</p>
            <a class="close-modal" href="#">Entendido</a>
        </div>
    </div>
    <div id="modal-castracion" class="modal-overlay">
        <div class="modal-content">
            <h3>Castración</h3>
            <p>Organizamos y difundimos campañas de castración para prevenir el abandono y mejorar la salud de perros y gatos.</p>
            <a class="close-modal" href="#">Entendido</a>
        </div>
    </div>
    <div id="modal-maltrato" class="modal-overlay">
        <div class="modal-content">
            <h3>Denuncia maltrato</h3>
            <p>Si presenciás maltrato animal, escribinos y te orientamos para hacer la denuncia correspondiente.</p>
            <a class="close-modal" href="#">Entendido</a>
        </div>
    </div>
    <div id="modal-donaciones" class="modal-overlay">
        <div class="modal-content">
            <h3>Donaciones</h3>
            <p>¡Muchas gracias! Podés colaborar mediante transferencia a nuestro alias oficial: PATITAS.SEGURAS.MP o por Mercado Pago.</p>
            <a class="close-modal" href="#">Entendido</a>
        </div>
    </div>
    <div id="modal-alimentos" class="modal-overlay">
        <div class="modal-content">
            <h3>Donar Alimentos</h3>
            <p>Recibimos alimento balanceado, pipetas, mantas en buen estado y artículos de limpieza.</p>
            <a class="close-modal" href="#">Entendido</a>
        </div>
    </div>
    <div id="modal-apadrina" class="modal-overlay">
        <div class="modal-content">
            <h3>Apadriná</h3>
            <p>El apadrinamiento mensual nos ayuda a sostener los gastos veterinarios fijos de los rescatados.</p>
            <a class="close-modal" href="#">Entendido</a>
        </div>
    </div>
    <div id="modal-difundi" class="modal-overlay">
        <div class="modal-content">
            <h3>Difundí</h3>
            <p>Compartiendo nuestras publicaciones en redes sociales nos ayudás a llegar a más personas.</p>
            <a class="close-modal" href="#">Entendido</a>
        </div>
    </div>


</body>
</html>