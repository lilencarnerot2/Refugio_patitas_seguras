function guardarCookie(nombre, valor) {
    document.cookie = nombre + '=' + encodeURIComponent(valor || '') + '; path=/; SameSite=Lax';
}

function borrarCookie(nombre) {
    document.cookie = nombre + '=; path=/; SameSite=Lax; max-age=0';
}

function guardarUsuario(usuario, usuarioLocal, roles) {
    guardarCookie('usuario_id', usuario.id);
    guardarCookie('usuario_nombre', usuario.nombre);
    guardarCookie('usuario_apellido', usuario.apellido);
    guardarCookie('usuario_email', usuario.email);
    guardarCookie('usuario_rol', usuario.id_rol);
    guardarCookie('usuario_nombre_rol', usuarioLocal.nombre_rol);

    if (puedeEntrarAdmin(usuarioLocal.nombre_rol, roles)) {
        guardarCookie('usuario_acceso_admin', '1');
    } else {
        borrarCookie('usuario_acceso_admin');
    }
}

function borrarUsuario() {
    borrarCookie('usuario');
    borrarCookie('usuario_id');
    borrarCookie('usuario_nombre');
    borrarCookie('usuario_apellido');
    borrarCookie('usuario_email');
    borrarCookie('usuario_rol');
    borrarCookie('usuario_nombre_rol');
    borrarCookie('usuario_acceso_admin');
}

function rolEnApi(nombreRol, roles) {
    if (!nombreRol || !roles || !roles.length) {
        return null;
    }

    var i;

    for (i = 0; i < roles.length; i++) {
        if (roles[i] && roles[i].nombre_rol === nombreRol) {
            return roles[i];
        }
    }

    return null;
}

function puedeEntrarAdmin(nombreRol, roles) {
    var rol = rolEnApi(nombreRol, roles);
    var ordenados = rolesOrdenadosDeApi(roles);

    return rol && ordenados.length > 0 && Number(rol.id) !== Number(ordenados[0].id);
}

function destinoSegunRol(nombreRol, roles) {
    if (puedeEntrarAdmin(nombreRol, roles)) {
        return '/admin/dashboard.php';
    }

    return '../index.php';
}

function avisoFormulario(formulario, mensaje) {
    var aviso = formulario.querySelector('.campo-obligatorio');

    if (!aviso) {
        return;
    }

    aviso.textContent = mensaje || '';
}

document.addEventListener('DOMContentLoaded', function () {
    if (new URLSearchParams(window.location.search).get('salir') !== null) {
        borrarUsuario();
        window.location.href = '/index.php';
        return;
    }

    const formulario = document.getElementById('form-login');

    if (!formulario) {
        return;
    }

    const boton = formulario.querySelector('.btn-primary');

    if (!boton) {
        return;
    }

    boton.addEventListener('click', function () {
        const correo = document.getElementById('correo').value.trim();
        const clave = document.getElementById('clave').value;

        avisoFormulario(formulario, '');

        apiRequest('login', {
            metodo: 'POST',
            cuerpo: {
                email: correo,
                password: clave
            }
        })
        .then(function (respuesta) {
            var usuario = (respuesta.data && respuesta.data.usuario) || {};

            return Promise.all([
                apiRequest('usuarios', {
                    parametros: { id: usuario.id }
                }),
                apiRequest('roles')
            ]).then(function (resultados) {
                var usuarioLocal = resultados[0].data || {};
                var roles = resultados[1].data || [];

                guardarUsuario(usuario, usuarioLocal, roles);
                window.location.href = destinoSegunRol(usuarioLocal.nombre_rol, roles);
            });
        })
        .catch(function (error) {
            avisoFormulario(formulario, mensajeDeApi(error));
        });
    });
});
