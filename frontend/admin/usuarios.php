<?php
$tituloPagina = 'Usuarios - Patitas Seguras';
$paginaAdmin = 'usuarios';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <section id="panel-usuarios">
        <p id="mensaje-formulario" class="mensaje-formulario"></p>

        <article class="info-card">
            <div class="dash-card-cabecera">
                <span class="ficha-usuario-icono"><i class="fa-solid fa-users"></i></span>
                <h3>Usuarios</h3>
            </div>
            <div class="lista-scroll-mascotas">
                <table class="dash-tabla">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Uid</th>
                            <th>Id de rol</th>
                            <th>Rol</th>
                            <th>Id de domicilio</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Fecha de nacimiento</th>
                            <th>Fecha de alta</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody id="cuerpo-usuarios"></tbody>
                </table>
            </div>
        </article>
    </section>

<script src="config.php"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/usuarios.js"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
