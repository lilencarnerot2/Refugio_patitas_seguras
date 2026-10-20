<?php
$tituloPagina = 'Configuración - Patitas Seguras';
$paginaAdmin = 'configuracion';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <section id="panel-configuracion" class="panel-listado">
        <p id="mensaje-formulario" class="mensaje-formulario"></p>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="h3 text-success mb-1"><i class="bi bi-gear-fill me-2"></i>Configuración del Refugio</h2>
                <p class="text-muted mb-0">Administrá la información de contacto, los datos de donación y los catálogos del sistema.</p>
            </div>
        </div>

        <div class="row g-4">
            <!-- Tarjeta 1: Datos de Contacto -->
            <div class="col-lg-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h3 class="h5 mb-3 text-success"><i class="bi bi-person-lines-fill me-2"></i>Datos de Contacto</h3>
                        <p class="text-muted small mb-4">Esta información se mostrará en el sitio público y será utilizada para que la comunidad pueda comunicarse con el refugio.</p>
                        
                        <form id="form-contacto">
                            <div class="mb-3">
                                <label class="form-label" for="input-direccion">Dirección</label>
                                <input type="text" class="form-control" id="input-direccion" placeholder="Av. Libertador 1234, San Martín, Buenos Aires">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="input-telefono">Teléfono / WhatsApp</label>
                                <input type="text" class="form-control" id="input-telefono" placeholder="+54 9 11 2345 6789">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="input-email">Email público</label>
                                <input type="email" class="form-control" id="input-email" placeholder="info@patitaseguras.org">
                            </div>
                            <button type="button" class="btn btn-success w-100 mt-2" id="btn-guardar-contacto">
                                <i class="bi bi-save me-2"></i>Guardar Cambios
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tarjeta 2: Datos de Donación -->
            <div class="col-lg-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h3 class="h5 mb-3 text-success"><i class="bi bi-heart-fill me-2"></i>Datos de Donación</h3>
                        <p class="text-muted small mb-4">Estos datos se usarán para que las personas puedan realizar donaciones al refugio.</p>
                        
                        <form id="form-donacion">
                            <div class="mb-3">
                                <label class="form-label" for="input-alias">Alias de Mercado Pago</label>
                                <input type="text" class="form-control" id="input-alias" placeholder="patitas.seguras.mp">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="input-cbu">CBU</label>
                                <input type="text" class="form-control" id="input-cbu" placeholder="0000003100001234567890">
                            </div>
                            <div class="alert alert-success d-flex align-items-center mt-4 small" role="alert">
                                <i class="bi bi-info-circle-fill me-2"></i>
                                <div>Recordá mantener estos datos actualizados para que las donaciones lleguen correctamente.</div>
                            </div>
                            <button type="button" class="btn btn-success w-100" id="btn-guardar-donacion">
                                <i class="bi bi-save me-2"></i>Guardar Cambios
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjeta 3: Catálogos -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="h5 mb-3 text-success"><i class="bi bi-book-fill me-2"></i>Catálogos</h3>
                        <p class="text-muted small mb-3">Administrá las categorías que se utilizan en el sistema.</p>
                        
                        <div class="list-group">
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1 fw-bold">Tipos de Vivienda</h6>
                                    <small class="text-muted">Definí los tipos de vivienda disponibles para las adopciones.</small>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-success btn-editar-catalogo" data-catalogo="tipos_vivienda">Editar</button>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1 fw-bold">Especies</h6>
                                    <small class="text-muted">Gestioná las especies de animales del refugio.</small>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-success btn-editar-catalogo" data-catalogo="especies">Editar</button>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1 fw-bold">Motivos de Adopción</h6>
                                    <small class="text-muted">Configurá los motivos por los cuales se pueden adoptar.</small>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-success btn-editar-catalogo" data-catalogo="motivos_adopcion">Editar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<script src="config.php"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/configuracion.js"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>