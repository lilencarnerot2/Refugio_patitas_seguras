var panel = document.getElementById('panel-usuario-detalle');
var ficha = document.getElementById('ficha-usuario');
var mensaje = document.getElementById('mensaje-formulario');
var cabeceraSolicitudes = document.getElementById('cabecera-solicitudes');
var cuerpoSolicitudes = document.getElementById('cuerpo-solicitudes');

function asignarSolicitud(fila, solicitud) {
    asignarCeldasDeApi(fila, cabeceraSolicitudes, solicitud, claveDeApi(cabeceraSolicitudes));
}

if (panel && ficha) {
    var clave = claveDeApi(ficha, '.pet-detail-meta [data-campo]');
    var id = clave ? new URLSearchParams(window.location.search).get(clave) : '';
    var listado = panel.getAttribute('data-listado') || '';

    if (id) {
        apiRequest('roles')
            .then(function (rolesRespuesta) {
                var idsVisibles = mapaValoresDeApi(rolesListadoDeApi(listaDeApi(rolesRespuesta)));

                return apiRequest('usuarios', {
                    parametros: { id: id }
                }).then(function (respuesta) {
                    var usuario = respuesta.data;

                    if (!usuario) {
                        if (mensaje) {
                            mensaje.textContent = mensajeDeApi(respuesta);
                        }
                        return;
                    }

                    if (!idsVisibles[textoApi(usuario.id_rol)]) {
                        if (listado) {
                            window.location.href = listado;
                        }
                        return;
                    }

                    asignarCamposDeApi(ficha, usuario);

                    if (!cuerpoSolicitudes || !cabeceraSolicitudes) {
                        return;
                    }

                    return apiRequest('solicitudes').then(function (solicitudesRespuesta) {
                        asignarFilasDeApi(cuerpoSolicitudes, listaDeApi(solicitudesRespuesta).filter(function (solicitud) {
                            return textoApi(solicitud.id_usuario) === textoApi(usuario.id);
                        }), asignarSolicitud);
                    });
                });
            })
            .catch(function (error) {
                if (mensaje) {
                    mensaje.textContent = mensajeDeApi(error);
                }
            });
    }
}
