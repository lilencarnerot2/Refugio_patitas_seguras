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
            <span class="filtro-btn" data-activa="true" data-especie=""><i class="fa-solid fa-paw"></i> Todos</span>
            <span class="filtro-btn" id="filtro-modelo-especie" data-activa="false" hidden><i class="fa-solid fa-paw"></i> <span data-texto-filtro></span></span>
        </div>
    </section>

    <section id="catalogo">
        <div id="contenedor-mascotas" class="pet-catalog"></div>
        <div id="aviso-catalogo" class="catalog-empty" hidden>
            <i class="fa-solid fa-paw"></i>
            <h4>Todavía no hay mascotas cargadas</h4>
            <p>No se encontraron mascotas disponibles.</p>
        </div>
    </section>

    <div id="modelos-catalogo" hidden>
        <article class="pet-card" id="tarjeta-modelo" data-separador=" · ">
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
    </div>

<script src="/public/config.php"></script>
<script src="/assets/js/api.js"></script>
<script src="/assets/js/mascotas.js"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
