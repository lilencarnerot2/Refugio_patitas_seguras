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
        boton.disabled = true;

        enviarFormularioApi(formulario, mensaje, 'login', {
            metodo: 'POST',
            cuerpo: {
                email: document.getElementById('correo').value.trim(),
                password: document.getElementById('clave').value
            }
        })
        .then(function (respuesta) {
            return irTrasSesion(respuesta);
        })
        .catch(function () {
            return null;
        })
        .finally(function () {
            boton.disabled = false;
        });
    });
});
