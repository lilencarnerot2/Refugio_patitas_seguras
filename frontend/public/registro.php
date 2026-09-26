<?php
$tituloPagina = 'Crear cuenta - Patitas Seguras';
$cuerpoClase = 'page-adapt';
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
                <input class="form-control" id="correo" name="correo" type="text" required>
            </div>

            <div class="mb-3">
                <label class="form-label" for="clave">Contraseña</label>
                <div class="clave-wrap">
                    <input class="form-control clave-campo" id="clave" name="clave" type="password" autocomplete="new-password" required>
                    <input class="clave-toggle" id="ver-clave-registro" type="checkbox">
                    <label class="clave-ojo" for="ver-clave-registro" title="Mostrar u ocultar contraseña">
                        <i class="fa-regular fa-eye"></i>
                        <i class="fa-regular fa-eye-slash"></i>
                    </label>
                </div>
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
