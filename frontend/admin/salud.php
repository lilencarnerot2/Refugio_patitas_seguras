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
                        <circle id="donut-fichas" class="dash-donut-valor" cx="18" cy="18" r="14" pathLength="100" stroke-dasharray="0 100"></circle>
                    </svg>
                    <p id="texto-fichas-pct" class="dash-kpi-numero">0%</p>
                </div>
                <ul>
                    <li>Con ficha <span id="texto-con-ficha">0% (0)</span></li>
                    <li>Sin ficha <span id="texto-sin-ficha">0% (0)</span></li>
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
                <div class="lista-scroll-mascotas">
                    <table class="dash-tabla">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Nombre</th>
                            <th>Especie</th>
                            <th>Nacimiento</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="cuerpo-mascotas-salud"></tbody>
                </table>
                </div>
            </article>
        </div>
    </section>

<script src="config.php"></script>
<script src="../assets/js/api.js?t=<?php echo filemtime(__DIR__ . '/../assets/js/api.js'); ?>"></script>
<script src="../assets/js/salud.js?t=<?php echo filemtime(__DIR__ . '/../assets/js/salud.js'); ?>"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
