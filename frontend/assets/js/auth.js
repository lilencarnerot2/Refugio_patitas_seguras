document.addEventListener('DOMContentLoaded', function () {
    if (new URLSearchParams(window.location.search).get('salir') !== null) {
        borrarSesion();

        // el inicio lo trae config.php
        if (typeof INICIO_URL !== 'undefined' && INICIO_URL) {
            window.location.href = INICIO_URL;
        }

        return;
    }

    var boton = document.querySelector('#form-login .btn-primary, form .btn-primary');

    if (!boton) {
        return;
    }

    var formulario = boton.closest('form');
    var mensaje = (formulario && formulario.querySelector('.mensaje-formulario')) || document.querySelector('.mensaje-formulario');

    boton.addEventListener('click', function () {
        if (mensaje) {
            avisoDeApi(mensaje, '');
        }

        boton.disabled = true;

        apiRequest('login', {
            metodo: 'POST',
            cuerpo: {
                email: document.getElementById('correo').value.trim(),
                password: document.getElementById('clave').value
            }
        })
        .then(function (respuesta) {
            if (mensaje) {
                avisoDeApi(mensaje, respuesta);
            }

            return irTrasSesion(respuesta);
        })
        .catch(function (error) {
            if (mensaje) {
                avisoDeApi(mensaje, error);
            }
        })
        .finally(function () {
            boton.disabled = false;
        });
    });
});
