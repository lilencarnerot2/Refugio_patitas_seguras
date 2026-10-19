<?php
$tituloPagina = 'Nosotros - Patitas Seguras';
$refugio = require __DIR__ . '/includes/datos_refugio.php';
$cuit = $refugio['cuentas'][0]['datos']['CUIT'] ?? '';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <section id="panel-nosotros">
        <div class="page-intro text-center">
            <h2>Nosotros</h2>
            <p>Conocé quiénes somos y cómo trabajamos todos los días.</p>
        </div>

        <div class="nosotros-card">
            <h3>QUIÉNES SOMOS</h3>
            <p><?php echo htmlspecialchars($refugio['sobre_nosotros'], ENT_QUOTES, 'UTF-8'); ?></p>
        </div>

        <div class="row g-4" id="nosotros-trabajo">
<?php foreach ($refugio['trabajo'] as $tarea) { ?>
            <div class="col-12 col-md-6">
                <article class="info-card h-100">
                    <h3><i class="bi <?php echo htmlspecialchars($tarea['icono'], ENT_QUOTES, 'UTF-8'); ?>" aria-hidden="true"></i> <?php echo htmlspecialchars($tarea['titulo'], ENT_QUOTES, 'UTF-8'); ?></h3>
                    <p><?php echo htmlspecialchars($tarea['texto'], ENT_QUOTES, 'UTF-8'); ?></p>
                </article>
            </div>
<?php } ?>
        </div>

        <div class="info-card" id="nosotros-transparencia">
            <h3><i class="bi bi-shield-check" aria-hidden="true"></i> Transparencia</h3>
            <ul>
                <li>Somos un refugio independiente y sin fines de lucro.</li>
<?php if ($cuit !== '') { ?>
                <li>CUIT: <?php echo htmlspecialchars($cuit, ENT_QUOTES, 'UTF-8'); ?></li>
<?php } ?>
                <li>Todas las cuentas oficiales para colaborar están en <a href="/public/donaciones.php">Donación</a>.</li>
            </ul>
        </div>

        <div class="nosotros-acciones">
            <a class="btn-donate" href="/public/mascotas.php#adopcion"><i class="bi bi-heart-fill"></i> Conocé a los rescatados</a>
            <a class="btn-outline" href="/public/contacto.php">Escribinos</a>
        </div>
    </section>

<?php require __DIR__ . '/includes/footer.php'; ?>
