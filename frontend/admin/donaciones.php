<?php
$tituloPagina = 'Tipos de aportes - Patitas Seguras';
$paginaAdmin = 'donaciones';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <section id="panel-aportes-admin" class="panel-listado panel-aportes" data-por-pagina="5">
        <p id="mensaje-aportes" class="mensaje-formulario"></p>

        <div class="page-intro">
            <h2>Aportes </h2>
            <p>Monetarios y no monetarios.</p>
        </div>
        <article class="info-card">
            <div class="dash-card-cabecera">
                <span class="ficha-usuario-icono"><i class="bi bi-box-arrow-up"></i></span>
                <h3>Registrar egreso</h3>
            </div>
            <form id="form-egreso" class="form-catalogo" novalidate autocomplete="off">
                <input type="hidden" name="fecha" data-fecha-hoy>
                <div hidden>
                    <select id="egreso-tipo-movimiento" name="id_tipo_movimiento" data-catalogo="tipos_movimientos_aportes" data-incluir="egreso" required>
                        <option value="" selected hidden></option>
                    </select>
                    <select id="egreso-tipo-aporte" name="id_tipo_aporte" data-catalogo="tipos_aportes" data-incluir="dinero" required>
                        <option value="" selected hidden></option>
                    </select>
                </div>
                <div class="row g-3">
                    <div class="col-12 col-md-6 min-w-0">
                        <label class="form-label" for="egreso-monto">Monto</label>
                        <input class="form-control" id="egreso-monto" name="monto" type="number" step="any" min="0.01" required>
                    </div>
                    <div class="col-12 col-md-6 min-w-0">
                        <label class="form-label" for="egreso-metodo-pago">Método de pago</label>
                        <select class="form-select" id="egreso-metodo-pago" name="id_metodo_pago" data-catalogo="metodos_pago" required>
                            <option value="" selected hidden></option>
                        </select>
                    </div>
                    <div class="col-12 min-w-0">
                        <label class="form-label" for="egreso-descripcion">Descripción</label>
                        <textarea class="form-control" id="egreso-descripcion" name="descripcion" rows="2" maxlength="1000" required></textarea>
                    </div>
                </div>
                <div class="acciones-catalogo mt-3">
                    <button class="btn-primary" type="submit">Registrar egreso</button>
                </div>
            </form>
        </article>
        <article class="info-card">
            <div class="dash-card-cabecera">
                <span class="ficha-usuario-icono"><i class="bi bi-table"></i></span>
                <h3>Tipos de aportes</h3>
            </div>
            <div class="listado-controles row g-2">
                <div class="col-12 col-md min-w-0">
                    <div class="input-group listado-filtro-grupo" aria-label="Aportes">
                        <select id="filtro-aporte" class="form-select listado-filtro" data-filtro="id" aria-label="Aportes">
                            <option value="" selected hidden></option>
                        </select>
                        <button type="button" class="btn btn-outline-secondary" data-limpiar="filtro-aporte" aria-label="Limpiar filtro"><i class="bi bi-x"></i></button>
                    </div>
                </div>
                <div class="col-12 col-md min-w-0">
                    <div class="input-group listado-filtro-grupo" aria-label="Tipos">
                        <select id="filtro-tipo-aporte" class="form-select listado-filtro" data-catalogo="tipos_aportes" data-filtro="id_tipo_aporte" aria-label="Tipos">
                            <option value="" selected hidden></option>
                        </select>
                        <button type="button" class="btn btn-outline-secondary" data-limpiar="filtro-tipo-aporte" aria-label="Limpiar filtro"><i class="bi bi-x"></i></button>
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
            $paginadorAncla = '#panel-aportes-admin';
            require __DIR__ . '/includes/paginador.php';
            ?>
        </article>
    </section>

<script src="config.php"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/aportes.js"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
