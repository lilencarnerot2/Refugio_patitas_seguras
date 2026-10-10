<?php
$tituloPagina = 'Detalle de solicitud - Patitas Seguras';
$paginaAdmin = 'solicitudes_detalle';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <section id="panel-solicitud-detalle" class="panel-listado" data-listado="solicitudes.php">
        <p id="mensaje-formulario" class="mensaje-formulario"></p>

        <div class="dash-fila dash-fila-dos">
            <article class="info-card" id="ficha-solicitud">
                <div class="dash-card-cabecera">
                    <span class="ficha-usuario-icono"><i class="bi bi-file-earmark-text"></i></span>
                    <h3>Solicitudes</h3>
                </div>
                <ul class="pet-detail-meta">
                    <li><span class="detalle-label">Nombre</span><span data-campo="nombre_usuario"></span></li>
                    <li><span class="detalle-label">Apellido</span><span data-campo="apellido_usuario"></span></li>
                    <li><span class="detalle-label">Mascota</span><span data-campo="nombre_mascota"></span></li>
                    <li><span class="detalle-label">Tipo</span><span data-campo="tipo_solicitud"></span></li>
                    <li><span class="detalle-label">Estado</span><span data-campo="estado_solicitud"></span></li>
                    <li><span class="detalle-label">Fecha</span><span data-campo="fecha_solicitud"></span></li>
                </ul>
            </article>

            <article class="info-card" id="ficha-formulario">
                <div class="dash-card-cabecera">
                    <span class="ficha-usuario-icono"><i class="bi bi-ui-checks"></i></span>
                    <h3>Formulario</h3>
                </div>
                <div class="mb-3" data-formulario="id_mascota">
                    <label class="detalle-label" for="id_mascota">Id de mascota</label>
                    <input class="form-control" id="id_mascota" type="text" readonly>
                </div>
                <div class="mb-3" data-formulario="id_tipo_solicitud">
                    <label class="detalle-label" for="id_tipo_solicitud">Id de tipo</label>
                    <input class="form-control" id="id_tipo_solicitud" type="text" readonly>
                </div>
            </article>
        </div>

        <article class="info-card" id="bloque-compatibilidad">
            <div class="dash-card-cabecera">
                <span class="ficha-usuario-icono"><i class="bi bi-heart-pulse"></i></span>
                <h3>Compatibilidad</h3>
            </div>
            <div id="botones-estado" class="d-flex gap-2 flex-wrap"></div>
            <!-- Modelo oculto con el diseño; el JS lo clona y solo pone datos del backend -->
            <div id="modelos-estado" hidden>
                <button type="button" class="btn btn-outline-primary" id="boton-modelo-estado"></button>
            </div>
        </article>
    </section>

<script src="config.php"></script>
<script src="../assets/js/api.js?t=<?php echo filemtime(__DIR__ . '/../assets/js/api.js'); ?>"></script>
<script src="../assets/js/solicitud-detalle.js?t=<?php echo filemtime(__DIR__ . '/../assets/js/solicitud-detalle.js'); ?>"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
