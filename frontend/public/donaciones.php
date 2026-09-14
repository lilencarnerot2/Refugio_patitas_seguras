<?php
$tituloPagina = 'Donaciones - Patitas Seguras';
$scriptsPagina = ['donaciones.js'];
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <section>
        <div class="page-intro">
            <h2>Donaciones y aportes</h2>
            <p>El refugio registra aportes monetarios y no monetarios. Esta página no procesa pagos: sirve para consultar las opciones y dejar constancia de un aporte.</p>
        </div>

        <div class="row g-4 info-cards">
            <div class="col-md-6">
                <div class="info-card">
                    <h3><i class="fa-solid fa-circle-dollar-to-slot"></i> Aporte monetario</h3>
                    <p>Podés colaborar con un monto único o periódico. Cuando corresponda se registra el método de pago y la frecuencia.</p>
                    <ul>
                        <li>Alias: PATITAS.SEGURAS.MP</li>
                        <li>Transferencia o Mercado Pago</li>
                        <li>El registro queda en el sistema del refugio</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-card">
                    <h3><i class="fa-solid fa-box-open"></i> Aporte no monetario</h3>
                    <p>También recibimos alimento, mantas, insumos de higiene y elementos de cuidado.</p>
                    <ul>
                        <li>Alimento balanceado y pipetas</li>
                        <li>Mantas y camitas en buen estado</li>
                        <li>Artículos de limpieza</li>
                    </ul>
                </div>
            </div>
        </div>

        <form id="form-donacion" class="public-form">
            <h3>Registrar un aporte</h3>
            <p>Completá los datos. El backend guarda la operación cuando el recurso de donaciones esté disponible.</p>

            <div class="mb-3">
                <label class="form-label" for="nombre">Nombre</label>
                <input class="form-control" id="nombre" name="nombre" type="text" required>
            </div>
            <div class="mb-3">
                <label class="form-label" for="tipo">Tipo de donación</label>
                <select class="form-select" id="tipo" name="tipo" required>
                    <option value="monetaria">Monetaria</option>
                    <option value="no_monetaria">No monetaria</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label" for="monto">Monto (si aplica)</label>
                <input class="form-control" id="monto" name="monto" type="number" min="0" step="0.01">
            </div>
            <div class="mb-3">
                <label class="form-label" for="metodo_pago">Método de pago (si aplica)</label>
                <input class="form-control" id="metodo_pago" name="metodo_pago" type="text">
            </div>
            <div class="mb-3">
                <label class="form-label" for="frecuencia">Frecuencia (si aplica)</label>
                <select class="form-select" id="frecuencia" name="frecuencia">
                    <option value="">Única / no aplica</option>
                    <option value="unica">Única</option>
                    <option value="mensual">Mensual</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label" for="detalle">Detalle del aporte</label>
                <textarea class="form-control" id="detalle" name="detalle" rows="3"></textarea>
            </div>
            <button class="btn-donate" type="submit">Enviar registro</button>
            <p id="form-feedback" class="form-feedback" hidden></p>
        </form>
    </section>

<?php require __DIR__ . '/includes/footer.php'; ?>
