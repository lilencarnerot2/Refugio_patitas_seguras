<?php
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <section>
        <div id="detalle-carga" class="catalog-empty">
            <i class="fa-solid fa-paw"></i>
            <h4>Cargando mascota...</h4>
            <p>Estamos consultando la información al refugio.</p>
        </div>

        <div id="detalle-vacio" class="catalog-empty" hidden>
            <i class="fa-solid fa-paw"></i>
            <h4>Mascota no encontrada</h4>
            <p id="detalle-vacio-texto">Volvé al listado para ver las mascotas disponibles.</p>
            <a class="empty-back-link" href="mascotas.php">Volver a mascotas</a>
        </div>

        <article id="ficha-mascota" class="pet-detail" hidden>
            <div class="row g-0">
                <div class="col-md-6 pet-detail-photo">
                    <img id="detalle-foto" class="img-fluid" alt="Foto de la mascota" hidden>
                    <div id="detalle-foto-vacia" class="pet-photo-empty"></div>
                </div>
                <div class="col-md-6 pet-detail-body">
                    <h2 id="detalle-nombre">Mascota</h2>
                    <ul class="pet-detail-meta">
                        <li>Especie: <span id="detalle-especie"></span></li>
                        <li>Raza: <span id="detalle-raza"></span></li>
                        <li>Sexo: <span id="detalle-sexo"></span></li>
                        <li>Tamaño: <span id="detalle-tamano"></span></li>
                        <li>Nacimiento estimado: <span id="detalle-nacimiento"></span></li>
                    </ul>
                    <p id="detalle-historial" class="pet-detail-historial"></p>
                    <div class="pet-card-actions">
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
            </div>
        </article>
    </section>

<?php require __DIR__ . '/includes/footer.php'; ?>
