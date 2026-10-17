(function () {
    if (window.__patitasMascotaAltaLista) {
        return;
    }

    window.__patitasMascotaAltaLista = true;

    var formulario = document.getElementById('formulario-mascota');
    var mensaje = document.getElementById('mensaje-formulario');
    var selectEspecie = document.getElementById('id_especie');
    var selectRaza = document.getElementById('id_raza');

    if (!formulario || !selectEspecie || !selectRaza) {
        return;
    }


    function nuevaOpcion(select) {
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

    function cargarEspecies() {
        apiRequest('especies')
            .then(function (resultado) {
                agregarOpciones(selectEspecie, resultado.data || [], 'nombre_especie');
            })
            .catch(function (error) {
                avisoDeApi(mensaje, error, false, formulario);
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
                agregarOpciones(selectRaza, resultado.data || [], 'nombre_raza');
            })
            .catch(function (error) {
                avisoDeApi(mensaje, error, false, formulario);
            });
    }

    function envioEnCurso() {
        return formulario.getAttribute('data-enviando') === '1';
    }

    function marcarEnvio(enCurso) {
        formulario.setAttribute('data-enviando', enCurso ? '1' : '0');
    }

    cargarEspecies();

    selectEspecie.addEventListener('change', function () {
        cargarRazas(selectEspecie.value);
    });

    formulario.addEventListener('submit', function (evento) {
        evento.preventDefault();

        if (envioEnCurso()) {
            evento.stopImmediatePropagation();
            return;
        }

        var botonEnviar = formulario.querySelector('button[type="submit"]');

        marcarEnvio(true);

        if (botonEnviar) {
            botonEnviar.disabled = true;
        }

        var datos = {
            id_especie: Number(formulario.elements.id_especie.value),
            id_raza: Number(formulario.elements.id_raza.value),
            nombre: formulario.elements.nombre.value,
            sexo: formulario.elements.sexo.value,
            tamaño: formulario.elements['tamaño'].value
        };

        var fecha = formulario.elements.fecha_nacimiento_estimada.value;
        var historial = formulario.elements.historial.value;
        var foto = formulario.elements.foto_url.value;

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
            metodo: 'POST',
            cuerpo: datos
        })
            .then(function () {
                formulario.reset();
                vaciarRazas();
            })
            .catch(function () {
                return null;
            })
            .finally(function () {
                marcarEnvio(false);

                if (botonEnviar) {
                    botonEnviar.disabled = false;
                }
            });
    }, true);
})();
