const mensaje = document.getElementById('mensaje-formulario');
const datosMascota = document.getElementById('datos-mascota');
const formularioCrearFicha = document.getElementById('formulario-crear-ficha');
const bloquesFicha = document.getElementById('bloques-ficha');
const resumenFicha = document.getElementById('resumen-ficha');
const bloqueCrearFicha = document.getElementById('bloque-crear-ficha');
const accionesCrearFicha = document.getElementById('acciones-crear-ficha');
const formularioControl = document.getElementById('formulario-control');
const formularioVacuna = document.getElementById('formulario-vacuna');
const formularioCastracion = document.getElementById('formulario-castracion');
const formularioDesparasitacion = document.getElementById('formulario-desparasitacion');
const idMascota = new URLSearchParams(window.location.search).get('id');

let idFicha = '';

function mostrarMensaje(texto, ok) {
    mensaje.textContent = texto || '';
    mensaje.classList.remove('is-ok', 'is-error');

    if (!texto) {
        return;
    }

    mensaje.classList.add(ok ? 'is-ok' : 'is-error');
}

function listaDe(resultado) {
    var lista = resultado.data || [];

    if (!Array.isArray(lista)) {
        lista = [];
    }

    return lista;
}

function vaciar(nodo) {
    while (nodo.firstChild) {
        nodo.removeChild(nodo.firstChild);
    }
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

function campoConValor(formulario) {
    var i;
    var campo;

    for (i = 0; i < formulario.elements.length; i++) {
        campo = formulario.elements[i];

        if (!campo.name || campo.type === 'submit') {
            continue;
        }

        if (campo.value) {
            return true;
        }
    }

    return false;
}

function enviarSiHayDatos(formulario, enviar) {
    if (!campoConValor(formulario)) {
        return Promise.resolve();
    }

    if (!formulario.checkValidity()) {
        formulario.reportValidity();
        return Promise.resolve();
    }

    return enviar();
}

function mostrarMascota(mascota) {
    var foto = document.getElementById('ficha-foto');
    var fotoVacia = document.getElementById('ficha-foto-vacia');

    document.getElementById('ficha-nombre').textContent = mascota.nombre || '';
    document.getElementById('ficha-especie').textContent = mascota.especie || '';
    document.getElementById('ficha-raza').textContent = mascota.raza || '';
    document.getElementById('ficha-sexo').textContent = mascota.sexo || '';
    document.getElementById('ficha-tamano').textContent = mascota.tamaño || '';
    document.getElementById('ficha-nacimiento').textContent = mascota.fecha_nacimiento_estimada || '';
    document.getElementById('ficha-historial').textContent = mascota.historial || '';

    if (mascota.foto_url) {
        foto.src = mascota.foto_url;
        foto.alt = mascota.nombre || '';
        foto.hidden = false;
        fotoVacia.hidden = true;
    } else {
        foto.removeAttribute('src');
        foto.hidden = true;
        fotoVacia.hidden = false;
    }

    datosMascota.hidden = false;
}

function mostrarUltimoControl(controles) {
    var control = controles[0] || {};
    var peso = document.getElementById('control-peso');
    var fecha = document.getElementById('control-fecha');

    peso.textContent = control.peso || '';
    fecha.textContent = control.fecha_control || '';
    document.getElementById('control-observaciones').textContent = control.observaciones || '';
}

function mostrarLista(lista, destino, campo) {
    var i;
    var item;

    vaciar(destino);

    for (i = 0; i < lista.length; i++) {
        item = document.createElement('li');
        item.textContent = lista[i][campo] || '';
        destino.appendChild(item);
    }
}

function mostrarCastracion(castracion) {
    var estado = document.getElementById('castracion-estado');

    estado.textContent = castracion.fecha_castracion || '';
    estado.classList.remove('is-ok', 'is-error');
    estado.classList.add('is-ok');
    document.getElementById('castracion-observaciones').textContent = castracion.observaciones || '';
    formularioCastracion.hidden = true;
}

function cargarControles() {
    return apiRequest('controles_medicos', {
        parametros: { id_ficha_medica: idFicha }
    })
        .then(function (resultado) {
            mostrarUltimoControl(listaDe(resultado));
        })
        .catch(function (error) {
            mostrarMensaje(error.message, false);
        });
}

function cargarVacunas() {
    return apiRequest('vacunas', {
        parametros: { id_ficha_medica: idFicha }
    })
        .then(function (resultado) {
            mostrarLista(listaDe(resultado), document.getElementById('lista-vacunas'), 'nombre_vacuna');
        })
        .catch(function (error) {
            mostrarMensaje(error.message, false);
        });
}

function cargarDesparasitaciones() {
    return apiRequest('desparasitaciones', {
        parametros: { id_ficha_medica: idFicha }
    })
        .then(function (resultado) {
            mostrarLista(
                listaDe(resultado),
                document.getElementById('lista-desparasitaciones'),
                'nombre_tipo'
            );
        })
        .catch(function (error) {
            mostrarMensaje(error.message, false);
        });
}

function cargarCastracion() {
    return apiRequest('castraciones', {
        parametros: { id_ficha_medica: idFicha }
    })
        .then(function (resultado) {
            mostrarCastracion(resultado.data || {});
        })
        .catch(function (error) {
            var estado = document.getElementById('castracion-estado');

            estado.classList.remove('is-ok', 'is-error');
            document.getElementById('castracion-observaciones').textContent = '';
            formularioCastracion.hidden = false;

            if (error.status === 404) {
                estado.textContent = error.message;
                estado.classList.add('is-error');
                return;
            }

            mostrarMensaje(error.message, false);
        });
}

function cargarTipos() {
    var selectVacuna = document.getElementById('id_tipo_vacuna');
    var selectProducto = document.getElementById('id_tipo_producto');

    selectVacuna.options.length = 1;
    selectProducto.options.length = 1;

    return Promise.all([
        apiRequest('tipos_vacunas'),
        apiRequest('tipos_productos')
    ])
        .then(function (resultados) {
            agregarOpciones(selectVacuna, listaDe(resultados[0]), 'nombre_vacuna');
            agregarOpciones(selectProducto, listaDe(resultados[1]), 'nombre_tipo');
        })
        .catch(function (error) {
            mostrarMensaje(error.message, false);
        });
}

function datosControl() {
    const datos = {
        id_ficha_medica: Number(idFicha),
        fecha_control: formularioControl.elements.fecha_control.value
    };
    const peso = formularioControl.elements.peso.value;
    const observaciones = formularioControl.elements.observaciones.value;

    if (peso) {
        datos.peso = Number(peso);
    }

    if (observaciones) {
        datos.observaciones = observaciones;
    }

    return datos;
}

function datosVacuna() {
    const datos = {
        id_ficha_medica: Number(idFicha),
        id_tipo_vacuna: Number(formularioVacuna.elements.id_tipo_vacuna.value),
        numero_dosis: Number(formularioVacuna.elements.numero_dosis.value),
        fecha_aplicacion: formularioVacuna.elements.fecha_aplicacion.value
    };
    const proxima = formularioVacuna.elements.proxima_dosis.value;
    const observaciones = formularioVacuna.elements.observaciones.value;

    if (proxima) {
        datos.proxima_dosis = proxima;
    }

    if (observaciones) {
        datos.observaciones = observaciones;
    }

    return datos;
}

function datosCastracion() {
    const datos = {
        id_ficha_medica: Number(idFicha)
    };
    const fecha = formularioCastracion.elements.fecha_castracion.value;
    const observaciones = formularioCastracion.elements.observaciones.value;

    if (fecha) {
        datos.fecha_castracion = fecha;
    }

    if (observaciones) {
        datos.observaciones = observaciones;
    }

    return datos;
}

function datosDesparasitacion() {
    const datos = {
        id_ficha_medica: Number(idFicha),
        id_tipo_producto: Number(formularioDesparasitacion.elements.id_tipo_producto.value),
        tipo_parasito: formularioDesparasitacion.elements.tipo_parasito.value,
        fecha_aplicacion: formularioDesparasitacion.elements.fecha_aplicacion.value
    };
    const proxima = formularioDesparasitacion.elements.proxima_dosis.value;
    const observaciones = formularioDesparasitacion.elements.observaciones.value;

    if (proxima) {
        datos.proxima_dosis = proxima;
    }

    if (observaciones) {
        datos.observaciones = observaciones;
    }

    return datos;
}

function enviarControl() {
    return apiRequest('controles_medicos', {
        metodo: 'POST',
        cuerpo: datosControl()
    })
        .then(function (resultado) {
            mostrarMensaje(resultado.message, true);
            formularioControl.reset();
            return cargarControles();
        });
}

function enviarVacuna() {
    return apiRequest('vacunas', {
        metodo: 'POST',
        cuerpo: datosVacuna()
    })
        .then(function (resultado) {
            mostrarMensaje(resultado.message, true);
            formularioVacuna.reset();
            return cargarVacunas();
        });
}

function enviarCastracion() {
    return apiRequest('castraciones', {
        metodo: 'POST',
        cuerpo: datosCastracion()
    })
        .then(function (resultado) {
            mostrarMensaje(resultado.message, true);
            mostrarCastracion(resultado.data || {});
            formularioCastracion.reset();
        });
}

function enviarDesparasitacion() {
    return apiRequest('desparasitaciones', {
        metodo: 'POST',
        cuerpo: datosDesparasitacion()
    })
        .then(function (resultado) {
            mostrarMensaje(resultado.message, true);
            formularioDesparasitacion.reset();
            return cargarDesparasitaciones();
        });
}

function enviarRegistrosIniciales() {
    return enviarSiHayDatos(formularioControl, enviarControl)
        .then(function () {
            return enviarSiHayDatos(formularioVacuna, enviarVacuna);
        })
        .then(function () {
            return enviarSiHayDatos(formularioCastracion, enviarCastracion);
        })
        .then(function () {
            return enviarSiHayDatos(formularioDesparasitacion, enviarDesparasitacion);
        });
}

function mostrarAltaFicha() {
    bloquesFicha.hidden = false;
    resumenFicha.hidden = true;
    bloqueCrearFicha.hidden = false;
    accionesCrearFicha.hidden = false;
    cargarTipos();
}

function mostrarFichaMedica(ficha) {
    idFicha = ficha.id;
    document.getElementById('ficha-observaciones').textContent = ficha.observaciones || '';
    bloqueCrearFicha.hidden = true;
    accionesCrearFicha.hidden = true;
    resumenFicha.hidden = false;
    bloquesFicha.hidden = false;

    cargarControles();
    cargarVacunas();
    cargarDesparasitaciones();
    cargarCastracion();
    cargarTipos();
}

function cargarFicha() {
    return apiRequest('fichas_medicas', {
        parametros: { id_mascota: idMascota }
    })
        .then(function (respuesta) {
            var ficha = respuesta.data;

            mostrarFichaMedica(ficha);
        })
        .catch(function (error) {
            mostrarMensaje(error.message, false);
            mostrarAltaFicha();
        });
}

if (idMascota) {
    apiRequest('mascotas', {
        parametros: { id: idMascota }
    })
        .then(function (resultado) {
            mostrarMascota(resultado.data || {});
            return cargarFicha();
        })
        .catch(function (error) {
            mostrarMensaje(error.message, false);
        });
}

formularioCrearFicha.addEventListener('submit', function (evento) {
    evento.preventDefault();

    const datos = {
        id_mascota: Number(idMascota)
    };
    const observaciones = formularioCrearFicha.elements.observaciones.value;

    if (observaciones) {
        datos.observaciones = observaciones;
    }

    apiRequest('fichas_medicas', {
        metodo: 'POST',
        cuerpo: datos
    })
        .then(function (resultado) {
            var ficha = resultado.data || {};

            mostrarMensaje(resultado.message, true);
            idFicha = ficha.id;

            return enviarRegistrosIniciales()
                .catch(function (error) {
                    mostrarMensaje(error.message, false);
                })
                .then(function () {
                    return ficha;
                });
        })
        .then(function (ficha) {
            mostrarFichaMedica(ficha);
        })
        .catch(function (error) {
            mostrarMensaje(error.message, false);
        });
});

formularioControl.addEventListener('submit', function (evento) {
    evento.preventDefault();

    if (!formularioControl.checkValidity()) {
        formularioControl.reportValidity();
        return;
    }

    enviarControl().catch(function (error) {
        mostrarMensaje(error.message, false);
    });
});

formularioVacuna.addEventListener('submit', function (evento) {
    evento.preventDefault();

    if (!formularioVacuna.checkValidity()) {
        formularioVacuna.reportValidity();
        return;
    }

    enviarVacuna().catch(function (error) {
        mostrarMensaje(error.message, false);
    });
});

formularioCastracion.addEventListener('submit', function (evento) {
    evento.preventDefault();

    if (!formularioCastracion.checkValidity()) {
        formularioCastracion.reportValidity();
        return;
    }

    enviarCastracion().catch(function (error) {
        mostrarMensaje(error.message, false);
    });
});

formularioDesparasitacion.addEventListener('submit', function (evento) {
    evento.preventDefault();

    if (!formularioDesparasitacion.checkValidity()) {
        formularioDesparasitacion.reportValidity();
        return;
    }

    enviarDesparasitacion().catch(function (error) {
        mostrarMensaje(error.message, false);
    });
});
