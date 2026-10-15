<?php
$tituloPagina = 'Mi perfil - Patitas Seguras';
$cuerpoClase = 'pagina-perfil';
$paginaPerfil = 'datos';
require __DIR__ . '/../public/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <section id="panel-mi-perfil" class="panel-perfil panel-aportes">
        <p id="mensaje-perfil" class="mensaje-formulario"></p>

        <div class="perfil-bienvenida card border-0 rounded-4 mb-4">
            <div class="card-body p-4 d-flex align-items-center justify-content-between gap-3">
                <div>
                    <h2 class="mb-2">¡Hola, <?php echo htmlspecialchars($usuarioPerfil['nombre'] ?? '', ENT_QUOTES, 'UTF-8'); ?>! <i class="fa-solid fa-paw"></i></h2>
                    <p class="mb-0">Gracias por ser parte de Patitas Seguras.<br>Desde acá podés ver tus datos de contacto y domicilio.</p>
                </div>
                <div class="perfil-bienvenida-ilustracion d-none d-md-flex align-items-end gap-2" aria-hidden="true">
                    <i class="fa-solid fa-dog"></i>
                    <i class="fa-solid fa-cat"></i>
                </div>
            </div>
        </div>

<<<<<<< Updated upstream
            <div class="tab-pane fade<?php echo $paginaPerfil === 'movimientos' ? ' show active' : ''; ?>" id="vista-movimientos" role="tabpanel">
                <div id="seguimiento-movimientos" class="seguimiento-movimientos">
                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-4">
                            <div class="card contador-solicitudes border-0 rounded-4 h-100">
                                <div class="card-body d-flex align-items-center gap-3">
                                    <span class="contador-icono rounded-circle d-inline-flex align-items-center justify-content-center"><i class="bi bi-list-check"></i></span>
                                    <div>
                                        <strong class="d-block fs-4" data-contador-total>0</strong>
                                        <span class="small d-block">Movimientos registrados</span>
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
                                        <strong class="d-block fs-4" data-contador-dinero>0</strong>
                                        <span class="small d-block">Aportes en dinero</span>
                                        <small class="text-body-secondary">registrados</small>
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
                                        <span class="small d-block">Aportes en especie</span>
                                        <small class="text-body-secondary">registrados</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h3 class="perfil-titulo h4 mb-3">Mis movimientos de donaciones</h3>
                    <p class="mensaje-formulario" data-mensaje-movimientos role="status"></p>

                    <article class="card solicitudes-grupo movimientos-donaciones-grupo border-0 rounded-4 shadow-sm" data-por-pagina="5" data-total="0">
                        <div class="card-body p-4">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                                <h4 class="h5 mb-0 d-flex align-items-center gap-2"><i class="bi bi-folder2"></i> Historial de aportes</h4>
                                <span class="badge rounded-pill solicitudes-cantidad"><span data-cantidad>0</span> <span class="cantidad-plural">movimientos</span><span class="cantidad-singular">movimiento</span></span>
                            </div>

                            <div class="solicitudes-vacio text-center rounded-4 p-4">
                                <i class="bi bi-inbox"></i>
                                <p class="fw-semibold mb-1">Todavía no registraste donaciones.</p>
                                <p class="small text-body-secondary mb-3">Cuando cargues un aporte en el sitio, lo vas a ver acá.</p>
                                <a class="btn btn-outline-success btn-sm rounded-pill px-3" href="/public/donaciones.php">Ir a donaciones <i class="bi bi-chevron-right"></i></a>
                            </div>

                            <div class="lista-scroll-mascotas overflow-auto mt-3">
                                <table class="dash-tabla mb-0">
                                    <thead>
                                        <tr id="cabecera-movimientos-perfil"></tr>
                                    </thead>
                                    <tbody id="cuerpo-movimientos-perfil"></tbody>
                                </table>
                            </div>

                            <nav class="paginador mt-3" data-paginador aria-label="Paginación de movimientos" hidden>
                                <ul class="pagination justify-content-center mb-0">
                                    <li class="page-item">
                                        <a class="page-link" data-pagina-anterior href="#vista-movimientos" aria-label="Página anterior"><i class="bi bi-chevron-left"></i></a>
                                    </li>
                                    <li class="page-item" data-pagina-modelo hidden>
                                        <button type="button" class="page-link"></button>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link" data-pagina-siguiente href="#vista-movimientos" aria-label="Página siguiente"><i class="bi bi-chevron-right"></i></a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </article>
                </div>
=======
        <div class="row g-4 align-items-stretch perfil-datos-grid">
            <div class="col-12 col-lg-6">
                <article class="card perfil-card border-0 rounded-4 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="perfil-card-icono rounded-circle d-inline-flex align-items-center justify-content-center"><i class="bi bi-person"></i></span>
                            <h3 class="h5 mb-0">Datos de contacto</h3>
                        </div>
                        <ul class="pet-detail-meta" id="datos-perfil" data-recurso="contactos"></ul>
                        <p class="perfil-vacio small mb-0">Todavía no cargaste datos de contacto.</p>
                    </div>
                </article>
>>>>>>> Stashed changes
            </div>

            <div class="col-12 col-lg-6">
                <article class="card perfil-card border-0 rounded-4 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="perfil-card-icono rounded-circle d-inline-flex align-items-center justify-content-center"><i class="bi bi-geo-alt"></i></span>
                            <h3 class="h5 mb-0">Domicilio</h3>
                        </div>
                        <ul class="pet-detail-meta" id="lista-domicilio" data-recurso="domicilios"></ul>
                        <p class="perfil-vacio small mb-0">Todavía no cargaste tu domicilio.</p>
                    </div>
                </article>
            </div>
        </div>
    </section>

<script src="../public/config.php"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/aportes.js"></script>
<script src="../assets/js/mi-perfil.js"></script>
<<<<<<< Updated upstream
<script src="../assets/js/mis-movimientos-perfil.js"></script>
<script src="../assets/js/seguimiento-solicitudes.js"></script>
=======
>>>>>>> Stashed changes

<?php require __DIR__ . '/includes/footer.php'; ?>
