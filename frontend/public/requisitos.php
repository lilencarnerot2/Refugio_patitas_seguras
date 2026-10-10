<?php
$tituloPagina = 'Requisitos - Patitas Seguras';
$cuerpoClase = 'page-adapt';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <div class="page-intro requisitos-titulo">
        <h2>Requisitos</h2>
    </div>

    <div class="requisitos-dos-columnas">
    <section class="requisitos-section" id="adopcion" data-tipo-solicitud="adopcion">
        <div class="page-intro">
            <h2>Adopción</h2>
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
                <li>El refugio evalúa, su compatibilidad.</li>
            </ol>
        </details>
    </section>

    <section class="requisitos-section" id="transito" data-tipo-solicitud="transito">
        <div class="page-intro">
            <h2>Hogar de tránsito</h2>
            <p>Cuidá una mascota de forma temporal mientras espera su hogar definitivo.</p>
        </div>

        <div class="requisitos-grid">
            <div class="requisito-item"><span class="requisito-mark"></span>Usuario registrado.</div>
            <div class="requisito-item"><span class="requisito-mark"></span>La solicitud va asociada a una mascota.</div>
            <div class="requisito-item"><span class="requisito-mark"></span>Completar vivienda, patio e integrantes.</div>
            <div class="requisito-item"><span class="requisito-mark"></span>Menores y tiempo que pasa sola.</div>
            <div class="requisito-item"><span class="requisito-mark"></span>Mascotas existentes y capacidad disponible.</div>
            <div class="requisito-item"><span class="requisito-mark"></span>Medicación, disponibilidad y seguimiento.</div>
        </div>

        <details class="proceso-transito">
            <summary>Proceso de hogar de tránsito</summary>
            <ol>
                <li>Ingresá con un usuario registrado.</li>
                <li>Elegí la mascota y completá el formulario.</li>
                <li>El refugio evalúa y coordina el cuidado temporal.</li>
            </ol>
        </details>
    </section>
    </div>

<?php require __DIR__ . '/includes/footer.php'; ?>
