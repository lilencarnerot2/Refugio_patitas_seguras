<?php
$tituloPagina = 'Detalle de usuario - Patitas Seguras';
$paginaAdmin = 'usuarios';
$filasSolicitudes = 20;
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <section id="panel-usuario-detalle" data-listado="usuarios.php">
        <p id="mensaje-formulario" class="mensaje-formulario"></p>

        <article id="ficha-usuario" class="info-card">
            <div class="ficha-foto-perfil dash-foto">
                <div id="usuario-foto-vacia" class="pet-photo-empty">
                    <i class="bi bi-person-fill"></i>
                </div>
            </div>
            <div class="ficha-perfil-datos">
                <div class="dash-card-cabecera">
                    <h3 id="ficha-nombre" data-campo="nombre"></h3>
                    <a class="btn-primary" href="usuarios.php"></a>
                </div>
                <ul class="pet-detail-meta">
                    <li><span class="detalle-label" id="etiqueta-nro"></span><span id="detalle-id" data-campo="id"></span></li>
                    <li><span class="detalle-label" id="etiqueta-nombre"></span><span id="detalle-nombre" data-campo="nombre"></span></li>
                    <li><span class="detalle-label" id="etiqueta-apellido"></span><span id="detalle-apellido" data-campo="apellido"></span></li>
                    <li><span class="detalle-label" id="etiqueta-nacimiento"></span><span id="detalle-nacimiento" data-campo="fecha_nacimiento"></span></li>
                    <li><span class="detalle-label" id="etiqueta-alta"></span><span id="detalle-alta" data-campo="fecha_alta"></span></li>
                    <li><span class="detalle-label" id="etiqueta-rol"></span><span id="detalle-nombre-rol" data-campo="nombre_rol"></span></li>
                    <li><span class="detalle-label" id="etiqueta-estado"></span><span id="detalle-estado" data-campo="estado" data-badge></span></li>
                </ul>
            </div>
        </article>

        <article id="bloque-solicitudes" class="info-card">
            <h3></h3>
            <div class="lista-scroll-mascotas">
                <table class="dash-tabla">
                    <thead>
                        <tr id="cabecera-solicitudes">
                            <th id="columna-solicitud-id" data-campo="id" data-href="adopcion_detalle.php"></th>
                            <th id="columna-solicitud-tipo" data-campo="tipo_solicitud"></th>
                            <th id="columna-solicitud-mascota" data-campo="nombre_mascota"></th>
                            <th id="columna-solicitud-estado" data-campo="estado_solicitud"></th>
                            <th id="columna-solicitud-fecha" data-campo="fecha_solicitud"></th>
                        </tr>
                    </thead>
                    <tbody id="cuerpo-solicitudes">
                        <?php for ($i = 0; $i < $filasSolicitudes; $i++) { ?>
                        <tr>
                            <td data-campo="id"><a href="adopcion_detalle.php"></a></td>
                            <td data-campo="tipo_solicitud"></td>
                            <td data-campo="nombre_mascota"></td>
                            <td data-campo="estado_solicitud"></td>
                            <td data-campo="fecha_solicitud"></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </article>
    </section>

    <div id="modelos-solicitud" hidden aria-hidden="true">
        <a data-enlace-detalle-tipo href="adopcion_detalle.php" data-id-tipo-solicitud="" tabindex="-1"></a>
        <a data-enlace-detalle-tipo href="transito_detalle.php" data-id-tipo-solicitud="" tabindex="-1"></a>
        <a data-enlace-detalle-default href="adopcion_detalle.php" tabindex="-1"></a>
    </div>

<script src="config.php"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/usuario-detalle.js"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
