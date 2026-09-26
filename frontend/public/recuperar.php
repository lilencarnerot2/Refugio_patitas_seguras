<?php
$tituloPagina = 'Nueva contraseña - Patitas Seguras';
$cuerpoClase = 'page-adapt';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <section>
        <div class="page-intro">
            <h2>Nueva contraseña</h2>
            <p>Se envia un correo que contiene un token, que dura 30 minutos.</p>
           
        </div>

        <form id="form-recuperar" class="public-form login-form" novalidate>
            <div class="mb-3">
                <label class="form-label" for="token">Token</label>
                <input class="form-control" id="token" name="token" type="text" required>
            </div>

            <div class="mb-3">
                <label class="form-label" for="password">Contraseña</label>
                <div class="clave-wrap">
                    <input class="form-control clave-campo" id="password" name="password" type="password" autocomplete="new-password" required>
                    <input class="clave-toggle" id="ver-clave-recuperar" type="checkbox">
                    <label class="clave-ojo" for="ver-clave-recuperar" title="Mostrar u ocultar contraseña">
                        <i class="fa-regular fa-eye"></i>
                        <i class="fa-regular fa-eye-slash"></i>
                    </label>
                </div>
            </div>

            <input class="form-intento" id="intento-recuperar" name="intento-recuperar" type="radio">
            <label class="btn-primary" for="intento-recuperar">Guardar contraseña</label>
            <p class="form-alt-link"><a href="login.php">Volver a iniciar sesión</a></p>
        </form>
    </section>

<?php require __DIR__ . '/includes/footer.php'; ?>
