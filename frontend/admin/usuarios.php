<?php
$tituloPagina = 'Usuarios - Patitas Seguras';
$paginaAdmin = 'usuarios';
$busquedaListado = strtolower(trim((string) ($_GET['q'] ?? '')));
$busquedaCss = str_replace(['\\', '"'], ['\\\\', '\\"'], $busquedaListado);
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';

function columnasUsuarios()
{
    return [
        ['id' => 'columna-nro', 'campo' => 'id', 'href' => 'usuario_detalle.php'],
        ['id' => 'columna-uid', 'campo' => 'uid_pp2', 'superadmin' => true],
        ['id' => 'columna-id-rol', 'campo' => 'id_rol', 'superadmin' => true],
        ['id' => 'columna-id-domicilio', 'campo' => 'id_domicilio', 'superadmin' => true],
        ['id' => 'columna-nombre', 'campo' => 'nombre', 'buscar' => true, 'titulo' => true],
        ['id' => 'columna-apellido', 'campo' => 'apellido', 'buscar' => true],
        ['id' => 'columna-nacimiento', 'campo' => 'fecha_nacimiento'],
        ['id' => 'columna-alta', 'campo' => 'fecha_alta'],
        ['id' => 'columna-rol', 'campo' => 'nombre_rol'],
        ['id' => 'columna-estado', 'campo' => 'estado', 'badge' => true],
        ['id' => 'columna-editar', 'href' => 'usuario_editar.php', 'superadmin' => true],
        ['id' => 'columna-accion-alta', 'accion' => 'alta', 'estado' => 'true', 'superadmin' => true],
        ['id' => 'columna-accion-baja', 'accion' => 'baja', 'estado' => 'false', 'superadmin' => true],
    ];
}

function columnasUsuariosVisibles($esSuperadmin)
{
    $visibles = [];

    foreach (columnasUsuarios() as $columna) {
        if (!empty($columna['superadmin']) && empty($esSuperadmin)) {
            continue;
        }

        $visibles[] = $columna;
    }

    return $visibles;
}

$porPagina = 5;
$paginas = 8;
$filas = $porPagina * $paginas;
$radiosRol = $paginas;
$radiosEstado = $paginas;
$columnasVisibles = columnasUsuariosVisibles($esSuperadmin);
$camposBuscar = [];

foreach ($columnasVisibles as $columna) {
    if (!empty($columna['buscar']) && !empty($columna['campo'])) {
        $camposBuscar[] = $columna['campo'];
    }
}
?>

    <section id="panel-usuarios" data-por-pagina="<?php echo $porPagina; ?>"<?php echo $busquedaListado !== '' ? ' data-q="' . htmlspecialchars($busquedaListado, ENT_QUOTES, 'UTF-8') . '"' : ''; ?>>
        <?php for ($pagina = 1; $pagina <= $paginas; $pagina++) { ?>
        <input class="pagina-radio" type="radio" name="pagina-listado" id="pagina-<?php echo $pagina; ?>"<?php echo $pagina === 1 ? ' checked' : ''; ?>>
        <?php } ?>
        <p id="mensaje-formulario" class="mensaje-formulario"></p>

        <article class="info-card">
            <div class="dash-card-cabecera">
                <span class="ficha-usuario-icono"><i class="bi bi-people-fill"></i></span>
                <h3>Usuarios</h3>
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
                        <?php for ($i = 0; $i < $radiosRol; $i++) { ?>
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
                        <?php for ($i = 0; $i < $radiosEstado; $i++) { ?>
                        <label><input type="checkbox" name="filtro-estado"></label>
                        <?php } ?>
                    </div>
                </form>
            </div>

            <div class="lista-scroll-mascotas">
                <table class="dash-tabla">
                    <thead>
                        <tr id="cabecera-usuarios">
                            <?php foreach ($columnasVisibles as $columna) { ?>
                            <th<?php
                                echo ' id="' . htmlspecialchars($columna['id'], ENT_QUOTES, 'UTF-8') . '"';
                                if (!empty($columna['campo'])) {
                                    echo ' data-campo="' . htmlspecialchars($columna['campo'], ENT_QUOTES, 'UTF-8') . '"';
                                }
                                if (!empty($columna['href'])) {
                                    echo ' data-href="' . htmlspecialchars($columna['href'], ENT_QUOTES, 'UTF-8') . '"';
                                }
                                if (!empty($columna['buscar'])) {
                                    echo ' data-buscar';
                                }
                            ?>></th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody id="cuerpo-usuarios">
                        <?php for ($i = 0; $i < $filas; $i++) { ?>
                        <tr>
                            <?php foreach ($columnasVisibles as $columna) { ?>
                            <td<?php echo !empty($columna['campo']) ? ' data-campo="' . htmlspecialchars($columna['campo'], ENT_QUOTES, 'UTF-8') . '"' : ''; ?>>
                                <?php if (!empty($columna['href'])) { ?>
                                <a href="<?php echo htmlspecialchars($columna['href'], ENT_QUOTES, 'UTF-8'); ?>"></a>
                                <?php } ?>
                                <?php if (!empty($columna['accion'])) { ?>
                                <button type="button" data-accion="<?php echo htmlspecialchars($columna['accion'], ENT_QUOTES, 'UTF-8'); ?>"<?php echo isset($columna['estado']) ? ' data-estado="' . htmlspecialchars($columna['estado'], ENT_QUOTES, 'UTF-8') . '"' : ''; ?>></button>
                                <?php } ?>
                            </td>
                            <?php } ?>
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
<?php for ($n = 1; $n <= $radiosRol; $n++) { ?>
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
<?php if ($busquedaListado !== '' && $camposBuscar) { ?>
.admin-page #panel-usuarios[data-q] tbody tr<?php foreach ($camposBuscar as $campo) { ?>:not(:has([data-campo="<?php echo htmlspecialchars($campo, ENT_QUOTES, 'UTF-8'); ?>"][data-valor*="<?php echo htmlspecialchars($busquedaCss, ENT_QUOTES, 'UTF-8'); ?>"]))<?php } ?> {
    display: none;
}
<?php } ?>
</style>

<script src="config.php"></script>
<script src="../assets/js/api.js?t=<?php echo filemtime(__DIR__ . '/../assets/js/api.js'); ?>"></script>
<script src="../assets/js/usuarios.js?t=<?php echo filemtime(__DIR__ . '/../assets/js/usuarios.js'); ?>"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
