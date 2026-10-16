<?php
$solicitudEvaluacionManual = !empty($solicitudEvaluacionManual);
?>
            <div class="col-12 col-lg-6 min-w-0 d-flex">
                <article class="info-card h-100 w-100 transito-card-par d-flex flex-column" id="ficha-evaluacion" data-cargado="0" data-evaluacion-lista="0">
                    <div class="dash-card-cabecera">
                        <span class="ficha-usuario-icono"><i class="bi bi-clipboard2-pulse"></i></span>
                        <h3>Evaluación</h3>
                    </div>
                    <div class="evaluacion-detalle-scroll flex-grow-1 min-h-0">
                        <div id="evaluacion-espera" class="<?php echo $solicitudEvaluacionManual ? 'evaluacion-compatibilidad-espera' : ''; ?>">
                            <ul class="pet-detail-meta evaluacion-placeholder mb-0">
                                <li><span class="detalle-label">Puntaje</span></li>
                                <li><span class="detalle-label">Clasificación</span></li>
                            </ul>
                            <p class="detalle-label mt-3 mb-2">Razones</p>
                            <div class="evaluacion-razones-vacia" aria-hidden="true"></div>
<?php if ($solicitudEvaluacionManual) { ?>
                            <div class="text-center mt-3 pt-1">
                                <button type="button" class="btn btn-primary" id="boton-compatibilidad">
                                    <i class="bi bi-heart-pulse" aria-hidden="true"></i>
                                    <span>Evaluar compatibilidad</span>
                                </button>
                            </div>
<?php } ?>
                        </div>
                        <div id="evaluacion-resultado" class="<?php echo $solicitudEvaluacionManual ? 'evaluacion-compatibilidad-resultado' : ''; ?>" hidden>
<?php if ($solicitudEvaluacionManual) { ?>
                            <div class="compatibilidad-visual d-flex align-items-center justify-content-center gap-3 flex-wrap mb-3 mx-auto">
                                <figure class="compatibilidad-avatar mb-0" id="compatibilidad-avatar-mascota">
                                    <img id="compatibilidad-foto-mascota" class="compatibilidad-avatar-img" alt="" hidden>
                                    <span class="compatibilidad-avatar-vacio" id="compatibilidad-foto-mascota-vacia" aria-hidden="true">
                                        <i class="bi bi-paw"></i>
                                    </span>
                                    <figcaption class="compatibilidad-avatar-etiqueta" data-campo="nombre_mascota"></figcaption>
                                </figure>
                                <div class="compatibilidad-corazon mx-auto" id="corazon-compatibilidad" aria-label="Puntaje de compatibilidad">
                                    <i class="bi bi-heart compatibilidad-corazon-vacio" aria-hidden="true"></i>
                                    <i class="bi bi-heart-fill compatibilidad-corazon-lleno" aria-hidden="true"></i>
                                    <span class="compatibilidad-porcentaje"><span data-campo="puntaje"></span></span>
                                </div>
                                <figure class="compatibilidad-avatar mb-0" id="compatibilidad-avatar-postulante">
                                    <span class="compatibilidad-avatar-vacio compatibilidad-avatar-persona" aria-hidden="true">
                                        <i class="bi bi-person-fill"></i>
                                    </span>
                                    <figcaption class="compatibilidad-avatar-etiqueta" data-campo="nombre_postulante"></figcaption>
                                </figure>
                            </div>
<?php } ?>
                            <ul class="pet-detail-meta mb-0">
                                <li><span class="detalle-label">Puntaje</span><span data-campo="puntaje"></span></li>
                                <li><span class="detalle-label">Clasificación</span><span data-campo="clasificacion"></span></li>
                            </ul>
                            <div class="bloque-razones">
                                <p class="detalle-label mt-3 mb-2">Razones</p>
                                <ul class="list-unstyled mb-0" id="lista-razones"></ul>
                            </div>
                        </div>
                        <p class="small text-muted mb-0 mt-2" id="aviso-evaluacion" role="status"></p>
                        <ul id="modelos-evaluacion" hidden>
                            <li id="modelo-razon-evaluacion"></li>
                        </ul>
                    </div>
                </article>
            </div>
