<?php
$tituloPagina = 'Detalle de solicitud de adopción - Patitas Seguras';
$paginaAdmin = 'adopcion_detalle';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <section id="panel-solicitud-detalle" class="panel-listado" data-listado="solicitudes.php" data-tipo="adopcion">
        <p id="mensaje-formulario" class="mensaje-formulario" role="alert" aria-live="assertive" aria-atomic="true"></p>

<?php require __DIR__ . '/includes/solicitud_detalle_cabecera.php'; ?>

        <div class="row g-3 align-items-stretch">
            <div class="col-12 col-lg-6 min-w-0">
                <article class="info-card h-100" id="ficha-mascota">
                    <div class="dash-card-cabecera">
                        <span class="ficha-icono-especie" data-especie-icono="" aria-hidden="true"><span class="icono-especie"></span></span>
                        <h3>Mascota</h3>
                    </div>
                    <div class="dash-foto mb-3">
                        <img id="detalle-foto" hidden alt="">
                        <div id="detalle-foto-vacia" class="pet-photo-empty"></div>
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
                <article class="info-card h-100" id="ficha-postulante">
                    <div class="dash-card-cabecera">
                        <span class="ficha-usuario-icono"><i class="bi bi-person"></i></span>
                        <h3>Postulante</h3>
                    </div>
                    <div class="postulante-detalle-cuerpo">
                        <section class="postulante-bloque">
                            <h4 class="postulante-bloque-titulo">Datos personales</h4>
                            <ul class="pet-detail-meta">
                                <li><span class="detalle-label">Nombre</span><span data-campo="nombre"></span></li>
                                <li><span class="detalle-label">Apellido</span><span data-campo="apellido"></span></li>
                            </ul>
                        </section>
                        <section class="postulante-bloque" id="seccion-contacto-postulante">
                            <h4 class="postulante-bloque-titulo">Contacto</h4>
                            <ul class="pet-detail-meta" id="lista-contacto-postulante"></ul>
                        </section>
                        <section class="postulante-bloque" id="seccion-domicilio-postulante">
                            <h4 class="postulante-bloque-titulo">Domicilio</h4>
                            <ul class="pet-detail-meta">
                                <li><span class="detalle-label">Tipo de vivienda</span><span data-campo="tipo_vivienda"></span></li>
                                <li><span class="detalle-label">Provincia</span><span data-campo="provincia"></span></li>
                                <li><span class="detalle-label">Comuna o municipio</span><span data-campo="municipio"></span></li>
                                <li><span class="detalle-label">Localidad</span><span data-campo="localidad"></span></li>
                                <li><span class="detalle-label">Barrio o zona</span><span data-campo="barrio_zona"></span></li>
                                <li><span class="detalle-label">Calle</span><span data-campo="calle"></span></li>
                                <li><span class="detalle-label">Altura</span><span data-campo="altura"></span></li>
                                <li><span class="detalle-label">Piso</span><span data-campo="piso"></span></li>
                                <li><span class="detalle-label">Departamento</span><span data-campo="departamento"></span></li>
                                <li><span class="detalle-label">Ruta</span><span data-campo="ruta_nombre"></span></li>
                                <li><span class="detalle-label">Kilómetro</span><span data-campo="kilometro"></span></li>
                                <li><span class="detalle-label">Entre calle 1</span><span data-campo="entre_calle_1"></span></li>
                                <li><span class="detalle-label">Entre calle 2</span><span data-campo="entre_calle_2"></span></li>
                                <li class="detalle-meta-ancho detalle-meta-multilinea"><span class="detalle-label">Indicadores de referencia</span><span data-campo="indicadores_referencia"></span></li>
                                <li><span class="detalle-label">Código postal</span><span data-campo="codigo_postal"></span></li>
                            </ul>
                        </section>
                    </div>
                    <ul id="modelos-postulante" hidden>
                        <li id="modelo-item-contacto-postulante">
                            <span class="detalle-label"></span>
                            <span></span>
                        </li>
                    </ul>
                </article>
            </div>
            <div class="col-12 col-lg-6 min-w-0 d-flex">
                <article class="info-card h-100 w-100" id="ficha-formulario">
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
                                        <div class="col-md-6">
                                            <label class="form-label">Si no puede criarlo</label>
                                            <input class="form-control" type="text" readonly data-campo="destino_mascota">
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
                                            <label class="form-label">Menores</label>
                                            <input class="form-control" type="text" readonly data-campo="hay_ninos">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Horas sola</label>
                                            <input class="form-control" type="text" readonly data-campo="horas_solo">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Otras mascotas</label>
                                            <input class="form-control" type="text" readonly data-campo="tiene_mascotas">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Responsable</label>
                                            <input class="form-control" type="text" readonly data-campo="responsable_principal">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Motivo</label>
                                            <textarea class="form-control" rows="2" readonly data-campo="motivo_adopcion"></textarea>
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
                                                <input class="form-check-input" type="checkbox" disabled data-campo="cuenta_con_apoyo">
                                                <span>Apoyo</span>
                                            </label>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Recursos</label>
                                            <input class="form-control" type="text" readonly data-campo="cuenta_con_recursos">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Limitaciones</label>
                                            <textarea class="form-control" rows="2" readonly data-campo="limitaciones_cuidado"></textarea>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Experiencia</label>
                                            <textarea class="form-control" rows="2" readonly data-campo="experiencia_cuidado"></textarea>
                                        </div>
                                        <div class="col-12">
                                            <p class="form-label">Compromisos</p>
                                            <div data-lista-valor="compromisos"></div>
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
                            <div class="col-md-4">
                                <label class="form-label">Sexo</label>
                                <input class="form-control" type="text" readonly data-campo="sexo">
                            </div>
                            <div class="col-md-4">
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" disabled data-campo="esterilizada">
                                    <span>Esterilizada</span>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" disabled data-campo="vacunas_al_dia">
                                    <span>Vacunas al día</span>
                                </label>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Enfermedad o condición</label>
                                <input class="form-control" type="text" readonly data-campo="enfermedad_condicion">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Descripción</label>
                                <textarea class="form-control" rows="2" readonly data-campo="descripcion"></textarea>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
            <div class="col-12 col-lg-6 min-w-0 d-flex flex-column gap-3">
                <article class="info-card h-100" id="ficha-evaluacion">
                    <div class="dash-card-cabecera">
                        <span class="ficha-usuario-icono"><i class="bi bi-clipboard2-pulse"></i></span>
                        <h3>Evaluación</h3>
                    </div>
                    <ul class="pet-detail-meta">
                        <li><span class="detalle-label">Compatibilidad</span><span data-campo="compatibilidad"></span></li>
                        <li><span class="detalle-label">Puntaje</span><span data-campo="puntaje"></span></li>
                        <li><span class="detalle-label">Clasificación</span><span data-campo="clasificacion"></span></li>
                    </ul>
                    <div class="bloque-razones">
                        <p class="detalle-label mt-3 mb-2">Razones</p>
                        <ul class="list-unstyled mb-0" id="lista-razones"></ul>
                    </div>
                    <p class="small text-muted mb-0 mt-2" id="aviso-evaluacion"></p>
                </article>
                <article class="info-card" id="ficha-resolucion">
                    <div class="dash-card-cabecera">
                        <span class="ficha-usuario-icono"><i class="bi bi-check2-square"></i></span>
                        <h3>Resolución</h3>
                    </div>
                    <p class="mb-3"><span class="detalle-label">Estado actual</span> <span data-campo="estado_solicitud"></span></p>
                    <div id="botones-estado" class="d-flex gap-2 flex-wrap" data-resolver></div>
                    <div id="modelos-estado" hidden>
                        <button type="button" class="btn btn-outline-primary" id="boton-modelo-estado"></button>
                    </div>
                </article>
            </div>
        </div>
    </section>

<script src="config.php"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/solicitud-detalle.js"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
