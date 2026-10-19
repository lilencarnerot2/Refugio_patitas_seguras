<?php
$esInicio = true;
$refugio = require __DIR__ . '/public/includes/datos_refugio.php';
require __DIR__ . '/public/includes/head.php';
require __DIR__ . '/public/includes/header.php';
?>

   
    <section id="inicio" class="hero">
        <div class="hero-content">
            <h2>CAMBIÁ UNA VIDA, <span>GANÁ UN AMIGO.</span></h2>
            <p>En Patitas Seguras rescatamos, cuidamos y buscamos un hogar lleno de amor para cada patita que lo necesita.</p>
        </div>
        <div class="hero-images">
            <img src="https://images.unsplash.com/photo-1543466835-00a7907e9de1" alt="Perro y gato felices">
        </div>
    </section>

    
    <section id="adopta" class="action-cards">
        <a class="card" href="/public/mascotas.php#adopcion">
            <div class="card-body">
                <h3><i class="bi bi-house-heart"></i> ADOPTÁ O ABRÍ TU HOGAR</h3>
                <p>Adoptá o abrí tu hogar por un tiempo: dale a un animalito una segunda oportunidad de ser feliz.</p>
            </div>
            <img src="https://images.unsplash.com/photo-1583511655857-d19b40a7a54e" alt="Adoptar cachorro u ofrecer un hogar de tránsito">
            <span class="card-btn">CONOCÉ A LOS RESCATADOS</span>
        </a>
        <a class="card" href="/public/voluntariado.php">
            <div class="card-body">
                <h3><i class="bi bi-people-fill"></i> SE VOLUNTARIO</h3>
                <p>Tu tiempo y tu amor pueden cambiarles la vida.</p>
            </div>
            <img src="https://images.unsplash.com/photo-1601758228041-f3b2795255f1" alt="Voluntariado">
            <span class="card-btn">SUMATE COMO VOLUNTARIO</span>
        </a>
    </section>

   
    <section>
        <div class="stats-container">
            <div class="stat-item">
                <h4><span class="icono-pata"></span> +1200</h4>
                <p>Rescates realizados</p>
            </div>
            <div class="stat-item">
                <h4><i class="bi bi-heart-fill"></i> +980</h4>
                <p>Adopciones felices</p>
            </div>
            <div class="stat-item">
                <h4><i class="bi bi-house"></i> +150</h4>
                <p>Hogares de tránsito</p>
            </div>
            <div class="stat-item">
                <h4><i class="bi bi-people-fill"></i> +200</h4>
                <p>Voluntarios activos</p>
            </div>
        </div>
    </section>

    <section id="ayudar">
        <h3 class="ayudar-titulo">OTRAS FORMAS DE AYUDAR</h3>
        <div class="other-ways">
            <a class="way-card" href="/public/donaciones_insumos.php">
                <i class="bi bi-basket"></i>
                <div>
                    <h5>DONÁ ALIMENTOS</h5>
                    <p>Alimento, mantas, camitas y todo lo que podamos necesitar.</p>
                </div>
            </a>
            <a class="way-card" href="/public/voluntariado.php">
    <i class="bi bi-heart"></i>
    <div>
        <h5>VOLUNTARIADO</h5>
        <p>Sumate a dar una mano, paseos, cuidados y más actividades.</p>
    </div>
</a>
            <a class="way-card" href="/public/donaciones.php">
                <i class="bi bi-cash-coin"></i>
                <div>
                    <h5>HACÉ UNA DONACIÓN</h5>
                    <p>Tu donación nos permite seguir rescatando y cuidando más patitas.</p>
                </div>
            </a>
            <a class="way-card" href="#modal-difundi">
                <i class="bi bi-share"></i>
                <div>
                    <h5>DIFUNDÍ</h5>
                    <p>Compartí nuestras historias y ayudanos a encontrarles un hogar.</p>
                </div>
            </a>
        </div>
    </section>



    <div id="modal-voluntariado" class="modal-overlay">
        <div class="modal-content">
            <h3>Voluntariado</h3>
            <p>Anotate a un turno desde el formulario. Te llega un mail con los datos, la dirección, las indicaciones y el WhatsApp del refugio.</p>
            <a class="close-modal" href="/public/voluntariado.php">Ir al formulario</a>
        </div>
    </div>
    <div id="modal-donaciones" class="modal-overlay">
        <div class="modal-content">
            <h3>Donaciones</h3>
            <p>¡Muchas gracias! Podés colaborar mediante transferencia a nuestro alias oficial: <?php echo htmlspecialchars($refugio['alias_principal'], ENT_QUOTES, 'UTF-8'); ?> o por Mercado Pago.</p>
            <a class="close-modal" href="#">Entendido</a>
        </div>
    </div>
   <a href="donar_alimentos.php" class="card-link">
    <div class="card">
        <div class="card-icon">
    </div>
</a>


<?php require __DIR__ . '/public/includes/footer.php'; ?>