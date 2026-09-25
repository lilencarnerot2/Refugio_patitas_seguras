function guardarCookie(nombre, valor) {
    document.cookie = nombre + '=' + encodeURIComponent(valor || '') + '; path=/; SameSite=Lax';
}

function guardarSesion(usuario, usuarioLocal) {
    guardarCookie('usuario_id', usuario.id);
    guardarCookie('usuario_nombre', usuario.nombre);
    guardarCookie('usuario_apellido', usuario.apellido);
    guardarCookie('usuario_email', usuario.email);
    guardarCookie('usuario_rol', usuario.id_rol);
    guardarCookie('usuario_nombre_rol', usuarioLocal.nombre_rol);
}

function mensajeDeApi(origen) {
    if (origen && origen.data && origen.data.success === false && origen.data.message) {
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
    const formulario = document.getElementById('form-registro');

    if (!formulario) {
        return;
    }

    const boton = formulario.querySelector('.btn-primary');

    if (!boton) {
        return;
    }

    boton.addEventListener('click', function () {
        const nombre = document.getElementById('nombre').value.trim();
        const apellido = document.getElementById('apellido').value.trim();
        const correo = document.getElementById('correo').value.trim();
        const clave = document.getElementById('clave').value;

        avisoFormulario(formulario, '');

        boton.disabled = true;

        apiRequest('registro', {
            metodo: 'POST',
            cuerpo: {
                nombre: nombre,
                apellido: apellido,
                email: correo,
                password: clave
            }
        })
        .then(function (respuesta) {
            var usuario = (respuesta.data && respuesta.data.usuario) || {};

            return apiRequest('usuarios', {
                parametros: { id: usuario.id }
            }).then(function (usuarioRespuesta) {
                guardarSesion(usuario, usuarioRespuesta.data || {});
                window.location.href = '/index.php';
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
