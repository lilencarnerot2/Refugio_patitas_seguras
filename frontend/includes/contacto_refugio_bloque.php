<?php

if (!isset($patitasContacto)) {
    require __DIR__ . '/contacto_refugio.php';
}

?>
<?php if ($patitasContacto['telefono'] !== '') { ?>
<p><i class="bi bi-telephone"></i> <?php echo htmlspecialchars($patitasContacto['telefono'], ENT_QUOTES, 'UTF-8'); ?></p>
<?php } ?>
<?php if ($patitasContacto['email'] !== '') { ?>
<p><i class="bi bi-envelope"></i> <?php echo htmlspecialchars($patitasContacto['email'], ENT_QUOTES, 'UTF-8'); ?></p>
<?php } ?>
<?php if ($patitasContacto['direccion'] !== '') { ?>
<p><i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($patitasContacto['direccion'], ENT_QUOTES, 'UTF-8'); ?></p>
<?php } ?>
