<?php
$tituloPagina = 'Reportes de movimiento - Patitas Seguras';
$paginaAdmin = 'reportes_movimiento';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <section id="panel-aportes-reportes" class="panel-listado panel-aportes" data-por-pagina="5">
        <p id="mensaje-aportes" class="mensaje-formulario"></p>

        <div class="page-intro">
            <h2>Reportes de movimiento</h2>
            <p>Listado filtrado por tipo, frecuencia y método.</p>
        </div>

        <div id="resumen-aportes" class="dash-fila"></div>

        <article class="info-card">
            <div class="dash-card-cabecera">
                <span class="ficha-usuario-icono"><i class="bi bi-bar-chart"></i></span>
                <h3>Listado</h3>
            </div>
            <form id="form-movimiento-reporte" class="form-catalogo" hidden novalidate>
                <input type="hidden" name="id" value="">
                <div class="row g-3" data-campos-form></div>
                <div class="acciones-catalogo mt-3">
                    <button class="btn btn-primary" type="submit" aria-label="Alta"><i class="bi bi-check-lg"></i></button>
                    <button class="btn btn-outline-secondary" type="button" data-limpiar-form aria-label="Cerrar"><i class="bi bi-x"></i></button>
                </div>
            </form>
            <div class="listado-controles row g-2">
                <div class="col-12 col-md min-w-0">
                    <div class="input-group listado-filtro-grupo" aria-label="Tipo de movimiento">
                        <select id="filtro-tipo-movimiento" class="form-select listado-filtro" data-catalogo="tipos_movimientos_aportes" data-filtro="id_tipo_movimiento" aria-label="Tipo de movimiento">
                            <option value="" selected hidden></option>
                        </select>
                        <button type="button" class="btn btn-outline-secondary" data-limpiar="filtro-tipo-movimiento" aria-label="Limpiar filtro"><i class="bi bi-x"></i></button>
                    </div>
                </div>
                <div class="col-12 col-md min-w-0">
                    <div class="input-group listado-filtro-grupo" aria-label="Tipos">
                        <select id="filtro-tipo-aporte" class="form-select listado-filtro" data-catalogo="tipos_aportes" data-filtro="id_tipo_aporte" aria-label="Tipo de aporte">
                            <option value="" selected hidden></option>
                        </select>
                        <button type="button" class="btn btn-outline-secondary" data-limpiar="filtro-tipo-aporte" aria-label="Limpiar filtro"><i class="bi bi-x"></i></button>
                    </div>
                </div>
                <div class="col-12 col-md min-w-0">
                    <div class="input-group listado-filtro-grupo" aria-label="Frecuencia">
                        <select id="filtro-frecuencia" class="form-select listado-filtro" data-catalogo="frecuencia_aportes" data-filtro="id_frecuencia_aporte" aria-label="Frecuencia">
                            <option value="" selected hidden></option>
                        </select>
                        <button type="button" class="btn btn-outline-secondary" data-limpiar="filtro-frecuencia" aria-label="Limpiar filtro"><i class="bi bi-x"></i></button>
                    </div>
                </div>
                <div class="col-12 col-md min-w-0">
                    <div class="input-group listado-filtro-grupo" aria-label="Método de pago">
                        <select id="filtro-metodo" class="form-select listado-filtro" data-catalogo="metodos_pago" data-filtro="id_metodo_pago" aria-label="Método de pago">
                            <option value="" selected hidden></option>
                        </select>
                        <button type="button" class="btn btn-outline-secondary" data-limpiar="filtro-metodo" aria-label="Limpiar filtro"><i class="bi bi-x"></i></button>
                    </div>
                </div>
            </div>
            <div class="lista-scroll-mascotas">
                <table class="dash-tabla">
                    <thead>
                        <tr id="cabecera-movimientos"></tr>
                    </thead>
                    <tbody id="cuerpo-movimientos"></tbody>
                </table>
            </div>
            <?php
            $paginadorAncla = '#panel-aportes-reportes';
            require __DIR__ . '/includes/paginador.php';
            ?>
        </article>
    </section>

<script src="config.php"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/aportes.js"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
