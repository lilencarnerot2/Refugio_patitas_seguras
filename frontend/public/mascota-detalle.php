<?php
$tituloPagina = 'Detalle de mascota - Patitas Seguras';
$cuerpoClase = 'page-adapt';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <section class="detalle-section">
        <div id="detalle-carga" class="catalog-empty">
            <i class="fa-solid fa-paw"></i>
            <h4>Cargando mascota...</h4>
            <p>Estamos consultando la información al refugio.</p>
        </div>

        <div id="detalle-vacio" class="catalog-empty" hidden>
            <i class="fa-solid fa-paw"></i>
            <h4>Mascota no encontrada</h4>
            <p>Volvé al listado para ver las mascotas disponibles.</p>
            <a class="empty-back-link" href="mascotas.php">Volver a mascotas</a>
        </div>

        <article id="ficha-mascota" class="pet-detail" hidden>
            <div class="pet-detail-photo">
                <img id="detalle-foto" hidden alt="">
                <div id="detalle-foto-vacia" class="pet-photo-empty"></div>
            </div>
            <div class="pet-detail-body">
                <h2 id="detalle-nombre"></h2>
                <ul class="pet-detail-meta">
                    <li><span class="detalle-label">Especie</span><span id="detalle-especie"></span></li>
                    <li><span class="detalle-label">Raza</span><span id="detalle-raza"></span></li>
                    <li><span class="detalle-label">Sexo</span><span id="detalle-sexo"></span></li>
                    <li><span class="detalle-label">Tamaño</span><span id="detalle-tamano"></span></li>
                    <li><span class="detalle-label">Nacimiento estimado</span><span id="detalle-nacimiento"></span></li>
                </ul>
                <p id="detalle-historial" class="pet-detail-historial"></p>
                <div class="pet-detail-actions">
                    <a id="detalle-adoptar" class="btn-adopt" href="../user/solicitar_adopcion.php">
                        <i class="fa-solid fa-heart"></i>
                        Adoptar
                    </a>
                    <a id="detalle-transito" class="btn-transito" href="../user/solicitar_transito.php">
                        <i class="fa-solid fa-house-chimney"></i>
                        Hogar de tránsito
                    </a>
                    <a class="empty-back-link" href="mascotas.php">Volver al catálogo</a>
                </div>
            </div>
        </article>
    </section>

<script src="config.php"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/mascotas-detalles.js"></script>
<?php require __DIR__ . '/includes/footer.php'; ?>
