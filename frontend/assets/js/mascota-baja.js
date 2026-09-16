const formulario = document.getElementById('formulario-baja');
const mensaje = document.getElementById('mensaje-formulario');
const selectMascota = document.getElementById('id_mascota');

function cargarMascotas() {
    return apiRequest('mascotas')
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

function quitarOpcion(id) {
    var i;

    for (i = 0; i < selectMascota.options.length; i++) {
        if (selectMascota.options[i].value === String(id)) {
            selectMascota.remove(i);
            return;
        }
    }
}

cargarMascotas();

selectMascota.addEventListener('change', function () {
    mensaje.textContent = '';
});

formulario.addEventListener('submit', function (evento) {
    evento.preventDefault();

    const id = selectMascota.value;

    apiRequest('mascotas', {
        metodo: 'DELETE',
        parametros: { id: id }
    })
        .then(function (resultado) {
            mensaje.textContent = resultado.message || '';
            quitarOpcion(id);
            selectMascota.selectedIndex = 0;
        })
        .catch(function (error) {
            mensaje.textContent = error.message || 'No se pudo dar de baja la mascota.';
        });
});
