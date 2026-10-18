<?php $esFormularioTransito = ($solicitudDetalleTipo ?? '') === 'transito'; ?>
            <div class="col-12 col-lg-6 min-w-0 d-flex">
                <article class="info-card h-100 w-100 transito-card-par" id="ficha-formulario">
                    <div class="dash-card-cabecera">
                        <span class="ficha-usuario-icono"><i class="bi bi-ui-checks"></i></span>
                        <h3>Formulario</h3>
                    </div>
                    <p id="aviso-formulario" class="small text-muted mb-2" role="status"></p>
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
<?php if (!$esFormularioTransito) { ?>
                                        <div class="col-md-6">
                                            <label class="form-label">Si no puede criarlo</label>
                                            <input class="form-control" type="text" readonly data-campo="destino_mascota">
                                        </div>
<?php } ?>
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
<?php if ($esFormularioTransito) { ?>
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
<?php } else { ?>
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
<?php } ?>
                                    </div>
                                </section>
                            </div>
<?php if ($esFormularioTransito) { ?>
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
<?php } ?>
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
<?php if ($esFormularioTransito) { ?>
                                        <div class="col-md-6">
                                            <label class="form-check">
                                                <input class="form-check-input" type="checkbox" disabled data-campo="puede_administrar_medicacion">
                                                <span>Medicación</span>
                                            </label>
                                        </div>
<?php } ?>
                                        <div class="col-md-6">
                                            <label class="form-check">
                                                <input class="form-check-input" type="checkbox" disabled data-campo="cuenta_con_apoyo">
                                                <span>Apoyo</span>
                                            </label>
                                        </div>
<?php if (!$esFormularioTransito) { ?>
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
<?php } else { ?>
                                        <div class="col-12">
                                            <label class="form-label">Limitaciones</label>
                                            <textarea class="form-control" rows="2" readonly data-campo="limitaciones_cuidado"></textarea>
                                        </div>
<?php } ?>
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
<?php if (!$esFormularioTransito) { ?>
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
<?php } ?>
                        </div>
                    </div>
                </article>
            </div>
