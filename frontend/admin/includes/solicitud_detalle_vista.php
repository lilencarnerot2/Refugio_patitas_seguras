<?php
$esDetalleTransito = ($solicitudDetalleTipo ?? '') === 'transito';
$solicitudDetalleVariante = $esDetalleTransito ? 'transito' : 'adopcion';
$atributoCompatibilidadManual = empty($solicitudCompatibilidadManual) ? '' : ' data-compatibilidad-manual';
$atributoEvaluacionManual = !empty($solicitudEvaluacionManual) ? ' data-evaluacion-manual' : '';
$solicitudDetalleHref = $solicitudDetalleHref ?? ($esDetalleTransito ? 'transito_detalle.php' : 'adopcion_detalle.php');
$solicitudRecursoFormulario = $esDetalleTransito ? 'formularios_transito' : 'formularios_adopcion';
?>
    <section id="panel-solicitud-detalle" class="panel-listado" data-listado="solicitudes.php" data-tipo="<?php echo htmlspecialchars($solicitudDetalleVariante, ENT_QUOTES, 'UTF-8'); ?>" data-vista-href="<?php echo htmlspecialchars($solicitudDetalleHref, ENT_QUOTES, 'UTF-8'); ?>" data-recurso-formulario="<?php echo htmlspecialchars($solicitudRecursoFormulario, ENT_QUOTES, 'UTF-8'); ?>" data-etiqueta-si="Sí" data-etiqueta-no="No"<?php echo $atributoEvaluacionManual . $atributoCompatibilidadManual; ?>>
        <p id="mensaje-formulario" class="mensaje-formulario" role="alert" aria-live="assertive" aria-atomic="true"></p>

<?php require __DIR__ . '/solicitud_detalle_cabecera.php'; ?>

        <div class="row g-3 align-items-start mb-3">
<?php require __DIR__ . '/solicitud_detalle_mascota.php'; ?>
<?php require __DIR__ . '/solicitud_detalle_postulante.php'; ?>
        </div>

        <div class="row g-3 align-items-stretch mb-3 transito-fila-formulario">
<?php require __DIR__ . '/solicitud_detalle_formulario.php'; ?>
<?php require __DIR__ . '/solicitud_detalle_evaluacion.php'; ?>
        </div>

<?php require __DIR__ . '/solicitud_detalle_resolucion.php'; ?>
    </section>

<?php require __DIR__ . '/solicitud_detalle_modal_estado.php'; ?>

    <div id="modelos-solicitud" hidden aria-hidden="true">
<?php require __DIR__ . '/modelos_enlace_solicitud.php'; ?>
    </div>

<script src="config.php"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/solicitud-detalle.js"></script>
