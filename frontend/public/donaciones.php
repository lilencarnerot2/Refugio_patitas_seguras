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
                        <i class="bi <?php echo $cuenta['icono']; ?> tarjeta-alias-icono" aria-hidden="true"></i>
                        <h3 class="card-title tarjeta-alias-titulo"><?php echo $cuenta['medio']; ?></h3>
                        <hr class="w-100 my-3">
                        <span class="tarjeta-alias-valor rounded-pill"><?php echo $cuenta['alias']; ?></span>
                        <ul class="list-unstyled small mb-0 mt-3 tarjeta-alias-datos">
<?php foreach ($cuenta['datos'] as $etiqueta => $valor) { ?>
                            <li><b><?php echo $etiqueta; ?>:</b> <?php echo $valor; ?></li>
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
            <div class="col-6 col-md-4 col-lg-3">
                <article class="card tarjeta-monto h-100 border-0 rounded-4 shadow-sm text-center">
                    <div class="card-body d-flex flex-column align-items-center justify-content-between p-4">
                        <i class="bi bi-arrow-repeat tarjeta-monto-icono" aria-hidden="true"></i>
                        <span class="tarjeta-monto-valor">Débito automático</span>
<?php if (!empty($refugio['debito_automatico_url'])) { ?>
                        <a class="btn-donate" href="<?php echo $refugio['debito_automatico_url']; ?>" target="_blank" rel="noopener noreferrer">Adherirme</a>
<?php } else { ?>
                        <button class="btn-donate" type="button" disabled>Adherirme</button>
<?php } ?>
                    </div>
                </article>
            </div>
<?php foreach ($refugio['montos_mp'] as $opcion) { ?>
            <div class="col-6 col-md-4 col-lg-3">
                <article class="card tarjeta-monto h-100 border-0 rounded-4 shadow-sm text-center">
                    <div class="card-body d-flex flex-column align-items-center justify-content-between p-4">
                        <i class="bi bi-heart-fill tarjeta-monto-icono" aria-hidden="true"></i>
                        <span class="tarjeta-monto-valor">$ <?php echo number_format($opcion['monto'], 0, ',', '.'); ?></span>
<?php if (!empty($opcion['url'])) { ?>
                        <a class="btn-donate" href="<?php echo $opcion['url']; ?>" target="_blank" rel="noopener noreferrer">Donar</a>
<?php } else { ?>
                        <button class="btn-donate" type="button" disabled>Próximamente</button>
<?php } ?>
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
            </ul>
            <a href="/public/castraciones.php" class="btn btn-success mt-3">Ver más</a>
        </div>
    </section>

<?php require __DIR__ . '/includes/footer.php'; ?>
