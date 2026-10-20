<?php
$recursoSeguimiento = trim((string) ($recursoSeguimiento ?? ''));
$claveSolicitud = trim((string) ($claveSolicitud ?? ''));
$porPaginaSeguimientos = (int) ($porPaginaSeguimientos ?? 5);
?>
    <section id="panel-seguimientos-admin" class="panel-listado panel-seguimientos"
        data-recurso="<?php echo $recursoSeguimiento; ?>"
        data-clave-solicitud="<?php echo $claveSolicitud; ?>"
        data-por-pagina="<?php echo $porPaginaSeguimientos; ?>"
        data-texto-seguimiento-activo="Activo"
        data-texto-seguimiento-realizado="Realizado"
        data-observacion-oculta="Seguimiento generado automáticamente">
        <p id="mensaje-seguimientos" class="mensaje-formulario"></p>

        <div class="seguimientos-toolbar admin-barra-acciones d-flex mb-3">
            <a id="seguimientos-volver-solicitud" class="btn-outline btn-sm solicitud-detalle-accion seguimientos-toolbar-btn" href="#" hidden>Volver al detalle</a>
            <button type="button" class="btn btn-primary btn-sm seguimientos-toolbar-btn" data-abrir-alta><i class="bi bi-plus-lg" aria-hidden="true"></i> <span class="seguimientos-toolbar-btn-texto">Nuevo seguimiento</span></button>
        </div>

        <div class="seguimientos-titulo page-intro text-center mb-3">
            <?php if (!empty($tituloSeguimientos)) { ?>
            <h2 class="mb-1"><?php echo $tituloSeguimientos; ?></h2>
            <?php } ?>
            <?php if (!empty($subtituloSeguimientos)) { ?>
            <p class="mb-0 text-muted small" id="seguimientos-subtitulo-general"><?php echo $subtituloSeguimientos; ?></p>
            <?php } ?>
        </div>

        <article class="info-card mb-3">
            <div class="listado-controles row g-2">
                <div class="col-12 col-md min-w-0">
                    <div class="input-group listado-filtro-grupo" aria-label="Tipo">
                        <select id="filtro-tipo-seguimiento" class="form-select listado-filtro" data-filtro="id_tipo_seguimiento" aria-label="Tipo">
                            <option value="" selected hidden></option>
                        </select>
                        <button type="button" class="btn btn-outline-secondary" data-limpiar="filtro-tipo-seguimiento" aria-label="Limpiar filtro de tipo"><i class="bi bi-x"></i></button>
                    </div>
                </div>
                <div class="col-12 col-md min-w-0">
                    <div class="input-group listado-filtro-grupo" aria-label="Estado">
                        <select id="filtro-estado-seguimiento" class="form-select listado-filtro" data-filtro="estado" aria-label="Estado">
                            <option value="" selected hidden></option>
                            <option value="true">Activo (pendiente o vencido)</option>
                            <option value="false">Realizado</option>
                        </select>
                        <button type="button" class="btn btn-outline-secondary" data-limpiar="filtro-estado-seguimiento" aria-label="Limpiar filtro de estado"><i class="bi bi-x"></i></button>
                    </div>
                </div>
                <div class="col-12 col-md min-w-0">
                    <div class="input-group listado-filtro-grupo listado-filtro-grupo-fecha" aria-label="Fecha programada" data-con-valor="false">
                        <input type="date" id="filtro-fecha-programada" class="form-control listado-filtro-fecha" data-filtro="fecha_programada" aria-label="Fecha programada">
                        <button type="button" class="btn btn-outline-secondary" data-limpiar="filtro-fecha-programada" aria-label="Limpiar fecha programada"><i class="bi bi-x"></i></button>
                    </div>
                </div>
                <div class="col-12 col-md-auto d-grid d-md-flex align-items-md-end">
                    <button type="button" class="btn btn-outline-secondary btn-sm w-100 w-md-auto" data-refrescar><i class="bi bi-arrow-clockwise"></i> Actualizar</button>
                </div>
            </div>
        </article>

        <article class="info-card">
            <div class="lista-scroll-mascotas">
                <table class="dash-tabla tabla-seguimientos">
                    <thead>
                        <tr>
                            <th>Mascota</th>
                            <th>Responsable</th>
                            <th>Próxima fecha</th>
                            <th>Estado</th>
                            <th>Último realizado</th>
                            <th>Tipo</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="cuerpo-seguimientos">
                        <tr id="fila-modelo-seguimiento" hidden>
                            <td data-campo="nombre_mascota"></td>
                            <td data-campo="responsable"></td>
                            <td data-campo="fecha_programada"></td>
                            <td data-campo="situacion"></td>
                            <td data-campo="ultimo_realizado"></td>
                            <td data-campo="tipo_seguimiento"></td>
                            <td class="seguimientos-acciones">
                                <a class="abm-ver" href="#" data-accion="ver" role="button"></a>
                                <button type="button" class="abm-alta" data-accion="realizar" hidden></button>
                                <button type="button" class="abm-editar" data-accion="reprogramar" hidden></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <?php
            $paginadorAncla = '#panel-seguimientos-admin';
            $paginadorModeloId = 'pagina-modelo-seguimiento';
            require __DIR__ . '/paginador.php';
            ?>
        </article>
    </section>

    <div class="modal fade" id="modal-seguimiento-alta" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-seguimiento-form">
            <form class="modal-content" id="form-seguimiento-alta" novalidate>
                <div class="modal-header">
                    <h2 class="modal-title fs-5 text-break">Nuevo seguimiento</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-12">
                        <label class="form-label" for="alta-id-solicitud">Solicitud</label>
                        <select class="form-select" id="alta-id-solicitud" name="id_solicitud" required></select>
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label" for="alta-fecha-programada">Fecha programada</label>
                        <input type="date" class="form-control" id="alta-fecha-programada" name="fecha_programada" required>
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label" for="alta-id-tipo">Tipo / medio</label>
                        <select class="form-select" id="alta-id-tipo" name="id_tipo_seguimiento" required></select>
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="alta-observaciones">Observaciones</label>
                        <textarea class="form-control" id="alta-observaciones" name="observaciones" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer justify-content-center gap-2 flex-wrap">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modal-seguimiento-realizar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-seguimiento-form">
            <form class="modal-content" id="form-seguimiento-realizar" novalidate>
                <div class="modal-header">
                    <h2 class="modal-title fs-5 text-break">Registrar seguimiento realizado</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body row g-3">
                    <input type="hidden" name="id">
                    <div class="col-12">
                        <label class="form-label" for="realizar-fecha">Fecha realizada</label>
                        <input type="date" class="form-control" id="realizar-fecha" name="fecha_realizada">
                        <p class="form-text small mb-0">Día en que se efectuó el seguimiento.</p>
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="realizar-observaciones">Observaciones</label>
                        <textarea class="form-control" id="realizar-observaciones" name="observaciones" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer justify-content-center gap-2 flex-wrap">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Confirmar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modal-seguimiento-ver" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-seguimiento-ver">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h2 class="modal-title fs-5">Detalle del seguimiento</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body pt-2">
                    <article id="detalle-seguimiento-ver" class="info-card seguimiento-detalle-panel mb-0">
                        <div class="seguimiento-detalle-resumen d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                            <span class="seguimiento-detalle-id small text-muted">Seguimiento <span data-detalle="id"></span></span>
                            <span class="badge rounded-pill seguimiento-detalle-situacion" data-detalle="situacion"></span>
                        </div>
                        <ul class="pet-detail-meta list-unstyled mb-0">
                            <li><span>Solicitud</span><span data-detalle="id_solicitud"></span></li>
                            <li><span>Mascota</span><span data-detalle="nombre_mascota"></span></li>
                            <li><span>Responsable</span><span data-detalle="responsable"></span></li>
                            <li><span>Fecha programada</span><span data-detalle="fecha_programada"></span></li>
                            <li><span>Fecha realizada</span><span data-detalle="fecha_realizada"></span></li>
                            <li class="seguimiento-detalle-ultimo-realizado"><span>Último realizado</span><span data-detalle="ultimo_realizado"></span></li>
                            <li><span>Tipo / medio</span><span data-detalle="tipo_seguimiento"></span></li>
                            <li><span>Estado</span><span data-detalle="estado"></span></li>
                            <li class="seguimiento-detalle-observaciones"><span>Observaciones</span><span data-detalle="observaciones"></span></li>
                        </ul>
                    </article>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-seguimiento-reprogramar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-seguimiento-form">
            <form class="modal-content" id="form-seguimiento-reprogramar" novalidate>
                <div class="modal-header">
                    <h2 class="modal-title fs-5 text-break">Reprogramar seguimiento</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id">
                    <label class="form-label" for="reprogramar-fecha">Nueva fecha programada</label>
                    <input type="date" class="form-control" id="reprogramar-fecha" name="fecha_programada" required>
                </div>
                <div class="modal-footer justify-content-center gap-2 flex-wrap">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
