<?php
$solicitudDetalleTitulo = preg_replace('/\s*-\s*Patitas Seguras\s*$/u', '', $tituloPagina ?? '');
$solicitudDetalleVolver = $solicitudDetalleVolver ?? 'solicitudes.php';
$solicitudDetalleIcono = $solicitudDetalleIcono ?? '';
?>
        <div class="dash-card-cabecera mb-3 solicitud-detalle-cabecera">
            <div class="solicitud-detalle-fila-principal d-flex align-items-center gap-2 w-100">
                <div class="d-flex align-items-center gap-2 flex-nowrap solicitud-detalle-titulo min-w-0">
                    <?php if ($solicitudDetalleIcono !== '') { ?>
                    <span class="ficha-usuario-icono flex-shrink-0"><i class="bi <?php echo $solicitudDetalleIcono; ?>"></i></span>
                    <?php } ?>
                    <h3 class="mb-0 min-w-0"><?php echo $solicitudDetalleTitulo; ?></h3>
                </div>
                <div class="solicitud-detalle-acciones admin-barra-acciones d-flex flex-shrink-0 ms-auto">
                    <a id="link-seguimientos-solicitud" class="btn-outline solicitud-detalle-accion" href="#" hidden>Seguimiento</a>
                    <?php if ($solicitudDetalleVolver !== '') { ?>
                    <a class="btn-outline solicitud-detalle-accion solicitud-detalle-volver" href="<?php echo $solicitudDetalleVolver; ?>">Volver</a>
                    <?php } ?>
                </div>
            </div>
            <div id="ficha-solicitud-meta" class="solicitud-meta-datos w-100">
                <span class="solicitud-meta-item">
                    <span class="detalle-label">Fecha de solicitud</span>
                    <span data-campo="fecha_solicitud"></span>
                </span>
            </div>
        </div>
