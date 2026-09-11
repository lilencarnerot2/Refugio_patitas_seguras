<?php
$id = isset($_GET['id']) ? (string) $_GET['id'] : '';
$mascota = null;

if ($id !== '' && ctype_digit($id)) {
    $bases = ['http://backend', 'http://patitas_backend'];
    $env = rtrim((string) getenv('BACKEND_SERVICE_URL'), '/');

    if ($env !== '' && !preg_match('#^https?://(localhost|127\.0\.0\.1)(:\d+)?$#i', $env)) {
        array_unshift($bases, $env);
    }

    $contexto = stream_context_create([
        'http' => ['method' => 'GET', 'timeout' => 5, 'ignore_errors' => true],
    ]);

    foreach (array_unique($bases) as $base) {
        $respuesta = @file_get_contents(
            $base . '/public/index.php?recurso=mascotas&id=' . rawurlencode($id),
            false,
            $contexto
        );
        $datos = json_decode((string) $respuesta, true);

        if (!empty($datos['success']) && isset($datos['data']['id'])) {
            $mascota = $datos['data'];
            break;
        }
    }
}

$tituloPagina = ($mascota['nombre'] ?? 'Detalle de mascota') . ' - Patitas Seguras';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';

function textoDetalle($valor, $vacio = '')
{
    return htmlspecialchars((string) ($valor ?? $vacio), ENT_QUOTES, 'UTF-8');
}
?>

    <section>
        <div id="detalle-carga" class="catalog-empty" hidden>
            <i class="fa-solid fa-paw"></i>
            <h4>Cargando mascota...</h4>
            <p>Estamos consultando la información al refugio.</p>
        </div>

        <div id="detalle-vacio" class="catalog-empty"<?php echo $mascota ? ' hidden' : ''; ?>>
            <i class="fa-solid fa-paw"></i>
            <h4>Mascota no encontrada</h4>
            <p id="detalle-vacio-texto">Volvé al listado para ver las mascotas disponibles.</p>
            <a class="empty-back-link" href="mascotas.php">Volver a mascotas</a>
        </div>

        <article id="ficha-mascota" class="pet-detail"<?php echo $mascota ? '' : ' hidden'; ?>>
            <div class="row">
                <div class="col-md-6 pet-detail-photo">
                    <?php if (!empty($mascota['foto_url'])): ?>
                    <img id="detalle-foto" src="<?php echo textoDetalle($mascota['foto_url']); ?>" alt="Foto de <?php echo textoDetalle($mascota['nombre']); ?>">
                    <div id="detalle-foto-vacia" class="pet-photo-empty" hidden></div>
                    <?php else: ?>
                    <img id="detalle-foto" hidden alt="">
                    <div id="detalle-foto-vacia" class="pet-photo-empty"<?php echo $mascota ? '' : ' hidden'; ?>></div>
                    <?php endif; ?>
                </div>
                <div class="col-md-6 pet-detail-body">
                    <h2 id="detalle-nombre"><?php echo $mascota ? textoDetalle($mascota['nombre']) : ''; ?></h2>
                    <ul class="pet-detail-meta">
                        <li>Especie: <span id="detalle-especie"><?php echo $mascota ? textoDetalle($mascota['especie']) : ''; ?></span></li>
                        <li>Raza: <span id="detalle-raza"><?php echo $mascota ? textoDetalle($mascota['raza']) : ''; ?></span></li>
                        <li>Sexo: <span id="detalle-sexo"><?php echo $mascota ? textoDetalle($mascota['sexo']) : ''; ?></span></li>
                        <li>Tamaño: <span id="detalle-tamano"><?php echo $mascota ? textoDetalle($mascota['tamaño'] ?? '') : ''; ?></span></li>
                        <li>Nacimiento estimado: <span id="detalle-nacimiento"><?php echo $mascota ? textoDetalle($mascota['fecha_nacimiento_estimada'] ?? 'No informado') : ''; ?></span></li>
                    </ul>
                    <p id="detalle-historial" class="pet-detail-historial"><?php echo $mascota ? textoDetalle($mascota['historial'] ?? '') : ''; ?></p>
                    <div class="pet-card-actions">
                        <a id="detalle-adoptar" class="btn-adopt" href="../user/solicitar_adopcion.php<?php echo $mascota ? '?id=' . textoDetalle($mascota['id']) : ''; ?>">
                            <i class="fa-solid fa-heart"></i>
                            Adoptar
                        </a>
                        <a id="detalle-transito" class="btn-transito" href="../user/solicitar_transito.php<?php echo $mascota ? '?id=' . textoDetalle($mascota['id']) : ''; ?>">
                            <i class="fa-solid fa-house-chimney"></i>
                            Hogar de tránsito
                        </a>
                        <a class="empty-back-link" href="mascotas.php">Volver al catálogo</a>
                    </div>
                </div>
            </div>
        </article>
    </section>

    <script src="../assets/js/api.js"></script>
    <script src="../assets/js/mascotas.js"></script>
<?php require __DIR__ . '/includes/footer.php'; ?>
