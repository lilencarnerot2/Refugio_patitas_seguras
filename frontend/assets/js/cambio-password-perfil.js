document.addEventListener('DOMContentLoaded', function () {
    var seccion = document.getElementById('perfil-cambio-password');

    if (!seccion) {
        return;
    }

    var boton = seccion.querySelector('[data-toggle-cambio-password]');
    var formulario = seccion.querySelector('[data-form-cambio-password]');
    var mensaje = seccion.querySelector('[data-mensaje-cambio-password]');
    var campoEmail = seccion.querySelector('[name="email"]');
    var botonEnviar;

    if (!boton || !formulario || !mensaje || !campoEmail) {
        return;
    }

    boton.addEventListener('click', function () {
        var expandido = boton.getAttribute('aria-expanded') === 'true';

        boton.setAttribute('aria-expanded', String(!expandido));
        formulario.hidden = expandido;
        boton.textContent = expandido ? 'Modificar' : 'Cancelar';
    });

    formulario.addEventListener('submit', function (evento) {
        evento.preventDefault();

        botonEnviar = formulario.querySelector('[type="submit"]');
        botonEnviar.disabled = true;

        enviarFormularioApi(formulario, mensaje, 'password-change-request', {
            metodo: 'POST',
            cuerpo: {
                email: campoEmail.value.trim()
            }
        }).catch(function () {
            return null;
        }).finally(function () {
            botonEnviar.disabled = false;
        });
    });
});
