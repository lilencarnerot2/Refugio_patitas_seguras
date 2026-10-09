<?php
$tituloPagina = 'Baja de mascota - Patitas Seguras';
$paginaAdmin = 'mascota_baja';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <section>
        <div class="page-intro">
            <h2>Dar de baja una mascota</h2>
            <p>La mascota queda desactivada y deja de mostrarse en el catálogo.</p>
        </div>

        <form id="formulario-baja" class="formulario-mascota">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="id_mascota">Mascota</label>
                    <select class="form-select" id="id_mascota" name="id_mascota" required>
                        <option value="" selected disabled hidden>Seleccioná una mascota</option>
                    </select>
                </div>
            </div>

            <p id="mensaje-formulario" class="mensaje-formulario"></p>

            <div class="acciones-formulario">
                <button type="submit" class="btn-primary btn-baja">
                    <i class="bi bi-slash-circle"></i>
                    Dar de baja
                </button>
                <a class="btn-outline" href="../public/mascotas.php">Volver al catálogo</a>
            </div>
        </form>
    </section>

<script src="config.php"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/mascota-baja.js"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
