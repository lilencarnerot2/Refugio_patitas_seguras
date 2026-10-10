<?php
$tituloPagina = 'Control de salud - Patitas Seguras';
$paginaAdmin = 'salud';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <section id="panel-salud">
        <p id="mensaje-formulario" class="mensaje-formulario"></p>

        <div class="dash-fila dash-fila-tres">
            <article class="info-card">
                <h3>Fichas médicas</h3>
                <div class="dash-kpi">
                    <svg class="dash-donut" viewBox="0 0 36 36" aria-hidden="true">
                        <circle class="dash-donut-fondo" cx="18" cy="18" r="14" pathLength="100"></circle>
                        <circle id="donut-fichas" class="dash-donut-valor" cx="18" cy="18" r="14" pathLength="100" stroke-dasharray="0"></circle>
                    </svg>
                    <p class="dash-kpi-numero"><span id="texto-fichas-pct">0</span></p>
                </div>
                <ul class="resumen-fichas">
                    <li>Con ficha <span id="texto-con-ficha-pct">0</span> (<span id="texto-con-ficha-num">0</span>)</li>
                    <li>Sin ficha <span id="texto-sin-ficha-pct">0</span> (<span id="texto-sin-ficha-num">0</span>)</li>
                </ul>
            </article>

            <article class="info-card">
                <h3>Tipos de vacunas</h3>
                <p id="texto-tipos-vacunas" class="dash-kpi-numero">0</p>
                <p>Tipos</p>
                <ul id="lista-tipos-vacunas"></ul>
            </article>

            <article class="info-card">
                <h3>Tipos de productos</h3>
                <p id="texto-tipos-productos" class="dash-kpi-numero">0</p>
                <p>Tipos</p>
                <ul id="lista-tipos-productos"></ul>
            </article>
        </div>

        <div class="dash-fila">
            <article class="info-card dash-card-recientes">
                <div class="dash-card-cabecera">
                    <h3>Fichas médicas por mascota</h3>
                </div>
                <div class="listado-controles">
                    <div class="input-group listado-buscar">
                        <i class="bi bi-search input-group-text"></i>
                        <input type="search" id="buscar-salud" class="form-control" placeholder="Buscar" aria-label="Buscar">
                        <button type="button" class="btn btn-outline-secondary" data-limpiar="buscar-salud" aria-label="Limpiar búsqueda"><i class="bi bi-x"></i></button>
                    </div>
                    <div class="input-group listado-filtro-grupo">
                        <select id="filtro-especie-salud" class="form-select listado-filtro" aria-label="Especie">
                            <option value="" selected disabled hidden>Especie</option>
                        </select>
                        <button type="button" class="btn btn-outline-secondary" data-limpiar="filtro-especie-salud" aria-label="Limpiar filtro de especie"><i class="bi bi-x"></i></button>
                    </div>
                    <div class="input-group listado-filtro-grupo">
                        <select id="filtro-ficha-salud" class="form-select listado-filtro" aria-label="Ficha">
                            <option value="" selected disabled hidden>Ficha</option>
                            <option value="ok">Con ficha</option>
                            <option value="error">Sin ficha</option>
                        </select>
                        <button type="button" class="btn btn-outline-secondary" data-limpiar="filtro-ficha-salud" aria-label="Limpiar filtro de ficha"><i class="bi bi-x"></i></button>
                    </div>
                </div>
                <div class="lista-scroll-mascotas">
                    <table class="dash-tabla table table-sm align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Nombre</th>
                            <th>Especie</th>
                            <th>Nacimiento</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="cuerpo-mascotas-salud" data-texto-ok="Ficha médica" data-texto-vacio="Sin ficha médica">
                        <tr id="fila-modelo-salud" hidden>
                            <td><span class="dash-foto"><img alt=""></span></td>
                            <td data-celda="nombre"></td>
                            <td data-celda="especie"></td>
                            <td data-celda="nacimiento"></td>
                            <td><a class="empty-back-link" href="ficha_medica.php" data-estado="pendiente">Verificando…</a></td>
                        </tr>
                    </tbody>
                </table>
                <!-- Modelos ocultos con el diseño; el JS los clona y solo pone datos -->
                <p id="mensaje-vacio-salud" class="text-muted" hidden>No hay mascotas para mostrar.</p>
                <div id="modelos-salud" hidden>
                    <ul><li id="item-modelo-salud"></li></ul>
                    <option id="opcion-modelo-salud"></option>
                </div>
                </div>
            </article>
        </div>
    </section>

<script src="config.php"></script>
<script src="../assets/js/api.js?t=<?php echo filemtime(__DIR__ . '/../assets/js/api.js'); ?>"></script>
<script src="../assets/js/salud.js?t=<?php echo filemtime(__DIR__ . '/../assets/js/salud.js'); ?>"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
