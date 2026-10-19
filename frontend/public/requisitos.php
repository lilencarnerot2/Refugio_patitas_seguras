<?php
$tituloPagina = 'Requisitos - Patitas Seguras';
$cuerpoClase = 'page-adapt';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <div class="page-intro requisitos-titulo">
        <h2>Requisitos</h2>
    </div>

    <div class="requisitos-columnas">
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

        <div class="requisitos-accion">
            <a class="btn-primary" href="/public/mascotas.php#adopcion"><i class="bi bi-search-heart"></i> Ver catálogo de mascotas</a>
        </div>
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

        <div class="requisitos-accion">
            <a class="btn-primary" href="/public/mascotas.php"><i class="bi bi-search-heart"></i> Ver catálogo de mascotas</a>
        </div>
    </section>

    <section class="requisitos-section" id="voluntariado" data-tipo-solicitud="voluntariado">
        <div class="page-intro">
            <h2>Voluntariado</h2>
            <p>Sumá tu tiempo y tu cariño para ayudar en el día a día del refugio.</p>
        </div>

        <div class="requisitos-grid">
            <div class="requisito-item"><span class="requisito-mark"></span>No hace falta usuario registrado.</div>
            <div class="requisito-item"><span class="requisito-mark"></span>Completar tus datos de contacto.</div>
            <div class="requisito-item"><span class="requisito-mark"></span>Elegir fecha y horario de un turno disponible.</div>
            <div class="requisito-item"><span class="requisito-mark"></span>Contar tu experiencia con perros y gatos.</div>
            <div class="requisito-item"><span class="requisito-mark"></span>Indicar con qué tareas podés ayudar.</div>
            <div class="requisito-item"><span class="requisito-mark"></span>Aceptar las indicaciones del refugio.</div>
            <div class="requisito-item"><span class="requisito-mark"></span>Avisar si no podés asistir.</div>
        </div>


        <div class="requisitos-accion">
            <a class="btn-primary" href="/public/voluntariado.php"><i class="bi bi-hand-thumbs-up"></i> Quiero ser voluntario</a>
        </div>
    </section>
    </div>

<?php require __DIR__ . '/includes/footer.php'; ?>
