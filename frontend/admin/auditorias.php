<?php
$paginaAdmin = 'auditorias';
$recursoUsuarios = 'usuarios';
$detallePhp = 'auditoria_detalle.php';
$formatoFechaHora = 'fecha-hora';
$tituloPagina = $paginaAdmin;
$requiereSuperadministrador = true;
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <section id="panel-auditorias" class="panel-listado" data-por-pagina="10" data-lista-max="8" data-formato-fecha-hora="<?php echo $formatoFechaHora; ?>" data-recurso="<?php echo $paginaAdmin; ?>" data-recurso-usuarios="<?php echo $recursoUsuarios; ?>">
        <p id="mensaje-auditorias" class="mensaje-formulario"></p>

        <article class="info-card">
            <div class="dash-card-cabecera">
                <span class="ficha-usuario-icono"><i class="bi bi-journal-text"></i></span>
                <h3 data-etiqueta="<?php echo $paginaAdmin; ?>"></h3>
            </div>
            <div class="auditoria-listado-ancho">
                <div class="listado-controles auditoria-filtros">
                    <div class="auditoria-filtro-col" data-filtro-col="usuario">
                        <label class="form-label" for="filtro-auditoria-usuario" data-filtro="id_usuario" data-etiqueta="usuario"></label>
                        <div class="auditoria-filtro-campo">
                            <select id="filtro-auditoria-usuario" class="form-select" data-filtro="id_usuario" data-opcion-texto="nombre apellido">
                                <option value="" selected disabled hidden></option>
                            </select>
                            <button type="button" class="btn-limpiar-campo" data-limpiar="filtro-auditoria-usuario"><i class="bi bi-x-lg"></i></button>
                        </div>
                    </div>
                    <div class="auditoria-filtro-col" data-filtro-col="tabla">
                        <label class="form-label" for="filtro-auditoria-tabla" data-filtro="nombre_tabla" data-etiqueta="tabla"></label>
                        <div class="auditoria-filtro-campo">
                            <select id="filtro-auditoria-tabla" class="form-select" data-filtro="nombre_tabla">
                                <option value="" selected disabled hidden></option>
                            </select>
                            <button type="button" class="btn-limpiar-campo" data-limpiar="filtro-auditoria-tabla"><i class="bi bi-x-lg"></i></button>
                        </div>
                    </div>
                    <div class="auditoria-filtro-col" data-filtro-col="accion">
                        <label class="form-label" for="filtro-auditoria-accion" data-filtro="accion" data-etiqueta="accion"></label>
                        <div class="auditoria-filtro-campo">
                            <select id="filtro-auditoria-accion" class="form-select" data-filtro="accion">
                                <option value="" selected disabled hidden></option>
                            </select>
                            <button type="button" class="btn-limpiar-campo" data-limpiar="filtro-auditoria-accion"><i class="bi bi-x-lg"></i></button>
                        </div>
                    </div>
                    <div class="auditoria-filtro-col" data-filtro-col="fecha">
                        <label class="form-label" for="filtro-auditoria-fecha" data-filtro="fecha_desde" data-etiqueta="fecha"></label>
                        <div class="auditoria-filtro-campo">
                            <input type="date" id="filtro-auditoria-fecha" class="form-control" data-filtro="fecha_desde" data-filtro-par="fecha_hasta">
                            <button type="button" class="btn-limpiar-campo" data-limpiar="filtro-auditoria-fecha"><i class="bi bi-x-lg"></i></button>
                        </div>
                    </div>
                </div>
                <div class="lista-scroll-mascotas auditoria-listado-scroll">
                <table class="dash-tabla">
                    <thead>
                        <tr id="cabecera-auditorias">
                            <th data-campo="fecha_hora" data-etiqueta="fecha"></th>
                            <th data-campo="nombre_usuario" data-etiqueta="nombre"></th>
                            <th data-campo="apellido_usuario" data-etiqueta="apellido"></th>
                            <th data-campo="nombre_tabla" data-etiqueta="tabla"></th>
                            <th data-campo="accion" data-etiqueta="accion"></th>
                            <th data-campo="ip_origen" data-etiqueta="ip"></th>
                            <th data-campo="id" data-etiqueta="ver"></th>
                        </tr>
                    </thead>
                    <tbody id="cuerpo-auditorias">
                        <tr id="fila-modelo-auditoria" hidden>
                            <td data-campo="fecha_hora" data-etiqueta="fecha" data-formato="<?php echo $formatoFechaHora; ?>"></td>
                            <td data-campo="nombre_usuario" data-etiqueta="nombre"></td>
                            <td data-campo="apellido_usuario" data-etiqueta="apellido"></td>
                            <td data-campo="nombre_tabla" data-etiqueta="tabla"></td>
                            <td data-campo="accion" data-etiqueta="accion"></td>
                            <td data-campo="ip_origen" data-etiqueta="ip"></td>
                            <td><a class="abm-ver" href="<?php echo $detallePhp; ?>" data-campo="id" data-etiqueta="ver"></a></td>
                        </tr>
                    </tbody>
                </table>
                </div>
            </div>
            <p id="mensaje-vacio-auditorias" class="text-muted" hidden></p>
            <?php
            $paginadorAncla = '#panel-auditorias';
            $paginadorModeloId = 'pagina-modelo-auditorias';
            require __DIR__ . '/includes/paginador.php';
            ?>
            <div id="modelos-auditorias" hidden>
                <option id="opcion-modelo-auditorias"></option>
            </div>
        </article>
    </section>

<script src="config.php"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/auditorias.js"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
