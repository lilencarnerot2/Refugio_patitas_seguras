var mensaje = document.getElementById('mensaje-formulario');
var panel = document.getElementById('panel-solicitudes');
var resumen = document.getElementById('resumen-solicitudes');
var cuerpo = document.getElementById('cuerpo-solicitudes');
var cabecera = document.getElementById('cabecera-solicitudes');
var opcionesRol = document.getElementById('opciones-rol');
var opcionesEstado = document.getElementById('opciones-estado');

function asignarFila(fila, solicitud) {
    asignarCeldasDeApi(fila, cabecera, solicitud, claveDeApi(cabecera));
    fila.setAttribute('data-rol-pos', posicionDeApi(opcionesRol, solicitud.id_tipo_solicitud));
    fila.setAttribute('data-estado-pos', posicionDeApi(opcionesEstado, solicitud.id_estado_solicitud));
}

function limpiarResumen(tarjeta) {
    var titulo = tarjeta.querySelector('h3');
    var numero = tarjeta.querySelector('.dash-kpi-numero');

    if (titulo) {
        titulo.removeAttribute('data-texto');
    }

    if (numero) {
        numero.removeAttribute('data-texto');
    }

    asignarArcoDeApi(tarjeta.querySelector('.dash-donut-valor'), 0);
}

function asignarResumen(tarjeta, tipo, solicitudes) {
    var titulo = tarjeta.querySelector('h3');
    var numero = tarjeta.querySelector('.dash-kpi-numero');
    var id = campoDe(tipo, 'id');
    var cantidad = contarSiDeApi(solicitudes, function (solicitud) {
        return campoDe(solicitud, 'id_tipo_solicitud') === id;
    });
    var pct = porcentajeDeApi(cantidad, solicitudes.length);

    if (titulo) {
        titulo.setAttribute('data-texto', campoDe(tipo, 'nombre_tipo'));
    }

    if (numero) {
        numero.setAttribute('data-texto', textoApi(pct));
    }

    asignarArcoDeApi(tarjeta.querySelector('.dash-donut-valor'), pct);
}

function cargarResumen(tipos, solicitudes) {
    var tarjetas = resumen ? resumen.querySelectorAll('.info-card') : [];
    var i;

    for (i = 0; i < tarjetas.length; i++) {
        if (tipos[i]) {
            asignarResumen(tarjetas[i], tipos[i], solicitudes);
        } else {
            limpiarResumen(tarjetas[i]);
        }
    }
}

function cargarListado() {
    if (mensaje) {
        mensaje.textContent = '';
    }

    return Promise.all([
        apiRequest('tipos_solicitudes'),
        apiRequest('estados_solicitudes'),
        apiRequest('solicitudes')
    ]).then(function (respuestas) {
        var tipos = listaDeApi(respuestas[0]);
        var estados = listaDeApi(respuestas[1]);
        var solicitudes = listaDeApi(respuestas[2]);

        cargarOpcionesDeApi(opcionesRol, tipos, function (tipo) {
            return campoDe(tipo, 'id');
        }, function (tipo) {
            return campoDe(tipo, 'nombre_tipo');
        }, null, resumenFiltroDeApi(opcionesRol), false);

        cargarOpcionesDeApi(opcionesEstado, estados, function (estado) {
            return campoDe(estado, 'id');
        }, function (estado) {
            return campoDe(estado, 'nombre_estado');
        }, null, resumenFiltroDeApi(opcionesEstado), false);

        asignarFilasDeApi(cuerpo, solicitudes, asignarFila);
        cargarResumen(tipos, solicitudes);
    }).catch(function (error) {
        asignarFilasDeApi(cuerpo, [], asignarFila);
        cargarResumen([], []);

        if (mensaje) {
            mensaje.textContent = mensajeDeApi(error);
        }
    });
}

if (panel && cuerpo && cabecera && opcionesRol && opcionesEstado) {
    cargarListado();
}
