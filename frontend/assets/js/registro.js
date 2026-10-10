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
        var mensaje = formulario.querySelector('.campo-obligatorio');

        if (mensaje) {
            mensaje.textContent = '';
        }

        boton.disabled = true;

        apiRequest('registro', {
            metodo: 'POST',
            cuerpo: {
                nombre: document.getElementById('nombre').value.trim(),
                apellido: document.getElementById('apellido').value.trim(),
                email: document.getElementById('correo').value.trim(),
                password: document.getElementById('clave').value
            }
        })
        .then(function (respuesta) {
            if (mensaje) {
                mensaje.textContent = mensajeDeApi(respuesta);
            }

            return irTrasSesion(respuesta);
        })
        .catch(function (error) {
            if (mensaje) {
                mensaje.textContent = mensajeDeApi(error);
            }
        })
        .finally(function () {
            boton.disabled = false;
        });
    });
});
