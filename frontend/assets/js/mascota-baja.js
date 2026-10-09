const formulario = document.getElementById('formulario-baja');
const mensaje = document.getElementById('mensaje-formulario');
const selectMascota = document.getElementById('id_mascota');

function mostrarMensaje(texto, ok) {
    mensaje.textContent = texto || '';
    mensaje.classList.remove('is-ok', 'is-error');

    if (!texto) {
        return;
    }

    mensaje.classList.add(ok ? 'is-ok' : 'is-error');
}

function cargarMascotas() {
    apiRequest('mascotas')
        .then(function (resultado) {
            var lista = resultado.data || [];
            var i;
            var opcion;

            for (i = 0; i < lista.length; i++) {
                opcion = document.createElement('option');
                opcion.value = lista[i].id;
                opcion.textContent = lista[i].nombre;
                selectMascota.appendChild(opcion);
            }
        })
        .catch(function (error) {
            mostrarMensaje(error.message, false);
        });
}

cargarMascotas();

selectMascota.addEventListener('change', function () {
    mostrarMensaje('');
});

formulario.addEventListener('submit', function (evento) {
    evento.preventDefault();

    apiRequest('mascotas', {
        metodo: 'DELETE',
        parametros: { id: selectMascota.value }
    })
        .then(function (resultado) {
            mostrarMensaje(resultado.message, true);
            selectMascota.options[selectMascota.selectedIndex].remove();
            selectMascota.selectedIndex = 0;
        })
        .catch(function (error) {
            mostrarMensaje(error.message, false);
        });
});
