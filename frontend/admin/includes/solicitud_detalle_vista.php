<?php
$tipoDetalle = trim((string) ($solicitudDetalleTipo ?? ''));
$solicitudDetalleHref = basename($_SERVER['SCRIPT_NAME'] ?? '');
$solicitudDetalleListado = $solicitudDetalleListado ?? 'solicitudes.php';
$atributoCompatibilidadManual = empty($solicitudCompatibilidadManual) ? '' : ' data-compatibilidad-manual';
$atributoEvaluacionManual = !empty($solicitudEvaluacionManual) ? ' data-evaluacion-manual' : '';
$recursoFormularioDetalle = trim((string) ($solicitudRecursoFormulario ?? ''));
if ($recursoFormularioDetalle === '' && $tipoDetalle === 'transito') {
    $recursoFormularioDetalle = 'formularios_transito';
} elseif ($recursoFormularioDetalle === '' && $tipoDetalle === 'adopcion') {
    $recursoFormularioDetalle = 'formularios_adopcion';
}
$atributoRecursoFormulario = $recursoFormularioDetalle === ''
    ? ''
    : ' data-recurso-formulario="' . htmlspecialchars($recursoFormularioDetalle, ENT_QUOTES, 'UTF-8') . '"';
?>
    <section id="panel-solicitud-detalle" class="panel-listado"
        data-listado="<?php echo htmlspecialchars($solicitudDetalleListado, ENT_QUOTES, 'UTF-8'); ?>"
        data-tipo="<?php echo htmlspecialchars($tipoDetalle, ENT_QUOTES, 'UTF-8'); ?>"
        data-vista-href="<?php echo htmlspecialchars($solicitudDetalleHref, ENT_QUOTES, 'UTF-8'); ?>"
        data-si="Sí"
        data-no="No"
        data-etiqueta-si="Sí"
        data-etiqueta-no="No"<?php echo $atributoRecursoFormulario . $atributoEvaluacionManual . $atributoCompatibilidadManual; ?>>
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

<script src="config.php?v=<?php echo (int) @filemtime(__DIR__ . '/../config.php'); ?>"></script>
<script src="../assets/js/api.js?v=<?php echo (int) @filemtime(__DIR__ . '/../../assets/js/api.js'); ?>"></script>
<script src="../assets/js/solicitud-detalle.js?v=<?php echo (int) @filemtime(__DIR__ . '/../../assets/js/solicitud-detalle.js'); ?>"></script>
