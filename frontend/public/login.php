<?php
$tituloPagina = 'Iniciar sesión - Patitas Seguras';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <section>
        <div class="page-intro">
            <h2>Iniciar sesión</h2>
            <p>Ingresá con el correo asociado a tu usuario.</p>
        </div>

        <form id="form-login" class="public-form login-form">
            <h3>Accedé a tu cuenta</h3>
            <p>Completá tus datos para ingresar.</p>
            <p class="mensaje-formulario campo-obligatorio"></p>

            <div class="mb-3">
                <label class="form-label" for="correo">Correo electrónico</label>
                <input class="form-control" id="correo" name="correo" type="text">
            </div>

            <div class="mb-3">
                <label class="form-label" for="clave">Contraseña</label>
                <input class="form-control" id="clave" name="clave" type="password">
            </div>

            <button class="btn-primary" type="button">Ingresar</button>
            <p class="form-alt-link"><a href="recuperar.php">Recuperar contraseña</a></p>
            <p class="form-alt-link">¿No tenés usuario? <a href="registro.php">Crear cuenta</a></p>
        </form>
    </section>

<script src="config.php"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/auth.js"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
