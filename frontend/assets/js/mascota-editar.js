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

function cargarEspecies() {
    return apiRequest('especies')
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

function cargarRazas(idEspecie, razaSeleccionada) {
    vaciarRazas();

    if (!idEspecie) {
        return Promise.resolve();
    }

    return apiRequest('razas', { parametros: { id_especie: idEspecie } })
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
            var idEspecie = mascota.id_especie
                ? String(mascota.id_especie)
                : valorPorTexto(selectEspecie, mascota.especie);
            var fecha = mascota.fecha_nacimiento_estimada || '';

            formulario.elements.nombre.value = mascota.nombre || '';
            formulario.elements.sexo.value = mascota.sexo || '';
            formulario.elements['tamaño'].value = mascota.tamaño || mascota.tamano || '';
            formulario.elements.fecha_nacimiento_estimada.value = fecha ? String(fecha).slice(0, 10) : '';
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

    return cargarMascota(idInicial);
});

selectMascota.addEventListener('change', function () {
    mensaje.textContent = '';

    if (selectMascota.value) {
        cargarMascota(selectMascota.value);
    }
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
            mensaje.textContent = resultado.message || '';
            selectMascota.options[selectMascota.selectedIndex].textContent = datos.nombre;
        })
        .catch(function (error) {
            mensaje.textContent = error.message || 'No se pudo guardar la mascota.';
        });
});
