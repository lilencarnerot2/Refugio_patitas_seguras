<?php
$tituloPagina = 'Donación - Patitas Seguras';
$refugio = require __DIR__ . '/includes/datos_refugio.php';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <section id="panel-aportes-sitio" class="panel-aportes">
        <div class="page-intro text-center">
            <h2>Donación</h2>
            <p>Podés colaborar con una transferencia a cualquiera de estas cuentas.</p>
        </div>

        <div class="row g-4 justify-content-center" id="tarjetas-alias">
<?php foreach ($refugio['cuentas'] as $cuenta) { ?>
            <div class="col-12 col-md-6 col-lg-4">
                <article class="card tarjeta-alias h-100 border-0 rounded-4 shadow-sm text-center">
                    <div class="card-body d-flex flex-column align-items-center p-4">
                        <i class="bi <?php echo htmlspecialchars($cuenta['icono'], ENT_QUOTES, 'UTF-8'); ?> tarjeta-alias-icono" aria-hidden="true"></i>
                        <h3 class="card-title tarjeta-alias-titulo"><?php echo htmlspecialchars($cuenta['medio'], ENT_QUOTES, 'UTF-8'); ?></h3>
                        <hr class="w-100 my-3">
                        <span class="tarjeta-alias-valor rounded-pill"><?php echo htmlspecialchars($cuenta['alias'], ENT_QUOTES, 'UTF-8'); ?></span>
                        <ul class="list-unstyled small mb-0 mt-3 tarjeta-alias-datos">
<?php foreach ($cuenta['datos'] as $etiqueta => $valor) { ?>
                            <li><b><?php echo htmlspecialchars($etiqueta, ENT_QUOTES, 'UTF-8'); ?>:</b> <?php echo htmlspecialchars($valor, ENT_QUOTES, 'UTF-8'); ?></li>
<?php } ?>
                        </ul>
                    </div>
                </article>
            </div>
<?php } ?>
        </div>

        <div class="page-intro text-center" id="donar-mercado-pago">
            <h3>Donar con Mercado Pago</h3>
            <p>Elegí un monto y completá el pago en Mercado Pago.</p>
        </div>

        <div class="row g-4 justify-content-center" id="tarjetas-montos">
<?php foreach ($refugio['montos_mp'] as $opcion) { ?>
            <div class="col-6 col-md-4 col-lg-3">
                <article class="card tarjeta-monto h-100 border-0 rounded-4 shadow-sm text-center">
                    <div class="card-body d-flex flex-column align-items-center justify-content-between p-4">
                        <i class="bi bi-heart-fill tarjeta-monto-icono" aria-hidden="true"></i>
                        <span class="tarjeta-monto-valor">$ <?php echo number_format($opcion['monto'], 0, ',', '.'); ?></span>
                        <a class="btn-donate" href="<?php echo htmlspecialchars($opcion['url'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer">Donar</a>
                    </div>
                </article>
            </div>
<?php } ?>
        </div>

        <p class="donaciones-mp-nota">
<?php if (usuarioAutenticado()) { ?>
            Tenés la sesión iniciada: tu donación va a figurar a tu nombre.
<?php } else { ?>
            Podés donar sin registrarte y figurar como donador anónimo. Si preferís que figure a tu nombre, <a href="/public/login.php">ingresá</a> o <a href="/public/registro.php">creá tu cuenta</a> antes de donar.
<?php } ?>
        </p>

        <div class="donaciones-insumos-accion">
            <p>¿Preferís donar alimento, mantas, medicación u otros insumos?</p>
            <a class="btn-donate" href="/public/donaciones_insumos.php"><i class="bi bi-box-seam"></i> Donar insumos</a>
        </div>

        <div class="info-card" id="info-castracion">
            <h3><i class="bi bi-scissors"></i> Castración</h3>
            <p>Organizamos y difundimos campañas de castración para prevenir el abandono y mejorar la salud de perros y gatos.</p>
            <ul>
                <li>Tu donación monetaria también ayuda a costear estas campañas.</li>
                <li>Si querés que tu aporte vaya a castraciones, avisanos desde <a href="/public/contacto.php">Contacto</a> cuando hagas la transferencia.</li>
            </ul>
        </div>
    </section>

<?php require __DIR__ . '/includes/footer.php'; ?>
