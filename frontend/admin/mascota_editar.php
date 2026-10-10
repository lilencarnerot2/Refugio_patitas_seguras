<?php
$tituloPagina = 'Editar mascota - Patitas Seguras';
$paginaAdmin = 'mascota_editar';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <section>
        <div class="page-intro">
            <h2>Editar mascota</h2>
            <p>Visualizá y modificá los datos actuales de la mascota.</p>
        </div>

        <form id="formulario-mascota" class="formulario-mascota">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="id_mascota">Mascota</label>
                    <select class="form-select" id="id_mascota" name="id_mascota" required>
                        <option value="" selected disabled hidden>Seleccioná una mascota</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="id_especie">Especie</label>
                    <select class="form-select" id="id_especie" name="id_especie" required>
                        <option value="" selected disabled>Seleccioná una especie</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="id_raza">Raza</label>
                    <select class="form-select" id="id_raza" name="id_raza" required>
                        <option value="" selected disabled>Seleccioná una raza</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="nombre">Nombre</label>
                    <input class="form-control" type="text" id="nombre" name="nombre" maxlength="30" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="sexo">Sexo</label>
                    <select class="form-select" id="sexo" name="sexo" required>
                        <option value="">Seleccioná el sexo</option>
                        <option value="macho">Macho</option>
                        <option value="hembra">Hembra</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="tamano">Tamaño</label>
                    <select class="form-select" id="tamano" name="tamaño" required>
                        <option value="">Seleccioná el tamaño</option>
                        <option value="pequeño">Pequeño</option>
                        <option value="mediano">Mediano</option>
                        <option value="grande">Grande</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="fecha_nacimiento_estimada">Fecha de nacimiento estimada</label>
                    <input class="form-control" type="date" id="fecha_nacimiento_estimada" name="fecha_nacimiento_estimada" max="<?php echo date('Y-m-d'); ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="foto_url">Foto (URL)</label>
                    <input class="form-control" type="url" id="foto_url" name="foto_url" maxlength="500">
                </div>

                <div class="col-12">
                    <label class="form-label" for="historial">Historial</label>
                    <textarea class="form-control" id="historial" name="historial" rows="4"></textarea>
                </div>
            </div>

            <p id="mensaje-formulario" class="mensaje-formulario"></p>

            <div class="acciones-formulario">
                <button type="submit" class="btn-primary">
                    <i class="bi bi-pencil-square"></i>
                    Editar mascota
                </button>
                <a class="btn-outline" href="../public/mascotas.php">Volver al catálogo</a>
            </div>
        </form>
    </section>

<script src="config.php"></script>
<script src="../assets/js/api.js?t=<?php echo filemtime(__DIR__ . '/../assets/js/api.js'); ?>"></script>
<script src="../assets/js/mascota-editar.js?t=<?php echo filemtime(__DIR__ . '/../assets/js/mascota-editar.js'); ?>"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
