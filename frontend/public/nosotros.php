<?php
$tituloPagina = 'Nosotros - Patitas Seguras';
$refugio = require __DIR__ . '/includes/datos_refugio.php';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <section id="panel-nosotros">
        <div class="page-intro text-center">
            <h2>Nosotros</h2>
            <p>Conocé quiénes somos y cómo trabajamos todos los días.</p>
        </div>

        <div class="nosotros-card">
            <h3>¿Quiénes somos?</h3>
            <p><?php echo $refugio['sobre_nosotros']; ?></p>
        </div>

        <div class="row g-4" id="nosotros-trabajo">
<?php foreach ($refugio['trabajo'] as $tarea) { ?>
            <div class="col-12 col-md-6">
                <article class="info-card h-100">
                    <h3><i class="bi <?php echo $tarea['icono']; ?>" aria-hidden="true"></i> <?php echo $tarea['titulo']; ?></h3>
                    <p><?php echo $tarea['texto']; ?></p>
                </article>
            </div>
<?php } ?>
        </div>
        <div class="nosotros-acciones">
            <a class="btn-donate" href="/public/mascotas.php#adopcion"><i class="bi bi-heart-fill"></i> Conocé a los rescatados</a>
            <a class="btn-outline" href="/public/contacto.php">Escribinos</a>
        </div>
    </section>

<?php require __DIR__ . '/includes/footer.php'; ?>
