const formulario = document.getElementById('formulario-mascota');
const mensaje = document.getElementById('mensaje-formulario');
const selectEspecie = document.getElementById('id_especie');
const selectRaza = document.getElementById('id_raza');

function cargarEspecies() {
    apiRequest('especies')
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

function vaciarRazas() {
    selectRaza.options.length = 1;
    selectRaza.selectedIndex = 0;
}

function cargarRazas(idEspecie) {
    vaciarRazas();

    if (!idEspecie) {
        return;
    }

    apiRequest('razas', { parametros: { id_especie: idEspecie } })
        .then(function (resultado) {
            var lista = resultado.data || [];
            var i;
            var opcion;

            for (i = 0; i < lista.length; i++) {
                opcion = document.createElement('option');
                opcion.value = lista[i].id;
                opcion.textContent = lista[i].nombre_raza;
                selectRaza.appendChild(opcion);
            }
        })
        .catch(function (error) {
            mensaje.textContent = error.message || 'No se pudieron cargar las razas.';
        });
}

cargarEspecies();

selectEspecie.addEventListener('change', function () {
    cargarRazas(selectEspecie.value);
});

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

    apiRequest('mascotas', {
        metodo: 'POST',
        cuerpo: datos
    })
        .then(function (resultado) {
            mensaje.textContent = resultado.message || '';

            if (resultado.success) {
                formulario.reset();
                vaciarRazas();
            }
        })
        .catch(function (error) {
            mensaje.textContent = error.message || 'No se pudo enviar el formulario.';
        });
});
