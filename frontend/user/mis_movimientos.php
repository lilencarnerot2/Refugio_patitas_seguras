<?php
$tituloPagina = 'Mis donaciones - Patitas Seguras';
$cuerpoClase = 'pagina-perfil';
$paginaPerfil = 'movimientos';
require __DIR__ . '/../public/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <section id="panel-mi-perfil" class="panel-perfil panel-aportes">
        <p id="mensaje-perfil" class="mensaje-formulario"></p>
        <div id="seguimiento-movimientos" class="seguimiento-solicitudes seguimiento-movimientos">
            <div class="row g-3 mb-4">
                <div class="col-12 col-md-6">
                    <div class="card contador-solicitudes border-0 rounded-4 h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <span class="contador-icono rounded-circle d-inline-flex align-items-center justify-content-center"><i class="bi bi-box-seam"></i></span>
                            <div>
                                <strong class="d-block fs-4" data-contador-total>0</strong>
                                <span class="small d-block">Donaciones de insumos</span>
                                <small class="text-body-secondary">registradas</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="card contador-solicitudes border-0 rounded-4 h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <span class="contador-icono rounded-circle d-inline-flex align-items-center justify-content-center"><i class="bi bi-calendar3"></i></span>
                            <div>
                                <strong class="d-block fs-5" data-ultimo-aporte>—</strong>
                                <span class="small d-block">Última donación</span>
                                <small class="text-body-secondary">fecha del aporte más reciente</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <h3 class="perfil-titulo h4 mb-3">Mis donaciones</h3>
            <p class="mensaje-formulario" data-mensaje-movimientos role="status"></p>
            <article class="card solicitudes-grupo border-0 rounded-4 shadow-sm donaciones-insumos-grupo" data-total="0">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
                        <h4 class="h5 mb-0">Donaciones de insumos</h4>
                        <span class="badge rounded-pill solicitudes-cantidad"><span data-cantidad>0</span> <span class="cantidad-plural">donaciones</span><span class="cantidad-singular">donación</span></span>
                    </div>
                    <ul class="list-unstyled d-grid gap-3 mb-0" data-lista></ul>
                    <div class="solicitudes-vacio text-center rounded-4 p-4">
                        <i class="bi bi-box-seam"></i>
                        <p class="fw-semibold mb-1">Aún no registraste donaciones de insumos.</p>
                        <p class="small mb-3">Cuando registres un aporte, vas a poder consultar los detalles desde acá.</p>
                        <a class="btn btn-sm btn-outline-success rounded-pill px-3" href="/public/donaciones_insumos.php">Ver qué donar</a>
                    </div>
                </div>
            </article>
            <template data-modelo-donacion>
                <li class="card solicitud-item donacion-insumo-item rounded-4">
                    <div class="card-body d-flex flex-column flex-sm-row gap-3">
                        <div class="donacion-insumo-icono rounded-4 flex-shrink-0 d-flex align-items-center justify-content-center">
                            <i class="bi bi-box-seam" aria-hidden="true"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-1" data-campo="tipo_aporte"></h5>
                            <p class="small mb-2"><i class="bi bi-calendar3"></i> Registrada el <span data-campo="fecha"></span></p>
                            <p class="small mt-2 mb-0" data-campo="descripcion"></p>
                        </div>
                    </div>
                </li>
            </template>
        </div>
    </section>

<script src="../public/config.php"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/aportes.js"></script>
<script src="../assets/js/mi-perfil.js"></script>
<script src="../assets/js/mis-movimientos-perfil.js"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
