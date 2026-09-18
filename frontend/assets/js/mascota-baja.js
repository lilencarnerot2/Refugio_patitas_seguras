const formulario = document.getElementById('formulario-baja');
const mensaje = document.getElementById('mensaje-formulario');
const opciones = document.getElementById('opciones-mascotas');

function cargarMascotas() {
    return apiRequest('mascotas')
        .then(function (resultado) {
            var lista = resultado.data || [];
            var i;
            var etiqueta;
            var radio;

            for (i = 0; i < lista.length; i++) {
                radio = document.createElement('input');
                radio.type = 'radio';
                radio.name = 'id_mascota';
                radio.value = lista[i].id;

                etiqueta = document.createElement('label');
                etiqueta.appendChild(radio);
                etiqueta.appendChild(document.createTextNode(' ' + lista[i].nombre));
                opciones.appendChild(etiqueta);
            }
        })
        .catch(function () {
            mensaje.textContent = 'No se pudieron cargar las mascotas.';
        });
}

function mascotaElegida() {
    return opciones.querySelector('input[name="id_mascota"]:checked');
}

cargarMascotas();

opciones.addEventListener('change', function () {
    mensaje.textContent = '';
});

formulario.addEventListener('submit', function (evento) {
    evento.preventDefault();

    const elegida = mascotaElegida();

    if (!elegida) {
        mensaje.textContent = 'Seleccioná una mascota.';
        return;
    }

    apiRequest('mascotas', {
        metodo: 'DELETE',
        parametros: { id: elegida.value }
    })
        .then(function (resultado) {
            mensaje.textContent = resultado.message || '';
            elegida.parentNode.remove();
        })
        .catch(function (error) {
            mensaje.textContent = error.message || 'No se pudo dar de baja la mascota.';
        });
});
