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

document.addEventListener('DOMContentLoaded', function () {
    const formulario = document.getElementById('form-registro');

    if (!formulario) {
        return;
    }

    const boton = formulario.querySelector('button[type="button"]');

    if (!boton) {
        return;
    }

    boton.addEventListener('click', function () {
        const nombre = document.getElementById('nombre').value.trim();
        const apellido = document.getElementById('apellido').value.trim();
        const correo = document.getElementById('correo').value.trim();
        const clave = document.getElementById('clave').value;

        if (!nombre || !apellido || !correo || !clave) {
            alert('Completá todos los campos.');
            return;
        }

        if (clave.length < 8) {
            alert('La contraseña debe tener al menos 8 caracteres.');
            return;
        }

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
                alert(respuesta.message || 'Usuario registrado correctamente.');
                window.location.href = '/index.php';
            });
        })
        .catch(function (error) {
            alert(error.message || 'No se pudo registrar el usuario.');
        })
        .finally(function () {
            boton.disabled = false;
        });
    });
});
