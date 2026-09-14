document.addEventListener('DOMContentLoaded', function () {
    const formulario = document.getElementById('form-donacion');

    if (!formulario) {
        return;
    }

    formulario.addEventListener('submit', function (evento) {
        evento.preventDefault();

        const feedback = document.getElementById('form-feedback');
        const datos = Object.fromEntries(new FormData(formulario).entries());

        apiRequest('donaciones', {
            metodo: 'POST',
            cuerpo: datos
        })
            .then(function (data) {
                mostrarFeedback(feedback, data.message || 'Registramos tu aporte. Gracias por ayudar.', true);
                formulario.reset();
            })
            .catch(function (error) {
                mostrarFeedback(
                    feedback,
                    error.message || 'No se pudo registrar la donación.',
                    false
                );
            });
    });
});
