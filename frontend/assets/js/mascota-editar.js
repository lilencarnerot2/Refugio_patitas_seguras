const formulario = document.getElementById('formulario-mascota');
const mensaje = document.getElementById('mensaje-formulario');
const selectMascota = document.getElementById('id_mascota');
const selectEspecie = document.getElementById('id_especie');
const selectRaza = document.getElementById('id_raza');
const idInicial = new URLSearchParams(window.location.search).get('id');

function valorPorTexto(select, texto) {
    var i;

    for (i = 0; i < select.options.length; i++) {
        if (select.options[i].textContent === texto) {
            return select.options[i].value;
        }
    }

    return '';
}

function agregarOpciones(select, lista, campo) {
    var i;
    var opcion;

    for (i = 0; i < lista.length; i++) {
        opcion = document.createElement('option');
        opcion.value = lista[i].id;
        opcion.textContent = lista[i][campo];
        select.appendChild(opcion);
    }
}

function cargarMascotas() {
    return apiRequest('mascotas')
        .then(function (resultado) {
            agregarOpciones(selectMascota, resultado.data || [], 'nombre');
        })
        .catch(function () {
            mensaje.textContent = 'No se pudieron cargar las mascotas.';
        });
}

function cargarEspecies() {
    return apiRequest('especies')
        .then(function (resultado) {
            agregarOpciones(selectEspecie, resultado.data || [], 'nombre_especie');
        })
        .catch(function () {
            mensaje.textContent = 'No se pudieron cargar las especies.';
        });
}

function vaciarRazas() {
    selectRaza.options.length = 1;
    selectRaza.selectedIndex = 0;
}

function cargarRazas(idEspecie, razaSeleccionada) {
    vaciarRazas();

    if (!idEspecie) {
        return Promise.resolve();
    }

    return apiRequest('razas', { parametros: { id_especie: idEspecie } })
        .then(function (resultado) {
            agregarOpciones(selectRaza, resultado.data || [], 'nombre_raza');

            if (razaSeleccionada) {
                selectRaza.value = valorPorTexto(selectRaza, razaSeleccionada);
            }
        })
        .catch(function (error) {
            mensaje.textContent = error.message || 'No se pudieron cargar las razas.';
        });
}

function cargarMascota(id) {
    return apiRequest('mascotas', { parametros: { id: id } })
        .then(function (resultado) {
            var mascota = resultado.data || {};
            var idEspecie = valorPorTexto(selectEspecie, mascota.especie);

            formulario.elements.nombre.value = mascota.nombre || '';
            formulario.elements.sexo.value = mascota.sexo || '';
            formulario.elements['tamaño'].value = mascota.tamaño || '';
            formulario.elements.fecha_nacimiento_estimada.value = mascota.fecha_nacimiento_estimada || '';
            formulario.elements.historial.value = mascota.historial || '';
            formulario.elements.foto_url.value = mascota.foto_url || '';
            selectEspecie.value = idEspecie;

            return cargarRazas(idEspecie, mascota.raza);
        })
        .catch(function (error) {
            mensaje.textContent = error.message || 'No se pudo cargar la mascota.';
        });
}

Promise.all([cargarMascotas(), cargarEspecies()]).then(function () {
    if (!idInicial) {
        return;
    }

    selectMascota.value = idInicial;

    if (!selectMascota.value) {
        return;
    }

    return cargarMascota(idInicial);
});

selectMascota.addEventListener('change', function () {
    mensaje.textContent = '';

    cargarMascota(selectMascota.value);
});

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
        metodo: 'PUT',
        parametros: { id: selectMascota.value },
        cuerpo: datos
    })
        .then(function (resultado) {
            const mascota = resultado.data || {};
            const nombre = mascota.nombre || '';

            mensaje.textContent = resultado.message || '';
            formulario.elements.nombre.value = nombre;
            selectMascota.options[selectMascota.selectedIndex].textContent = nombre;
        })
        .catch(function (error) {
            mensaje.textContent = error.message || 'No se pudo guardar la mascota.';
        });
});
