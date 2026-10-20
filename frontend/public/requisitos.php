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
            <p>Encontrá una familia definitiva para una mascota y asumí el compromiso de acompañarla durante toda su vida.</p>
        </div>

        <div class="requisitos-grid">
            <div class="requisito-item">
                <span class="requisito-mark"></span>
                Ser mayor de 18 años.
            </div>

            <div class="requisito-item">
                <span class="requisito-mark"></span>
                Tener una cuenta registrada en Patitas Seguras.
            </div>

            <div class="requisito-item">
                <span class="requisito-mark"></span>
                Elegir una mascota disponible para adopción.
            </div>

            <div class="requisito-item">
                <span class="requisito-mark"></span>
                Completar los datos de vivienda y del grupo familiar.
            </div>

            <div class="requisito-item">
                <span class="requisito-mark"></span>
                Informar si hay niños u otras mascotas en el hogar.
            </div>

            <div class="requisito-item">
                <span class="requisito-mark"></span>
                Contar con tiempo, recursos y condiciones adecuadas para su cuidado.
            </div>

            <div class="requisito-item">
                <span class="requisito-mark"></span>
                Aceptar los compromisos de adopción y el seguimiento posterior.
            </div>
        </div>

        <div class="requisitos-accion">
            <a class="btn-primary" href="/public/mascotas.php#adopcion">
                <i class="bi bi-search-heart"></i>
                Ver catálogo de mascotas
            </a>
        </div>
    </section>


    <section class="requisitos-section" id="transito" data-tipo-solicitud="transito">
        <div class="page-intro">
            <h2>Hogar de tránsito</h2>
            <p>Brindá un hogar temporal y seguro mientras la mascota espera su adopción definitiva.</p>
        </div>

        <div class="requisitos-grid">
            <div class="requisito-item">
                <span class="requisito-mark"></span>
                Ser mayor de 18 años.
            </div>

            <div class="requisito-item">
                <span class="requisito-mark"></span>
                Tener una cuenta registrada en Patitas Seguras.
            </div>

            <div class="requisito-item">
                <span class="requisito-mark"></span>
                Elegir una mascota disponible para hogar de tránsito.
            </div>

            <div class="requisito-item">
                <span class="requisito-mark"></span>
                Completar los datos de vivienda y del grupo familiar.
            </div>

            <div class="requisito-item">
                <span class="requisito-mark"></span>
                Informar si hay niños u otras mascotas en el hogar.
            </div>

            <div class="requisito-item">
                <span class="requisito-mark"></span>
                Indicar la capacidad del hogar y el período de disponibilidad.
            </div>

            <div class="requisito-item">
                <span class="requisito-mark"></span>
                Informar si puede administrar medicación cuando sea necesario.
            </div>

            <div class="requisito-item">
                <span class="requisito-mark"></span>
                Aceptar el seguimiento y las visitas programadas por el refugio.
            </div>
        </div>

        <div class="requisitos-accion">
            <a class="btn-primary" href="/public/mascotas.php">
                <i class="bi bi-search-heart"></i>
                Ver catálogo de mascotas
            </a>
        </div>
    </section>


    <section class="requisitos-section" id="voluntariado" data-tipo-solicitud="voluntariado">
        <div class="page-intro">
            <h2>Voluntariado</h2>
            <p>Sumá tu tiempo y colaboración para acompañar las actividades y el cuidado diario del refugio.</p>
        </div>

        <div class="requisitos-grid">
            <div class="requisito-item">
                <span class="requisito-mark"></span>
                No es necesario tener una cuenta registrada.
            </div>

            <div class="requisito-item">
                <span class="requisito-mark"></span>
                Completar los datos personales y de contacto.
            </div>

            <div class="requisito-item">
                <span class="requisito-mark"></span>
                Seleccionar una fecha y un turno disponible.
            </div>

            <div class="requisito-item">
                <span class="requisito-mark"></span>
                Indicar experiencia previa con animales, si la hubiera.
            </div>

            <div class="requisito-item">
                <span class="requisito-mark"></span>
                Informar en qué tareas puede colaborar.
            </div>

            <div class="requisito-item">
                <span class="requisito-mark"></span>
                Comprometerse a respetar las indicaciones del refugio.
            </div>

            <div class="requisito-item">
                <span class="requisito-mark"></span>
                Avisar con anticipación si no puede asistir.
            </div>
        </div>

        <div class="requisitos-accion">
            <a class="btn-primary" href="/public/voluntariado.php">
                <i class="bi bi-hand-thumbs-up"></i>
                Quiero ser voluntario
            </a>
        </div>
    </section>

</div>

<?php require __DIR__ . '/includes/footer.php'; ?>