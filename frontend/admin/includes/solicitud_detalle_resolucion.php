<?php
$solicitudResolucionAviso = $solicitudResolucionAviso ?? 'Esperando compatibilidad para resolver.';
?>
        <article class="info-card mb-3" id="ficha-resolucion" data-compatibilidad-lista="0">
            <div class="dash-card-cabecera">
                <span class="ficha-usuario-icono"><i class="bi bi-check2-square"></i></span>
                <h3>Resolución</h3>
            </div>
            <div class="row g-3 align-items-end">
                <div class="col-12 col-md-auto">
                    <p class="mb-0 solicitud-estado-actual"><span class="detalle-label">Estado actual:</span> <strong data-campo="estado_solicitud"></strong></p>
                </div>
                <div class="col-12">
                    <p id="resolucion-espera-compatibilidad" class="mb-2 text-body-secondary small"><?php echo $solicitudResolucionAviso; ?></p>
                    <div id="botones-estado" class="d-flex gap-2 flex-wrap" data-resolver data-compatibilidad-lista="0" hidden></div>
                </div>
            </div>
            <div id="modelos-estado" hidden>
                <button type="button" class="btn btn-outline btn-estado-solicitud" id="boton-modelo-estado" data-confirmar-estado="1" disabled></button>
            </div>
        </article>
