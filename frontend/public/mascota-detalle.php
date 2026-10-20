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
            <div class="pet-detail-gallery">
                <div class="pet-detail-photo">
                    <img id="detalle-foto" hidden alt="">
                    <div id="detalle-foto-vacia" class="pet-photo-empty"></div>
                </div>
            </div>
            <div class="pet-detail-body">
                <h2 id="detalle-nombre"></h2>
                <div class="row g-2 pet-detail-facts">
                    <div class="col-6">
                        <div class="pet-detail-chip">
                            <i class="fa-solid fa-venus-mars" aria-hidden="true"></i>
                            <div>
                                <span class="detalle-label">Sexo</span>
                                <strong id="detalle-sexo"></strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="pet-detail-chip">
                            <i class="fa-solid fa-ruler-combined" aria-hidden="true"></i>
                            <div>
                                <span class="detalle-label">Tamaño</span>
                                <strong id="detalle-tamano"></strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="pet-detail-chip">
                            <i class="fa-solid fa-paw" aria-hidden="true"></i>
                            <div>
                                <span class="detalle-label">Especie</span>
                                <strong id="detalle-especie"></strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="pet-detail-chip">
                            <i class="fa-solid fa-dna" aria-hidden="true"></i>
                            <div>
                                <span class="detalle-label">Raza</span>
                                <strong id="detalle-raza"></strong>
                            </div>
                        </div>
                    </div>
                    <div id="detalle-nacimiento-caja" class="col-12" hidden>
                        <div class="pet-detail-chip">
                            <i class="fa-solid fa-calendar-days" aria-hidden="true"></i>
                            <div>
                                <span class="detalle-label">Nacimiento estimado</span>
                                <strong id="detalle-nacimiento"></strong>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="detalle-historial-caja" class="pet-detail-historial" hidden>
                    <span class="detalle-label">Historial</span>
                    <p id="detalle-historial"></p>
                </div>
                <section id="detalle-galeria-adicional" class="pet-detail-extra-gallery" hidden>
                    <h3>Más fotos</h3>
                    <div id="detalle-fotos-adicionales" class="pet-detail-thumbnails" role="group" aria-label="Fotos adicionales"></div>
                    <p id="detalle-fotos-mensaje" class="pet-gallery-message" role="status" aria-live="polite" hidden></p>
                </section>
                <div class="pet-card-actions">
                    <a id="detalle-adoptar" class="btn-adopt" href="../user/solicitar_adopcion.php">Adoptar</a>
                    <a id="detalle-transito" class="btn-transito" href="../user/solicitar_transito.php">Hogar de tránsito</a>
                    <a class="btn-detail" href="mascotas.php">Volver al catálogo</a>
                </div>
            </div>
        </article>
    </section>

<script src="config.php"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/mascotas-detalles.js"></script>
<?php require __DIR__ . '/includes/footer.php'; ?>
