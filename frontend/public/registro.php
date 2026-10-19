<?php
$tituloPagina = 'Crear cuenta - Patitas Seguras';
$cuerpoClase = 'page-adapt';
$reglas = require __DIR__ . '/includes/reglas_cuenta.php';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <section>
        <div class="page-intro">
            <h2>Crear cuenta</h2>
            <p>Registrate para poder hacer solicitudes de adopción y hogar de tránsito.</p>
        </div>

        <form id="form-registro" class="public-form login-form" novalidate>
            <h3>Tus datos</h3>
            <p>Completá la información para crear tu usuario.</p>

            <div class="mb-3">
                <label class="form-label" for="nombre">Nombre</label>
                <input class="form-control" id="nombre" name="nombre" type="text" required>
            </div>

            <div class="mb-3">
                <label class="form-label" for="apellido">Apellido</label>
                <input class="form-control" id="apellido" name="apellido" type="text" required>
            </div>

            <div class="mb-3">
                <label class="form-label" for="correo">Correo electrónico</label>
                <input class="form-control" id="correo" name="correo" type="text" inputmode="email" autocomplete="email" maxlength="254" required pattern="<?php echo htmlspecialchars($reglas['patron_email'], ENT_QUOTES, 'UTF-8'); ?>" title="<?php echo htmlspecialchars($reglas['texto_email'], ENT_QUOTES, 'UTF-8'); ?>">
            </div>

            <div class="mb-3">
                <label class="form-label" for="fecha_nacimiento">Fecha de nacimiento</label>
                <input class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" type="date" required max="<?php echo date('Y-m-d', strtotime('-18 years')); ?>">
            </div>

            <div class="mb-3">
                <label class="form-label" for="telefono">Teléfono</label>
                <input class="form-control" id="telefono" name="telefono" type="tel" required>
            </div>

            <div class="mb-3">
                <label class="form-label" for="clave">Contraseña</label>
                <div class="clave-wrap">
                    <input class="form-control clave-campo" id="clave" name="clave" type="text" autocomplete="new-password" maxlength="64" required pattern="<?php echo htmlspecialchars($reglas['patron_clave'], ENT_QUOTES, 'UTF-8'); ?>" title="<?php echo htmlspecialchars($reglas['texto_clave'], ENT_QUOTES, 'UTF-8'); ?>">
                    <input class="clave-toggle" id="ver-clave-registro" type="checkbox">
                    <label class="clave-ojo" for="ver-clave-registro" title="Mostrar u ocultar contraseña">
                        <i class="fa-regular fa-eye"></i>
                        <i class="fa-regular fa-eye-slash"></i>
                    </label>
                </div>
                <div class="form-text"><?php echo htmlspecialchars($reglas['ayuda_clave'], ENT_QUOTES, 'UTF-8'); ?></div>
            </div>

            <p class="campo-obligatorio"></p>

            <input class="form-intento" id="intento-registro" name="intento-registro" type="radio">
            <label class="btn-primary" for="intento-registro">Crear cuenta</label>
            <p class="form-alt-link">¿Ya tenés usuario? <a href="login.php">Ingresar</a></p>
        </form>
    </section>

<script src="config.php"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/registro.js"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
