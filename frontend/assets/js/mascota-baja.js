const formulario = document.getElementById('formulario-baja');
const mensaje = document.getElementById('mensaje-formulario');
const selectMascota = document.getElementById('id_mascota');

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
        .catch(function () {
            mensaje.textContent = 'No se pudieron cargar las mascotas.';
        });
}

cargarMascotas();

selectMascota.addEventListener('change', function () {
    mensaje.textContent = '';
});

formulario.addEventListener('submit', function (evento) {
    evento.preventDefault();

    apiRequest('mascotas', {
        metodo: 'DELETE',
        parametros: { id: selectMascota.value }
    })
        .then(function (resultado) {
            mensaje.textContent = resultado.message || '';
            selectMascota.options[selectMascota.selectedIndex].remove();
            selectMascota.selectedIndex = 0;
        })
        .catch(function (error) {
            mensaje.textContent = error.message || 'No se pudo dar de baja la mascota.';
        });
});
