<?php
$tituloPagina = 'Mis donaciones - Patitas Seguras';
$cuerpoClase = 'pagina-perfil';
$paginaPerfil = 'movimientos';
require __DIR__ . '/../public/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <section id="panel-mi-perfil" class="panel-perfil panel-aportes">
        <p id="mensaje-perfil" class="mensaje-formulario"></p>

        <div id="seguimiento-movimientos" class="seguimiento-movimientos">
            <div class="row g-3 mb-4">
                <div class="col-12 col-md-4">
                    <div class="card contador-solicitudes border-0 rounded-4 h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <span class="contador-icono rounded-circle d-inline-flex align-items-center justify-content-center"><i class="bi bi-list-check"></i></span>
                            <div>
                                <strong class="d-block fs-4" data-contador-total>0</strong>
                                <span class="small d-block">Donaciones registradas</span>
                                <small class="text-body-secondary">en total</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="card contador-solicitudes border-0 rounded-4 h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <span class="contador-icono rounded-circle d-inline-flex align-items-center justify-content-center"><i class="bi bi-cash-stack"></i></span>
                            <div>
                                <strong class="d-block fs-4" data-contador-monetaria>0</strong>
                                <span class="small d-block">Donaciones monetarias</span>
                                <small class="text-body-secondary">registradas</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="card contador-solicitudes border-0 rounded-4 h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <span class="contador-icono rounded-circle d-inline-flex align-items-center justify-content-center"><i class="bi bi-box-seam"></i></span>
                            <div>
                                <strong class="d-block fs-4" data-contador-insumos>0</strong>
                                <span class="small d-block">Donación de insumos</span>
                                <small class="text-body-secondary">registradas</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <h3 class="perfil-titulo h4 mb-3">Mis donaciones</h3>
            <p class="mensaje-formulario" data-mensaje-movimientos role="status"></p>

            <ul class="nav row g-3 mb-3 solicitudes-pestanas movimientos-pestanas" role="tablist">
                <li class="col-12 col-md-6" role="presentation">
                    <button class="nav-link pestana-monetaria active w-100 d-flex align-items-center justify-content-between rounded-4" type="button" role="tab" data-bs-toggle="pill" data-bs-target="#donaciones-monetaria" aria-controls="donaciones-monetaria" aria-selected="true">
                        <span class="d-flex align-items-center gap-2"><i class="bi bi-folder2"></i> Donaciones monetarias</span>
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </li>
                <li class="col-12 col-md-6" role="presentation">
                    <button class="nav-link pestana-insumos w-100 d-flex align-items-center justify-content-between rounded-4" type="button" role="tab" data-bs-toggle="pill" data-bs-target="#donaciones-insumos" aria-controls="donaciones-insumos" aria-selected="false">
                        <span class="d-flex align-items-center gap-2"><i class="bi bi-folder2"></i> Donación de insumos</span>
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="donaciones-monetaria" role="tabpanel" data-clave="monetaria">
                    <article class="card solicitudes-grupo movimientos-donaciones-grupo border-0 rounded-4 shadow-sm" data-por-pagina="5" data-total="0">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
                                <h4 class="h5 mb-0 d-flex align-items-center gap-2"><i class="bi bi-folder2"></i> Donaciones monetarias</h4>
                                <span class="badge rounded-pill solicitudes-cantidad"><span data-cantidad>0</span> <span class="cantidad-plural">donaciones</span><span class="cantidad-singular">donación</span></span>
                            </div>
                            <div class="solicitudes-vacio text-center rounded-4 p-4">
                                <i class="bi bi-inbox"></i>
                                <p class="fw-semibold mb-1">Aún no registraste donaciones monetarias.</p>
                                <p class="small text-body-secondary mb-3">Cuando cargues un aporte en dinero, lo vas a ver acá.</p>
                                <a class="btn btn-outline-success btn-sm rounded-pill px-3" href="/public/donaciones_monetarias.php">Ir a donaciones monetarias <i class="bi bi-chevron-right"></i></a>
                            </div>
                            <div class="lista-scroll-mascotas lista-scroll-donaciones overflow-auto mt-3">
                                <table class="dash-tabla mb-0">
                                    <thead>
                                        <tr data-cabecera-movimientos></tr>
                                    </thead>
                                    <tbody data-cuerpo-movimientos></tbody>
                                </table>
                            </div>
                            <nav class="paginador mt-3" data-paginador aria-label="Paginación de donaciones monetarias" hidden>
                                <ul class="pagination justify-content-center mb-0">
                                    <li class="page-item">
                                        <a class="page-link" data-pagina-anterior href="#donaciones-monetaria" aria-label="Página anterior"><i class="bi bi-chevron-left"></i></a>
                                    </li>
                                    <li class="page-item" data-pagina-modelo hidden>
                                        <button type="button" class="page-link"></button>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link" data-pagina-siguiente href="#donaciones-monetaria" aria-label="Página siguiente"><i class="bi bi-chevron-right"></i></a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </article>
                </div>
                <div class="tab-pane fade" id="donaciones-insumos" role="tabpanel" data-clave="insumos">
                    <article class="card solicitudes-grupo movimientos-donaciones-grupo border-0 rounded-4 shadow-sm" data-por-pagina="5" data-total="0">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
                                <h4 class="h5 mb-0 d-flex align-items-center gap-2"><i class="bi bi-folder2"></i> Donación de insumos</h4>
                                <span class="badge rounded-pill solicitudes-cantidad"><span data-cantidad>0</span> <span class="cantidad-plural">donaciones</span><span class="cantidad-singular">donación</span></span>
                            </div>
                            <div class="solicitudes-vacio text-center rounded-4 p-4">
                                <i class="bi bi-inbox"></i>
                                <p class="fw-semibold mb-1">Aún no registraste donación de insumos.</p>
                                <p class="small text-body-secondary mb-3">Cuando cargues un aporte de insumos, lo vas a ver acá.</p>
                                <a class="btn btn-outline-success btn-sm rounded-pill px-3" href="/public/donaciones_insumos.php">Ir a donación de insumos <i class="bi bi-chevron-right"></i></a>
                            </div>
                            <div class="lista-scroll-mascotas lista-scroll-donaciones overflow-auto mt-3">
                                <table class="dash-tabla mb-0">
                                    <thead>
                                        <tr data-cabecera-movimientos></tr>
                                    </thead>
                                    <tbody data-cuerpo-movimientos></tbody>
                                </table>
                            </div>
                            <nav class="paginador mt-3" data-paginador aria-label="Paginación de donación de insumos" hidden>
                                <ul class="pagination justify-content-center mb-0">
                                    <li class="page-item">
                                        <a class="page-link" data-pagina-anterior href="#donaciones-insumos" aria-label="Página anterior"><i class="bi bi-chevron-left"></i></a>
                                    </li>
                                    <li class="page-item" data-pagina-modelo hidden>
                                        <button type="button" class="page-link"></button>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link" data-pagina-siguiente href="#donaciones-insumos" aria-label="Página siguiente"><i class="bi bi-chevron-right"></i></a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </section>

<script src="../public/config.php"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/aportes.js"></script>
<script src="../assets/js/mi-perfil.js"></script>
<script src="../assets/js/mis-movimientos-perfil.js"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
