<?php
$tituloPagina = 'Detalle de solicitud - Patitas Seguras';
$paginaAdmin = 'solicitudes_detalle';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <section id="panel-solicitud-detalle" class="panel-listado" data-listado="solicitudes.php">
        <p id="mensaje-formulario" class="mensaje-formulario"></p>

        <div class="dash-fila dash-fila-dos">
            <article class="info-card" id="ficha-solicitud">
                <div class="dash-card-cabecera">
                    <span class="ficha-usuario-icono"><i class="bi bi-file-earmark-text"></i></span>
                    <h3>Solicitudes</h3>
                </div>
                <ul class="pet-detail-meta">
                    <li><span class="detalle-label">Nombre</span><span data-campo="nombre_usuario"></span></li>
                    <li><span class="detalle-label">Apellido</span><span data-campo="apellido_usuario"></span></li>
                    <li><span class="detalle-label">Mascota</span><span data-campo="nombre_mascota"></span></li>
                    <li><span class="detalle-label">Tipo</span><span data-campo="tipo_solicitud"></span></li>
                    <li><span class="detalle-label">Estado</span><span data-campo="estado_solicitud"></span></li>
                    <li><span class="detalle-label">Fecha</span><span data-campo="fecha_solicitud"></span></li>
                </ul>
            </article>

            <article class="info-card" id="ficha-formulario">
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
                                        <label class="form-label">Condición de vivienda</label>
                                        <input class="form-control" type="text" readonly data-campo="condicion_vivienda">
                                    </div>
                                    <div class="col-12">
                                        <div class="row g-3 align-items-end">
                                            <div class="col-md-6">
                                                <label class="form-check">
                                                    <input class="form-check-input" type="checkbox" disabled data-campo="tiene_patio">
                                                    <span>Tiene patio</span>
                                                </label>
                                            </div>
                                            <div class="col-md-6" id="grupo-metros-patio" hidden>
                                                <label class="form-label">Metros cuadrados del patio</label>
                                                <input class="form-control" type="text" readonly data-campo="metros_cuadrados_patio">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">En caso de no poder criarlo</label>
                                        <input class="form-control" type="text" readonly data-campo="destino_mascota">
                                    </div>
                                </div>
                            </section>
                        </div>
                        <div class="col-12">
                            <section class="form-seccion">
                                <h3 class="form-seccion-titulo">Hogar</h3>
                                <div class="row g-3">
                                    <div class="col-md-6 d-flex flex-column gap-2">
                                        <div>
                                            <label class="form-label">Cantidad de integrantes</label>
                                            <input class="form-control" type="text" readonly data-campo="cantidad_integrantes">
                                        </div>
                                        <label class="form-check">
                                            <input class="form-check-input" type="checkbox" disabled data-campo="hay_ninos">
                                            <span>Hay niños</span>
                                        </label>
                                    </div>
                                    <div class="col-md-6 d-flex flex-column gap-2">
                                        <div>
                                            <label class="form-label">Horas solo</label>
                                            <input class="form-control" type="text" readonly data-campo="horas_solo">
                                        </div>
                                        <label class="form-check">
                                            <input class="form-check-input" type="checkbox" disabled data-campo="tiene_mascotas">
                                            <span>Tiene mascotas</span>
                                        </label>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Responsable principal</label>
                                        <input class="form-control" type="text" readonly data-campo="responsable_principal">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Motivo de adopción</label>
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
                                            <span>Puede realizar paseos</span>
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-check">
                                            <input class="form-check-input" type="checkbox" disabled data-campo="puede_realizar_traslados">
                                            <span>Puede realizar traslados</span>
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-check">
                                            <input class="form-check-input" type="checkbox" disabled data-campo="cuenta_con_apoyo">
                                            <span>Cuenta con apoyo</span>
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-check">
                                            <input class="form-check-input" type="checkbox" disabled data-campo="cuenta_con_recursos">
                                            <span>Cuenta con recursos</span>
                                        </label>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Limitaciones de cuidado</label>
                                        <textarea class="form-control" rows="2" readonly data-campo="limitaciones_cuidado"></textarea>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Experiencia de cuidado</label>
                                        <textarea class="form-control" rows="2" readonly data-campo="experiencia_cuidado"></textarea>
                                    </div>
                                    <div class="col-12">
                                        <p class="form-label">Compromisos de adopción</p>
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
                            <label class="form-label">Tipo de mascota</label>
                            <input class="form-control" type="text" readonly data-campo="tipo_mascota">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nombre</label>
                            <input class="form-control" type="text" readonly data-campo="nombre">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Edad aproximada</label>
                            <input class="form-control" type="text" readonly data-campo="edad_aproximada">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Sexo</label>
                            <input class="form-control" type="text" readonly data-campo="sexo">
                        </div>
                        <div class="col-md-8">
                            <div class="row g-2 align-items-end h-100">
                                <div class="col-6 d-flex align-items-end">
                                    <label class="form-check w-100">
                                        <input class="form-check-input" type="checkbox" disabled data-campo="esterilizada">
                                        <span>Esterilizada</span>
                                    </label>
                                </div>
                                <div class="col-6 d-flex align-items-end">
                                    <label class="form-check w-100">
                                        <input class="form-check-input" type="checkbox" disabled data-campo="vacunas_al_dia">
                                        <span>Vacunas al día</span>
                                    </label>
                                </div>
                            </div>
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

        <article class="info-card" id="bloque-compatibilidad">
            <div class="dash-card-cabecera">
                <span class="ficha-usuario-icono"><i class="bi bi-heart-pulse"></i></span>
                <h3>Compatibilidad</h3>
            </div>
            <div id="botones-estado" class="d-flex gap-2 flex-wrap"></div>
            <!-- Modelo oculto con el diseño; el JS lo clona y solo pone datos del backend -->
            <div id="modelos-estado" hidden>
                <button type="button" class="btn btn-outline-primary" id="boton-modelo-estado"></button>
            </div>
        </article>
    </section>

<script src="config.php"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/solicitud-detalle.js"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
