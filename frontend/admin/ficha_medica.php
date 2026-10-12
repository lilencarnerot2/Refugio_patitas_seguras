<?php
$tituloPagina = 'Ficha médica - Patitas Seguras';
$paginaAdmin = 'ficha_medica';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <section>
        <p id="mensaje-formulario" class="mensaje-formulario"></p>

        <article id="datos-mascota" class="info-card" hidden>
            <div class="ficha-foto-perfil dash-foto">
                <img id="ficha-foto" hidden alt="">
                <div id="ficha-foto-vacia" class="pet-photo-empty"></div>
            </div>
            <div class="ficha-perfil-datos">
                <div class="dash-card-cabecera">
                    <h3 id="ficha-nombre"></h3>
                    <a class="btn-primary" href="#editar">Editar</a>
                </div>
                <ul class="pet-detail-meta">
                    <li><span class="detalle-label">Especie</span><span id="ficha-especie"></span></li>
                    <li><span class="detalle-label">Raza</span><span id="ficha-raza"></span></li>
                    <li><span class="detalle-label">Sexo</span><span id="ficha-sexo"></span></li>
                    <li><span class="detalle-label">Tamaño</span><span id="ficha-tamano"></span></li>
                    <li><span class="detalle-label">Nacimiento estimado</span><span id="ficha-nacimiento"></span></li>
                    <li><span class="detalle-label">Observaciones</span><span id="ficha-observaciones"></span></li>
                    <li><span class="detalle-label">Historial</span><span id="ficha-historial"></span></li>
                </ul>
            </div>
        </article>

        <div id="bloques-ficha" hidden>
            <div id="resumen-ficha" class="row g-4">
                <div class="col-md-6">
                    <article class="info-card">
                        <h3>Último control</h3>
                        <p>Peso: <span id="control-peso"></span></p>
                        <p>Fecha: <span id="control-fecha"></span></p>
                        <p id="control-observaciones"></p>
                    </article>
                </div>
                <div class="col-md-6">
                    <article class="info-card">
                        <h3>Castración</h3>
                        <p>Estado: <span id="castracion-estado"></span></p>
                        <p id="castracion-observaciones"></p>
                    </article>
                </div>
                <div class="col-md-6">
                    <article class="info-card">
                        <h3>Vacunas</h3>
                        <ul id="lista-vacunas"></ul>
                    </article>
                </div>
                <div class="col-md-6">
                    <article class="info-card">
                        <h3>Desparasitaciones</h3>
                        <ul id="lista-desparasitaciones"></ul>
                    </article>
                </div>
            </div>

            <div id="editar" class="row g-4">
                <div class="col-12" id="bloque-crear-ficha" hidden>
                    <article class="info-card">
                        <form id="formulario-crear-ficha">
                            <h3>Observaciones</h3>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label" for="observaciones_ficha">Observaciones</label>
                                    <textarea class="form-control" id="observaciones_ficha" name="observaciones" rows="3"></textarea>
                                </div>
                            </div>
                        </form>
                    </article>
                </div>
                <div class="col-md-6">
                    <article class="info-card">
                        <form id="formulario-control">
                            <h3>Último control</h3>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="fecha_control">Fecha</label>
                                    <input class="form-control" type="date" id="fecha_control" name="fecha_control" required max="<?php echo date('Y-m-d'); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="peso">Peso</label>
                                    <input class="form-control" type="number" id="peso" name="peso" min="0" step="0.1">
                                </div>
                                <div class="col-12">
                                    <label class="form-label" for="observaciones_control">Observaciones</label>
                                    <textarea class="form-control" id="observaciones_control" name="observaciones" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="acciones-formulario">
                                <button type="submit" class="btn-primary">Agregar control</button>
                            </div>
                        </form>
                    </article>
                </div>
                <div class="col-md-6">
                    <article class="info-card">
                        <form id="formulario-vacuna">
                            <h3>Vacunas</h3>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="id_tipo_vacuna">Tipo de vacuna</label>
                                    <select class="form-select" id="id_tipo_vacuna" name="id_tipo_vacuna" required>
                                        <option value="" selected disabled>Seleccioná un tipo</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="numero_dosis">Número de dosis</label>
                                    <input class="form-control" type="number" id="numero_dosis" name="numero_dosis" min="1" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="fecha_aplicacion_vacuna">Fecha de aplicación</label>
                                    <input class="form-control" type="date" id="fecha_aplicacion_vacuna" name="fecha_aplicacion" required max="<?php echo date('Y-m-d'); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="proxima_dosis_vacuna">Próxima dosis</label>
                                    <input class="form-control" type="date" id="proxima_dosis_vacuna" name="proxima_dosis">
                                </div>
                                <div class="col-12">
                                    <label class="form-label" for="observaciones_vacuna">Observaciones</label>
                                    <textarea class="form-control" id="observaciones_vacuna" name="observaciones" rows="2"></textarea>
                                </div>
                            </div>
                            <div class="acciones-formulario">
                                <button type="submit" class="btn-primary">Agregar vacuna</button>
                            </div>
                        </form>
                    </article>
                </div>
                <div class="col-md-6">
                    <article class="info-card">
                        <form id="formulario-castracion">
                            <h3>Castración</h3>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="fecha_castracion">Fecha</label>
                                    <input class="form-control" type="date" id="fecha_castracion" name="fecha_castracion" max="<?php echo date('Y-m-d'); ?>">
                                </div>
                                <div class="col-12">
                                    <label class="form-label" for="observaciones_castracion">Observaciones</label>
                                    <textarea class="form-control" id="observaciones_castracion" name="observaciones" rows="2"></textarea>
                                </div>
                            </div>
                            <div class="acciones-formulario">
                                <button type="submit" class="btn-primary">Registrar castración</button>
                            </div>
                        </form>
                    </article>
                </div>
                <div class="col-md-6">
                    <article class="info-card">
                        <form id="formulario-desparasitacion">
                            <h3>Desparasitaciones</h3>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="id_tipo_producto">Tipo de producto</label>
                                    <select class="form-select" id="id_tipo_producto" name="id_tipo_producto" required>
                                        <option value="" selected disabled>Seleccioná un tipo</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="tipo_parasito">Tipo de parásito</label>
                                    <select class="form-select" id="tipo_parasito" name="tipo_parasito" required>
                                        <option value="" selected disabled>Seleccioná un tipo</option>
                                        <option value="interno">Interno</option>
                                        <option value="externo">Externo</option>
                                        <option value="ambos">Ambos</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="fecha_aplicacion_desparasitacion">Fecha de aplicación</label>
                                    <input class="form-control" type="date" id="fecha_aplicacion_desparasitacion" name="fecha_aplicacion" required max="<?php echo date('Y-m-d'); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="proxima_dosis_desparasitacion">Próxima dosis</label>
                                    <input class="form-control" type="date" id="proxima_dosis_desparasitacion" name="proxima_dosis">
                                </div>
                                <div class="col-12">
                                    <label class="form-label" for="observaciones_desparasitacion">Observaciones</label>
                                    <textarea class="form-control" id="observaciones_desparasitacion" name="observaciones" rows="2"></textarea>
                                </div>
                            </div>
                            <div class="acciones-formulario">
                                <button type="submit" class="btn-primary">Agregar desparasitación</button>
                            </div>
                        </form>
                    </article>
                </div>
                <div class="col-12" id="acciones-crear-ficha" hidden>
                    <div class="acciones-formulario">
                        <button type="submit" class="btn-primary" form="formulario-crear-ficha">Crear ficha médica</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="acciones-formulario">
            <a class="btn-outline" href="salud.php">Volver a salud</a>
        </div>
    </section>

    <!-- Modelos ocultos con el diseño; el JS los clona y solo pone datos -->
    <div id="modelos-ficha" hidden>
        <option id="opcion-modelo-ficha"></option>
        <ul><li id="item-modelo-ficha"></li></ul>
    </div>

<script src="config.php"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/ficha-medica.js"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
