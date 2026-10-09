<?php
$esInicio = true;
require __DIR__ . '/public/includes/head.php';
require __DIR__ . '/public/includes/header.php';
?>

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
        <a class="card" href="/public/mascotas.php#adopcion">
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
        <a class="card" href="/public/mascotas.php#transito">
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
                <h4><i class="fa-solid fa-paw"></i> +1200</h4>
                <p>Rescates realizados</p>
            </div>
            <div class="stat-item">
                <h4><i class="fa-solid fa-heart"></i> +980</h4>
                <p>Adopciones felices</p>
            </div>
            <div class="stat-item">
                <h4><i class="fa-solid fa-house"></i> +150</h4>
                <p>Hogares de tránsito</p>
            </div>
            <div class="stat-item">
                <h4><i class="fa-solid fa-users"></i> +200</h4>
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

<?php require __DIR__ . '/public/includes/footer.php'; ?>