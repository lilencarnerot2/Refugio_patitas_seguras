<?php
$tituloPagina = 'Dashboard - Patitas Seguras';
$paginaAdmin = 'dashboard';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';

$porPagina = 5;
$paginas = 8;
$filas = $porPagina * $paginas;
$slots = $paginas;
?>

    <section id="panel-dashboard" class="panel-listado">
        <p id="mensaje-formulario" class="mensaje-formulario"></p>

        <div class="dash-fila dash-fila-tres">
            <article id="card-solicitudes" class="info-card">
                <div class="dash-card-cabecera">
                    <span class="ficha-usuario-icono"><i class="bi bi-file-earmark-text"></i></span>
                    <h3></h3>
                </div>
                <div class="dash-kpi">
                    <svg id="torta-solicitudes" class="dash-torta-svg" viewBox="0 0 42 42" role="img" aria-label="Distribución">
                        <circle class="dash-torta-fondo" cx="21" cy="21" r="15.9"></circle>
                        <?php for ($i = 1; $i <= $slots; $i++) { ?><circle class="dash-torta-seg seg-<?php echo $i; ?>" data-seg="<?php echo $i; ?>" cx="21" cy="21" r="15.9" pathLength="100" stroke-dasharray="0" stroke-dashoffset="0" transform="rotate(-90 21 21)"></circle><?php } ?>
                    </svg>
                    <ul id="leyenda-tipos" class="dash-leyenda">
                        <?php for ($i = 0; $i < $slots; $i++) { ?>
                        <li><span data-leyenda="nombre"></span><span data-leyenda="pct"></span><span data-leyenda="cantidad"></span></li>
                        <?php } ?>
                    </ul>
                </div>
            </article>

            <article id="card-pendientes" class="info-card">
                <div class="dash-card-cabecera">
                    <span class="ficha-usuario-icono"><i class="bi bi-hourglass-split"></i></span>
                    <h3></h3>
                </div>
                <div class="dash-kpi">
                    <div class="dash-torta-caja">
                        <svg id="torta-pendientes" class="dash-torta-svg" viewBox="0 0 42 42" role="img" aria-label="Distribución">
                            <circle class="dash-torta-fondo" cx="21" cy="21" r="15.9"></circle>
                            <?php for ($i = 1; $i <= $slots; $i++) { ?><circle class="dash-torta-seg seg-<?php echo $i; ?>" data-seg="<?php echo $i; ?>" cx="21" cy="21" r="15.9" pathLength="100" stroke-dasharray="0" stroke-dashoffset="0" transform="rotate(-90 21 21)"></circle><?php } ?>
                        </svg>
                        <p id="numero-pendientes" class="dash-kpi-numero"></p>
                    </div>
                    <ul id="leyenda-estados" class="dash-leyenda">
                        <?php for ($i = 0; $i < $slots; $i++) { ?>
                        <li><span data-leyenda="nombre"></span><span data-leyenda="pct"></span><span data-leyenda="cantidad"></span></li>
                        <?php } ?>
                    </ul>
                </div>
            </article>

            <article id="card-mascotas" class="info-card">
                <div class="dash-card-cabecera">
                    <span class="ficha-usuario-icono"><span class="icono-pata"></span></span>
                    <h3></h3>
                </div>
                <div class="dash-kpi">
                    <div class="dash-torta-caja">
                        <svg id="torta-mascotas" class="dash-torta-svg" viewBox="0 0 42 42" role="img" aria-label="Distribución">
                            <circle class="dash-torta-fondo" cx="21" cy="21" r="15.9"></circle>
                            <?php for ($i = 1; $i <= $slots; $i++) { ?><circle class="dash-torta-seg seg-<?php echo $i; ?>" data-seg="<?php echo $i; ?>" cx="21" cy="21" r="15.9" pathLength="100" stroke-dasharray="0" stroke-dashoffset="0" transform="rotate(-90 21 21)"></circle><?php } ?>
                        </svg>
                        <p id="numero-mascotas" class="dash-kpi-numero"></p>
                    </div>
                    <ul id="leyenda-movimientos" class="dash-leyenda">
                        <?php for ($i = 0; $i < $slots; $i++) { ?>
                        <li><span data-leyenda="nombre"></span><span data-leyenda="pct"></span><span data-leyenda="cantidad"></span></li>
                        <?php } ?>
                    </ul>
                </div>
            </article>
        </div>

        <div class="dash-fila dash-fila-dos">
            <article id="card-mascotas-lista" class="info-card dash-card-recientes">
                <div class="dash-card-cabecera">
                    <span class="ficha-usuario-icono"><span class="icono-pata"></span></span>
                    <h3></h3>
                    <a class="empty-back-link" href="mascotas.php"></a>
                </div>
                <div class="lista-scroll-mascotas">
                    <table class="dash-tabla">
                        <thead>
                            <tr id="cabecera-dashboard-mascotas">
                                <th data-campo="id" data-href="ficha_medica.php?id="></th>
                                <th data-campo="nombre"></th>
                                <th data-campo="especie"></th>
                                <th data-campo="raza"></th>
                                <th data-campo="fecha_nacimiento_estimada"></th>
                            </tr>
                        </thead>
                        <tbody id="cuerpo-dashboard-mascotas">
                            <?php for ($i = 0; $i < $filas; $i++) { ?>
                            <tr>
                                <td data-campo="id"><a href="ficha_medica.php"></a></td>
                                <td data-campo="nombre"><a href="ficha_medica.php"></a></td>
                                <td data-campo="especie"><a href="ficha_medica.php"></a></td>
                                <td data-campo="raza"><a href="ficha_medica.php"></a></td>
                                <td data-campo="fecha_nacimiento_estimada"><a href="ficha_medica.php"></a></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </article>

            <article id="card-donaciones" class="info-card">
                <div class="dash-card-cabecera">
                    <span class="ficha-usuario-icono"><i class="bi bi-heart-fill"></i></span>
                    <h3></h3>
                </div>
                <p id="numero-donaciones" class="dash-kpi-numero"></p>
                <div id="barras-donaciones" class="dash-semanas">
                    <?php for ($i = 0; $i < $slots; $i++) { ?>
                    <div>
                        <progress max="1" value="0"></progress>
                        <span></span>
                    </div>
                    <?php } ?>
                </div>
            </article>

            <article id="card-solicitudes-lista" class="info-card dash-card-recientes">
                <div class="dash-card-cabecera">
                    <span class="ficha-usuario-icono"><i class="bi bi-hourglass-split"></i></span>
                    <h3></h3>
                    <a class="empty-back-link" href="solicitudes.php"></a>
                </div>
                <div class="lista-scroll-mascotas pe-2">
                    <table class="dash-tabla table table-sm align-middle mb-0">
                        <thead>
                            <tr id="cabecera-dashboard-solicitudes">
                                <th data-campo="id" data-href="solicitudes_detalle.php?id="></th>
                                <th data-campo="nombre_usuario"></th>
                                <th data-campo="apellido_usuario"></th>
                                <th data-campo="nombre_mascota"></th>
                                <th data-campo="tipo_solicitud"></th>
                                <th data-campo="estado_solicitud"></th>
                            </tr>
                        </thead>
                        <tbody id="cuerpo-dashboard-solicitudes">
                            <?php for ($i = 0; $i < $filas; $i++) { ?>
                            <tr>
                                <td data-campo="id"><a href="solicitudes_detalle.php"></a></td>
                                <td data-campo="nombre_usuario"><a href="solicitudes_detalle.php"></a></td>
                                <td data-campo="apellido_usuario"><a href="solicitudes_detalle.php"></a></td>
                                <td data-campo="nombre_mascota"><a href="solicitudes_detalle.php"></a></td>
                                <td data-campo="tipo_solicitud"><a href="solicitudes_detalle.php"></a></td>
                                <td data-campo="estado_solicitud"><a href="solicitudes_detalle.php"></a></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </article>
        </div>
    </section>

<script src="config.php"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/dashboard.js"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
