function guardarCookie(nombre, valor) {
    document.cookie = nombre + '=' + encodeURIComponent(valor || '') + '; path=/; SameSite=Lax';
}

function borrarCookie(nombre) {
    document.cookie = nombre + '=; path=/; SameSite=Lax; max-age=0';
}

function guardarSesion(usuario, usuarioLocal) {
    guardarCookie('usuario_id', usuario.id);
    guardarCookie('usuario_nombre', usuario.nombre);
    guardarCookie('usuario_apellido', usuario.apellido);
    guardarCookie('usuario_email', usuario.email);
    guardarCookie('usuario_rol', usuario.id_rol);
    guardarCookie('usuario_nombre_rol', usuarioLocal.nombre_rol);
}

function borrarSesion() {
    borrarCookie('usuario');
    borrarCookie('usuario_id');
    borrarCookie('usuario_nombre');
    borrarCookie('usuario_apellido');
    borrarCookie('usuario_email');
    borrarCookie('usuario_rol');
    borrarCookie('usuario_nombre_rol');
}

function destinoSegunRol(nombreRol) {
    if (nombreRol === 'Administrador' || nombreRol === 'Superadministrador') {
        return '/admin/dashboard.php';
    }

    return '../index.php';
}

document.addEventListener('DOMContentLoaded', function () {
    if (new URLSearchParams(window.location.search).get('salir') !== null) {
        borrarSesion();
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

            return apiRequest('usuarios', {
                parametros: { id: usuario.id }
            }).then(function (usuarioRespuesta) {
                var usuarioLocal = usuarioRespuesta.data || {};

                guardarSesion(usuario, usuarioLocal);
                alert(respuesta.message || 'Inicio de sesión exitoso.');
                window.location.href = destinoSegunRol(usuarioLocal.nombre_rol);
            });
        })
        .catch(function (error) {
            avisoFormulario(formulario, mensajeDeApi(error));
        });
    });
});
