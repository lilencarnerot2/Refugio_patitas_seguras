<?php
$tituloPagina = 'Contacto - Patitas Seguras';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <section id="panel-contacto" class="panel-aportes">
        <div class="page-intro text-center">
            <h2>Contacto</h2>
            <p>Para contactarte con nosotros completá el siguiente formulario. Tu mensaje le llega directo al mail del refugio y te respondemos a la brevedad. Muchas gracias.</p>
        </div>

        <p id="mensaje-contacto" class="alert text-center mensaje-formulario form-feedback" role="alert" data-aviso-exito="Listo! Tu mensaje fue enviado. Te vamos a responder por mail o teléfono."></p>

        <form id="form-contacto" class="public-form public-form-tarjeta" novalidate autocomplete="off">
            <h3>Escribinos</h3>
            <p class="text-center text-muted small">Los campos con * son obligatorios.</p>

            <div class="row g-3">
                <div class="col-12">
                    <section class="form-seccion">
                        <h3 class="form-seccion-titulo"><i class="bi bi-person"></i> Tus datos</h3>
                        <div class="row g-3">
                            <div class="col-12 col-md-6 min-w-0">
                                <label class="form-label" for="nombre">Nombre</label>
                                <input class="form-control" id="nombre" name="nombre" type="text" maxlength="60" required>
                            </div>
                            <div class="col-12 col-md-6 min-w-0">
                                <label class="form-label" for="apellido">Apellido</label>
                                <input class="form-control" id="apellido" name="apellido" type="text" maxlength="60" required>
                            </div>
                            <div class="col-12 col-md-6 min-w-0">
                                <label class="form-label" for="email">Correo electrónico</label>
                                <input class="form-control" id="email" name="email" type="text" maxlength="120" required>
                            </div>
                            <div class="col-12 col-md-6 min-w-0">
                                <label class="form-label" for="telefono">Teléfono</label>
                                <input class="form-control" id="telefono" name="telefono" type="tel" maxlength="30" required>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="col-12">
                    <section class="form-seccion">
                        <h3 class="form-seccion-titulo"><i class="bi bi-chat-dots"></i> Mensaje</h3>
                        <div class="row g-3">
                            <div class="col-12 min-w-0">
                                <label class="form-label" for="mensaje">¿En qué podemos ayudarte?</label>
                                <textarea class="form-control" id="mensaje" name="mensaje" rows="5" maxlength="1000" required></textarea>
                            </div>
                        </div>
                    </section>
                </div>
            </div>

            <button class="btn-primary" type="submit">Enviar mensaje</button>
        </form>
    </section>

<script src="config.php"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/contacto.js"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
