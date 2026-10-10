document.addEventListener('DOMContentLoaded', function () {
    var formulario = document.getElementById('form-voluntariado');

    if (!formulario) {
        return;
    }

    formulario.addEventListener('submit', function (evento) {
        evento.preventDefault();

        var aviso = document.getElementById('form-feedback');
        var datos = Object.fromEntries(new FormData(formulario).entries());

        apiRequest('voluntariado', {
            metodo: 'POST',
            cuerpo: datos
        })
            .then(function (data) {
                mostrarAviso(aviso, mensajeDeApi(data), true);
                formulario.reset();
            })
            .catch(function (error) {
                mostrarAviso(aviso, mensajeDeApi(error), false);
            });
    });
});

function mostrarAviso(elemento, texto, ok) {
    if (!elemento) {
        return;
    }

    elemento.hidden = false;
    elemento.dataset.estado = ok ? 'ok' : 'error';
    elemento.textContent = texto || '';
}
