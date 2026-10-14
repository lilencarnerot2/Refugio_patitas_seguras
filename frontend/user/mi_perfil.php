<?php
$tituloPagina = $tituloPagina ?? 'Mi perfil - Patitas Seguras';
$cuerpoClase = 'pagina-perfil';
$paginaPerfil = $paginaPerfil ?? 'datos';
require __DIR__ . '/../public/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <section id="panel-mi-perfil" class="panel-perfil panel-aportes">
        <p id="mensaje-perfil" class="mensaje-formulario"></p>

        <div class="tab-content">
            <div class="tab-pane fade<?php echo $paginaPerfil === 'datos' ? ' show active' : ''; ?>" id="vista-datos" role="tabpanel">
                <div class="row g-3 align-items-start justify-content-center perfil-datos-grid">
                    <div class="col-12 col-lg-6">
                        <article class="info-card ficha-compacta h-auto">
                            <div class="dash-card-cabecera">
                                <span class="ficha-usuario-icono"><i class="bi bi-person-fill"></i></span>
                                <h3>Datos</h3>
                            </div>
                            <ul class="pet-detail-meta" id="datos-perfil" data-recurso="contactos"></ul>
                            <p class="perfil-vacio">Todavía no cargaste datos de contacto.</p>
                        </article>
                    </div>

                    <div class="col-12 col-lg-6">
                        <article class="info-card ficha-compacta h-auto">
                            <div class="dash-card-cabecera">
                                <span class="ficha-usuario-icono"><i class="bi bi-geo-alt"></i></span>
                                <h3>Domicilio</h3>
                            </div>
                            <ul class="pet-detail-meta" id="lista-domicilio" data-recurso="domicilios"></ul>
                        </article>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade<?php echo $paginaPerfil === 'movimientos' ? ' show active' : ''; ?>" id="vista-movimientos" role="tabpanel">
                <article class="info-card ficha-compacta ficha-ancha ficha-listado h-auto" data-por-pagina="5">
                    <div class="dash-card-cabecera">
                        <span class="ficha-usuario-icono"><i class="bi bi-list-ul"></i></span>
                        <h3>Mis movimientos</h3>
                    </div>
                    <p class="mensaje-formulario" data-aviso></p>
                    <div class="lista-scroll-mascotas overflow-auto">
                        <table class="dash-tabla">
                            <thead>
                                <tr id="cabecera-movimientos"></tr>
                            </thead>
                            <tbody id="cuerpo-movimientos" data-recurso="movimientos"></tbody>
                        </table>
                    </div>
                    <nav class="paginador" data-paginador aria-label="Paginación" hidden>
                        <ul class="pagination">
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
                </article>
            </div>

            <div class="tab-pane fade<?php echo $paginaPerfil === 'solicitudes' ? ' show active' : ''; ?>" id="vista-solicitudes" role="tabpanel">
                <article class="info-card ficha-compacta ficha-ancha ficha-listado h-auto" data-por-pagina="5">
                    <div class="dash-card-cabecera">
                        <span class="ficha-usuario-icono"><i class="bi bi-file-earmark-text"></i></span>
                        <h3>Mis solicitudes</h3>
                    </div>
                    <p class="mensaje-formulario" data-aviso></p>
                    <div class="lista-scroll-mascotas overflow-auto">
                        <table class="dash-tabla">
                            <thead>
                                <tr id="cabecera-solicitudes"></tr>
                            </thead>
                            <tbody id="cuerpo-solicitudes" data-recurso="solicitudes"></tbody>
                        </table>
                    </div>
                    <nav class="paginador" data-paginador aria-label="Paginación" hidden>
                        <ul class="pagination">
                            <li class="page-item">
                                <a class="page-link" data-pagina-anterior href="#vista-solicitudes" aria-label="Página anterior"><i class="bi bi-chevron-left"></i></a>
                            </li>
                            <li class="page-item" data-pagina-modelo hidden>
                                <button type="button" class="page-link"></button>
                            </li>
                            <li class="page-item">
                                <a class="page-link" data-pagina-siguiente href="#vista-solicitudes" aria-label="Página siguiente"><i class="bi bi-chevron-right"></i></a>
                            </li>
                        </ul>
                    </nav>
                </article>
            </div>
        </div>
    </section>

<script src="../public/config.php"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/aportes.js"></script>
<script src="../assets/js/mi-perfil.js"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
