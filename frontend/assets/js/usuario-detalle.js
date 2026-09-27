var panel = document.getElementById('panel-usuario-detalle');
var ficha = document.getElementById('ficha-usuario');
var mensaje = document.getElementById('mensaje-formulario');
var cabeceraSolicitudes = document.getElementById('cabecera-solicitudes');
var cuerpoSolicitudes = document.getElementById('cuerpo-solicitudes');

function claveId() {
    var nodo = ficha.querySelector('.pet-detail-meta [data-campo]');

    return nodo ? nodo.getAttribute('data-campo') : '';
}

function asignarUsuario(usuario) {
    var nodos = ficha.querySelectorAll('[data-campo]');
    var i;
    var nodo;
    var campo;
    var texto;

    for (i = 0; i < nodos.length; i++) {
        nodo = nodos[i];
        campo = nodo.getAttribute('data-campo');
        texto = textoApi(usuario[campo]);

        if (nodo.hasAttribute('data-badge')) {
            nodo.setAttribute('data-estado', texto.toLowerCase());
            continue;
        }

        nodo.setAttribute('data-texto', texto);
    }
}

function asignarSolicitud(fila, solicitud) {
    var th = cabeceraSolicitudes.querySelector('[data-campo]');
    var clave = th ? th.getAttribute('data-campo') : '';

    asignarCeldasDeApi(fila, cabeceraSolicitudes, solicitud, clave);
}

if (panel && ficha) {
    var clave = claveId();
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

                    asignarUsuario(usuario);

                    if (!cuerpoSolicitudes || !cabeceraSolicitudes) {
                        return;
                    }

                    return apiRequest('solicitudes').then(function (solicitudesRespuesta) {
                        pintarFilasDeApi(cuerpoSolicitudes, listaDeApi(solicitudesRespuesta).filter(function (solicitud) {
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
