<?php
$tituloPagina = 'Solicitudes - Patitas Seguras';
$paginaAdmin = 'solicitudes';
$busquedaListado = strtolower(trim((string) ($_GET['q'] ?? '')));
$busquedaCss = str_replace(['\\', '"'], ['\\\\', '\\"'], $busquedaListado);
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';

$porPagina = 5;
$paginas = 8;
$filas = $porPagina * $paginas;
$opcionesRol = $paginas;
$opcionesEstado = $paginas;
?>

    <section id="panel-solicitudes" class="panel-listado" data-por-pagina="<?php echo $porPagina; ?>"<?php echo $busquedaListado !== '' ? ' data-q="' . $busquedaListado . '"' : ''; ?>>
        <?php for ($pagina = 1; $pagina <= $paginas; $pagina++) { ?>
        <input class="pagina-radio" type="radio" name="pagina-listado" id="pagina-<?php echo $pagina; ?>"<?php echo $pagina === 1 ? ' checked' : ''; ?>>
        <?php } ?>
        <p id="mensaje-formulario" class="mensaje-formulario"></p>

        <div id="resumen-solicitudes" class="dash-fila dash-fila-tres">
            <?php for ($i = 0; $i < $opcionesRol; $i++) { ?>
            <article class="info-card">
                <h3></h3>
                <div class="dash-kpi">
                    <svg class="dash-donut" viewBox="0 0 36 36" aria-hidden="true">
                        <circle class="dash-donut-fondo" cx="18" cy="18" r="14" pathLength="100"></circle>
                        <circle class="dash-donut-valor" cx="18" cy="18" r="14" pathLength="100" stroke-dasharray="0 100"></circle>
                    </svg>
                    <p class="dash-kpi-numero"></p>
                </div>
            </article>
            <?php } ?>
        </div>

        <article class="info-card">
            <div class="dash-card-cabecera">
                <span class="ficha-usuario-icono"><i class="bi bi-file-earmark-text"></i></span>
                <h3></h3>
            </div>

            <div class="listado-controles">
                <form class="listado-buscar" method="get">
                    <i class="bi bi-search"></i>
                    <input class="form-control" id="buscar-solicitud" name="q" type="search" placeholder=" " value="<?php echo (string) ($_GET['q'] ?? ''); ?>">
                    <span></span>
                </form>
                <form class="listado-filtro listado-filtro-rol">
                    <input class="filtro-interruptor" type="checkbox" id="filtro-rol-abierto">
                    <label class="filtro-fondo" for="filtro-rol-abierto"></label>
                    <div class="listado-filtro-resumen">
                        <label class="filtro-abrir" for="filtro-rol-abierto"><span class="listado-filtro-flecha"></span></label>
                        <button class="filtro-limpiar" type="reset" tabindex="-1"></button>
                    </div>
                    <div id="opciones-rol">
                        <?php for ($i = 0; $i < $opcionesRol; $i++) { ?>
                        <label><input type="checkbox" name="filtro-rol"></label>
                        <?php } ?>
                    </div>
                </form>
                <form class="listado-filtro listado-filtro-estado-nombre">
                    <input class="filtro-interruptor" type="checkbox" id="filtro-estado-abierto">
                    <label class="filtro-fondo" for="filtro-estado-abierto"></label>
                    <div class="listado-filtro-resumen">
                        <label class="filtro-abrir" for="filtro-estado-abierto"><span class="listado-filtro-flecha"></span></label>
                        <button class="filtro-limpiar" type="reset" tabindex="-1"></button>
                    </div>
                    <div id="opciones-estado">
                        <?php for ($i = 0; $i < $opcionesEstado; $i++) { ?>
                        <label><input type="checkbox" name="filtro-estado"></label>
                        <?php } ?>
                    </div>
                </form>
            </div>

            <div class="lista-scroll-mascotas">
                <table class="dash-tabla">
                    <thead>
                        <tr id="cabecera-solicitudes">
                            <th id="columna-solicitud-id" data-campo="id" data-href="solicitudes_detalle.php"></th>
                            <th id="columna-solicitud-nombre" data-campo="nombre_usuario" data-buscar></th>
                            <th id="columna-solicitud-apellido" data-campo="apellido_usuario" data-buscar></th>
                            <th id="columna-solicitud-mascota" data-campo="nombre_mascota" data-buscar></th>
                            <th id="columna-solicitud-tipo" data-campo="tipo_solicitud"></th>
                            <th id="columna-solicitud-estado" data-campo="estado_solicitud"></th>
                            <th id="columna-solicitud-fecha" data-campo="fecha_solicitud"></th>
                        </tr>
                    </thead>
                    <tbody id="cuerpo-solicitudes">
                        <?php for ($i = 0; $i < $filas; $i++) { ?>
                        <tr>
                            <td data-campo="id"><a href="solicitudes_detalle.php"></a></td>
                            <td data-campo="nombre_usuario"><a href="solicitudes_detalle.php"></a></td>
                            <td data-campo="apellido_usuario"><a href="solicitudes_detalle.php"></a></td>
                            <td data-campo="nombre_mascota"><a href="solicitudes_detalle.php"></a></td>
                            <td data-campo="tipo_solicitud"><a href="solicitudes_detalle.php"></a></td>
                            <td data-campo="estado_solicitud"><a href="solicitudes_detalle.php"></a></td>
                            <td data-campo="fecha_solicitud"><a href="solicitudes_detalle.php"></a></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <div class="listado-paginacion">
                <span id="pagina-anterior">
                    <?php for ($pagina = 1; $pagina < $paginas; $pagina++) { ?>
                    <label for="pagina-<?php echo $pagina; ?>"></label>
                    <?php } ?>
                </span>
                <p id="pagina-indicador"></p>
                <span id="pagina-siguiente">
                    <?php for ($pagina = 2; $pagina <= $paginas; $pagina++) { ?>
                    <label for="pagina-<?php echo $pagina; ?>"></label>
                    <?php } ?>
                </span>
            </div>
        </article>
    </section>
<style>
<?php if ($busquedaListado !== '') { ?>
.admin-page #panel-solicitudes[data-q] tbody tr:not(:has([data-campo="nombre_usuario"][data-valor*="<?php echo $busquedaCss; ?>"])):not(:has([data-campo="apellido_usuario"][data-valor*="<?php echo $busquedaCss; ?>"])):not(:has([data-campo="nombre_mascota"][data-valor*="<?php echo $busquedaCss; ?>"])) {
    display: none;
}
<?php } ?>
</style>

<script src="config.php"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/solicitudes.js"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
