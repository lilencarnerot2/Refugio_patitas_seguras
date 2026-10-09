var mensaje = document.getElementById('mensaje-formulario');
var panel = document.getElementById('panel-dashboard');
var cabeceraSolicitudes = document.getElementById('cabecera-dashboard-solicitudes');
var cabeceraMascotas = document.getElementById('cabecera-dashboard-mascotas');
var cuerpoSolicitudes = document.getElementById('cuerpo-dashboard-solicitudes');
var cuerpoMascotas = document.getElementById('cuerpo-dashboard-mascotas');

function asignarFilaDe(cabecera) {
    return function (fila, item) {
        if (cabecera) {
            asignarCeldasDeApi(fila, cabecera, item, claveDeApi(cabecera));
        }
    };
}

function movimientoDe(idMascota, movimientos) {
    var i;

    for (i = 0; i < movimientos.length; i++) {
        if (mismoValorDeApi(movimientos[i].id_mascota, idMascota)) {
            return movimientos[i];
        }
    }

    return null;
}

function partesDe(catalogo, campoNombre, cantidadDe) {
    return catalogo.map(function (item) {
        return {
            nombre: campoDe(item, campoNombre),
            cantidad: cantidadDe(item)
        };
    });
}

function limpiarMarca(nodo) {
    nodo.removeAttribute('data-texto');
    nodo.removeAttribute('data-cantidad');
}

function mostrarTorta(idTorta, idLeyenda, partes, total) {
    asignarTortaDeApi(document.getElementById(idTorta), partes, total);
    asignarHijosDeApi(document.getElementById(idLeyenda), partes, function (nodo, parte) {
        asignarTextoDeApi(nodo, [parte.nombre, porcentajeDeApi(parte.cantidad, total) + '%', '(' + parte.cantidad + ')'].join(' '));
        asignarCantidadDeApi(nodo, parte.cantidad);
    }, limpiarMarca);
}

function asignarBarras(contenedor, items, valorDe, textoDe) {
    var maximo = 1;
    var i;
    var valor;

    for (i = 0; i < items.length; i++) {
        valor = valorDe(items[i]);

        if (valor > maximo) {
            maximo = valor;
        }
    }

    asignarHijosDeApi(contenedor, items, function (grupo, item) {
        var barra = grupo.querySelector('progress');
        var etiqueta = grupo.querySelector('span');

        valor = valorDe(item);

        if (barra) {
            barra.max = maximo;
            barra.value = valor;
        }

        asignarTextoDeApi(etiqueta, textoDe(item));
    }, function (grupo) {
        var barra = grupo.querySelector('progress');
        var etiqueta = grupo.querySelector('span');

        if (barra) {
            barra.max = 1;
            barra.value = 0;
        }

        asignarTextoDeApi(etiqueta, '');
    });
}

function mostrarSolicitudes(tipos, solicitudes) {
    var total = solicitudes.length;

    mostrarTorta('torta-solicitudes', 'leyenda-tipos', partesDe(tipos, 'nombre_tipo', function (tipo) {
        return contarCampoDeApi(solicitudes, 'id_tipo_solicitud', tipo.id);
    }), total);
}

function mostrarPendientes(estados, solicitudes) {
    var total = solicitudes.length;

    asignarTextoDeApi(document.getElementById('numero-pendientes'), total);
    mostrarTorta('torta-pendientes', 'leyenda-estados', partesDe(estados, 'nombre_estado', function (estado) {
        return contarCampoDeApi(solicitudes, 'id_estado_solicitud', estado.id);
    }), total);
}

function mostrarMascotas(mascotas, tiposMovimiento, movimientos) {
    var total = mascotas.length;

    asignarTextoDeApi(document.getElementById('numero-mascotas'), total);
    mostrarTorta('torta-mascotas', 'leyenda-movimientos', partesDe(tiposMovimiento, 'nombre_movimiento', function (tipo) {
        return contarSiDeApi(mascotas, function (mascota) {
            var movimiento = movimientoDe(mascota.id, movimientos);

            return movimiento && mismoValorDeApi(movimiento.id_tipo_movimiento, tipo.id);
        });
    }), total);
    asignarFilasDeApi(cuerpoMascotas, mascotas, asignarFilaDe(cabeceraMascotas));
}

function mostrarDonaciones(donaciones) {
    var barras = document.getElementById('barras-donaciones');
    var campo = claveDeApi(barras);
    var tipos = listaUnicaDeApi(donaciones, function (item) {
        return campoDe(item, campo);
    }, function (item) {
        return campoDe(item, campo);
    });

    asignarTextoDeApi(document.getElementById('numero-donaciones'), donaciones.length);
    asignarBarras(barras, tipos, function (tipo) {
        return contarCampoDeApi(donaciones, campo, tipo.valor);
    }, function (tipo) {
        return tipo.texto;
    });
}

function cargarPanel() {
    if (mensaje) {
        mensaje.textContent = '';
    }

    Promise.all([
        listaPedidoDeApi('tipos_solicitudes'),
        listaPedidoDeApi('estados_solicitudes'),
        listaPedidoDeApi('solicitudes'),
        listaPedidoDeApi('mascotas'),
        listaPedidoDeApi('tipos_movimientos_mascotas'),
        listaPedidoDeApi('movimientos_mascotas'),
        listaPedidoDeApi('donaciones')
    ]).then(function (respuestas) {
        mostrarSolicitudes(respuestas[0], respuestas[2]);
        mostrarPendientes(respuestas[1], respuestas[2]);
        mostrarMascotas(respuestas[3], respuestas[4], respuestas[5]);
        asignarFilasDeApi(cuerpoSolicitudes, respuestas[2], asignarFilaDe(cabeceraSolicitudes));
        mostrarDonaciones(respuestas[6]);
    }).catch(function (error) {
        if (mensaje) {
            mensaje.textContent = mensajeDeApi(error);
        }
    });
}

if (panel) {
    cargarPanel();
}
