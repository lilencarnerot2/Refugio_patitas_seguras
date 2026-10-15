<?php
$tituloPagina = 'Mis solicitudes - Patitas Seguras';
$cuerpoClase = 'pagina-perfil';
$paginaPerfil = 'solicitudes';
require __DIR__ . '/../public/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <section id="panel-mi-perfil" class="panel-perfil panel-aportes">
        <p id="mensaje-perfil" class="mensaje-formulario"></p>
            <div id="seguimiento-solicitudes" class="seguimiento-solicitudes" data-param-detalle="solicitud">
                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-4">
                        <div class="card contador-solicitudes contador-adopcion border-0 rounded-4 h-100">
                            <div class="card-body d-flex align-items-center gap-3">
                                <span class="contador-icono rounded-circle d-inline-flex align-items-center justify-content-center"><i class="bi bi-heart"></i></span>
                                <div>
                                    <strong class="d-block fs-4" data-contador data-contar-clave="adopcion" data-contar-estado="pendiente">0</strong>
                                    <span class="small d-block">Solicitudes de adopción</span>
                                    <small class="text-body-secondary">En proceso</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="card contador-solicitudes contador-transito border-0 rounded-4 h-100">
                            <div class="card-body d-flex align-items-center gap-3">
                                <span class="contador-icono rounded-circle d-inline-flex align-items-center justify-content-center"><i class="bi bi-house-heart"></i></span>
                                <div>
                                    <strong class="d-block fs-4" data-contador data-contar-clave="transito" data-contar-estado="pendiente">0</strong>
                                    <span class="small d-block">Solicitudes de tránsito</span>
                                    <small class="text-body-secondary">en proceso</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="card contador-solicitudes contador-total border-0 rounded-4 h-100">
                            <div class="card-body d-flex align-items-center gap-3">
                                <span class="contador-icono rounded-circle d-inline-flex align-items-center justify-content-center"><i class="bi bi-file-earmark-text"></i></span>
                                <div>
                                    <strong class="d-block fs-4" data-contador>0</strong>
                                    <span class="small d-block">Solicitudes totales</span>
                                    <small class="text-body-secondary">Enviadas</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <h3 class="perfil-titulo h4 mb-3">Mis solicitudes</h3>
                <p class="mensaje-formulario" data-mensaje-solicitudes role="status"></p>
                <ul class="nav row g-3 mb-3 solicitudes-pestanas" role="tablist">
                    <li class="col-12 col-md-6" role="presentation">
                        <button class="nav-link pestana-adopcion active w-100 d-flex align-items-center justify-content-between rounded-4" type="button" role="tab" data-bs-toggle="pill" data-bs-target="#solicitudes-adopcion" aria-controls="solicitudes-adopcion" aria-selected="true">
                            <span class="d-flex align-items-center gap-2"><i class="bi bi-folder2"></i> Mis solicitudes de adopción</span>
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </li>
                    <li class="col-12 col-md-6" role="presentation">
                        <button class="nav-link pestana-transito w-100 d-flex align-items-center justify-content-between rounded-4" type="button" role="tab" data-bs-toggle="pill" data-bs-target="#solicitudes-transito" aria-controls="solicitudes-transito" aria-selected="false">
                            <span class="d-flex align-items-center gap-2"><i class="bi bi-folder2"></i> Mis solicitudes de hogar de tránsito</span>
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="solicitudes-adopcion" role="tabpanel" data-clave="adopcion" data-recurso-formulario="formularios_adopcion">
                        <article class="card solicitudes-grupo border-0 rounded-4 shadow-sm">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
                                    <h4 class="h5 mb-0">Solicitudes de adopción</h4>
                                    <span class="badge rounded-pill solicitudes-cantidad"><span data-cantidad>0</span> <span class="cantidad-plural">solicitudes</span><span class="cantidad-singular">solicitud</span></span>
                                </div>
                                <ul class="list-unstyled d-grid gap-3 mb-0" data-lista></ul>
                                <div class="solicitudes-vacio text-center rounded-4 p-4">
                                    <i class="fa-solid fa-cat"></i>
                                    <p class="fw-semibold mb-1">Aún no tenés solicitudes de adopción.</p>
                                    <p class="small mb-3">Cuando completes un formulario y lo envíes, vas a poder ver su estado desde acá.</p>
                                    <a class="btn btn-sm btn-outline-success rounded-pill px-3" href="/public/mascotas.php">Ver mascotas</a>
                                </div>
                            </div>
                        </article>
                        <template data-modelo>
                            <li class="card solicitud-item rounded-4">
                                <div class="card-body d-flex flex-column flex-sm-row gap-3">
                                    <div class="solicitud-foto rounded-4 flex-shrink-0 d-flex align-items-center justify-content-center">
                                        <img data-campo="foto_url" alt="">
                                        <i class="fa-solid fa-paw"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="mb-1"><span data-campo="nombre_mascota"></span> <i class="fa-solid fa-paw solicitud-pata"></i></h5>
                                        <p class="solicitud-meta small mb-1"><span data-campo="especie"></span><span data-campo="raza"></span><span data-campo="tamaño"></span></p>
                                        <p class="small mb-2"><i class="bi bi-calendar3"></i> Enviada el <span data-campo="fecha_solicitud" data-formato="fecha"></span></p>
                                        <span class="badge rounded-pill estado-solicitud" data-campo="estado_solicitud"></span>
                                        <p class="solicitud-ayuda small mt-2 mb-0" data-ayuda-estado="pendiente">Tu solicitud de adopción está siendo evaluada por el equipo del refugio. Te vamos a contactar si necesitamos más información.</p>
                                        <p class="solicitud-ayuda small mt-2 mb-0" data-ayuda-estado="aprobado">¡Tu solicitud fue aprobada! El refugio se va a comunicar con vos para coordinar la adopción.</p>
                                        <p class="solicitud-ayuda small mt-2 mb-0" data-ayuda-estado="rechazado">Esta vez tu solicitud no fue aprobada. Podés conocer a otras mascotas del refugio.</p>
                                        <p class="solicitud-ayuda small mt-2 mb-0" data-ayuda-estado="finalizado">La adopción finalizó. ¡Gracias por darle un hogar!</p>
                                    </div>
                                    <div class="align-self-sm-end">
                                        <button type="button" class="btn btn-outline-success btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modal-detalle-solicitud">Ver detalle <i class="bi bi-chevron-right"></i></button>
                                    </div>
                                </div>
                            </li>
                        </template>
                    </div>
                    <div class="tab-pane fade" id="solicitudes-transito" role="tabpanel" data-clave="transito" data-recurso-formulario="formularios_transito">
                        <article class="card solicitudes-grupo border-0 rounded-4 shadow-sm">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
                                    <h4 class="h5 mb-0">Solicitudes de hogar de tránsito</h4>
                                    <span class="badge rounded-pill solicitudes-cantidad"><span data-cantidad>0</span> <span class="cantidad-plural">solicitudes</span><span class="cantidad-singular">solicitud</span></span>
                                </div>
                                <ul class="list-unstyled d-grid gap-3 mb-0" data-lista></ul>
                                <div class="solicitudes-vacio text-center rounded-4 p-4">
                                    <i class="fa-solid fa-cat"></i>
                                    <p class="fw-semibold mb-1">Aún no tenés solicitudes de hogar de tránsito.</p>
                                    <p class="small mb-3">Cuando completes un formulario y lo envíes, vas a poder ver su estado desde acá.</p>
                                    <a class="btn btn-sm btn-outline-success rounded-pill px-3" href="/public/mascotas.php">Ver mascotas</a>
                                </div>
                            </div>
                        </article>
                        <template data-modelo>
                            <li class="card solicitud-item rounded-4">
                                <div class="card-body d-flex flex-column flex-sm-row gap-3">
                                    <div class="solicitud-foto rounded-4 flex-shrink-0 d-flex align-items-center justify-content-center">
                                        <img data-campo="foto_url" alt="">
                                        <i class="fa-solid fa-paw"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="mb-1"><span data-campo="nombre_mascota"></span> <i class="fa-solid fa-paw solicitud-pata"></i></h5>
                                        <p class="solicitud-meta small mb-1"><span data-campo="especie"></span><span data-campo="raza"></span><span data-campo="tamaño"></span></p>
                                        <p class="small mb-2"><i class="bi bi-calendar3"></i> Enviada el <span data-campo="fecha_solicitud" data-formato="fecha"></span></p>
                                        <span class="badge rounded-pill estado-solicitud" data-campo="estado_solicitud"></span>
                                        <p class="solicitud-ayuda small mt-2 mb-0" data-ayuda-estado="pendiente">Recibimos tu solicitud y el refugio la está evaluando. Te vamos a contactar si necesitamos más información.</p>
                                        <p class="solicitud-ayuda small mt-2 mb-0" data-ayuda-estado="aprobado">¡Tu solicitud fue aprobada! El refugio se va a comunicar con vos para coordinar el tránsito.</p>
                                        <p class="solicitud-ayuda small mt-2 mb-0" data-ayuda-estado="rechazado">Esta vez tu solicitud no fue aprobada. Podés ofrecerte como tránsito para otra mascota.</p>
                                        <p class="solicitud-ayuda small mt-2 mb-0" data-ayuda-estado="finalizado">El tránsito finalizó. ¡Gracias por ayudar!</p>
                                    </div>
                                    <div class="align-self-sm-end">
                                        <button type="button" class="btn btn-outline-success btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modal-detalle-solicitud">Ver detalle <i class="bi bi-chevron-right"></i></button>
                                    </div>
                                </div>
                            </li>
                        </template>
                    </div>
                </div>
            </div>
    </section>
    <div class="modal fade" id="modal-detalle-solicitud" tabindex="-1" aria-labelledby="modal-detalle-titulo" aria-hidden="true" data-si="Sí" data-no="No">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 rounded-4">
                <div class="modal-header border-0 px-4 pt-4">
                    <h5 class="modal-title" id="modal-detalle-titulo">Solicitud de <span data-campo="nombre_mascota"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body px-4 pb-4">
                    <div class="seguimiento-estado rounded-4 p-3 mb-3 d-flex flex-wrap align-items-center gap-3">
                        <span class="badge rounded-pill estado-solicitud" data-campo="estado_solicitud"></span>
                        <p class="mb-0 small" data-ayuda-estado="pendiente">Recibimos tu solicitud y el refugio la está evaluando.</p>
                        <p class="mb-0 small" data-ayuda-estado="aprobado">¡Tu solicitud fue aprobada! El refugio se va a comunicar con vos.</p>
                        <p class="mb-0 small" data-ayuda-estado="rechazado">Esta vez tu solicitud no fue aprobada.</p>
                        <p class="mb-0 small" data-ayuda-estado="finalizado">Esta solicitud ya finalizó. ¡Gracias por ayudar!</p>
                    </div>

                    <p class="mensaje-formulario" data-mensaje-detalle role="status"></p>

                    <div class="seguimiento-formulario-accion mb-3">
                        <p class="small text-body-secondary mb-2 mb-md-0">Acá podés revisar los datos que enviaste en el formulario.</p>
                        <button type="button" class="btn btn-outline-success rounded-pill px-3" data-ver-formulario>
                            <i class="bi bi-file-earmark-text"></i> Ver mi formulario completado
                        </button>
                    </div>

                    <dl class="mb-0 seguimiento-datos" data-formulario-completado hidden>
                        <div><dt>Tipo de solicitud</dt><dd data-campo="tipo_solicitud"></dd></div>
                        <div><dt>Fecha de solicitud</dt><dd data-campo="fecha_solicitud" data-formato="fecha"></dd></div>
                        <div><dt>Disponible desde</dt><dd data-campo="disponibilidad_desde" data-formato="fecha"></dd></div>
                        <div><dt>Disponible hasta</dt><dd data-campo="disponibilidad_hasta" data-formato="fecha"></dd></div>
                        <div><dt>Tipo de vivienda</dt><dd data-campo="tipo_vivienda"></dd></div>
                        <div><dt>Condición de vivienda</dt><dd data-campo="condicion_vivienda"></dd></div>
                        <div><dt>Tiene patio</dt><dd data-campo="tiene_patio"></dd></div>
                        <div><dt>Metros cuadrados del patio</dt><dd data-campo="metros_cuadrados_patio"></dd></div>
                        <div><dt>Destino de la mascota</dt><dd data-campo="destino_mascota"></dd></div>
                        <div><dt>Integrantes del hogar</dt><dd data-campo="cantidad_integrantes"></dd></div>
                        <div><dt>Hay niños</dt><dd data-campo="hay_ninos"></dd></div>
                        <div><dt>Horas solo</dt><dd data-campo="horas_solo"></dd></div>
                        <div><dt>Tiene mascotas</dt><dd data-campo="tiene_mascotas"></dd></div>
                        <div><dt>Mascotas del hogar</dt><dd data-campo="mascotas_hogar"></dd></div>
                        <div><dt>Cantidad máxima de mascotas</dt><dd data-campo="cantidad_maxima_mascotas"></dd></div>
                        <div><dt>Responsable principal</dt><dd data-campo="responsable_principal"></dd></div>
                        <div><dt>Puede realizar paseos</dt><dd data-campo="puede_realizar_paseos"></dd></div>
                        <div><dt>Puede realizar traslados</dt><dd data-campo="puede_realizar_traslados"></dd></div>
                        <div><dt>Puede administrar medicación</dt><dd data-campo="puede_administrar_medicacion"></dd></div>
                        <div><dt>Cuenta con apoyo</dt><dd data-campo="cuenta_con_apoyo"></dd></div>
                        <div><dt>Cuenta con recursos</dt><dd data-campo="cuenta_con_recursos"></dd></div>
                        <div class="seguimiento-dato-largo"><dt>Motivo de la adopción</dt><dd data-campo="motivo_adopcion"></dd></div>
                        <div class="seguimiento-dato-largo"><dt>Experiencia de cuidado</dt><dd data-campo="experiencia_cuidado"></dd></div>
                        <div class="seguimiento-dato-largo"><dt>Limitaciones de cuidado</dt><dd data-campo="limitaciones_cuidado"></dd></div>
                        <div class="seguimiento-dato-largo"><dt>Compromisos</dt><dd data-campo="compromisos"></dd></div>
                        <div class="seguimiento-dato-largo"><dt>Condiciones especiales</dt><dd data-campo="condiciones_especiales"></dd></div>
                        <div class="seguimiento-dato-largo"><dt>Observaciones</dt><dd data-campo="observaciones"></dd></div>
                    </dl>
                </div>
            </div>
        </div>
    </div>

<script src="../public/config.php"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/mi-perfil.js"></script>
<script src="../assets/js/seguimiento-solicitudes.js"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
