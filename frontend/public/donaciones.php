<?php
$tituloPagina = 'Donaciones - Patitas Seguras';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <section id="panel-aportes-sitio" class="panel-aportes">
        <div class="page-intro">
            <h2>Donaciones y aportes</h2>
            <p>Registrá un aporte monetario o no monetario.</p>
        </div>

        <p id="mensaje-aportes" class="mensaje-formulario"></p>

        <form id="form-movimiento" class="public-form" novalidate>
            <h3>Registrar un aporte</h3>
            <p class="form-text"> Completarlos campos obligatorios.</p>

            <div class="row g-3">
                <div class="col-12 col-md-6 min-w-0">
                    <label class="form-label text-break" for="id_tipo_aporte">Tipo de aporte</label>
                    <select class="form-select w-100" id="id_tipo_aporte" name="id_tipo_aporte" data-catalogo="tipos_aportes" data-a-seleccionar required>
                        <option value="" selected hidden></option>
                    </select>
                </div>
                <div class="col-12 col-md-6 min-w-0">
                    <label class="form-label text-break" for="id_tipo_movimiento_aporte">Tipo de movimiento</label>
                    <select class="form-select w-100" id="id_tipo_movimiento_aporte" name="id_tipo_movimiento_aporte" data-catalogo="tipos_movimientos_aportes" data-a-seleccionar required>
                        <option value="" selected hidden></option>
                    </select>
                </div>
                <div class="col-12 col-md-6 min-w-0">
                    <label class="form-label text-break" for="id_frecuencia_aporte">Frecuencia</label>
                    <select class="form-select w-100" id="id_frecuencia_aporte" name="id_frecuencia_aporte" data-catalogo="frecuencia_aportes" data-a-seleccionar required>
                        <option value="" selected hidden></option>
                    </select>
                </div>
                <div class="col-12 col-md-6 min-w-0">
                    <label class="form-label text-break" for="id_metodo_pago">Método de pago</label>
                    <select class="form-select w-100" id="id_metodo_pago" name="id_metodo_pago" data-catalogo="metodos_pago">
                        <option value="" selected hidden></option>
                    </select>
                </div>
                <div class="col-12 col-md-6 min-w-0" data-depende="id_metodo_pago" hidden>
                    <label class="form-label" for="monto">Monto</label>
                    <input class="form-control w-100" id="monto" name="monto" type="number" step="any" min="0" disabled>
                </div>
            </div>

            <button class="btn-donate mt-4" type="submit">Enviar registro</button>
        </form>
    </section>

<script src="config.php"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/aportes.js"></script>
<script src="../assets/js/donaciones.js"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
