<?php
$tituloPagina = 'Voluntariado - Patitas Seguras';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <section>
        <div class="page-intro">
            <h2>Voluntariado</h2>
            <p>Inscribite a una actividad. Si la inscripción se registra correctamente, el refugio puede enviarte una confirmación por correo o WhatsApp.</p>
        </div>

        <div class="info-card info-card-single">
            <h3><i class="fa-solid fa-hand-holding-heart"></i> Cómo sumarte</h3>
            <p>El voluntariado es puntual según cada actividad. Completar este formulario no implica quedar registrado para comunicaciones periódicas.</p>
            <ul>
                <li>Paseos, socialización y cuidado en el refugio.</li>
                <li>Apoyo en campañas de castración y difusión.</li>
                <li>La confirmación la coordina el backend con el servicio de notificaciones.</li>
            </ul>
        </div>

        <form id="form-voluntariado" class="public-form">
            <h3>Inscripción</h3>
            <p>Dejanos tus datos y nos ponemos en contacto para coordinar.</p>

            <div class="mb-3">
                <label class="form-label" for="nombre">Nombre</label>
                <input class="form-control" id="nombre" name="nombre" type="text" required>
            </div>
            <div class="mb-3">
                <label class="form-label" for="email">Correo electrónico</label>
                <input class="form-control" id="email" name="email" type="email" required>
            </div>
            <div class="mb-3">
                <label class="form-label" for="telefono">Teléfono / WhatsApp</label>
                <input class="form-control" id="telefono" name="telefono" type="tel">
            </div>
            <div class="mb-3">
                <label class="form-label" for="actividad">Actividad de interés</label>
                <input class="form-control" id="actividad" name="actividad" type="text">
            </div>
            <div class="mb-3">
                <label class="form-label" for="mensaje">Mensaje</label>
                <textarea class="form-control" id="mensaje" name="mensaje" rows="4" required></textarea>
            </div>
            <button class="btn-primary" type="submit">Enviar inscripción</button>
            <p id="form-feedback" class="form-feedback" hidden></p>
        </form>
    </section>

<script src="config.php"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/voluntariado.js"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
