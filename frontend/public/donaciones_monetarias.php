<?php
$tituloPagina = 'Donación monetaria - Patitas Seguras';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <section id="panel-aportes-sitio" class="panel-aportes">
        <p id="mensaje-aportes" class="alert alert-danger text-center mensaje-formulario" role="alert"></p>

        <div id="bloque-aportes" hidden>
            <div class="page-intro text-center">
                <h2>Donación monetaria</h2>
                <p>Usá un alias para transferir y después registrá el aporte.</p>
            </div>

            <form id="form-movimiento" class="public-form" novalidate autocomplete="off">
                <h3>Registrar donación</h3>
                <div class="row g-3">
                    <div class="col-12 col-md-6 min-w-0">
                        <label class="form-label text-break" for="id_tipo_aporte">Tipo de aporte</label>
                        <select class="form-select w-100" id="id_tipo_aporte" name="id_tipo_aporte" data-catalogo="tipos_aportes" data-incluir="dinero" data-a-seleccionar required>
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
                        <label class="form-label text-break" for="id_metodo_pago">Método de pago</label>
                        <select class="form-select w-100" id="id_metodo_pago" name="id_metodo_pago" data-catalogo="metodos_pago" required>
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
                        <label class="form-label" for="monto">Monto</label>
                        <input class="form-control w-100" id="monto" name="monto" type="number" step="any" min="0" required>
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

            <div class="row g-4 justify-content-center" id="tarjetas-alias">
                <div class="col-12 col-md-6 col-lg-4">
                    <article class="card tarjeta-alias h-100 border-0 rounded-4 shadow-sm text-center" data-medio="Mercado Pago" data-alias="PATITAS.SEGURAS.MP">
                        <div class="card-body d-flex flex-column align-items-center p-4">
                            <i class="bi bi-wallet2 tarjeta-alias-icono" aria-hidden="true"></i>
                            <h3 class="card-title tarjeta-alias-titulo" data-campo="medio">Mercado Pago</h3>
                            <hr class="w-100 my-3">
                            <span class="tarjeta-alias-valor rounded-pill" data-campo="alias">PATITAS.SEGURAS.MP</span>
                            <ul class="list-unstyled small mb-0 mt-3 tarjeta-alias-datos">
                                <li><b>CVU:</b> 0000003100084739201847</li>
                                <li><b>CUIT:</b> 30-71684920-4</li>
                                <li><b>Titular:</b> Asociación Civil Patitas Seguras</li>
                            </ul>
                        </div>
                    </article>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <article class="card tarjeta-alias h-100 border-0 rounded-4 shadow-sm text-center" data-medio="Banco Nación" data-alias="patitas.seguras.nacion">
                        <div class="card-body d-flex flex-column align-items-center p-4">
                            <i class="bi bi-bank tarjeta-alias-icono" aria-hidden="true"></i>
                            <h3 class="card-title tarjeta-alias-titulo" data-campo="medio">Banco Nación</h3>
                            <hr class="w-100 my-3">
                            <span class="tarjeta-alias-valor rounded-pill" data-campo="alias">patitas.seguras.nacion</span>
                            <ul class="list-unstyled small mb-0 mt-3 tarjeta-alias-datos">
                                <li><b>CBU:</b> 0110592730059281048201</li>
                                <li><b>CUIT:</b> 30-71684920-4</li>
                                <li><b>Titular:</b> Refugio Patitas Seguras</li>
                            </ul>
                        </div>
                    </article>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <article class="card tarjeta-alias h-100 border-0 rounded-4 shadow-sm text-center" data-medio="Banco Provincia" data-alias="patitas.refugio.ba">
                        <div class="card-body d-flex flex-column align-items-center p-4">
                            <i class="bi bi-bank2 tarjeta-alias-icono" aria-hidden="true"></i>
                            <h3 class="card-title tarjeta-alias-titulo" data-campo="medio">Banco Provincia</h3>
                            <hr class="w-100 my-3">
                            <span class="tarjeta-alias-valor rounded-pill" data-campo="alias">patitas.refugio.ba</span>
                            <ul class="list-unstyled small mb-0 mt-3 tarjeta-alias-datos">
                                <li><b>CBU:</b> 0140999803100051283920</li>
                                <li><b>CUIT:</b> 30-71684920-4</li>
                                <li><b>Titular:</b> Refugio Patitas Seguras</li>
                            </ul>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </section>

<script src="config.php"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/aportes.js"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
