const formulario = document.getElementById('formulario-mascota');
const mensaje = document.getElementById('mensaje-formulario');
const selectEspecie = document.getElementById('id_especie');

function cargarEspecies() {
    fetch(`${API_URL}/public/index.php?recurso=especies`)
        .then(function (respuesta) {
            return respuesta.json();
        })
        .then(function (resultado) {
            var lista = resultado.data || [];
            var i;
            var opcion;

            for (i = 0; i < lista.length; i++) {
                opcion = document.createElement('option');
                opcion.value = lista[i].id;
                opcion.textContent = lista[i].nombre_especie;
                selectEspecie.appendChild(opcion);
            }
        })
        .catch(function () {
            mensaje.textContent = 'No se pudieron cargar las especies.';
        });
}

cargarEspecies();

formulario.addEventListener('submit', function (evento) {
    evento.preventDefault();

    const datos = {
        id_especie: Number(formulario.elements.id_especie.value),
        id_raza: Number(formulario.elements.id_raza.value),
        nombre: formulario.elements.nombre.value,
        sexo: formulario.elements.sexo.value,
        tamaño: formulario.elements['tamaño'].value
    };

    const fecha = formulario.elements.fecha_nacimiento_estimada.value;
    const historial = formulario.elements.historial.value;
    const foto = formulario.elements.foto_url.value;

    if (fecha) {
        datos.fecha_nacimiento_estimada = fecha;
    }

    if (historial) {
        datos.historial = historial;
    }

    if (foto) {
        datos.foto_url = foto;
    }

    fetch(`${API_URL}/public/index.php?recurso=mascotas`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(datos)
    })
        .then(function (respuesta) {
            return respuesta.json();
        })
        .then(function (resultado) {
            mensaje.textContent = resultado.message || '';

            if (resultado.success) {
                formulario.reset();
            }
        })
        .catch(function () {
            mensaje.textContent = 'No se pudo enviar el formulario.';
        });
});
