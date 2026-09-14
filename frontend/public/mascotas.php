<?php
$tituloPagina = 'Mascotas - Patitas Seguras';
$cuerpoClase = 'page-adapt';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <section>
        <div class="page-intro" id="adopcion">
            <h2>Conocelos</h2>
            <p>Todos nuestros animales están vacunados, desparasitados y evaluados por nuestro veterinario. Encontrá tu compañero ideal.</p>
        </div>

        <div class="catalog-filters" id="filtros-especies">
            <span class="filtro-btn is-activo" data-especie=""><i class="fa-solid fa-paw"></i> Todos</span>
        </div>
    </section>

    <section id="catalogo">
        <div id="contenedor-mascotas" class="pet-catalog"></div>
    </section>

    <template id="plantilla-tarjeta">
        <article class="pet-card">
            <img hidden alt="">
            <div class="pet-photo-empty"></div>
            <div class="pet-card-body">
                <h4></h4>
                <p class="pet-card-meta"></p>
                <div class="pet-card-actions">
                    <a class="btn-adopt" href="../user/solicitar_adopcion.php">Adoptar</a>
                    <a class="btn-transito" href="../user/solicitar_transito.php">Hogar de tránsito</a>
                    <a class="btn-detail" href="mascota-detalle.php">Ver detalle</a>
                </div>
            </div>
        </article>
    </template>

    <section class="requisitos-section" id="transito">
        <div class="page-intro">
            <h2>Requisitos para adoptar</h2>
            <p>Nos aseguramos de que cada animal vaya al hogar ideal para él.</p>
        </div>

        <div class="requisitos-grid">
            <div class="requisito-item"><span class="requisito-mark"></span>Usuario registrado.</div>
            <div class="requisito-item"><span class="requisito-mark"></span>La solicitud va asociada a una mascota.</div>
            <div class="requisito-item"><span class="requisito-mark"></span>Completar vivienda, patio y hogar.</div>
            <div class="requisito-item"><span class="requisito-mark"></span>Integrantes, menores y horas sola.</div>
            <div class="requisito-item"><span class="requisito-mark"></span>Otras mascotas, experiencia y cuidado.</div>
            <div class="requisito-item"><span class="requisito-mark"></span>Compromisos y seguimiento posterior.</div>
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

<script src="/public/config.php"></script>
<script src="/assets/js/api.js"></script>
<script src="/assets/js/mascotas.js"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
