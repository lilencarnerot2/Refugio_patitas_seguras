<?php
$tituloPagina = 'Usuarios - Patitas Seguras';
$paginaAdmin = 'usuarios';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <section id="panel-usuarios" class="panel-listado" data-por-pagina="5">
        <p id="mensaje-formulario" class="mensaje-formulario"></p>

        <article class="info-card">
            <div class="dash-card-cabecera">
                <span class="ficha-usuario-icono"><i class="bi bi-people-fill"></i></span>
                <h3></h3>
            </div>
            <div class="listado-controles row g-2">
                <div class="col-12 col-lg min-w-0">
                    <div class="input-group listado-buscar">
                        <i class="bi bi-search input-group-text"></i>
                        <input type="search" id="buscar-usuarios" class="form-control" placeholder="Buscar" aria-label="Buscar">
                        <button type="button" class="btn btn-outline-secondary" data-limpiar="buscar-usuarios" aria-label="Limpiar búsqueda"><i class="bi bi-x"></i></button>
                    </div>
                </div>
                <div class="col-12 col-md min-w-0">
                    <div class="input-group listado-filtro-grupo" aria-label="Rol">
                        <select id="filtro-rol" class="form-select listado-filtro" aria-label="Rol">
                            <option value="" selected hidden></option>
                        </select>
                        <button type="button" class="btn btn-outline-secondary" data-limpiar="filtro-rol" aria-label="Limpiar filtro de rol"><i class="bi bi-x"></i></button>
                    </div>
                </div>
                <div class="col-12 col-md min-w-0">
                    <div class="input-group listado-filtro-grupo" aria-label="Estado">
                        <select id="filtro-estado" class="form-select listado-filtro" aria-label="Estado">
                            <option value="" selected hidden></option>
                            <option value="true">Activo</option>
                            <option value="false">Inactivo</option>
                        </select>
                        <button type="button" class="btn btn-outline-secondary" data-limpiar="filtro-estado" aria-label="Limpiar filtro de estado"><i class="bi bi-x"></i></button>
                    </div>
                </div>
            </div>
            <div class="lista-scroll-mascotas">
                <table class="dash-tabla">
                    <thead>
                        <tr id="cabecera-usuarios">
                            <th id="columna-nro"></th>
                            <th id="columna-nombre"></th>
                            <th id="columna-apellido"></th>
                            <th id="columna-nacimiento"></th>
                            <th id="columna-alta"></th>
                            <th id="columna-rol"></th>
                            <th id="columna-estado"></th>
                            <th id="columna-usuario-ver"></th>
                            <?php if ($esSuperadministrador) { ?>
                            <th id="columna-usuario-editar"></th>
                            <th id="columna-usuario-alta"></th>
                            <th id="columna-usuario-baja"></th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody id="cuerpo-usuarios">
                        <tr id="fila-modelo-usuario" hidden>
                            <td data-campo="id"></td>
                            <td data-campo="nombre"></td>
                            <td data-campo="apellido"></td>
                            <td data-campo="fecha_nacimiento"></td>
                            <td data-campo="fecha_alta"></td>
                            <td data-campo="nombre_rol"></td>
                            <td data-campo="estado"></td>
                            <td><a class="abm-ver" href="usuario_detalle.php" aria-label="Ver"></a></td>
                            <?php if ($esSuperadministrador) { ?>
                            <td><a class="abm-editar" href="usuario_editar.php" aria-label="Editar"></a></td>
                            <td><button type="button" class="abm-alta" disabled aria-label="Alta"></button></td>
                            <td><button type="button" class="abm-baja" aria-label="Baja"></button></td>
                            <?php } ?>
                        </tr>
                    </tbody>
                </table>
            </div>
            <nav class="paginador" data-paginador aria-label="Paginación" hidden>
                <ul class="pagination">
                    <li class="page-item">
                        <a class="page-link" data-pagina-anterior href="#panel-usuarios" aria-label="Página anterior"><i class="bi bi-chevron-left"></i></a>
                    </li>
                    <li class="page-item" id="pagina-modelo-usuarios" hidden>
                        <a class="page-link" href="#panel-usuarios"></a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" data-pagina-siguiente href="#panel-usuarios" aria-label="Página siguiente"><i class="bi bi-chevron-right"></i></a>
                    </li>
                </ul>
            </nav>
            <!-- Modelo oculto con el diseño; el JS lo clona y solo pone datos -->
            <div id="modelos-usuarios" hidden>
                <option id="opcion-modelo-usuarios"></option>
            </div>
        </article>
    </section>

<script src="config.php"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/usuarios.js"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
