<?php
$tituloPagina = 'Mi perfil - Patitas Seguras';
$cuerpoClase = 'pagina-perfil';
$paginaPerfil = 'datos';
require __DIR__ . '/../public/includes/head.php';
require __DIR__ . '/includes/header.php';

$datosPersonales = [
    'Nombre' => trim($usuarioPerfil['nombre'] ?? ''),
    'Apellido' => trim($usuarioPerfil['apellido'] ?? ''),
    'Correo electrónico' => trim($usuarioPerfil['email'] ?? '')
];
?>

    <section id="panel-mi-perfil" class="panel-perfil panel-aportes">
        <p id="mensaje-perfil" class="mensaje-formulario"></p>

        <div class="perfil-bienvenida card border-0 rounded-4 mb-4">
            <div class="card-body p-4 d-flex align-items-center justify-content-between gap-3">
                <div>
                    <h2 class="mb-2">¡Hola, <?php echo $usuarioPerfil['nombre'] ?? ''; ?>! <i class="fa-solid fa-paw"></i></h2>
                    <p class="mb-0">Gracias por ser parte de Patitas Seguras.<br>Desde acá podés ver y completar tus datos personales y tu domicilio.</p>
                </div>
                <div class="perfil-bienvenida-ilustracion d-none d-md-flex align-items-end gap-2" aria-hidden="true">
                    <i class="fa-solid fa-dog"></i>
                    <i class="fa-solid fa-cat"></i>
                </div>
            </div>
        </div>

        <div class="row g-4 align-items-start perfil-datos-grid">
            <div class="col-12">
                <article class="card perfil-card border-0 rounded-4 shadow-sm" id="perfil-personales">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="perfil-card-icono rounded-circle d-inline-flex align-items-center justify-content-center"><i class="bi bi-person-vcard"></i></span>
                            <h3 class="h5 mb-0">Datos personales</h3>
                            <span class="perfil-estado">
                                <span data-estado-texto="completo">Obligatorios completos</span>
                                <span data-estado-texto="incompleto">Incompleto</span>
                                <span data-estado-texto="vacio">Sin cargar</span>
                            </span>
                        </div>
                        <p class="mensaje-formulario" data-mensaje></p>
                        <ul class="pet-detail-meta">
