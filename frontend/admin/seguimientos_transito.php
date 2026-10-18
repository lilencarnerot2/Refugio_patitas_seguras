<?php
$paginaAdmin = basename(__FILE__, '.php');
$recursoSeguimiento = $paginaAdmin;
$claveSolicitud = 'transito';
$tituloPagina = 'Seguimientos de tránsito - Patitas Seguras';
$tituloSeguimientos = 'Seguimientos de tránsito';
$subtituloSeguimientos = 'Consulta, registro, realización y reprogramación.';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/seguimientos_panel.php';
?>
<script src="config.php"></script>
<script src="../assets/js/api.js?v=<?php echo (int) @filemtime(__DIR__ . '/../assets/js/api.js'); ?>"></script>
<script src="../assets/js/seguimientos-admin.js?v=<?php echo (int) @filemtime(__DIR__ . '/../assets/js/seguimientos-admin.js'); ?>"></script>
<?php require __DIR__ . '/includes/footer.php'; ?>
