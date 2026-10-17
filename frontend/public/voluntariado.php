<?php
$tituloPagina = 'Voluntariado - Patitas Seguras';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <section id="panel-voluntariado" class="panel-aportes">
        <div class="page-intro text-center">
            <h2>Voluntariado</h2>
            <p>Anotate a un turno. Si la postulación se envía bien, te llega un mail con los datos, la dirección, las indicaciones y el WhatsApp del refugio.</p>
        </div>

        <div class="info-card info-card-single">
            <h3><i class="fa-solid fa-hand-holding-heart"></i> ¿Que tareas realiza un voluntario?</h3>
            <p>Completar este formulario no implica quedar registrado para comunicaciones periódicas.</p>
            <ul>
                <li>Paseos, socialización y cuidado en el refugio.</li>
                <li>Apoyo en limpieza, alimentacion, curaciones .</li>
                <li>Si no podés asistir, avisá por WhatsApp al refugio.</li>
            </ul>
        </div>

        <p id="mensaje-voluntariado" class="alert text-center mensaje-formulario form-feedback" role="alert" data-aviso-exito="Listo! Si no te llega el mail en unos minutos, fijate en spam."></p>

        <form id="form-voluntariado" class="public-form public-form-tarjeta" novalidate autocomplete="off">
            <h3>Inscripción</h3>
            <p class="text-center text-muted small">Completá los campos con * son obligatorios.</p>

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
                                <label class="form-label" for="telefono">Teléfono / WhatsApp</label>
                                <input class="form-control" id="telefono" name="telefono" type="tel" maxlength="30" required>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="col-12">
                    <section class="form-seccion">
                        <h3 class="form-seccion-titulo"><i class="bi bi-calendar-week"></i> Turno</h3>
                        <div class="row g-3 turno-voluntariado">
                            <div class="col-12 min-w-0">
                                <label class="form-label" for="fecha">Fecha</label>
                                <input type="hidden" id="fecha" name="fecha" required>
                                <div class="card shadow-sm border" id="calendario-voluntariado">
                                    <div class="card-header bg-white py-2 border-bottom-0">
                                        <div class="d-flex align-items-center justify-content-between gap-2">
                                            <button class="btn btn-sm btn-outline-secondary rounded-circle calendario-nav" type="button" data-mes-anterior aria-label="Mes anterior"><i class="bi bi-chevron-left"></i></button>
                                            <span class="fw-semibold text-capitalize text-center text-nowrap flex-grow-1 mb-0 text-success" data-mes-titulo></span>
                                            <button class="btn btn-sm btn-outline-secondary rounded-circle calendario-nav" type="button" data-mes-siguiente aria-label="Mes siguiente"><i class="bi bi-chevron-right"></i></button>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0 px-2 pb-2">
                                        <div class="calendario-semana text-center small fw-semibold text-muted mb-1" data-calendario-cabecera></div>
                                        <div class="calendario-semana text-center" data-calendario-grid role="grid" aria-label="Elegí una fecha disponible"></div>
                                        <p class="small text-muted mb-0 mt-2">Tocá un día disponible según los turnos del refugio.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 min-w-0">
                                <label class="form-label" for="rango_horario">Rango horario</label>
                                <select class="form-select" id="rango_horario" name="rango_horario" data-a-seleccionar required aria-describedby="ayuda-rango-horario">
                                    <option value="" selected hidden></option>
                                </select>
                                <p class="turno-horario-ayuda small text-muted mb-0 mt-1" id="ayuda-rango-horario">Elegí una fecha en el calendario para habilitar el horario.</p>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="col-12">
                    <section class="form-seccion">
                        <h3 class="form-seccion-titulo"><i class="bi bi-heart"></i> Experiencia y trato con perros/gatos</h3>
                        <p class="small text-muted">Contanos tu experiencia y con qué tareas te sentís cómodo/a. No son obligatorias.</p>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-check" for="experiencia_perros_gatos">
                                    <input class="form-check-input" type="checkbox" id="experiencia_perros_gatos" name="experiencia_perros_gatos">
                                    <span>¿Ya conviviste, cuidaste o adoptaste perros o gatos?</span>
                                </label>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="observaciones">¿Cómo describirías tu experiencia y tu trato con perros y gatos?</label>
                                <textarea class="form-control" id="observaciones" name="observaciones" rows="3" maxlength="1000"></textarea>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-check" for="puede_pasearlos">
                                    <input class="form-check-input" type="checkbox" id="puede_pasearlos" name="puede_pasearlos">
                                    <span>¿Podés pasear perros?</span>
                                </label>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-check" for="puede_limpiar">
                                    <input class="form-check-input" type="checkbox" id="puede_limpiar" name="puede_limpiar">
                                    <span>¿Podés ayudar a limpiar espacios del refugio?</span>
                                </label>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-check" for="puede_colaborar_alimento">
                                    <input class="form-check-input" type="checkbox" id="puede_colaborar_alimento" name="puede_colaborar_alimento">
                                    <span>¿Podés colaborar con alimento y agua?</span>
                                </label>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-check" for="puede_manipular_grandes">
                                    <input class="form-check-input" type="checkbox" id="puede_manipular_grandes" name="puede_manipular_grandes">
                                    <span>¿Te sentís cómodo/a con animales grandes?</span>
                                </label>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="limitaciones">¿Tenés alguna limitación que debamos tener en cuenta?</label>
                                <textarea class="form-control" id="limitaciones" name="limitaciones" rows="2" maxlength="500"></textarea>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="col-12">
                    <section class="form-seccion">
                        <h3 class="form-seccion-titulo"><i class="bi bi-clipboard-check"></i> Confirmaciones</h3>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-check" for="acepta_indicaciones">
                                    <input class="form-check-input" type="checkbox" id="acepta_indicaciones" name="acepta_indicaciones" data-requerido>
                                    <span>Acepto las indicaciones del refugio</span>
                                </label>
                            </div>
                            <div class="col-12">
                                <label class="form-check" for="acepta_avisar">
                                    <input class="form-check-input" type="checkbox" id="acepta_avisar" name="acepta_avisar" data-requerido>
                                    <span>Aviso si no puedo asistir</span>
                                </label>
                            </div>
                        </div>
                    </section>
                </div>
            </div>

            <button class="btn-primary" type="submit">Enviar inscripción</button>
        </form>
    </section>

<script src="config.php"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/voluntariado.js"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
