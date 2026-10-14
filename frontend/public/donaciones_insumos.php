<?php
$tituloPagina = 'Donación de insumos - Patitas Seguras';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <section id="panel-aportes-sitio" class="panel-aportes">
        <p id="mensaje-aportes" class="alert alert-danger text-center mensaje-formulario" role="alert"></p>

        <div id="bloque-aportes" hidden>
            <div class="page-intro text-center">
                <h2>Donación de insumos</h2>
                <p>Registrá un aporte no monetario.</p>
            </div>

            <form id="form-movimiento" class="public-form" novalidate autocomplete="off">
                <h3>Registrar insumos</h3>
                <div class="row g-3">
                    <div class="col-12 col-md-6 min-w-0">
                        <label class="form-label text-break" for="id_tipo_aporte">Tipo de aporte</label>
                        <select class="form-select w-100" id="id_tipo_aporte" name="id_tipo_aporte" data-catalogo="tipos_aportes" data-excluir="dinero" data-a-seleccionar required>
                            <option value="" selected hidden></option>
                        </select>
                    </div>
                    <div class="col-12 col-md-6 min-w-0">
                        <label class="form-label text-break" for="id_tipo_movimiento">Tipo de movimiento</label>
                        <select class="form-select w-100" id="id_tipo_movimiento" name="id_tipo_movimiento" data-catalogo="tipos_movimientos_aportes" data-excluir="egreso" data-a-seleccionar required>
                            <option value="" selected hidden></option>
                        </select>
                    </div>
                    <div class="col-12 col-md-6 min-w-0">
                        <label class="form-label text-break" for="id_frecuencia_aporte">Frecuencia</label>
                        <select class="form-select w-100" id="id_frecuencia_aporte" name="id_frecuencia_aporte" data-catalogo="frecuencia_aportes">
                            <option value="" selected hidden></option>
                        </select>
                    </div>
                    <div class="col-12 col-md-6 min-w-0">
                        <label class="form-label" for="fecha">Fecha</label>
                        <input class="form-control w-100" id="fecha" name="fecha" type="date">
                    </div>
                    <div class="col-12 min-w-0">
                        <label class="form-label" for="descripcion">Descripción</label>
                        <textarea class="form-control w-100" id="descripcion" name="descripcion" rows="2" maxlength="1000"></textarea>
                    </div>
                </div>
                <button class="btn-donate mt-4" type="submit">Enviar registro</button>
            </form>
        </div>
    </section>

<script src="config.php"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/aportes.js"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
