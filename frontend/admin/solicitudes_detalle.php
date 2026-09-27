<?php
$tituloPagina = 'Detalle de solicitud - Patitas Seguras';
$paginaAdmin = 'solicitudes';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <section id="panel-solicitud-detalle" data-listado="solicitudes.php">
        <p id="mensaje-formulario" class="mensaje-formulario"></p>

        <article id="ficha-solicitud" class="info-card">
            <div class="ficha-perfil-datos">
                <div class="dash-card-cabecera">
                    <h3 id="ficha-nombre" data-campo="tipo_solicitud"></h3>
                    <a class="btn-primary" href="solicitudes.php"></a>
                </div>
                <ul class="pet-detail-meta">
                    <li><span class="detalle-label" id="columna-solicitud-id"></span><span id="detalle-id" data-campo="id"></span></li>
                    <li><span class="detalle-label" id="columna-solicitud-nombre"></span><span id="detalle-nombre" data-campo="nombre_usuario"></span></li>
                    <li><span class="detalle-label" id="columna-solicitud-apellido"></span><span id="detalle-apellido" data-campo="apellido_usuario"></span></li>
                    <li><span class="detalle-label" id="columna-solicitud-mascota"></span><span id="detalle-mascota" data-campo="nombre_mascota"></span></li>
                    <li><span class="detalle-label" id="columna-solicitud-tipo"></span><span id="detalle-tipo" data-campo="tipo_solicitud"></span></li>
                    <li><span class="detalle-label" id="columna-solicitud-estado"></span><span id="detalle-estado" data-campo="estado_solicitud"></span></li>
                    <li><span class="detalle-label" id="columna-solicitud-fecha"></span><span id="detalle-fecha" data-campo="fecha_solicitud"></span></li>
                </ul>
            </div>
        </article>
    </section>

<script src="config.php"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/solicitud-detalle.js"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
