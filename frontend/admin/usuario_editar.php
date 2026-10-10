<?php
if (empty($_COOKIE['usuario_acceso_superadmin'])) {
    header('Location: /admin/usuarios.php');
    exit;
}

$tituloPagina = 'Editar usuario - Patitas Seguras';
$paginaAdmin = 'usuario_editar';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

    <section id="panel-usuario-editar" data-listado="usuarios.php">
        <div class="page-intro">
            <h2>Editar usuario</h2>
        </div>

        <form id="formulario-usuario" class="formulario-mascota">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="id_usuario">Usuario</label>
                    <select class="form-select" id="id_usuario" name="id_usuario">
                        <option value="" selected disabled hidden></option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="nombre">Nombre</label>
                    <input class="form-control" id="nombre" name="nombre" type="text">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="apellido">Apellido</label>
                    <input class="form-control" id="apellido" name="apellido" type="text">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="fecha_nacimiento">Fecha de nacimiento</label>
                    <input class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" type="date" max="<?php echo date('Y-m-d', strtotime('-12 years -1 day')); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="fecha_alta">Fecha de alta</label>
                    <input class="form-control" id="fecha_alta" name="fecha_alta" type="text">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="nombre_rol">Rol</label>
                    <input class="form-control" id="nombre_rol" name="nombre_rol" type="text">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="id_rol">Id de rol</label>
                    <input class="form-control" id="id_rol" name="id_rol" type="text">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="id">Id</label>
                    <input class="form-control" id="id" name="id" type="text">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="uid_pp2">Uid</label>
                    <input class="form-control" id="uid_pp2" name="uid_pp2" type="text">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="id_domicilio">Id de domicilio</label>
                    <input class="form-control" id="id_domicilio" name="id_domicilio" type="text">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="estado">Estado</label>
                    <input class="form-control" id="estado" name="estado" type="text">
                </div>
            </div>

            <p id="mensaje-formulario" class="mensaje-formulario"></p>

            <div class="acciones-formulario">
                <button type="submit" class="btn-primary">Guardar</button>
                <a class="btn-outline" href="usuarios.php">Usuarios</a>
            </div>
        </form>
    </section>

<script src="config.php"></script>
<script src="../assets/js/api.js?t=<?php echo filemtime(__DIR__ . '/../assets/js/api.js'); ?>"></script>
<script src="../assets/js/usuario-editar.js?t=<?php echo filemtime(__DIR__ . '/../assets/js/usuario-editar.js'); ?>"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
