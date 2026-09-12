<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mascotas - Patitas Seguras</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body class="page-mascotas">

<?php require __DIR__ . '/includes/header.php'; ?>

    <section>
        <div class="page-intro" id="adopcion">
            <h2>Conocelos</h2>
            <p>Todos nuestros animales están vacunados, desparasitados y evaluados por nuestro veterinario. Encontrá tu compañero ideal.</p>
        </div>

        <div class="catalog-filters">
            <span class="filtro-btn is-activo"><i class="fa-solid fa-paw"></i> Todos</span>
            <span class="filtro-btn"><i class="fa-solid fa-dog"></i> Perros</span>
            <span class="filtro-btn"><i class="fa-solid fa-cat"></i> Gatos</span>
        </div>
    </section>

    <section id="catalogo">
        <div id="contenedor-mascotas" class="pet-catalog"></div>
    </section>

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
<script src="../assets/js/mascotas.js?t=<?php echo filemtime(__DIR__ . '/../assets/js/mascotas.js'); ?>"></script>

</body>
</html>
