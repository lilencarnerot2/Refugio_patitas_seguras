<?php
$autenticado = !empty($_COOKIE['usuario_id']);

if (!isset($_GET['salir']) && $autenticado) {
    header('Location: /index.php');
    exit;
}

$tituloPagina = 'Iniciar sesión - Patitas Seguras';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <section>
        <div class="page-intro">
            <h2>Iniciar sesión</h2>
            <p>Ingresá con el correo asociado a tu usuario.</p>
        </div>

<form id="form-login" class="public-form login-form" novalidate>
            <h3>Accedé a tu cuenta</h3>
            <p>Completá tus datos para ingresar.</p>
            <p class="mensaje-formulario campo-obligatorio"></p>

            <div class="mb-3">
                <label class="form-label" for="correo">Correo electrónico</label>
                <input class="form-control" id="correo" name="correo" type="text" autocomplete="username" required>
            </div>

            <div class="mb-3">
                <label class="form-label" for="clave">Contraseña</label>
                <div class="clave-wrap">
                    <input class="form-control clave-campo" id="clave" name="clave" type="text" autocomplete="current-password" required>
                    <input class="clave-toggle" id="ver-clave-login" type="checkbox">
                    <label class="clave-ojo" for="ver-clave-login" title="Mostrar u ocultar contraseña">
                        <i class="fa-regular fa-eye"></i>
                        <i class="fa-regular fa-eye-slash"></i>
                    </label>
                </div>
            </div>

            <p class="campo-obligatorio"></p>

            <input class="form-intento" id="intento-login" name="intento-login" type="radio">
            <button class="btn-primary" type="button">Ingresar</button>
            <p class="form-alt-link"><a href="recuperar.php">Recuperar contraseña</a></p>
            <p class="form-alt-link">¿No tenés usuario? <a href="registro.php">Crear cuenta</a></p>
</form>
    </section>

<script src="config.php"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/auth.js"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
