<?php
$tituloPagina = 'Solicitudes - Patitas Seguras';
$paginaAdmin = 'solicitudes';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <section id="panel-solicitudes" class="panel-listado" data-por-pagina="5">
        <p id="mensaje-formulario" class="mensaje-formulario"></p>

        <div id="resumen-solicitudes" class="dash-fila"></div>

        <div id="bloque-solicitudes" class="info-card">
            <h3></h3>
            <div class="listado-controles row g-2">
                <div class="col-12 col-lg min-w-0">
                    <div class="input-group listado-buscar" aria-label="Buscar">
                        <i class="bi bi-search input-group-text"></i>
                        <input type="search" id="buscar-solicitudes" class="form-control" placeholder="Buscar" aria-label="Buscar">
                        <button type="button" class="btn btn-outline-secondary" data-limpiar="buscar-solicitudes" aria-label="Limpiar búsqueda"><i class="bi bi-x"></i></button>
                    </div>
                </div>
                <div class="col-12 col-md min-w-0">
                    <div class="input-group listado-filtro-grupo" aria-label="Estado">
                        <select id="filtro-estado-solicitud" class="form-select listado-filtro" aria-label="Estado">
                            <option value="" selected hidden></option>
                        </select>
                        <button type="button" class="btn btn-outline-secondary" data-limpiar="filtro-estado-solicitud" aria-label="Limpiar filtro de estado"><i class="bi bi-x"></i></button>
                    </div>
                </div>
                <div class="col-12 col-md min-w-0">
                    <div class="input-group listado-filtro-grupo" aria-label="Especie">
                        <select id="filtro-especie-solicitud" class="form-select listado-filtro" aria-label="Especie">
                            <option value="" selected hidden></option>
                        </select>
                        <button type="button" class="btn btn-outline-secondary" data-limpiar="filtro-especie-solicitud" aria-label="Limpiar filtro de especie"><i class="bi bi-x"></i></button>
                    </div>
                </div>
            </div>
            <div class="lista-scroll-mascotas">
                <table class="dash-tabla">
                    <thead>
                        <tr id="cabecera-solicitudes">
                            <th id="columna-solicitud-id"></th>
                            <th id="columna-solicitud-solicitante"></th>
                            <th id="columna-solicitud-mascota"></th>
                            <th id="columna-solicitud-tipo"></th>
                            <th id="columna-solicitud-estado"></th>
                            <th id="columna-solicitud-ver"></th>
                        </tr>
                    </thead>
                    <tbody id="cuerpo-solicitudes">
                        <tr id="fila-modelo-solicitud" hidden>
                            <td data-campo="id"></td>
                            <td data-campo="solicitante"></td>
                            <td data-campo="nombre_mascota"></td>
                            <td data-campo="tipo_solicitud"></td>
                            <td data-campo="estado_solicitud"></td>
                            <td><a class="abm-ver" href="adopcion_detalle.php" aria-label="Ver"></a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <?php
            $paginadorAncla = '#panel-solicitudes';
            $paginadorModeloId = 'pagina-modelo-solicitud';
            require __DIR__ . '/includes/paginador.php';
            ?>
            <!-- Modelos ocultos con el diseño; el JS los clona y solo pone datos -->
            <div id="modelos-solicitud" hidden>
                <article class="info-card" id="tarjeta-modelo-tipo">
                    <h3></h3>
                    <div class="dash-kpi">
                        <svg class="dash-donut" viewBox="0 0 42 42" aria-hidden="true">
                            <circle class="dash-donut-fondo" cx="21" cy="21" r="15.9"></circle>
                            <circle class="dash-donut-valor" cx="21" cy="21" r="15.9" pathLength="100" stroke-dasharray="0"></circle>
                        </svg>
                        <p class="dash-kpi-numero"></p>
                    </div>
                    <ul class="dash-leyenda"><li><span data-parte="cantidad"></span> / <span data-parte="total"></span></li></ul>
                </article>
                <option id="opcion-modelo-solicitud"></option>
            </div>
        </div>
    </section>

<script src="config.php"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/solicitudes.js"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
