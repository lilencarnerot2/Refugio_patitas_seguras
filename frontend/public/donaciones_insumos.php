<?php
$tituloPagina = 'Donación de insumos - Patitas Seguras';
$refugio = require __DIR__ . '/includes/datos_refugio.php';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <section id="panel-aportes-sitio" class="panel-aportes">
        <div class="page-intro text-center">
            <h2>Donación de insumos</h2>
            <p>Mirá qué necesitamos y registrá tu aporte.</p>
        </div>

        <p id="mensaje-aportes" class="alert alert-danger text-center mensaje-formulario" role="alert"></p>

        <div class="row g-4" id="insumos-necesarios">
            <div class="col-12">
                <h3 class="insumos-titulo">Insumos que se necesitan</h3>
            </div>
<?php foreach ($refugio['insumos'] as $categoria) { ?>
            <div class="col-12 col-md-6">
                <article class="info-card h-100">
                    <h3><i class="bi <?php echo htmlspecialchars($categoria['icono'], ENT_QUOTES, 'UTF-8'); ?>" aria-hidden="true"></i> <?php echo htmlspecialchars($categoria['titulo'], ENT_QUOTES, 'UTF-8'); ?></h3>
                    <ul>
<?php foreach ($categoria['items'] as $insumo) { ?>
                        <li><?php echo htmlspecialchars($insumo, ENT_QUOTES, 'UTF-8'); ?></li>
<?php } ?>
                    </ul>
                </article>
            </div>
<?php } ?>
        </div>
        <p class="insumos-nota">Para coordinar la entrega de los insumos escribinos desde <a href="/public/contacto.php">Contacto</a>.</p>

        <div id="bloque-aportes" hidden>
            <form id="form-movimiento" class="public-form" novalidate autocomplete="off">
                <h3>Registrar insumos</h3>
                <input type="hidden" name="fecha" data-fecha-hoy>
                <div class="row g-3">
                    <div class="col-12 min-w-0">
                        <label class="form-label text-break" for="id_tipo_aporte">Tipo de aporte</label>
                        <select class="form-select w-100" id="id_tipo_aporte" name="id_tipo_aporte" data-catalogo="tipos_aportes" data-excluir="dinero" data-a-seleccionar required>
                            <option value="" selected hidden></option>
                        </select>
                    </div>
                    <div hidden>
                        <select id="id_tipo_movimiento" name="id_tipo_movimiento" data-catalogo="tipos_movimientos_aportes" data-excluir="egreso" data-a-seleccionar required>
                            <option value="" selected hidden></option>
                        </select>
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
