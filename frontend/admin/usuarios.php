<?php
$tituloPagina = 'Usuarios - Patitas Seguras';
$paginaAdmin = 'usuarios';
$busquedaListado = strtolower(trim((string) ($_GET['q'] ?? '')));
$busquedaCss = str_replace(['\\', '"'], ['\\\\', '\\"'], $busquedaListado);
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <section id="panel-usuarios" data-por-pagina="5"<?php echo $busquedaListado !== '' ? ' data-q="' . htmlspecialchars($busquedaListado, ENT_QUOTES, 'UTF-8') . '"' : ''; ?>>
        <input class="pagina-radio" type="radio" name="pagina-listado" id="pagina-1" checked>
        <input class="pagina-radio" type="radio" name="pagina-listado" id="pagina-2">
        <input class="pagina-radio" type="radio" name="pagina-listado" id="pagina-3">
        <input class="pagina-radio" type="radio" name="pagina-listado" id="pagina-4">
        <input class="pagina-radio" type="radio" name="pagina-listado" id="pagina-5">
        <input class="pagina-radio" type="radio" name="pagina-listado" id="pagina-6">
        <input class="pagina-radio" type="radio" name="pagina-listado" id="pagina-7">
        <input class="pagina-radio" type="radio" name="pagina-listado" id="pagina-8">
        <p id="mensaje-formulario" class="mensaje-formulario"></p>

        <article class="info-card">
            <div class="dash-card-cabecera">
                <span class="ficha-usuario-icono"><i class="fa-solid fa-users"></i></span>
                <h3>Usuarios</h3>
            </div>

            <div class="listado-controles">
                <form class="listado-buscar" method="get">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input class="form-control" id="buscar-usuario" name="q" type="search" placeholder=" " value="<?php echo htmlspecialchars((string) ($_GET['q'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                    <span></span>
                </form>
                <div class="listado-filtro listado-filtro-rol" tabindex="0">
                    <div class="listado-filtro-resumen"><span class="listado-filtro-flecha"></span></div>
                    <div id="opciones-rol">
                        <label><input type="radio" name="filtro-rol"></label>
                        <label><input type="radio" name="filtro-rol"></label>
                    </div>
                </div>
                <div class="listado-filtro listado-filtro-estado" tabindex="0">
                    <div class="listado-filtro-resumen"><span class="listado-filtro-flecha"></span></div>
                    <div id="opciones-estado">
                        <label><input type="radio" name="filtro-estado"></label>
                        <label><input type="radio" name="filtro-estado"></label>
                    </div>
                </div>
            </div>

            <div class="lista-scroll-mascotas">
                <table class="dash-tabla">
                    <thead>
                        <tr id="cabecera-usuarios">
                            <th id="columna-nro" data-campo="id"></th>
                            <th id="columna-nombre" data-campo="nombre"></th>
                            <th id="columna-apellido" data-campo="apellido"></th>
                            <th id="columna-nacimiento" data-campo="fecha_nacimiento"></th>
                            <th id="columna-alta" data-campo="fecha_alta"></th>
                            <th id="columna-rol" data-campo="nombre_rol"></th>
                            <th id="columna-estado" data-campo="estado"></th>
                        </tr>
                    </thead>
                    <tbody id="cuerpo-usuarios"></tbody>
                </table>
            </div>

            <div class="listado-paginacion">
                <span id="pagina-anterior">
                    <label for="pagina-1"></label>
                    <label for="pagina-2"></label>
                    <label for="pagina-3"></label>
                    <label for="pagina-4"></label>
                    <label for="pagina-5"></label>
                    <label for="pagina-6"></label>
                    <label for="pagina-7"></label>
                </span>
                <p id="pagina-indicador"></p>
                <span id="pagina-siguiente">
                    <label for="pagina-2"></label>
                    <label for="pagina-3"></label>
                    <label for="pagina-4"></label>
                    <label for="pagina-5"></label>
                    <label for="pagina-6"></label>
                    <label for="pagina-7"></label>
                    <label for="pagina-8"></label>
                </span>
            </div>
        </article>
    </section>
<?php if ($busquedaListado !== '') { ?>
<style>
.admin-page #panel-usuarios[data-q] tbody tr:not(:has([data-valor*="<?php echo htmlspecialchars($busquedaCss, ENT_QUOTES, 'UTF-8'); ?>"])) {
    display: none;
}
</style>
<?php } ?>

<script src="config.php"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/usuarios.js"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
