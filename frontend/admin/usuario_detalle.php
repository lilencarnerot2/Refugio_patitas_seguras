<?php
$tituloPagina = 'Detalle de usuario - Patitas Seguras';
$paginaAdmin = 'usuarios';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <section id="panel-usuario-detalle">
        <p id="mensaje-formulario" class="mensaje-formulario"></p>

        <article id="ficha-usuario" class="info-card">
            <div class="dash-card-cabecera">
                <span class="ficha-usuario-icono"><i class="fa-solid fa-user"></i></span>
                <h3>Usuario</h3>
                <a class="btn-primary" href="usuarios.php">Usuarios</a>
            </div>
            <ul class="pet-detail-meta">
                <li><span class="detalle-label">Id</span><span id="detalle-id"></span></li>
                <li><span class="detalle-label">Uid</span><span id="detalle-uid"></span></li>
                <li><span class="detalle-label">Id de rol</span><span id="detalle-id-rol"></span></li>
                <li><span class="detalle-label">Rol</span><span id="detalle-nombre-rol"></span></li>
                <li><span class="detalle-label">Id de domicilio</span><span id="detalle-domicilio"></span></li>
                <li><span class="detalle-label">Nombre</span><span id="detalle-nombre"></span></li>
                <li><span class="detalle-label">Apellido</span><span id="detalle-apellido"></span></li>
                <li><span class="detalle-label">Fecha de nacimiento</span><span id="detalle-nacimiento"></span></li>
                <li><span class="detalle-label">Fecha de alta</span><span id="detalle-alta"></span></li>
                <li><span class="detalle-label">Estado</span><span id="detalle-estado"></span></li>
            </ul>
        </article>
    </section>

<script src="config.php"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/usuario-detalle.js"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
