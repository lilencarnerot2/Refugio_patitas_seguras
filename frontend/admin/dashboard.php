<?php
$tituloPagina = 'Panel administrativo - Patitas Seguras';
$paginaAdmin = 'dashboard';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <section id="panel-dashboard">
        <p id="mensaje-panel" class="mensaje-formulario"></p>

        <div class="dash-fila dash-fila-tres">
            <article class="info-card">
                <h3>Solicitudes</h3>
                <div class="dash-kpi">
                    <svg class="dash-torta" viewBox="0 0 32 32" aria-hidden="true">
                        <circle class="dash-torta-vacia" r="16" cx="16" cy="16"></circle>
                        <circle id="torta-solicitudes-resto" class="dash-torta-resto" r="16" cx="16" cy="16" pathLength="100" stroke-dasharray="0 100"></circle>
                        <circle id="torta-solicitudes" class="dash-torta-parte" r="16" cx="16" cy="16" pathLength="100" stroke-dasharray="0 100"></circle>
                    </svg>
                    <ul id="leyenda-solicitudes"></ul>
                </div>
            </article>

            <article class="info-card">
                <h3>Mascotas en tránsito</h3>
                <div class="dash-kpi">
                    <svg class="dash-donut" viewBox="0 0 36 36" aria-hidden="true">
                        <circle class="dash-donut-fondo" cx="18" cy="18" r="14" pathLength="100"></circle>
                        <circle id="donut-transito" class="dash-donut-valor" cx="18" cy="18" r="14" pathLength="100" stroke-dasharray="0 100"></circle>
                    </svg>
                    <p id="texto-transito-pct" class="dash-kpi-numero">0%</p>
                </div>
                <ul>
                    <li>En tránsito <span id="texto-en-transito">0% (0)</span></li>
                    <li>Disponibles <span id="texto-disponibles">0% (0)</span></li>
                </ul>
            </article>

            <article class="info-card">
                <h3>Adopciones completadas (último mes)</h3>
                <p id="texto-adopciones-total" class="dash-kpi-numero">0</p>
                <p>Adopciones</p>
                <div id="adopciones-semanas" class="dash-semanas">
                    <div><progress max="1" value="0"></progress><span>Sem 1</span></div>
                    <div><progress max="1" value="0"></progress><span>Sem 2</span></div>
                    <div><progress max="1" value="0"></progress><span>Sem 3</span></div>
                    <div><progress max="1" value="0"></progress><span>Sem 4</span></div>
                </div>
            </article>
        </div>

        <div class="dash-fila dash-fila-dos">
            <article class="info-card">
                <div class="dash-card-cabecera">
                    <h3>Mascotas recientes</h3>
                    <a class="empty-back-link" href="mascotas.php">Ver todas</a>
                </div>
                <table class="dash-tabla">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Nombre</th>
                            <th>Edad</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody id="mascotas-recientes-cuerpo"></tbody>
                </table>
            </article>

            <article class="info-card">
                <h3>Estado de hogares de tránsito</h3>
                <p>Disponibilidad</p>
                <p><span id="texto-hogares-disponibles">0</span>% hogares disponibles</p>
                <progress id="barra-hogares-disponibles" class="dash-barra" max="100" value="0"></progress>
                <p>Capacidad ocupada</p>
                <p><span id="texto-hogares-ocupados">0</span>% hogares ocupados</p>
                <progress id="barra-hogares-ocupados" class="dash-barra" max="100" value="0"></progress>
                <a class="empty-back-link" href="hogares_transito.php">Ir a hogares de tránsito</a>
            </article>
        </div>
    </section>

<script src="config.php"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/dashboard.js"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
