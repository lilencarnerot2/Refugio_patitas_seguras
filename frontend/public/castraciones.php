<?php
$tituloPagina = 'Jornadas de Castración - Patitas Seguras';
$refugio = require __DIR__ . '/includes/datos_refugio.php';
$enlacesApadrinamiento = $refugio['enlaces_apadrinamiento'];
$opcionesApadrinamiento = [
    [
        'clave' => 'una_castracion',
        'titulo' => '1 castración',
        'boton' => 'Apadrinar'
    ],
    [
        'clave' => 'media_castracion',
        'titulo' => '1/2 castración',
        'boton' => 'Apadrinar'
    ],
    [
        'clave' => 'dos_castraciones',
        'titulo' => '2 castraciones',
        'boton' => 'Apadrinar'
    ]
];
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <section id="panel-castracion" class="panel-aportes">
        <div class="page-intro text-center">
            <h2>Jornadas de Castración</h2>
            <p>La castración y esterilización es el método más eficaz, ético y definitivo para controlar la superpoblación animal, prevenir enfermedades y evitar el abandono.</p>
        </div>

        <div class="info-card info-card-single">
            <h3><i class="bi bi-info-circle"></i> Importante</h3>
            <p>Las castraciones organizadas por Patitas Seguras son <b>a bajo costo hechas con contribución voluntaria</b> para cubrir la práctica, los insumos quirúrgicos y el postoperatorio. Los cupos son limitados y requieren inscripción previa.</p>
            <p>También se suelen organizar campañas de <b>castraciones gratuitas</b> en conjunto con <b>entidades gubernamentales</b>. Los cupos son limitados y requieren inscripción previa.</p>
        </div>

        <div class="info-card info-card-single">
            <h3><i class="bi bi-list-check"></i> Requisitos para la cirugía</h3>
            <ol>
                <li><b>Ayuno obligatorio:</b> El animal debe cumplir con 8 a 12 horas de ayuno absoluto de alimentos sólidos y líquidos.</li>
                <li><b>Edad requerida:</b> Perros y gatos (machos y hembras) a partir de los 6 meses de edad.</li>
                <li><b>Aptitud médica:</b> Deben estar clínicamente sanos el día de la jornada. Si está en celo o preñada, consultá previamente con el equipo veterinario.</li>
                <li><b>Acompañante mayor de edad:</b> Debe asistir una persona mayor de 18 años responsable del animal durante todo el procedimiento.</li>
            </ol>
        </div>

        <div class="info-card info-card-single">
            <h3><i class="bi bi-shield-check"></i> Medidas de traslado e insumos a traer</h3>
            <ul>
                <li><b>Gatos:</b> Deben ser trasladados individualmente en transportadora, bolso con cierre ventilado o funda de almohada (nunca en brazos o sueltos).</li>
                <li><b>Perros:</b> Deben concurrir obligatoriamente con collar, correa y bozal (en caso de ser reactivos o de gran tamaño).</li>
                <li><b>Manta o abrigo:</b> Traé una manta o abrigo limpio para la etapa de recuperación postoperatoria, ya que la anestesia reduce la temperatura corporal.</li>
            </ul>
        </div>

        <div class="info-card info-card-single">
            <h3><i class="bi bi-calendar-check"></i> ¿Cómo solicitar un turno?</h3>
            <p>Publicamos las fechas de las jornadas y la apertura de turnos en nuestras redes sociales y canal de difusión. Para coordinar un turno o consultar fechas disponibles, podés comunicarte directamente con nuestro equipo voluntario.</p>
        </div>

        <div class="page-intro text-center castracion-apadrinamiento-intro">
            <h2>Apadriná castraciones</h2>
            <p>Sé parte del cambio. Elegí cómo colaborar con Mercado Pago.</p>
        </div>

        <div class="row g-4 justify-content-center castracion-apadrinamiento" role="group" aria-label="Opciones para apadrinar castraciones">
            <?php foreach ($opcionesApadrinamiento as $opcion) { ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <article class="card tarjeta-monto h-100 border-0 rounded-4 shadow-sm text-center">
                        <div class="card-body d-flex flex-column align-items-center justify-content-between p-4">
                            <i class="bi bi-heart-fill tarjeta-monto-icono" aria-hidden="true"></i>
                            <span class="tarjeta-monto-valor"><?php echo $opcion['titulo']; ?></span>
                            <?php if ($enlacesApadrinamiento[$opcion['clave']] !== '') { ?>
                                <a class="btn-donate" href="<?php echo $enlacesApadrinamiento[$opcion['clave']]; ?>" target="_blank" rel="noopener noreferrer"><?php echo $opcion['boton']; ?></a>
                            <?php } else { ?>
                                <button class="btn-donate" type="button" disabled><?php echo $opcion['boton']; ?></button>
                            <?php } ?>
                        </div>
                    </article>
                </div>
            <?php } ?>
        </div>
        <?php if (in_array('', $enlacesApadrinamiento, true)) { ?>
            <p class="castracion-pago-pendiente"></p>
        <?php } ?>
    </section>

<?php require __DIR__ . '/includes/footer.php'; ?>
