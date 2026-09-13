document.addEventListener('DOMContentLoaded', function () {
    const formulario = document.getElementById('form-voluntariado');

    if (!formulario) {
        return;
    }

    formulario.addEventListener('submit', function (evento) {
        evento.preventDefault();

        const feedback = document.getElementById('form-feedback');
        const datos = Object.fromEntries(new FormData(formulario).entries());

        apiRequest('voluntariado', {
            metodo: 'POST',
            cuerpo: datos
        })
            .then(function (data) {
                mostrarFeedback(feedback, data.message || 'Inscripción registrada. Te vamos a contactar para coordinar.', true);
                formulario.reset();
            })
            .catch(function (error) {
                mostrarFeedback(
                    feedback,
                    error.message || 'No se pudo enviar la inscripción.',
                    false
                );
            });
    });
});
