<?php
$tituloPagina = 'Formulario de adopción - Patitas Seguras';
require __DIR__ . '/../public/includes/head.php';
require __DIR__ . '/../public/includes/header.php';
?>

    <section id="acceso-solicitud">
        <div id="mensaje-solicitud" class="alert alert-danger text-center" role="alert"></div>
        <div id="bloque-solicitud" hidden>
            <article id="ficha-mascota" class="pet-card">
                <img data-campo="foto_url" alt="" hidden>
                <div class="pet-card-body text-center">
                    <h4 data-campo="nombre"></h4>
                    <p data-campo="especie" data-etiqueta="Especie"></p>
                    <p data-campo="raza" data-etiqueta="Raza"></p>
                    <p data-campo="sexo" data-etiqueta="Sexo"></p>
                    <p data-campo="tamaño" data-etiqueta="Tamaño"></p>
                    <p data-campo="fecha_nacimiento_estimada" data-etiqueta="Nacimiento"></p>
                    <p data-campo="historial" data-etiqueta="Historial"></p>
                </div>
            </article>

            <div class="solicitud-formulario">
                <div class="page-intro text-center">
                    <h2>Formulario de adopción</h2>
                </div>
                <form id="formulario-solicitud" class="public-form" data-clave="adopcion" data-recurso="formularios_adopcion" novalidate autocomplete="off">
                    <input type="hidden" name="id_mascota" id="id_mascota">
                    <input type="hidden" name="id_tipo_solicitud" id="id_tipo_solicitud">

                        <div class="row g-3">
                            <div class="col-12">
                                <section class="form-seccion">
                                    <h3 class="form-seccion-titulo">Vivienda</h3>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label" for="id_tipo_vivienda">Tipo de vivienda</label>
                                            <select class="form-select" id="id_tipo_vivienda" name="id_tipo_vivienda" data-catalogo="tipos_vivienda" required>
                                                <option value="" selected disabled></option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="id_condicion_vivienda">Condición de vivienda</label>
                                            <select class="form-select" id="id_condicion_vivienda" name="id_condicion_vivienda" data-catalogo="condiciones_vivienda" required>
                                                <option value="" selected disabled></option>
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <div class="row g-3 align-items-end">
                                                <div class="col-md-6">
                                                    <label class="form-check" for="tiene_patio">
                                                        <input class="form-check-input" type="checkbox" id="tiene_patio" name="tiene_patio">
                                                        <span>Tiene patio</span>
                                                    </label>
                                                </div>
                                                <div class="col-md-6" id="grupo-metros-patio" hidden>
                                                    <label class="form-label" for="metros_cuadrados_patio">Metros cuadrados del patio</label>
                                                    <input class="form-control" type="number" step="0.01" min="0" id="metros_cuadrados_patio" name="metros_cuadrados_patio" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="id_destino_mascota">En caso de no poder criarlo</label>
                                            <select class="form-select" id="id_destino_mascota" name="id_destino_mascota" data-catalogo="destinos_mascotas" required>
                                                <option value=""></option>
                                            </select>
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
                                                <label class="form-label" for="cantidad_integrantes">Cantidad de integrantes</label>
                                                <input class="form-control" type="number" min="1" id="cantidad_integrantes" name="cantidad_integrantes" required>
                                            </div>
                                            <label class="form-check" for="hay_ninos">
                                                <input class="form-check-input" type="checkbox" id="hay_ninos" name="hay_ninos">
                                                <span>Hay niños</span>
                                            </label>
                                        </div>
                                        <div class="col-md-6 d-flex flex-column gap-2">
                                            <div>
                                                <label class="form-label" for="horas_solo">Horas solo</label>
                                                <input class="form-control" type="number" min="0" max="24" id="horas_solo" name="horas_solo">
                                            </div>
                                            <label class="form-check" for="tiene_mascotas">
                                                <input class="form-check-input" type="checkbox" id="tiene_mascotas" name="tiene_mascotas">
                                                <span>Tiene mascotas</span>
                                            </label>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label" for="responsable_principal">Responsable principal</label>
                                            <input class="form-control" type="text" id="responsable_principal" name="responsable_principal" maxlength="60" required>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label" for="motivo_adopcion">Motivo de adopción</label>
                                            <textarea class="form-control" id="motivo_adopcion" name="motivo_adopcion" rows="2" maxlength="250"></textarea>
                                        </div>
                                    </div>
                                </section>
                            </div>
                            <div class="col-12">
                                <section class="form-seccion">
                                    <h3 class="form-seccion-titulo">Cuidado</h3>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-check" for="puede_realizar_paseos">
                                                <input class="form-check-input" type="checkbox" id="puede_realizar_paseos" name="puede_realizar_paseos">
                                                <span>Puede realizar paseos</span>
                                            </label>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-check" for="puede_realizar_traslados">
                                                <input class="form-check-input" type="checkbox" id="puede_realizar_traslados" name="puede_realizar_traslados">
                                                <span>Puede realizar traslados</span>
                                            </label>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-check" for="cuenta_con_apoyo">
                                                <input class="form-check-input" type="checkbox" id="cuenta_con_apoyo" name="cuenta_con_apoyo">
                                                <span>Cuenta con apoyo</span>
                                            </label>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-check" for="cuenta_con_recursos">
                                                <input class="form-check-input" type="checkbox" id="cuenta_con_recursos" name="cuenta_con_recursos">
                                                <span>Cuenta con recursos</span>
                                            </label>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label" for="limitaciones_cuidado">Limitaciones de cuidado</label>
                                            <textarea class="form-control" id="limitaciones_cuidado" name="limitaciones_cuidado" rows="2"></textarea>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label" for="experiencia_cuidado">Experiencia de cuidado</label>
                                            <textarea class="form-control" id="experiencia_cuidado" name="experiencia_cuidado" rows="2"></textarea>
                                        </div>
                                        <div class="col-12">
                                            <p class="form-label">Compromisos de adopción</p>
                                            <div data-lista="compromisos_adopcion" data-name="compromisos" data-requerido></div>
                                        </div>
                                        <div class="col-12">
                                            <p class="form-label">Condiciones especiales</p>
                                            <div data-lista="condiciones_especiales" data-name="condiciones_especiales"></div>
                                        </div>
                                        <div class="col-12" id="grupo-mascotas-hogar" hidden>
                                            <p class="form-label">Mascotas del hogar</p>
                                            <div id="lista-mascotas-hogar"></div>
                                            <button class="btn btn-outline-primary" type="button" id="agregar-mascota-hogar">Agregar mascota</button>
                                        </div>
                                    </div>
                                </section>
                            </div>
                            <div class="col-12">
                                <section class="form-seccion">
                                    <h3 class="form-seccion-titulo">Domicilio y contacto</h3>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label" for="id_provincia">Provincia</label>
                                            <select class="form-select" id="id_provincia" name="id_provincia" data-catalogo="provincias" data-carga="id_comuna_municipio">
                                                <option value="" selected disabled></option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="id_comuna_municipio">Comuna o municipio</label>
                                            <select class="form-select" id="id_comuna_municipio" name="id_comuna_municipio" data-catalogo="comunas_municipios" data-param="id_provincia" data-carga="id_localidad">
                                                <option value="" selected disabled></option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="id_localidad">Localidad</label>
                                            <select class="form-select" id="id_localidad" name="id_localidad" data-catalogo="localidades" data-param="id_comuna_municipio" data-carga="id_barrio_zona">
                                                <option value="" selected disabled></option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="id_barrio_zona">Barrio o zona</label>
                                            <select class="form-select" id="id_barrio_zona" name="id_barrio_zona" data-catalogo="barrios_zonas" data-param="id_localidad">
                                                <option value="" selected disabled></option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="calle">Calle</label>
                                            <input class="form-control" type="text" id="calle" name="calle">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="altura">Altura</label>
                                            <input class="form-control" type="text" id="altura" name="altura">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="piso">Piso</label>
                                            <input class="form-control" type="text" id="piso" name="piso">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="departamento">Departamento</label>
                                            <input class="form-control" type="text" id="departamento" name="departamento">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="ruta_nombre">Ruta</label>
                                            <input class="form-control" type="text" id="ruta_nombre" name="ruta_nombre">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="kilometro">Kilómetro</label>
                                            <input class="form-control" type="text" id="kilometro" name="kilometro">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="entre_calle_1">Entre calle 1</label>
                                            <input class="form-control" type="text" id="entre_calle_1" name="entre_calle_1">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="entre_calle_2">Entre calle 2</label>
                                            <input class="form-control" type="text" id="entre_calle_2" name="entre_calle_2">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label" for="indicadores_referencia">Indicadores de referencia</label>
                                            <input class="form-control" type="text" id="indicadores_referencia" name="indicadores_referencia">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="codigo_postal">Código postal</label>
                                            <input class="form-control" type="text" id="codigo_postal" name="codigo_postal">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="id_tipo_contacto">Tipo de contacto</label>
                                            <select class="form-select" id="id_tipo_contacto" name="id_tipo_contacto" data-catalogo="tipos_contacto">
                                                <option value="" selected disabled></option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 d-flex flex-column gap-2">
                                            <div>
                                                <label class="form-label" for="valor">Contacto</label>
                                                <input class="form-control" type="text" id="valor" name="valor">
                                            </div>
                                            <label class="form-check" for="es_principal">
                                                <input class="form-check-input" type="checkbox" id="es_principal" name="es_principal">
                                                <span>Es principal</span>
                                            </label>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label" for="observaciones">Observaciones</label>
                                            <textarea class="form-control" id="observaciones" name="observaciones" rows="2"></textarea>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>

                    <p id="mensaje-formulario" class="mensaje-formulario"></p>
                    <button class="btn-primary" type="submit">Enviar solicitud</button>
                </form>
            </div>
        </div>
    </section>

    <div id="modelos-adopcion" hidden>
        <select id="modelo-select">
            <option id="modelo-opcion-select" value=""></option>
        </select>
        <button type="button" class="btn-limpiar-campo" id="modelo-limpiar-campo">×</button>
        <ul class="lista-desplegable" id="modelo-lista-desplegable">
            <li class="lista-desplegable-item" id="modelo-item-desplegable"></li>
        </ul>
        <label class="form-check" id="modelo-opcion-lista">
            <input class="form-check-input" type="checkbox">
            <span data-texto></span>
        </label>
        <div class="row g-2 mb-2 position-relative pt-1" id="modelo-mascota-hogar">
            <div class="col-12 d-flex justify-content-end py-0">
                <button class="btn-quitar-mascota" type="button">×</button>
            </div>
            <div class="col-md-4">
                <label class="form-label">Tipo de mascota</label>
                <select class="form-select" name="id_tipo_mascota" data-catalogo="tipos_mascotas" required>
                    <option value="" selected disabled></option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Nombre</label>
                <input class="form-control" type="text" name="nombre" maxlength="30">
            </div>
            <div class="col-md-4">
                <label class="form-label">Edad aproximada</label>
                <input class="form-control" type="number" min="0" name="edad_aproximada">
            </div>
            <div class="col-md-4">
                <label class="form-label">Sexo</label>
                <input class="form-control" type="text" name="sexo">
            </div>
            <div class="col-md-8">
                <div class="row g-2 align-items-end h-100">
                    <div class="col-6 d-flex align-items-end">
                        <label class="form-check w-100">
                            <input class="form-check-input" type="checkbox" name="esterilizada">
                            <span>Esterilizada</span>
                        </label>
                    </div>
                    <div class="col-6 d-flex align-items-end">
                        <label class="form-check w-100">
                            <input class="form-check-input" type="checkbox" name="vacunas_al_dia">
                            <span>Vacunas al día</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <label class="form-label">Enfermedad o condición</label>
                <input class="form-control" type="text" name="enfermedad_condicion">
            </div>
            <div class="col-12">
                <label class="form-label">Descripción</label>
                <textarea class="form-control" name="descripcion" rows="2"></textarea>
            </div>
        </div>
    </div>

<script src="../public/config.php"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/formulario-solicitud.js"></script>


<?php require __DIR__ . '/../public/includes/footer.php'; ?>
