<?php
$esInicio = true;
$refugio = require __DIR__ . '/public/includes/datos_refugio.php';
require __DIR__ . '/public/includes/head.php';
require __DIR__ . '/public/includes/header.php';
?>

   
    <section id="inicio" class="hero">
        <img class="hero-image" src="/assets/img/perrosrefugio.jpeg" alt="Tres perros rescatados en el refugio">
        <div class="hero-content">
            <h2>CAMBIÁ UNA VIDA, <span>GANÁ UN AMIGO.</span></h2>
            <p>En Patitas Seguras rescatamos, cuidamos y buscamos un hogar lleno de amor para cada patita que lo necesita.</p>
        </div>
    </section>

    
    <section id="adopta" class="action-cards">
        <a class="card" href="/public/mascotas.php#adopcion">
            <div class="card-body">
                <h3><i class="bi bi-house-heart"></i> ADOPTÁ O ABRÍ TU HOGAR</h3>
                <p>Adoptá o abrí tu hogar por un tiempo: dale a un animalito una segunda oportunidad de ser feliz.</p>
            </div>
            <img src="/assets/img/perro y gato.jpg" alt="Un perro y un gato juntos">
            <span class="card-btn">CONOCÉ A LOS RESCATADOS</span>
        </a>
        <a class="card" href="/public/voluntariado.php">
            <div class="card-body">
                <h3><i class="bi bi-people-fill"></i> SE VOLUNTARIO</h3>
                <p>Tu tiempo y tu amor pueden cambiarles la vida.</p>
            </div>
            <img src="/assets/img/voluntarios.jpg" alt="Personas voluntarias junto a animales rescatados">
            <span class="card-btn">SUMATE COMO VOLUNTARIO</span>
        </a>
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
            <a class="way-card" href="/public/castraciones.php">
                <i class="bi bi-plus-circle"></i>
                <div>
                    <h5>APADRINÁ</h5>
                    <p>Con tu aporte mensual nos ayudás a cubrir tratamientos y cuidados.</p>
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

    <div id="modal-difundi" class="modal-overlay">
        <div class="modal-content">
            <h3>Difundí</h3>
            <p>Compartiendo nuestras publicaciones en redes sociales nos ayudás a llegar a más personas.</p>
            <a class="close-modal" href="#">Entendido</a>
        </div>
    </div>

<?php require __DIR__ . '/public/includes/footer.php'; ?>