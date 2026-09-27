<?php
$tituloPagina = 'Usuarios - Patitas Seguras';
$paginaAdmin = 'usuarios';
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

    <section id="panel-usuarios" data-por-pagina="<?php echo $porPagina; ?>"<?php echo $busquedaListado !== '' ? ' data-q="' . htmlspecialchars($busquedaListado, ENT_QUOTES, 'UTF-8') . '"' : ''; ?>>
        <?php for ($pagina = 1; $pagina <= $paginas; $pagina++) { ?>
        <input class="pagina-radio" type="radio" name="pagina-listado" id="pagina-<?php echo $pagina; ?>"<?php echo $pagina === 1 ? ' checked' : ''; ?>>
        <?php } ?>
        <p id="mensaje-formulario" class="mensaje-formulario"></p>

        <article class="info-card">
            <div class="dash-card-cabecera">
                <span class="ficha-usuario-icono"><i class="bi bi-people-fill"></i></span>
                <h3></h3>
            </div>

            <div class="listado-controles">
                <form class="listado-buscar" method="get">
                    <i class="bi bi-search"></i>
                    <input class="form-control" id="buscar-usuario" name="q" type="search" placeholder=" " value="<?php echo htmlspecialchars((string) ($_GET['q'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
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
                <form class="listado-filtro listado-filtro-estado">
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
                        <tr id="cabecera-usuarios">
                            <th id="columna-nro" data-campo="id" data-href="usuario_detalle.php"></th>
                            <th id="columna-nombre" data-campo="nombre" data-buscar></th>
                            <th id="columna-apellido" data-campo="apellido" data-buscar></th>
                            <th id="columna-nacimiento" data-campo="fecha_nacimiento"></th>
                            <th id="columna-alta" data-campo="fecha_alta"></th>
                            <th id="columna-rol" data-campo="nombre_rol"></th>
                            <th id="columna-estado" data-campo="estado" data-badge></th>
                        </tr>
                    </thead>
                    <tbody id="cuerpo-usuarios">
                        <?php for ($i = 0; $i < $filas; $i++) { ?>
                        <tr>
                            <td data-campo="id"><a href="usuario_detalle.php"></a></td>
                            <td data-campo="nombre"><a href="usuario_detalle.php"></a></td>
                            <td data-campo="apellido"><a href="usuario_detalle.php"></a></td>
                            <td data-campo="fecha_nacimiento"><a href="usuario_detalle.php"></a></td>
                            <td data-campo="fecha_alta"><a href="usuario_detalle.php"></a></td>
                            <td data-campo="nombre_rol"><a href="usuario_detalle.php"></a></td>
                            <td data-campo="estado"><a href="usuario_detalle.php"></a></td>
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
<?php for ($n = 1; $n <= $opcionesRol; $n++) { ?>
.admin-page #panel-usuarios .listado-filtro-rol:has(#opciones-rol label:nth-child(<?php echo $n; ?>) input:checked):not(:has(#opciones-rol label:not(:nth-child(<?php echo $n; ?>)) input:checked)) .listado-filtro-resumen::after {
    display: inline;
    position: static;
    border: 0;
    padding: 0;
    background: transparent;
    color: var(--text-dark);
    font-size: inherit;
    font-weight: 400;
    content: attr(data-nombre-<?php echo $n; ?>);
}
.admin-page #panel-usuarios:has(#opciones-rol label:nth-child(<?php echo $n; ?>) input:checked):not(:has(#opciones-rol label:not(:nth-child(<?php echo $n; ?>)) input:checked)) tbody tr:not([data-rol-pos="<?php echo $n; ?>"]) {
    display: none;
}
<?php } ?>
<?php if ($busquedaListado !== '') { ?>
.admin-page #panel-usuarios[data-q] tbody tr:not(:has([data-campo="nombre"][data-valor*="<?php echo htmlspecialchars($busquedaCss, ENT_QUOTES, 'UTF-8'); ?>"])):not(:has([data-campo="apellido"][data-valor*="<?php echo htmlspecialchars($busquedaCss, ENT_QUOTES, 'UTF-8'); ?>"])) {
    display: none;
}
<?php } ?>
</style>

<script src="config.php"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/usuarios.js"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
