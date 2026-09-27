<?php
$tituloPagina = 'Evaluación y gestión - Patitas Seguras';
$paginaAdmin = 'evaluacion';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';

$porPagina = 5;
$paginas = 8;
$filas = $porPagina * $paginas;
?>

    <section id="panel-evaluacion" class="panel-listado">
        <p id="mensaje-formulario" class="mensaje-formulario"></p>

        <article id="bloque-seguimiento" class="info-card">
            <div class="dash-card-cabecera">
                <span class="ficha-usuario-icono"><i class="bi bi-clock-history"></i></span>
                <h3></h3>
            </div>
            <div class="lista-scroll-mascotas">
                <table class="dash-tabla">
                    <thead>
                        <tr id="cabecera-seguimiento">
                            <th id="columna-solicitud-id" data-campo="id" data-href="solicitudes_detalle.php"></th>
                            <th id="columna-solicitud-nombre" data-campo="nombre_usuario"></th>
                            <th id="columna-solicitud-apellido" data-campo="apellido_usuario"></th>
                            <th id="columna-solicitud-tipo" data-campo="tipo_solicitud"></th>
                            <th id="columna-solicitud-estado" data-campo="estado_solicitud"></th>
                            <th id="columna-solicitud-fecha" data-campo="fecha_solicitud"></th>
                        </tr>
                    </thead>
                    <tbody id="cuerpo-seguimiento">
                        <?php for ($i = 0; $i < $filas; $i++) { ?>
                        <tr>
                            <td data-campo="id"><a href="solicitudes_detalle.php"></a></td>
                            <td data-campo="nombre_usuario"><a href="solicitudes_detalle.php"></a></td>
                            <td data-campo="apellido_usuario"><a href="solicitudes_detalle.php"></a></td>
                            <td data-campo="tipo_solicitud"><a href="solicitudes_detalle.php"></a></td>
                            <td data-campo="estado_solicitud"><a href="solicitudes_detalle.php"></a></td>
                            <td data-campo="fecha_solicitud"><a href="solicitudes_detalle.php"></a></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </article>

        <article id="bloque-cambio" class="info-card">
            <div class="dash-card-cabecera">
                <span class="ficha-usuario-icono"><i class="bi bi-flag"></i></span>
                <h3></h3>
            </div>
            <div class="lista-scroll-mascotas">
                <table class="dash-tabla">
                    <thead>
                        <tr>
                            <th data-campo="nombre_usuario"></th>
                            <th data-campo="apellido_usuario"></th>
                            <th data-campo="tipo_solicitud" data-previo></th>
                            <th data-campo="tipo_solicitud"></th>
                            <th data-campo="estado_solicitud" data-previo></th>
                            <th data-campo="estado_solicitud"></th>
                        </tr>
                    </thead>
                    <tbody id="cuerpo-cambio">
                        <?php for ($i = 0; $i < $filas; $i++) { ?>
                        <tr>
                            <td data-campo="nombre_usuario"></td>
                            <td data-campo="apellido_usuario"></td>
                            <td data-campo="tipo_solicitud"></td>
                            <td data-campo="tipo_solicitud"></td>
                            <td data-campo="estado_solicitud" data-previo></td>
                            <td data-campo="estado_solicitud"></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </article>
    </section>

<script src="config.php"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/tipos-estados.js"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
