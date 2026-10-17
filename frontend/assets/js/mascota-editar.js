const formulario = document.getElementById('formulario-mascota');
const mensaje = document.getElementById('mensaje-formulario');
const selectMascota = document.getElementById('id_mascota');
const selectEspecie = document.getElementById('id_especie');
const selectRaza = document.getElementById('id_raza');
const idInicial = new URLSearchParams(window.location.search).get('id');

function mostrarMensaje(origen, ok) {
    avisoDeApi(mensaje, origen, ok, formulario);
}

let especies = [];
let razas = [];

function idPorNombre(lista, campo, nombre) {
    var i;

    for (i = 0; i < lista.length; i++) {
        if (lista[i][campo] === nombre) {
            return lista[i].id;
        }
    }

    return '';
}

function nuevaOpcion(select) {
    // clona la opción vacía del select
    var base = select.querySelector('option');
    var opcion = base.cloneNode(false);

    opcion.removeAttribute('selected');
    opcion.removeAttribute('disabled');
    opcion.removeAttribute('hidden');
    opcion.value = '';
    opcion.textContent = '';

    return opcion;
}

function agregarOpciones(select, lista, campo) {
    var i;

    for (i = 0; i < lista.length; i++) {
        var opcion = nuevaOpcion(select);
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
        .catch(function (error) {
            mostrarMensaje(error, false);
        });
}

function cargarEspecies() {
    return apiRequest('especies')
        .then(function (resultado) {
            especies = resultado.data || [];

            agregarOpciones(selectEspecie, especies, 'nombre_especie');
        })
        .catch(function (error) {
            mostrarMensaje(error, false);
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
            razas = resultado.data || [];

            agregarOpciones(selectRaza, razas, 'nombre_raza');

            if (razaSeleccionada) {
                selectRaza.value = idPorNombre(razas, 'nombre_raza', razaSeleccionada);
            }
        })
        .catch(function (error) {
            mostrarMensaje(error, false);
        });
}

function cargarMascota(id) {
    return apiRequest('mascotas', { parametros: { id: id } })
        .then(function (resultado) {
            var mascota = resultado.data || {};
            var idEspecie = idPorNombre(especies, 'nombre_especie', mascota.especie);

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
            mostrarMensaje(error, false);
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
    avisoDeApi(mensaje, '', true, formulario);

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

    enviarFormularioApi(formulario, mensaje, 'mascotas', {
        metodo: 'PUT',
        parametros: { id: selectMascota.value },
        cuerpo: datos
    })
        .then(function (resultado) {
            const mascota = resultado.data || {};
            const nombre = mascota.nombre || '';

            formulario.elements.nombre.value = nombre;
            selectMascota.options[selectMascota.selectedIndex].textContent = nombre;
        })
        .catch(function () {
            return null;
        });
});
