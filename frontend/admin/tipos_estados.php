<?php
$tituloPagina = 'Tipos y estados - Patitas Seguras';
$paginaAdmin = 'tipos_estados';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';

$porPagina = 5;
$paginas = 8;
$filas = $porPagina * $paginas;
$opcionesEstado = $paginas;
?>

    <section id="panel-tipos-estados" class="panel-listado">
        <p id="mensaje-formulario" class="mensaje-formulario"></p>

        <div id="listado-tipos">
            <div class="cuadros-catalogo">
                <?php for ($t = 0; $t < $paginas; $t++) { ?>
                <article class="info-card bloque-tipo">
                    <div class="dash-card-cabecera">
                        <span class="ficha-usuario-icono"><i class="bi bi-tags"></i></span>
                        <h3></h3>
                    </div>
                    <div class="lista-scroll-mascotas">
                        <table class="dash-tabla">
                            <thead>
                                <tr>
                                    <th data-campo="id_usuario" data-href="usuario_detalle.php"></th>
                                    <th data-campo="nombre_usuario"></th>
                                    <th data-campo="apellido_usuario"></th>
                                    <th data-campo="estado_solicitud"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php for ($i = 0; $i < $filas; $i++) { ?>
                                <tr>
                                    <td data-campo="id_usuario"><a href="usuario_detalle.php"></a></td>
                                    <td data-campo="nombre_usuario"><a href="usuario_detalle.php"></a></td>
                                    <td data-campo="apellido_usuario"><a href="usuario_detalle.php"></a></td>
                                    <td data-campo="estado_solicitud"><a href="usuario_detalle.php"></a></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="acciones-tipo">
                        <select class="form-select" name="id">
                            <option value="" disabled selected></option>
                            <?php for ($i = 0; $i < $filas; $i++) { ?>
                            <option></option>
                            <?php } ?>
                        </select>
                        <button type="button" class="btn-primary" data-alta></button>
                        <button type="button" class="abm-baja" data-baja></button>
                        <button type="button" class="btn-primary" data-editar></button>
                    </div>
                </article>
                <?php } ?>
            </div>
        </div>

        <div id="listado-estados">
            <div class="cuadros-catalogo">
                <?php for ($e = 0; $e < $paginas; $e++) { ?>
                <article class="info-card bloque-estado">
                    <div class="dash-card-cabecera">
                        <span class="ficha-usuario-icono"><i class="bi bi-flag"></i></span>
                        <h3></h3>
                    </div>
                    <div class="lista-scroll-mascotas">
                        <table class="dash-tabla">
                            <thead>
                                <tr>
                                    <th data-campo="id_usuario" data-href="usuario_detalle.php"></th>
                                    <th data-campo="nombre_usuario"></th>
                                    <th data-campo="apellido_usuario"></th>
                                    <th data-campo="tipo_solicitud"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php for ($i = 0; $i < $filas; $i++) { ?>
                                <tr>
                                    <td data-campo="id_usuario"><a href="usuario_detalle.php"></a></td>
                                    <td data-campo="nombre_usuario"><a href="usuario_detalle.php"></a></td>
                                    <td data-campo="apellido_usuario"><a href="usuario_detalle.php"></a></td>
                                    <td data-campo="tipo_solicitud"><a href="usuario_detalle.php"></a></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <form class="acciones-estado" data-recurso="solicitudes">
                        <select class="form-select" name="id">
                            <option value="" disabled selected></option>
                            <?php for ($i = 0; $i < $filas; $i++) { ?>
                            <option></option>
                            <?php } ?>
                        </select>
                        <div class="opciones-cambio">
                            <?php for ($i = 0; $i < $opcionesEstado; $i++) { ?>
                            <label class="btn-primary"><input type="radio" name="id_estado_solicitud"></label>
                            <?php } ?>
                            <button type="submit" class="btn-primary" data-editar></button>
                        </div>
                    </form>
                </article>
                <?php } ?>
            </div>
        </div>
    </section>

<script src="config.php"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/tipos-estados.js"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
