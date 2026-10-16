<?php
$esDetalleTransito = ($solicitudDetalleTipo ?? '') === 'transito';
$atributoCompatibilidadManual = empty($solicitudCompatibilidadManual) ? '' : ' data-compatibilidad-manual';
?>
    <section id="panel-solicitud-detalle" class="panel-listado" data-listado="solicitudes.php" data-tipo="<?php echo $esDetalleTransito ? 'transito' : 'adopcion'; ?>"<?php echo $atributoCompatibilidadManual; ?>>
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

<script src="config.php"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/solicitud-detalle.js"></script>
