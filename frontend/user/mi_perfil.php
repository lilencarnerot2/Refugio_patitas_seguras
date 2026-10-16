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

<?php require __DIR__ . '/includes/footer.php'; ?>
