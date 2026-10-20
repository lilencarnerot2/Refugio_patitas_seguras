<?php
$tituloPagina = 'Detalle de solicitud de adopción - Patitas Seguras';
$paginaAdmin = 'adopcion_detalle';
$solicitudDetalleTipo = 'adopcion';
$solicitudDetalleIcono = 'bi-heart';
$solicitudPostulanteTipoVivienda = true;
$solicitudEvaluacionManual = true;
$solicitudCompatibilidadManual = true;
$solicitudResolucionAviso = 'Evaluá la compatibilidad para habilitar aprobación o rechazo.';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/solicitud_detalle_vista.php';
require __DIR__ . '/includes/footer.php';
