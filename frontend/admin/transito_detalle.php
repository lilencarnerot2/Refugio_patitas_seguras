<?php
$tituloPagina = 'Detalle de hogar de tránsito - Patitas Seguras';
$paginaAdmin = 'transito_detalle';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <section id="panel-solicitud-detalle" class="panel-listado" data-listado="solicitudes.php" data-tipo="transito">
        <p id="mensaje-formulario" class="mensaje-formulario"></p>

        <div class="dash-card-cabecera mb-3">
            <div class="min-w-0">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span class="ficha-usuario-icono"><i class="bi bi-heart"></i></span>
                    <h3>Detalle de solicitud de tránsito</h3>
                </div>
                <div id="ficha-solicitud-meta" class="solicitud-meta-datos">
                    <span class="solicitud-meta-item">
                        <span class="detalle-label">Nro. solicitud</span>
                        <span data-campo="id"></span>
                    </span>
                    <span class="solicitud-meta-item">
                        <span class="detalle-label">Fecha</span>
                        <span data-campo="fecha_solicitud"></span>
                    </span>
                </div>
            </div>
            <a class="btn-outline" href="solicitudes.php">Volver</a>
        </div>

        <div class="row g-3 align-items-start mb-3">
            <div class="col-12 col-lg-6 min-w-0">
                <article class="info-card h-100" id="ficha-mascota">
                    <div class="dash-card-cabecera">
                        <span class="ficha-usuario-icono"><i class="bi bi-paw"></i></span>
                        <h3>Mascota</h3>
                    </div>
                    <div class="mascota-detalle-foto mb-3">
                        <img id="detalle-foto" class="mascota-detalle-foto-img" alt="" hidden>
                        <div id="detalle-foto-vacia" class="pet-photo-empty mascota-detalle-foto-vacia" aria-hidden="true"></div>
                    </div>
                    <ul class="pet-detail-meta">
                        <li><span class="detalle-label">Nombre</span><span data-campo="nombre"></span></li>
                        <li><span class="detalle-label">Especie</span><span data-campo="especie"></span></li>
                        <li><span class="detalle-label">Raza</span><span data-campo="raza"></span></li>
                        <li><span class="detalle-label">Tamaño</span><span data-campo="tamaño"></span></li>
                        <li><span class="detalle-label">Sexo</span><span data-campo="sexo"></span></li>
                        <li><span class="detalle-label">Nacimiento estimado</span><span data-campo="fecha_nacimiento_estimada"></span></li>
                        <li class="detalle-meta-ancho detalle-meta-multilinea"><span class="detalle-label">Historial</span><span data-campo="historial"></span></li>
                    </ul>
                </article>
            </div>
            <div class="col-12 col-lg-6 min-w-0">
                <article class="info-card postulante-panel" id="ficha-postulante">
                    <div class="dash-card-cabecera">
                        <span class="ficha-usuario-icono"><i class="bi bi-person"></i></span>
                        <h3>Postulante</h3>
                    </div>
                    <div class="postulante-layout d-flex flex-column gap-2">
                        <nav class="nav nav-pills postulante-nav-pills flex-nowrap gap-1" id="nav-postulante" aria-label="Secciones del postulante">
                            <button type="button" class="nav-link active" data-postulante-seccion="personal">Datos personales</button>
                            <button type="button" class="nav-link" data-postulante-seccion="contacto">Contacto</button>
                            <button type="button" class="nav-link" data-postulante-seccion="domicilio">Domicilio</button>
                        </nav>
                        <div class="postulante-panel-contenido postulante-detalle-cuerpo">
                            <section class="postulante-seccion-panel" data-panel-postulante="personal">
                                <div class="row g-2 postulante-datos-grid">
                                    <div class="col-sm-6">
                                        <div class="postulante-dato-item">
                                            <span class="detalle-label">Nombre</span>
                                            <span data-campo="nombre"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="postulante-dato-item">
                                            <span class="detalle-label">Apellido</span>
                                            <span data-campo="apellido"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="postulante-dato-item">
                                            <span class="detalle-label">Fecha de nacimiento</span>
                                            <span data-campo="fecha_nacimiento"></span>
                                        </div>
                                    </div>
                                </div>
                            </section>
                            <section class="postulante-seccion-panel d-none" data-panel-postulante="contacto" id="seccion-contacto-postulante">
                                <ul class="pet-detail-meta postulante-meta-dos-columnas mb-0" id="lista-contacto-postulante"></ul>
                            </section>
                            <section class="postulante-seccion-panel d-none" data-panel-postulante="domicilio" id="seccion-domicilio-postulante">
                                <ul class="pet-detail-meta postulante-meta-dos-columnas mb-0">
                                    <li><span class="detalle-label">Tipo de vivienda</span><span data-campo="tipo_vivienda"></span></li>
                                    <li><span class="detalle-label">Provincia</span><span data-campo="provincia"></span></li>
                                    <li><span class="detalle-label">Comuna o municipio</span><span data-campo="municipio"></span></li>
                                    <li><span class="detalle-label">Localidad</span><span data-campo="localidad"></span></li>
                                    <li><span class="detalle-label">Barrio o zona</span><span data-campo="barrio_zona"></span></li>
                                    <li><span class="detalle-label">Calle</span><span data-campo="calle"></span></li>
                                    <li><span class="detalle-label">Altura</span><span data-campo="altura"></span></li>
                                    <li><span class="detalle-label">Piso</span><span data-campo="piso"></span></li>
                                    <li><span class="detalle-label">Departamento</span><span data-campo="departamento"></span></li>
                                    <li><span class="detalle-label">Código postal</span><span data-campo="codigo_postal"></span></li>
                                </ul>
                            </section>
                        </div>
                    </div>
                    <ul id="modelos-postulante" hidden>
                        <li id="modelo-item-contacto-postulante">
                            <span class="detalle-label"></span>
                            <span></span>
                        </li>
                    </ul>
                </article>
            </div>
        </div>

        <div class="row g-3 align-items-stretch mb-3 transito-fila-formulario">
            <div class="col-12 col-lg-6 min-w-0 d-flex">
                <article class="info-card h-100 w-100 transito-card-par" id="ficha-formulario">
                    <div class="dash-card-cabecera">
                        <span class="ficha-usuario-icono"><i class="bi bi-ui-checks"></i></span>
                        <h3>Formulario</h3>
                    </div>
                    <div id="cuerpo-formulario" class="formulario-detalle-scroll overflow-auto">
                        <div id="campos-formulario" class="row g-3">
                            <div class="col-12">
                                <section class="form-seccion">
                                    <h3 class="form-seccion-titulo">Vivienda</h3>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Tipo de vivienda</label>
                                            <input class="form-control" type="text" readonly data-campo="tipo_vivienda">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Condición</label>
                                            <input class="form-control" type="text" readonly data-campo="condicion_vivienda">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Patio</label>
                                            <input class="form-control" type="text" readonly data-campo="tiene_patio">
                                        </div>
                                        <div class="col-md-6" id="grupo-metros-patio" hidden>
                                            <label class="form-label">Metros del patio</label>
                                            <input class="form-control" type="text" readonly data-campo="metros_cuadrados_patio">
                                        </div>
                                    </div>
                                </section>
                            </div>
                            <div class="col-12">
                                <section class="form-seccion">
                                    <h3 class="form-seccion-titulo">Hogar</h3>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Integrantes</label>
                                            <input class="form-control" type="text" readonly data-campo="cantidad_integrantes">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Horas sola</label>
                                            <input class="form-control" type="text" readonly data-campo="horas_solo">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Menores</label>
                                            <input class="form-control" type="text" readonly data-campo="hay_ninos">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Otras mascotas</label>
                                            <input class="form-control" type="text" readonly data-campo="tiene_mascotas">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Cantidad máxima de mascotas</label>
                                            <input class="form-control" type="text" readonly data-campo="cantidad_maxima_mascotas">
                                        </div>
                                    </div>
                                </section>
                            </div>
                            <div class="col-12">
                                <section class="form-seccion">
                                    <h3 class="form-seccion-titulo">Disponibilidad</h3>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Desde</label>
                                            <input class="form-control" type="text" readonly data-campo="disponibilidad_desde">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Hasta</label>
                                            <input class="form-control" type="text" readonly data-campo="disponibilidad_hasta">
                                        </div>
                                    </div>
                                </section>
                            </div>
                            <div class="col-12">
                                <section class="form-seccion">
                                    <h3 class="form-seccion-titulo">Cuidado</h3>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-check">
                                                <input class="form-check-input" type="checkbox" disabled data-campo="puede_realizar_paseos">
                                                <span>Paseos</span>
                                            </label>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-check">
                                                <input class="form-check-input" type="checkbox" disabled data-campo="puede_realizar_traslados">
                                                <span>Traslados</span>
                                            </label>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-check">
                                                <input class="form-check-input" type="checkbox" disabled data-campo="puede_administrar_medicacion">
                                                <span>Medicación</span>
                                            </label>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-check">
                                                <input class="form-check-input" type="checkbox" disabled data-campo="cuenta_con_apoyo">
                                                <span>Apoyo</span>
                                            </label>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Limitaciones</label>
                                            <textarea class="form-control" rows="2" readonly data-campo="limitaciones_cuidado"></textarea>
                                        </div>
                                        <div class="col-12">
                                            <p class="form-label">Condiciones especiales</p>
                                            <div data-lista-valor="condiciones_especiales"></div>
                                        </div>
                                        <div class="col-12" id="grupo-mascotas-hogar" hidden>
                                            <p class="form-label">Mascotas del hogar</p>
                                            <div id="lista-mascotas-hogar"></div>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Observaciones</label>
                                            <textarea class="form-control" rows="2" readonly data-campo="observaciones"></textarea>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                    <div id="modelos-formulario" hidden>
                        <label class="form-check" id="modelo-opcion-lista">
                            <input class="form-check-input" type="checkbox" disabled checked>
                            <span data-texto></span>
                        </label>
                        <div class="row g-2 mb-2" id="modelo-mascota-hogar">
                            <div class="col-md-4">
                                <label class="form-label">Tipo</label>
                                <input class="form-control" type="text" readonly data-campo="tipo_mascota">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Nombre</label>
                                <input class="form-control" type="text" readonly data-campo="nombre">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Edad</label>
                                <input class="form-control" type="text" readonly data-campo="edad_aproximada">
                            </div>
                        </div>
                    </div>
                </article>
            </div>
            <div class="col-12 col-lg-6 min-w-0 d-flex">
                <article class="info-card h-100 w-100 transito-card-par" id="ficha-evaluacion">
                    <div class="dash-card-cabecera">
                        <span class="ficha-usuario-icono"><i class="bi bi-clipboard2-pulse"></i></span>
                        <h3>Evaluación</h3>
                    </div>
                    <div class="evaluacion-detalle-scroll flex-grow-1 min-h-0">
                        <div id="evaluacion-espera">
                            <ul class="pet-detail-meta evaluacion-placeholder">
                                <li><span class="detalle-label">Puntaje</span></li>
                                <li><span class="detalle-label">Clasificación</span></li>
                            </ul>
                            <p class="detalle-label mt-3 mb-2">Razones</p>
                            <div class="evaluacion-razones-vacia" aria-hidden="true"></div>
                        </div>
                        <div id="evaluacion-resultado" hidden>
                            <ul class="pet-detail-meta">
                                <li><span class="detalle-label">Puntaje</span><span data-campo="puntaje"></span></li>
                                <li><span class="detalle-label">Clasificación</span><span data-campo="clasificacion"></span></li>
                            </ul>
                            <div class="bloque-razones">
                                <p class="detalle-label mt-3 mb-2">Razones</p>
                                <ul class="list-unstyled mb-0" id="lista-razones"></ul>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        </div>

        <article class="info-card mb-3" id="ficha-resolucion" data-compatibilidad-lista="0" data-compatibilidad-cargando="1">
            <div class="dash-card-cabecera">
                <span class="ficha-usuario-icono"><i class="bi bi-check2-square"></i></span>
                <h3>Resolución</h3>
            </div>
            <div class="row g-3 align-items-end">
                <div class="col-12 col-md-auto">
                    <p class="mb-0"><span class="detalle-label">Estado actual</span> <span data-campo="estado_solicitud"></span></p>
                </div>
                <div class="col-12">
                    <p id="resolucion-espera-compatibilidad" class="mb-2 text-body-secondary small">Esperando compatibilidad para resolver.</p>
                    <div id="botones-estado" class="d-flex gap-2 flex-wrap" data-resolver data-compatibilidad-lista="0" hidden></div>
                </div>
            </div>
            <div id="modelos-estado" hidden>
                <button type="button" class="btn btn-secondary btn-estado-solicitud" id="boton-modelo-estado" data-confirmar-estado="1" disabled></button>
            </div>
        </article>
    </section>

<div class="modal fade" id="modal-confirmar-estado" tabindex="-1" aria-labelledby="modal-confirmar-estado-titulo" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 rounded-4 text-center">
            <div class="modal-body p-4">
                <span class="modal-confirmar-estado-icono rounded-circle d-inline-flex align-items-center justify-content-center mb-3">
                    <i class="bi bi-question-lg"></i>
                </span>
                <h5 class="modal-title mb-2" id="modal-confirmar-estado-titulo">¿Estás seguro de tu selección?</h5>
                <p class="text-body-secondary small mb-1">Vas a cambiar el estado de la solicitud a</p>
                <p class="fw-semibold mb-4" id="modal-confirmar-estado-destino"></p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary rounded-pill px-4" id="modal-confirmar-estado-aceptar">Confirmar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="config.php"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/transito-detalle.js?v=20261016c"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
