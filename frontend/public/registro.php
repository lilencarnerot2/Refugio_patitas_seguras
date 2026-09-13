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

        <form id="form-registro" class="public-form">
            <h3>Tus datos</h3>
            <p>Completá la información para crear tu usuario.</p>

            <div class="mb-3">
                <label class="form-label" for="nombre">Nombre</label>
                <input class="form-control" id="nombre" name="nombre" type="text">
            </div>

            <div class="mb-3">
                <label class="form-label" for="apellido">Apellido</label>
                <input class="form-control" id="apellido" name="apellido" type="text">
            </div>

            <div class="mb-3">
                <label class="form-label" for="fecha_nacimiento">Fecha de nacimiento</label>
                <input class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" type="text">
            </div>

            <div class="mb-3">
                <label class="form-label" for="correo">Correo electrónico</label>
                <input class="form-control" id="correo" name="correo" type="text">
            </div>

            <div class="mb-3">
                <label class="form-label" for="clave">Contraseña</label>
                <div class="clave-wrap">
                    <input class="form-control clave-campo" id="clave" name="clave" type="text" autocomplete="new-password">
                    <input class="clave-toggle" id="ver-clave-registro" type="checkbox">
                    <label class="clave-ojo" for="ver-clave-registro" title="Mostrar u ocultar contraseña">
                        <i class="fa-regular fa-eye"></i>
                        <i class="fa-regular fa-eye-slash"></i>
                    </label>
                </div>
            </div>

            <button class="btn-primary" type="button">Crear cuenta</button>
            <p class="form-alt-link">¿Ya tenés usuario? <a href="login.php">Ingresar</a></p>
        </form>
    </section>

<?php require __DIR__ . '/includes/footer.php'; ?>
