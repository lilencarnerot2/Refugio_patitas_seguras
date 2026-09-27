<?php
$tituloPagina = 'Detalle de usuario - Patitas Seguras';
$paginaAdmin = 'usuario_detalle';
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
                    <a class="btn-outline" href="usuarios.php"></a>
                    <?php if (!empty($esSuperadmin)) { ?>
                    <a class="btn-primary" href="usuario_editar.php" data-href="usuario_editar.php"></a>
                    <?php } ?>
                </div>
                <ul class="pet-detail-meta">
                    <li><span class="detalle-label" id="etiqueta-nro"></span><span id="detalle-nro" data-campo="id"></span></li>
                    <?php if (!empty($esSuperadmin)) { ?>
                    <li><span class="detalle-label" id="etiqueta-uid"></span><span id="detalle-uid" data-campo="uid_pp2"></span></li>
                    <li><span class="detalle-label" id="etiqueta-id-rol"></span><span id="detalle-id-rol" data-campo="id_rol"></span></li>
                    <li><span class="detalle-label" id="etiqueta-id-domicilio"></span><span id="detalle-id-domicilio" data-campo="id_domicilio"></span></li>
                    <?php } ?>
                    <li><span class="detalle-label" id="etiqueta-nombre"></span><span id="detalle-nombre" data-campo="nombre"></span></li>
                    <li><span class="detalle-label" id="etiqueta-apellido"></span><span id="detalle-apellido" data-campo="apellido"></span></li>
                    <li><span class="detalle-label" id="etiqueta-nacimiento"></span><span id="detalle-nacimiento" data-campo="fecha_nacimiento"></span></li>
                    <li><span class="detalle-label" id="etiqueta-alta"></span><span id="detalle-alta" data-campo="fecha_alta"></span></li>
                    <li><span class="detalle-label" id="etiqueta-rol"></span><span id="detalle-rol" data-campo="nombre_rol"></span></li>
                    <li><span class="detalle-label" id="etiqueta-estado"></span><span id="detalle-estado" data-campo="estado" data-badge></span></li>
                </ul>
            </div>
        </article>
    </section>

<script src="config.php"></script>
<script src="../assets/js/api.js?t=<?php echo filemtime(__DIR__ . '/../assets/js/api.js'); ?>"></script>
<script src="../assets/js/usuario-detalle.js?t=<?php echo filemtime(__DIR__ . '/../assets/js/usuario-detalle.js'); ?>"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
