function guardarCookie(nombre, valor) {
    document.cookie = nombre + '=' + encodeURIComponent(valor || '') + '; path=/; SameSite=Lax';
}

function borrarCookie(nombre) {
    document.cookie = nombre + '=; path=/; SameSite=Lax; max-age=0';
}

function leerCookie(nombre) {
    var partes = document.cookie.split(';');
    var i;
    var parte;
    var prefijo = nombre + '=';

    for (i = 0; i < partes.length; i++) {
        parte = partes[i].replace(/^\s+/, '');

        if (parte.indexOf(prefijo) === 0) {
            return decodeURIComponent(parte.slice(prefijo.length));
        }
    }

    return '';
}

function listaRoles(roles) {
    if (!Array.isArray(roles)) {
        return [];
    }

    return roles;
}

function rolPorId(roles, idRol) {
    var lista = listaRoles(roles);
    var i;

    for (i = 0; i < lista.length; i++) {
        if (lista[i] && String(lista[i].id) === String(idRol)) {
            return lista[i];
        }
    }

    return null;
}

function rolBase(roles) {
    var lista = listaRoles(roles);

    if (!lista[0]) {
        return null;
    }

    return lista[0];
}

function rolSuperior(roles) {
    var lista = listaRoles(roles);

    if (lista.length < 2) {
        return null;
    }

    return lista[lista.length - 1];
}

function esRolPersonal(roles, idRol) {
    var rol = rolPorId(roles, idRol);
    var base = rolBase(roles);

    return !!(rol && base && String(rol.id) !== String(base.id));
}

function esRolSuperior(roles, idRol) {
    var rol = rolPorId(roles, idRol);
    var superior = rolSuperior(roles);

    return !!(rol && superior && String(rol.id) === String(superior.id));
}

function guardarSesion(usuario, usuarioLocal, roles) {
    guardarCookie('usuario_id', usuario.id);
    guardarCookie('usuario_nombre', usuario.nombre);
    guardarCookie('usuario_apellido', usuario.apellido);
    guardarCookie('usuario_email', usuario.email);
    guardarCookie('usuario_rol', usuarioLocal.id_rol);
    guardarCookie('usuario_nombre_rol', usuarioLocal.nombre_rol);

    if (esRolPersonal(roles, usuarioLocal.id_rol)) {
        guardarCookie('usuario_acceso_admin', '1');
    } else {
        borrarCookie('usuario_acceso_admin');
    }

    if (esRolSuperior(roles, usuarioLocal.id_rol)) {
        guardarCookie('usuario_acceso_superadmin', '1');
    } else {
        borrarCookie('usuario_acceso_superadmin');
    }
}

function borrarSesion() {
    borrarCookie('usuario');
    borrarCookie('usuario_id');
    borrarCookie('usuario_nombre');
    borrarCookie('usuario_apellido');
    borrarCookie('usuario_email');
    borrarCookie('usuario_rol');
    borrarCookie('usuario_nombre_rol');
    borrarCookie('usuario_acceso_admin');
    borrarCookie('usuario_acceso_superadmin');
}

function destinoSegunRol(usuarioLocal, roles) {
    if (esRolPersonal(roles, usuarioLocal.id_rol)) {
        return '/admin/dashboard.php';
    }

    return '/index.php';
}

function mensajeDeApi(origen) {
    if (origen && origen.message) {
        return origen.message;
    }

    if (origen && origen.data && origen.data.message) {
        return origen.data.message;
    }

    return '';
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
        borrarSesion();
        window.location.href = '/index.php';
        return;
    }

    if (leerCookie('usuario_id') && leerCookie('usuario_nombre_rol')) {
        if (leerCookie('usuario_acceso_admin') === '1') {
            window.location.href = '/admin/dashboard.php';
        } else {
            window.location.href = '/index.php';
        }
        return;
    }

    var formulario = document.getElementById('form-login');

    if (!formulario) {
        return;
    }

    var boton = formulario.querySelector('.btn-primary');

    if (!boton) {
        return;
    }

    boton.addEventListener('click', function () {
        var correo = document.getElementById('correo').value.trim();
        var clave = document.getElementById('clave').value;

        avisoFormulario(formulario, '');
        boton.disabled = true;

        apiRequest('login', {
            metodo: 'POST',
            cuerpo: {
                email: correo,
                password: clave
            }
        })
        .then(function (respuesta) {
            var usuario = (respuesta.data && respuesta.data.usuario) || {};

            return apiRequest('usuarios', {
                parametros: { id: usuario.id }
            }).then(function (usuarioRespuesta) {
                var usuarioLocal = usuarioRespuesta.data || {};

                return apiRequest('roles').then(function (rolesRespuesta) {
                    var roles = rolesRespuesta.data || [];

                    guardarSesion(usuario, usuarioLocal, roles);
                    window.location.href = destinoSegunRol(usuarioLocal, roles);
                });
            });
        })
        .catch(function (error) {
            avisoFormulario(formulario, mensajeDeApi(error));
        })
        .finally(function () {
            boton.disabled = false;
        });
    });
});
