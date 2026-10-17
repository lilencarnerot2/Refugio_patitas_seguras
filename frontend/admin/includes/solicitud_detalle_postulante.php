            <div class="col-12 col-lg-6 min-w-0">
                <article class="info-card postulante-panel h-100" id="ficha-postulante">
                    <div class="dash-card-cabecera">
                        <span class="ficha-usuario-icono"><i class="bi bi-person"></i></span>
                        <h3>Postulante</h3>
                    </div>
                    <div class="postulante-layout d-flex flex-column gap-2">
                        <nav class="nav nav-pills postulante-nav-pills flex-wrap gap-1" id="nav-postulante" aria-label="Secciones del postulante">
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
<?php if (!empty($solicitudPostulanteTipoVivienda)) { ?>
                                    <li><span class="detalle-label">Tipo de vivienda</span><span data-campo="tipo_vivienda"></span></li>
<?php } ?>
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
                    </div>
                    <ul id="modelos-postulante" hidden>
                        <li id="modelo-item-contacto-postulante">
                            <span class="detalle-label"></span>
                            <span></span>
                        </li>
                    </ul>
                </article>
            </div>
