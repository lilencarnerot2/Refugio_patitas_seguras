document.addEventListener('DOMContentLoaded', function () {
    var formulario = document.getElementById('form-contacto');
    var aviso = document.getElementById('mensaje-contacto');
    var boton;

    if (!formulario) {
        return;
    }

    boton = formulario.querySelector('.btn-primary');
    pegarDatosSesion();

    formulario.addEventListener('submit', function (evento) {
        evento.preventDefault();

        if (boton && boton.disabled) {
            return;
        }

        if (boton) {
            boton.disabled = true;
        }

        enviarFormularioApi(formulario, aviso, 'contacto', {
            metodo: 'POST',
            cuerpo: armarCuerpo()
        }).then(function (respuesta) {
            completarAvisoExito(respuesta);
            formulario.reset();
            pegarDatosSesion();
        }).catch(function () {
            return null;
        }).finally(function () {
            if (boton) {
                boton.disabled = false;
            }
        });
    });

    function completarAvisoExito(respuesta) {
        var texto;

        if (!aviso || mensajeDeApi(respuesta).trim()) {
            return;
        }

        texto = aviso.getAttribute('data-aviso-exito');

        if (!texto) {
            return;
        }

        aviso.textContent = texto.trim();
        aviso.setAttribute('data-estado', 'ok');
        aviso.removeAttribute('aria-busy');
    }

    function pegarDatosSesion() {
        var campos = {
            nombre: valorCookie('usuario_nombre'),
            apellido: valorCookie('usuario_apellido'),
            email: valorCookie('usuario_email')
        };
        var id;
        var campo;

        for (id in campos) {
            if (!Object.prototype.hasOwnProperty.call(campos, id)) {
                continue;
            }

            campo = formulario.elements[id];

            if (campo && !campo.value && campos[id]) {
                campo.value = campos[id];
            }
        }
    }

    function armarCuerpo() {
        var datos = {};
        var i;
        var campo;

        for (i = 0; i < formulario.elements.length; i++) {
            campo = formulario.elements[i];

            if (!campo.name || campo.disabled || campo.type === 'submit' || campo.type === 'button') {
                continue;
            }

            datos[campo.name] = String(campo.value || '').trim();
        }

        return datos;
    }

});