<?php foreach ($datosPersonales as $etiqueta => $valor) { ?>
                            <li<?php echo $valor === '' ? ' data-vacio' : ''; ?>>
                                <span class="detalle-label"><?php echo $etiqueta; ?></span>
                                <span data-texto="<?php echo $valor !== '' ? $valor : 'Sin completar'; ?>"></span>
                            </li>
<?php } ?>
                        </ul>
                        <ul class="pet-detail-meta perfil-lista-contacto" data-vista></ul>
                        <p class="perfil-nota small mb-0">Nombre, apellido y correo se cargaron cuando te registraste y no se modifican desde acá. Si necesitás corregirlos, comunicate con el refugio. El teléfono móvil es obligatorio y el fijo es opcional.</p>
                        <button class="btn btn-outline-primary btn-sm mt-3" type="button" data-accion="editar" hidden>
                            <span data-estado-texto="vacio">Cargar datos</span>
                            <span data-estado-texto="incompleto">Completar datos</span>
                            <span data-estado-texto="completo">Modificar datos</span>
                        </button>
                        <form class="public-form perfil-form" novalidate autocomplete="off" hidden>
                            <div class="row g-3" data-campos></div>
                            <div class="perfil-form-acciones">
                                <button class="btn-primary" type="submit">Guardar</button>
                                <button class="btn btn-outline-secondary" type="button" data-accion="cancelar">Cancelar</button>
                            </div>
                        </form>
                    </div>
                </article>
            </div>

            <div class="col-12">
                <article class="card perfil-card border-0 rounded-4 shadow-sm" id="perfil-domicilio">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="perfil-card-icono rounded-circle d-inline-flex align-items-center justify-content-center"><i class="bi bi-geo-alt"></i></span>
                            <h3 class="h5 mb-0">Domicilio</h3>
                            <span class="perfil-estado">
                                <span data-estado-texto="completo">Obligatorios completos</span>
                                <span data-estado-texto="incompleto">Incompleto</span>
                                <span data-estado-texto="vacio">Sin cargar</span>
                            </span>
                        </div>
                        <p class="mensaje-formulario" data-mensaje></p>
                        <ul class="pet-detail-meta" data-vista></ul>
                        <p class="perfil-nota small mb-0">Son obligatorios: tipo de vivienda, provincia, comuna o municipio, localidad, calle, altura y código postal. El resto es opcional.</p>
                        <button class="btn btn-outline-primary btn-sm mt-3" type="button" data-accion="editar" hidden>
                            <span data-estado-texto="vacio">Cargar domicilio</span>
                            <span data-estado-texto="incompleto">Completar domicilio</span>
                            <span data-estado-texto="completo">Modificar domicilio</span>
                        </button>
                        <form class="public-form perfil-form" novalidate autocomplete="off" hidden>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="perfil-tipo-vivienda">Tipo de vivienda</label>
                                    <select class="form-select" id="perfil-tipo-vivienda" name="id_tipo_vivienda" required>
                                        <option value="" selected disabled></option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="perfil-provincia">Provincia</label>
                                    <select class="form-select" id="perfil-provincia" name="id_provincia" required>
                                        <option value="" selected disabled></option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="perfil-comuna">Comuna o municipio</label>
                                    <select class="form-select" id="perfil-comuna" name="id_comuna_municipio" required>
                                        <option value="" selected disabled></option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="perfil-localidad">Localidad</label>
                                    <select class="form-select" id="perfil-localidad" name="id_localidad" required>
                                        <option value="" selected disabled></option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="perfil-barrio">Barrio o zona</label>
                                    <input class="form-control" type="text" id="perfil-barrio" name="nombre_barrio">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="perfil-codigo-postal">Código postal</label>
                                    <input class="form-control" type="text" id="perfil-codigo-postal" name="codigo_postal" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="perfil-calle">Calle</label>
                                    <input class="form-control" type="text" id="perfil-calle" name="calle" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="perfil-altura">Altura</label>
                                    <input class="form-control" type="text" id="perfil-altura" name="altura" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="perfil-piso">Piso</label>
                                    <input class="form-control" type="text" id="perfil-piso" name="piso">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="perfil-departamento">Departamento</label>
                                    <input class="form-control" type="text" id="perfil-departamento" name="departamento">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="perfil-ruta">Ruta</label>
                                    <input class="form-control" type="text" id="perfil-ruta" name="ruta_nombre">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="perfil-kilometro">Kilómetro</label>
                                    <input class="form-control" type="text" id="perfil-kilometro" name="kilometro">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="perfil-entre-calle-1">Entre calle 1</label>
                                    <input class="form-control" type="text" id="perfil-entre-calle-1" name="entre_calle_1">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="perfil-entre-calle-2">Entre calle 2</label>
                                    <input class="form-control" type="text" id="perfil-entre-calle-2" name="entre_calle_2">
                                </div>
                                <div class="col-12">
                                    <label class="form-label" for="perfil-indicadores">Indicadores de referencia</label>
                                    <textarea class="form-control" id="perfil-indicadores" name="indicadores_referencia" rows="2"></textarea>
                                </div>
                            </div>
                            <p class="perfil-nota small mt-3 mb-0">Calle, altura y código postal son obligatorios. Si tu zona no tiene barrios, dejá ese campo vacío.</p>
                            <div class="perfil-form-acciones">
                                <button class="btn-primary" type="submit">Guardar</button>
                                <button class="btn btn-outline-secondary" type="button" data-accion="cancelar">Cancelar</button>
                            </div>
                        </form>
                    </div>
                </article>
            </div>
        </div>

        <article class="card perfil-card border-0 rounded-4 shadow-sm mt-4" id="perfil-cambio-password">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="perfil-card-icono rounded-circle d-inline-flex align-items-center justify-content-center"><i class="bi bi-shield-lock"></i></span>
                    <h3 class="h5 mb-0">Cambiar contraseña</h3>
                </div>
                <button class="btn btn-outline-primary" type="button" data-toggle-cambio-password aria-expanded="false" aria-controls="form-cambio-password">
                    Modificar
                </button>
                <form class="public-form perfil-form mt-3" id="form-cambio-password" data-form-cambio-password autocomplete="off" hidden>
                    <div class="mb-3">
                        <label class="form-label" for="perfil-password-actual">Contraseña actual</label>
                        <input class="form-control" type="password" id="perfil-password-actual" name="current_password" autocomplete="current-password">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="perfil-password-nueva">Nueva contraseña</label>
                        <input class="form-control" type="password" id="perfil-password-nueva" name="new_password" autocomplete="new-password">
                    </div>
                
                    <button class="btn-primary" type="submit" disabled aria-disabled="true">
                        Guardar nueva contraseña
                    </button>
                </form>
            </div>
        </article>
    </section>

<script src="../public/config.php"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/mi-perfil.js"></script>
<script src="../assets/js/perfil-datos.js"></script>
<script src="../assets/js/cambio-password-perfil.js"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
