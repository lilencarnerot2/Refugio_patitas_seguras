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
            <nav class="paginador" data-paginador aria-label="Paginación" hidden>
                <ul class="pagination">
                    <li class="page-item">
                        <a class="page-link" data-pagina-anterior href="#panel-aportes-admin" aria-label="Página anterior"><i class="bi bi-chevron-left"></i></a>
                    </li>
                    <li class="page-item" data-pagina-modelo hidden>
                        <button type="button" class="page-link"></button>
                    </li>
                    <li class="page-item">
                        <a class="page-link" data-pagina-siguiente href="#panel-aportes-admin" aria-label="Página siguiente"><i class="bi bi-chevron-right"></i></a>
                    </li>
                </ul>
            </nav>
        </article>
    </section>

<script src="config.php"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/aportes.js"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
