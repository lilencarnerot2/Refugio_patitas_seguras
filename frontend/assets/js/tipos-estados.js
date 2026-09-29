var mensaje = document.getElementById('mensaje-formulario');
var panelTipos = document.getElementById('listado-tipos');
var panelEstados = document.getElementById('listado-estados');

function asignarFilaSolicitud(fila, solicitud) {
    var tabla = fila.parentNode && fila.parentNode.parentNode;
    var cabecera = tabla ? tabla.querySelector('thead tr') : null;

    if (cabecera) {
        asignarCeldasDeApi(fila, cabecera, solicitud, 'id');
    }
}

function asignarPersonas(select, items) {
    var opciones = select ? select.querySelectorAll('option:not([disabled])') : [];
    var i;

    if (!select) {
        return;
    }

    select.value = '';

    for (i = 0; i < opciones.length; i++) {
        opciones[i].value = items[i] ? campoDe(items[i], 'id') : '';
        opciones[i].textContent = items[i]
            ? [campoDe(items[i], 'nombre_usuario'), campoDe(items[i], 'apellido_usuario')].join(' ').trim()
            : '';
    }
}

function asignarEstados(grupo, estados) {
    if (!grupo) {
        return;
    }

    cargarOpcionesDeApi(grupo, estados, function (estado) {
        return campoDe(estado, 'id');
    }, function (estado) {
        return campoDe(estado, 'nombre_estado');
    });
}

function limpiarBloque(bloque) {
    var titulo = bloque.querySelector('h3');
    var cuerpo = bloque.querySelector('tbody');

    bloque.removeAttribute('data-id');

    if (titulo) {
        titulo.removeAttribute('data-texto');
    }

    if (cuerpo) {
        asignarFilasDeApi(cuerpo, [], asignarFilaSolicitud);
    }

    asignarPersonas(bloque.querySelector('select[name="id"]'), []);
    asignarEstados(bloque.querySelector('.opciones-cambio'), []);
}

function asignarBloque(bloque, item, solicitudes, campoId, campoNombre, estados) {
    var titulo = bloque.querySelector('h3');
    var cuerpo = bloque.querySelector('tbody');
    var id = campoDe(item, 'id');
    var lista = solicitudes.filter(function (solicitud) {
        return campoDe(solicitud, campoId) === id;
    });
    var otros = (estados || []).filter(function (estado) {
        return campoDe(estado, 'id') !== id;
    });

    bloque.setAttribute('data-id', id);

    if (titulo) {
        titulo.setAttribute('data-texto', campoDe(item, campoNombre));
    }

    if (cuerpo) {
        asignarFilasDeApi(cuerpo, lista, asignarFilaSolicitud);
    }

    asignarPersonas(bloque.querySelector('select[name="id"]'), lista);
    asignarEstados(bloque.querySelector('.opciones-cambio'), otros);
}

function cargarBloques(contenedor, selector, items, solicitudes, campoId, campoNombre, estados) {
    var bloques = contenedor ? contenedor.querySelectorAll(selector) : [];
    var i;

    for (i = 0; i < bloques.length; i++) {
        if (items[i]) {
            asignarBloque(bloques[i], items[i], solicitudes, campoId, campoNombre, estados);
        } else {
            limpiarBloque(bloques[i]);
        }
    }
}

function cargarListado() {
    return Promise.all([
        apiRequest('tipos_solicitudes'),
        apiRequest('estados_solicitudes'),
        apiRequest('solicitudes')
    ]).then(function (respuestas) {
        var estados = listaDeApi(respuestas[1]);
        var solicitudes = listaDeApi(respuestas[2]);

        cargarBloques(panelTipos, '.bloque-tipo', listaDeApi(respuestas[0]), solicitudes, 'id_tipo_solicitud', 'nombre_tipo');
        cargarBloques(panelEstados, '.bloque-estado', estados, solicitudes, 'id_estado_solicitud', 'nombre_estado', estados);
    }).catch(function (error) {
        cargarBloques(panelTipos, '.bloque-tipo', [], [], 'id_tipo_solicitud', 'nombre_tipo');
        cargarBloques(panelEstados, '.bloque-estado', [], [], 'id_estado_solicitud', 'nombre_estado');

        if (mensaje) {
            mensaje.textContent = mensajeDeApi(error);
        }
    });
}

function enviarEstado(formulario) {
    var id = formulario.querySelector('select[name="id"]');
    var estado = formulario.querySelector('[name="id_estado_solicitud"]:checked');
    var recurso = formulario.getAttribute('data-recurso');

    if (!id || !id.value || !estado || !estado.value || !recurso) {
        return;
    }

    if (mensaje) {
        mensaje.textContent = '';
    }

    return apiRequest(recurso, {
        metodo: 'PUT',
        parametros: { id: id.value },
        cuerpo: { id_estado_solicitud: estado.value }
    }).then(function (respuesta) {
        return cargarListado().then(function () {
            if (mensaje) {
                mensaje.textContent = mensajeDeApi(respuesta);
            }
        });
    }).catch(function (error) {
        if (mensaje) {
            mensaje.textContent = mensajeDeApi(error);
        }
    });
}

if (panelTipos && panelEstados) {
    cargarListado();

    panelEstados.addEventListener('submit', function (evento) {
        var formulario = evento.target;

        if (!formulario.getAttribute || !formulario.getAttribute('data-recurso')) {
            return;
        }

        evento.preventDefault();
        enviarEstado(formulario);
    });
}
