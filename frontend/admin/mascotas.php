<?php
$tituloPagina = 'Mascotas - Patitas Seguras';
$paginaAdmin = 'mascotas';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <section id="panel-mascotas" class="panel-listado" data-por-pagina="5">
        <p id="mensaje-formulario" class="mensaje-formulario"></p>

        <article class="info-card">
            <div class="dash-card-cabecera">
                <span class="ficha-usuario-icono"><i class="bi bi-paw"></i></span>
                <h3>Mascotas</h3>
            </div>
            <div class="listado-controles row g-2">
                <div class="col-12 col-lg min-w-0">
                    <div class="input-group listado-buscar">
                        <i class="bi bi-search input-group-text"></i>
                        <input type="search" id="buscar-mascotas" class="form-control" placeholder="Buscar" aria-label="Buscar">
                        <button type="button" class="btn btn-outline-secondary" data-limpiar="buscar-mascotas" aria-label="Limpiar búsqueda"><i class="bi bi-x"></i></button>
                    </div>
                </div>
                <div class="col-12 col-md min-w-0">
                    <div class="input-group listado-filtro-grupo" aria-label="Especie">
                        <select id="filtro-especie" class="form-select listado-filtro" aria-label="Especie">
                            <option value="" selected hidden></option>
                        </select>
                        <button type="button" class="btn btn-outline-secondary" data-limpiar="filtro-especie" aria-label="Limpiar filtro de especie"><i class="bi bi-x"></i></button>
                    </div>
                </div>
            </div>
            <div class="lista-scroll-mascotas">
                <table class="dash-tabla">
                    <thead>
                        <tr>
                            <th>Nro</th>
                            <th>Nombre</th>
                            <th>Especie</th>
                            <th>Raza</th>
                            <th>Sexo</th>
                            <th>Tamaño</th>
                            <th>Ver</th>
                            <th>Baja</th>
                        </tr>
                    </thead>
                    <tbody id="cuerpo-mascotas">
                        <tr id="fila-modelo-mascota" hidden>
                            <td data-celda="nro"></td>
                            <td data-celda="nombre"></td>
                            <td data-celda="especie"></td>
                            <td data-celda="raza"></td>
                            <td data-celda="sexo"></td>
                            <td data-celda="tamano"></td>
                            <td><a class="abm-ver" href="ficha_medica.php" aria-label="Ver"></a></td>
                            <td><button type="button" class="abm-baja" aria-label="Baja"></button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <nav class="paginador" data-paginador aria-label="Paginación" hidden>
                <ul class="pagination">
                    <li class="page-item">
                        <a class="page-link" data-pagina-anterior href="#panel-mascotas" aria-label="Página anterior"><i class="bi bi-chevron-left"></i></a>
                    </li>
                    <li class="page-item" id="pagina-modelo-mascotas" hidden>
                        <a class="page-link" href="#panel-mascotas"></a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" data-pagina-siguiente href="#panel-mascotas" aria-label="Página siguiente"><i class="bi bi-chevron-right"></i></a>
                    </li>
                </ul>
            </nav>
            <!-- Modelo oculto con el diseño; el JS lo clona y solo pone datos -->
            <div id="modelos-mascotas" hidden>
                <option id="opcion-modelo-mascotas"></option>
            </div>
        </article>
    </section>

<script src="config.php"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/mascotas-listado.js"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
