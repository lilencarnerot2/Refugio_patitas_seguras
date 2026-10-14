<?php
$tituloPagina = 'Donaciones - Patitas Seguras';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <section id="panel-aportes-sitio" class="panel-aportes">
        <div class="page-intro text-center">
            <h2>Donaciones</h2>
            <p>Elegí cómo querés ayudar. Cada registro sale de lo que pide el sitio.</p>
        </div>

        <div class="row g-4 justify-content-center donaciones-elegir">
            <div class="col-12 col-md-6 col-xl-5">
                <a class="way-card h-100" href="/public/donaciones_insumos.php">
                    <i class="bi bi-box-seam"></i>
                    <div>
                        <h5>Donación de insumos</h5>
                        <p>Alimento, mantas, medicación y otros aportes no monetarios.</p>
                    </div>
                </a>
            </div>
            <div class="col-12 col-md-6 col-xl-5">
                <a class="way-card h-100" href="/public/donaciones_monetarias.php">
                    <i class="bi bi-cash-coin"></i>
                    <div>
                        <h5>Donación monetaria</h5>
                        <p>Transferencia o pago. Vas a ver alias para enviar el dinero y registrar el aporte.</p>
                    </div>
                </a>
            </div>
        </div>
    </section>

<?php require __DIR__ . '/includes/footer.php'; ?>
