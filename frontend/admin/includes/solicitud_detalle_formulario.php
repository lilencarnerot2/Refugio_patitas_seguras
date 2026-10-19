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
                                            <span class="form-control formulario-dato" data-campo="tipo_vivienda"></span>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Condición</label>
                                            <span class="form-control formulario-dato" data-campo="condicion_vivienda"></span>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Patio</label>
                                            <span class="form-control formulario-dato" data-campo="tiene_patio"></span>
                                        </div>
                                        <div class="col-md-6" id="grupo-metros-patio" hidden>
                                            <label class="form-label">Metros del patio</label>
                                            <span class="form-control formulario-dato" data-campo="metros_cuadrados_patio"></span>
                                        </div>
<?php if (!$esFormularioTransito) { ?>
                                        <div class="col-md-6">
                                            <label class="form-label">Si no puede criarlo</label>
                                            <span class="form-control formulario-dato" data-campo="destino_mascota"></span>
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
                                            <span class="form-control formulario-dato" data-campo="cantidad_integrantes"></span>
                                        </div>
<?php if ($esFormularioTransito) { ?>
                                        <div class="col-md-6">
                                            <label class="form-label">Horas sola</label>
                                            <span class="form-control formulario-dato" data-campo="horas_solo"></span>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Menores</label>
                                            <span class="form-control formulario-dato" data-campo="hay_ninos"></span>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Otras mascotas</label>
                                            <span class="form-control formulario-dato" data-campo="tiene_mascotas"></span>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Cantidad máxima de mascotas</label>
                                            <span class="form-control formulario-dato" data-campo="cantidad_maxima_mascotas"></span>
                                        </div>
<?php } else { ?>
                                        <div class="col-md-6">
                                            <label class="form-label">Menores</label>
                                            <span class="form-control formulario-dato" data-campo="hay_ninos"></span>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Horas sola</label>
                                            <span class="form-control formulario-dato" data-campo="horas_solo"></span>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Otras mascotas</label>
                                            <span class="form-control formulario-dato" data-campo="tiene_mascotas"></span>
                                        </div>
<<<<<<< Updated upstream
                                        <div class="col-12">
                                            <label class="form-label">Responsable</label>
                                            <span class="form-control formulario-dato" data-campo="responsable_principal"></span>
                                        </div>
=======

>>>>>>> Stashed changes
                                        <div class="col-12">
                                            <label class="form-label">Motivo</label>
                                            <span class="form-control formulario-dato formulario-dato-multilinea" data-campo="motivo_adopcion"></span>
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
                                            <span class="form-control formulario-dato" data-campo="disponibilidad_desde"></span>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Hasta</label>
                                            <span class="form-control formulario-dato" data-campo="disponibilidad_hasta"></span>
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
                                            <span class="form-control formulario-dato" data-campo="cuenta_con_recursos"></span>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Limitaciones</label>
                                            <span class="form-control formulario-dato formulario-dato-multilinea" data-campo="limitaciones_cuidado"></span>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Experiencia</label>
                                            <span class="form-control formulario-dato formulario-dato-multilinea" data-campo="experiencia_cuidado"></span>
                                        </div>
                                        <div class="col-12">
                                            <p class="form-label">Compromisos</p>
                                            <div data-lista-valor="compromisos"></div>
                                        </div>
<?php } else { ?>
                                        <div class="col-12">
                                            <label class="form-label">Limitaciones</label>
                                            <span class="form-control formulario-dato formulario-dato-multilinea" data-campo="limitaciones_cuidado"></span>
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
                                            <span class="form-control formulario-dato formulario-dato-multilinea" data-campo="observaciones"></span>
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
                                <span class="form-control formulario-dato" data-campo="tipo_mascota"></span>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Nombre</label>
                                <span class="form-control formulario-dato" data-campo="nombre"></span>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Edad</label>
                                <span class="form-control formulario-dato" data-campo="edad_aproximada"></span>
                            </div>
<?php if (!$esFormularioTransito) { ?>
                            <div class="col-md-4">
                                <label class="form-label">Sexo</label>
                                <span class="form-control formulario-dato" data-campo="sexo"></span>
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
                                <span class="form-control formulario-dato" data-campo="enfermedad_condicion"></span>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Descripción</label>
                                <span class="form-control formulario-dato formulario-dato-multilinea" data-campo="descripcion"></span>
                            </div>
<?php } ?>
                        </div>
                    </div>
                </article>
            </div>
