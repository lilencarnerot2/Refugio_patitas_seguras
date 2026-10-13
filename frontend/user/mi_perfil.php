<?php
$tituloPagina = $tituloPagina ?? 'Mi perfil - Patitas Seguras';
$cuerpoClase = 'pagina-perfil';
$paginaPerfil = $paginaPerfil ?? 'datos';
require __DIR__ . '/../public/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <section id="panel-mi-perfil" class="panel-perfil panel-aportes">
        <p id="mensaje-perfil" class="mensaje-formulario"></p>
        <p id="mensaje-aportes" class="mensaje-formulario" hidden></p>

        <div class="tab-content">
            <div class="tab-pane fade<?php echo $paginaPerfil === 'datos' ? ' show active' : ''; ?>" id="vista-datos" role="tabpanel">
                <div class="row g-3 align-items-start justify-content-center perfil-datos-grid">
                    <div class="col-12 col-lg-6">
                        <article class="info-card ficha-compacta h-auto">
                            <div class="dash-card-cabecera">
                                <span class="ficha-usuario-icono"><i class="bi bi-person-fill"></i></span>
                                <h3>Datos</h3>
                            </div>
                            <ul class="pet-detail-meta" id="datos-perfil"></ul>
                        </article>
                    </div>

                    <div class="col-12 col-lg-6">
                        <article class="info-card ficha-compacta h-auto">
                            <div class="dash-card-cabecera">
                                <span class="ficha-usuario-icono"><i class="bi bi-geo-alt"></i></span>
                                <h3>Domicilio</h3>
                            </div>
                            <ul class="pet-detail-meta" id="lista-domicilio"></ul>
                        </article>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade<?php echo $paginaPerfil === 'movimientos' ? ' show active' : ''; ?>" id="vista-movimientos" role="tabpanel">
                <article class="info-card ficha-compacta ficha-ancha h-auto">
                    <div class="dash-card-cabecera">
                        <span class="ficha-usuario-icono"><i class="bi bi-list-ul"></i></span>
                        <h3>Mis movimientos</h3>
                    </div>
                    <div class="lista-scroll-mascotas overflow-auto">
                        <table class="dash-tabla">
                            <thead>
                                <tr id="cabecera-movimientos"></tr>
                            </thead>
                            <tbody id="cuerpo-movimientos"></tbody>
                        </table>
                    </div>
                </article>
            </div>

            <div class="tab-pane fade<?php echo $paginaPerfil === 'solicitudes' ? ' show active' : ''; ?>" id="vista-solicitudes" role="tabpanel">
                <article class="info-card ficha-compacta ficha-ancha h-auto">
                    <div class="dash-card-cabecera">
                        <span class="ficha-usuario-icono"><i class="bi bi-file-earmark-text"></i></span>
                        <h3>Mis solicitudes</h3>
                    </div>
                    <div class="lista-scroll-mascotas overflow-auto">
                        <table class="dash-tabla">
                            <thead>
                                <tr id="cabecera-solicitudes"></tr>
                            </thead>
                            <tbody id="cuerpo-solicitudes"></tbody>
                        </table>
                    </div>
                </article>
            </div>
        </div>
    </section>

<script src="../public/config.php"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/aportes.js"></script>
<script src="../assets/js/mi-perfil.js"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
