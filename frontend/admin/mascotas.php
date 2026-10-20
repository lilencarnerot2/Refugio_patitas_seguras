<?php
$tituloPagina = 'Mascotas - Patitas Seguras';
$paginaAdmin = 'mascotas';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <section id="panel-mascotas" class="panel-listado" data-por-pagina="5">
        <p id="mensaje-formulario" class="mensaje-formulario"></p>

        <div class="mascotas-toolbar d-flex justify-content-end mb-3">
            <button type="button" class="btn btn-primary btn-sm" data-abrir-alta><i class="bi bi-plus-lg"></i> <span>Nueva mascota</span></button>
        </div>

        <article class="info-card">
            <div class="dash-card-cabecera">
                <span class="ficha-usuario-icono"><i class="bi bi-paw"></i></span>
                <h3>Mascotas</h3>
            </div>
            <div class="listado-controles row g-2">
                <div class="col-12 col-lg min-w-0">
                    <div class="input-group listado-buscar">
                        <i class="bi bi-search input-group-text"></i>
                        <input type="search" id="buscar-mascotas" class="form-control" placeholder="Buscar" aria-label="Buscar">
                        <button type="button" class="btn btn-outline-secondary" data-limpiar="buscar-mascotas" aria-label="Limpiar búsqueda"><i class="bi bi-x"></i></button>
                    </div>
                </div>
                <div class="col-12 col-md min-w-0">
                    <div class="input-group listado-filtro-grupo" aria-label="Especie">
                        <select id="filtro-especie" class="form-select listado-filtro" aria-label="Especie">
                            <option value="" selected hidden></option>
                        </select>
                        <button type="button" class="btn btn-outline-secondary" data-limpiar="filtro-especie" aria-label="Limpiar filtro de especie"><i class="bi bi-x"></i></button>
                    </div>
                </div>
            </div>
            <div class="lista-scroll-mascotas">
                <table class="dash-tabla">
                    <thead>
                        <tr>
                            <th>Nro</th>
                            <th>Nombre</th>
                            <th>Especie</th>
                            <th>Raza</th>
                            <th>Sexo</th>
                            <th>Tamaño</th>
                            <th>Ver</th>
                            <th>Editar</th>
                            <th>Baja</th>
                        </tr>
                    </thead>
                    <tbody id="cuerpo-mascotas">
                        <tr id="fila-modelo-mascota" hidden>
                            <td data-celda="nro"></td>
                            <td data-celda="nombre"></td>
                            <td data-celda="especie"></td>
                            <td data-celda="raza"></td>
                            <td data-celda="sexo"></td>
                            <td data-celda="tamano"></td>
                            <td><a class="abm-ver" href="ficha_medica.php" aria-label="Ver"></a></td>
                            <td><button type="button" class="abm-editar" aria-label="Editar"></button></td>
                            <td><button type="button" class="abm-baja" aria-label="Baja"></button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <?php
            $paginadorAncla = '#panel-mascotas';
            $paginadorModeloId = 'pagina-modelo-mascotas';
            require __DIR__ . '/includes/paginador.php';
            ?>
            <!-- Modelo oculto con el diseño; el JS lo clona y solo pone datos -->
            <div id="modelos-mascotas" hidden>
                <option id="opcion-modelo-mascotas"></option>
            </div>
        </article>
    </section>

    <div class="modal fade" id="modal-mascota" tabindex="-1" aria-labelledby="titulo-modal-mascota" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <form class="modal-content" id="form-mascota" autocomplete="off">
                <div class="modal-header">
                    <h2 class="modal-title fs-5" id="titulo-modal-mascota">Nueva mascota</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-12 col-lg-6">
                        <label class="form-label" for="mascota-especie">Especie</label>
                        <select class="form-select" id="mascota-especie" name="id_especie" required>
                            <option value="" selected disabled>Seleccioná una especie</option>
                        </select>
                    </div>
                    <div class="col-12 col-lg-6">
                        <label class="form-label" for="mascota-raza">Raza</label>
                        <select class="form-select" id="mascota-raza" name="id_raza" required>
                            <option value="" selected disabled>Seleccioná una raza</option>
                        </select>
                    </div>
                    <div class="col-12 col-lg-6">
                        <label class="form-label" for="mascota-nombre">Nombre</label>
                        <input class="form-control" type="text" id="mascota-nombre" name="nombre" maxlength="30" required>
                    </div>
                    <div class="col-6 col-lg-3">
                        <label class="form-label" for="mascota-sexo">Sexo</label>
                        <select class="form-select" id="mascota-sexo" name="sexo" required>
                            <option value="">Seleccioná el sexo</option>
                            <option value="macho">Macho</option>
                            <option value="hembra">Hembra</option>
                        </select>
                    </div>
                    <div class="col-6 col-lg-3">
                        <label class="form-label" for="mascota-tamano">Tamaño</label>
                        <select class="form-select" id="mascota-tamano" name="tamaño" required>
                            <option value="">Seleccioná el tamaño</option>
                            <option value="pequeño">Pequeño</option>
                            <option value="mediano">Mediano</option>
                            <option value="grande">Grande</option>
                        </select>
                    </div>
                    <div class="col-12 col-lg-6">
                        <label class="form-label" for="mascota-fecha-nacimiento">Fecha de nacimiento estimada</label>
                        <input class="form-control" type="date" id="mascota-fecha-nacimiento" name="fecha_nacimiento_estimada" max="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="col-12 col-lg-6">
                        <label class="form-label" for="mascota-foto">Foto principal</label>
                        <input type="hidden" id="mascota-foto-actual" name="foto_url">
                        <input class="form-control" type="file" id="mascota-foto" name="foto" accept="image/jpeg,image/png,image/webp">
                        <div class="form-text">JPG, PNG o WebP. Máximo 5 MB.</div>
                    </div>
                    <div class="col-12" id="mascota-foto-bloque" hidden>
                        <img id="mascota-foto-vista" class="mascota-foto-vista" alt="Vista previa de la foto">
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="mascota-fotos-adicionales">Fotos adicionales</label>
                        <input class="form-control" type="file" id="mascota-fotos-adicionales" accept="image/jpeg,image/png,image/webp" multiple>
                        <div id="mascota-fotos-adicionales-ayuda" class="form-text">Hasta 6 fotos adicionales. JPG, PNG o WebP. Máximo 5 MB por foto.</div>
                        <div id="mascota-fotos-adicionales-mensaje" class="form-text" role="status" aria-live="polite"></div>
                        <div id="mascota-fotos-adicionales-lista" class="mascota-fotos-admin" role="group" aria-label="Fotos adicionales actuales"></div>
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="mascota-historial">Historial</label>
                        <textarea class="form-control" id="mascota-historial" name="historial" rows="4"></textarea>
                    </div>
                    <div class="col-12">
                        <p id="mensaje-modal-mascota" class="mensaje-formulario"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="boton-guardar-mascota">Guardar mascota</button>
                </div>
            </form>
        </div>
    </div>

<script src="config.php"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/mascotas-listado.js"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
