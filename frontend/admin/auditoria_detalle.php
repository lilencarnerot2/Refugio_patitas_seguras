<?php
$paginaAdmin = 'auditoria_detalle';
$recursoApi = 'auditorias';
$listadoPhp = 'auditorias.php';
$etiquetaListado = pathinfo($listadoPhp, PATHINFO_FILENAME);
$formatoFechaHora = 'fecha-hora';
$tituloPagina = $paginaAdmin;
$requiereSuperadministrador = true;
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <section id="panel-auditoria-detalle" data-listado="<?php echo $listadoPhp; ?>" data-recurso="<?php echo $recursoApi; ?>" data-formato-fecha-hora="<?php echo $formatoFechaHora; ?>">
        <p id="mensaje-formulario" class="mensaje-formulario"></p>

        <article class="info-card auditoria-detalle-card">
            <div class="dash-card-cabecera">
                <div class="auditoria-detalle-cabecera-titulo">
                    <span class="ficha-usuario-icono"><i class="bi bi-journal-text"></i></span>
                    <h3 id="auditoria-detalle-titulo" data-campo="id" data-etiqueta="Detalle de auditoría #"></h3>
                </div>
                <a class="btn-primary auditoria-detalle-volver" id="auditoria-detalle-listado" href="<?php echo $listadoPhp; ?>" data-etiqueta="<?php echo $etiquetaListado; ?>"></a>
            </div>

            <div class="auditoria-detalle-grid">
            <ul class="pet-detail-meta auditoria-detalle-meta">
                <li>
                    <span class="detalle-label" data-etiqueta="fecha"></span>
                    <span data-campo="fecha_hora" data-formato="<?php echo $formatoFechaHora; ?>"></span>
                </li>
                <li>
                    <span class="detalle-label" data-etiqueta="nombre"></span>
                    <span data-campo="nombre_usuario"></span>
                </li>
                <li>
                    <span class="detalle-label" data-etiqueta="apellido"></span>
                    <span data-campo="apellido_usuario"></span>
                </li>
                <li>
                    <span class="detalle-label" data-etiqueta="tabla"></span>
                    <span data-campo="nombre_tabla"></span>
                </li>
                <li>
                    <span class="detalle-label" data-etiqueta="accion"></span>
                    <span data-campo="accion"></span>
                </li>
                <li>
                    <span class="detalle-label" data-etiqueta="ip"></span>
                    <span data-campo="ip_origen"></span>
                </li>
            </ul>

            <div class="row g-3 auditoria-detalle-comparacion auditoria-detalle-json-fila">
                <div class="col-12 col-md-6 d-flex">
                    <div class="auditoria-json-panel">
                        <h6 class="auditoria-json-titulo" data-etiqueta="valores_anteriores"></h6>
                        <pre class="auditoria-json" data-campo="valores_anteriores"></pre>
                    </div>
                </div>
                <div class="col-12 col-md-6 d-flex">
                    <div class="auditoria-json-panel">
                        <h6 class="auditoria-json-titulo" data-etiqueta="valores_nuevos"></h6>
                        <pre class="auditoria-json" data-campo="valores_nuevos"></pre>
                    </div>
                </div>
            </div>
            </div>
        </article>
    </section>

<script src="config.php"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/auditoria-detalle.js"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
