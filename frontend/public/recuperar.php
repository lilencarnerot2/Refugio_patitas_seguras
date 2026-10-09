<?php
$tituloPagina = 'Recuperar contraseña - Patitas Seguras';
$cuerpoClase = 'page-adapt';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <section>
        <div class="page-intro">
            <h2 id="titulo-recuperar">Recuperar contraseña</h2>
            <p id="subtitulo-recuperar">Ingresá tu correo y te enviamos un link para crear una nueva.</p>
        </div>

        <!-- Formulario A: pedir email (sin token) -->
        <form id="form-recuperar-email" class="public-form login-form" novalidate>
            <div class="mb-3">
                <label class="form-label" for="email">Correo electrónico</label>
                <input class="form-control" id="email" name="email" type="text" autocomplete="email" required>
            </div>

            <p class="campo-obligatorio"></p>

            <input class="form-intento" id="intento-recuperar-email" name="intento-recuperar-email" type="radio">
            <label class="btn-primary" for="intento-recuperar-email">Enviar instrucciones</label>
            <p class="form-alt-link"><a href="login.php">Volver a iniciar sesión</a></p>
        </form>

        <!-- Formulario B: nueva contraseña (con token) -->
        <form id="form-recuperar-password" class="public-form login-form" novalidate hidden>
            <input type="hidden" id="token" name="token">

            <div class="mb-3">
                <label class="form-label" for="password">Nueva contraseña</label>
                <div class="clave-wrap">
                    <input class="form-control clave-campo" id="password" name="password" type="text" autocomplete="new-password" required>
                    <input class="clave-toggle" id="ver-clave-recuperar" type="checkbox">
                    <label class="clave-ojo" for="ver-clave-recuperar" title="Mostrar u ocultar contraseña">
                        <i class="fa-regular fa-eye"></i>
                        <i class="fa-regular fa-eye-slash"></i>
                    </label>
                </div>
            </div>

            <p class="campo-obligatorio"></p>

            <input class="form-intento" id="intento-recuperar-password" name="intento-recuperar-password" type="radio">
            <label class="btn-primary" for="intento-recuperar-password">Guardar contraseña</label>
            <p class="form-alt-link"><a href="login.php">Volver a iniciar sesión</a></p>
        </form>
    </section>

<script src="config.php"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/recuperar.js"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>