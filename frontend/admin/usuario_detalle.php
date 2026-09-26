<?php
$tituloPagina = 'Detalle de usuario - Patitas Seguras';
$paginaAdmin = 'usuarios';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <section id="panel-usuario-detalle">
        <p id="mensaje-formulario" class="mensaje-formulario"></p>

        <article id="ficha-usuario" class="info-card">
            <div class="ficha-foto-perfil dash-foto">
                <div id="usuario-foto-vacia" class="pet-photo-empty">
                    <i class="fa-solid fa-user"></i>
                </div>
            </div>
            <div class="ficha-perfil-datos">
                <div class="dash-card-cabecera">
                    <h3 id="ficha-nombre"></h3>
                    <a class="btn-primary" href="usuarios.php">Usuarios</a>
                </div>
                <ul class="pet-detail-meta">
                    <li><span class="detalle-label" id="etiqueta-nro"></span><span id="detalle-id"></span></li>
                    <?php if (!empty($esSuperadmin)) { ?>
                    <li><span class="detalle-label" id="etiqueta-uid"></span><span id="detalle-uid"></span></li>
                    <li><span class="detalle-label" id="etiqueta-id-rol"></span><span id="detalle-id-rol"></span></li>
                    <li><span class="detalle-label" id="etiqueta-domicilio"></span><span id="detalle-domicilio"></span></li>
                    <?php } ?>
                    <li><span class="detalle-label" id="etiqueta-nombre"></span><span id="detalle-nombre"></span></li>
                    <li><span class="detalle-label" id="etiqueta-apellido"></span><span id="detalle-apellido"></span></li>
                    <li><span class="detalle-label" id="etiqueta-nacimiento"></span><span id="detalle-nacimiento"></span></li>
                    <li><span class="detalle-label" id="etiqueta-alta"></span><span id="detalle-alta"></span></li>
                    <li><span class="detalle-label" id="etiqueta-rol"></span><span id="detalle-nombre-rol"></span></li>
                    <li><span class="detalle-label" id="etiqueta-estado"></span><span id="detalle-estado"></span></li>
                </ul>
            </div>
        </article>

        <article id="bloque-solicitudes" class="info-card">
            <h3>Solicitudes</h3>
            <div class="lista-scroll-mascotas">
                <table class="dash-tabla">
                    <thead>
                        <tr>
                            <th id="columna-solicitud-id"></th>
                            <th id="columna-solicitud-tipo"></th>
                            <th id="columna-solicitud-mascota"></th>
                            <th id="columna-solicitud-estado"></th>
                            <th id="columna-solicitud-fecha"></th>
                        </tr>
                    </thead>
                    <tbody id="cuerpo-solicitudes"></tbody>
                </table>
            </div>
        </article>
    </section>

<script src="config.php"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/usuario-detalle.js"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
