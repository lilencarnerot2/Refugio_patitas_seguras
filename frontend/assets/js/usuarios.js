var mensaje = document.getElementById('mensaje-formulario');
var panel = document.getElementById('panel-usuarios');
var cuerpo = document.getElementById('cuerpo-usuarios');
var cabecera = document.getElementById('cabecera-usuarios');
var opcionesRol = document.getElementById('opciones-rol');
var opcionesEstado = document.getElementById('opciones-estado');

function parEstado(valor) {
    var texto = textoApi(valor).toLowerCase();

    if (texto === 'true') {
        return 'false';
    }

    if (texto === 'false') {
        return 'true';
    }

    if (texto === 't') {
        return 'f';
    }

    if (texto === 'f') {
        return 't';
    }

    if (texto === '1') {
        return '0';
    }

    if (texto === '0') {
        return '1';
    }

    return '';
}

function asignarFila(fila, usuario) {
    asignarCeldasDeApi(fila, cabecera, usuario, claveDeApi(cabecera));
    fila.setAttribute('data-id-rol', textoApi(usuario.id_rol));
    fila.setAttribute('data-estado', textoApi(usuario.estado).toLowerCase());
    fila.setAttribute('data-rol-pos', posicionDeApi(opcionesRol, usuario.id_rol));
}

function cargarListado() {
    if (mensaje) {
        mensaje.textContent = '';
    }

    return apiRequest('roles')
        .then(function (rolesRespuesta) {
            var listaRoles = rolesListadoDeApi(listaDeApi(rolesRespuesta));
            var idsVisibles = mapaValoresDeApi(listaRoles);

            cargarOpcionesDeApi(opcionesRol, listaRoles, function (rol) {
                return campoDe(rol, 'id');
            }, function (rol) {
                return campoDe(rol, 'nombre_rol');
            }, null, resumenFiltroDeApi(opcionesRol), false);

            return apiRequest('usuarios').then(function (respuestaUsuarios) {
                return {
                    usuarios: listaDeApi(respuestaUsuarios).filter(function (usuario) {
                        return usuario && idsVisibles[campoDe(usuario, 'id_rol')];
                    })
                };
            });
        })
        .then(function (resultado) {
            cargarOpcionesDeApi(opcionesEstado, resultado.usuarios, function (usuario) {
                return campoDe(usuario, 'estado');
            }, function (usuario) {
                return campoDe(usuario, 'estado');
            }, parEstado, null, true);
            asignarFilasDeApi(cuerpo, resultado.usuarios, asignarFila);
        })
        .catch(function (error) {
            asignarFilasDeApi(cuerpo, [], asignarFila);

            if (mensaje) {
                mensaje.textContent = mensajeDeApi(error);
            }
        });
}

if (panel && cuerpo && cabecera && opcionesRol && opcionesEstado) {
    cargarListado();
}
