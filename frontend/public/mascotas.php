<?php
$tituloPagina = 'Mascotas - Patitas Seguras';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

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

    <section id="catalogo" style="padding-top: 0;">
        <div class="catalog-header">
            <h3><i class="fa-solid fa-paw"></i> Mascotas disponibles</h3>
            <span id="catalogo-count">Cargando...</span>
        </div>

        <div id="contenedor-mascotas" class="pet-catalog"></div>
    </section>

    <script src="config.php"></script>
    <script src="../assets/js/api.js"></script>
    <script src="../assets/js/mascotas.js"></script>
<?php require __DIR__ . '/includes/footer.php'; ?>
