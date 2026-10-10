document.addEventListener('DOMContentLoaded', function () {
    var panel = document.getElementById('panel-solicitud-detalle');

    if (!panel) {
        return;
    }

    var ficha = document.getElementById('ficha-solicitud');
    var mensaje = document.getElementById('mensaje-formulario');
    var botones = document.getElementById('botones-estado');
    var id = new URLSearchParams(window.location.search).get('id');
    var listado = panel.getAttribute('data-listado') || '';

    function texto(valor) {
        if (valor === undefined || valor === null) {
            return '';
        }

        return String(valor);
    }

    function mostrar(idSolicitud) {
        return apiRequest('solicitudes', {
            parametros: { id: idSolicitud }
        }).then(function (respuesta) {
            var solicitud = (respuesta && respuesta.data) || {};

            if (Array.isArray(solicitud)) {
                solicitud = solicitud[0] || {};
            }

            if (!solicitud.id) {
                if (mensaje) {
                    mensaje.textContent = mensajeDeApi(respuesta);
                }

                return null;
            }

            ficha.querySelectorAll('[data-campo]').forEach(function (nodo) {
                var campo = nodo.getAttribute('data-campo');
                var valor = campo.indexOf('fecha') === 0 ? fechaCorta(solicitud[campo]) : texto(solicitud[campo]);

                if (valor !== '') {
                    nodo.setAttribute('data-texto', valor);
                } else {
                    nodo.removeAttribute('data-texto');
                }
            });

            [['id_mascota', solicitud.id_mascota],
                ['id_tipo_solicitud', solicitud.id_tipo_solicitud]
            ].forEach(function (par) {
                var entrada = document.getElementById(par[0]);
                var valor = texto(par[1]);

                if (!entrada) {
                    return;
                }

                entrada.value = valor;

                var grupo = entrada.closest('[data-formulario]');

                if (grupo) {
                    grupo.hidden = valor === '';
                }
            });

            return solicitud;
        });
    }

    function cargarEstados(idActual) {
        return apiRequest('estados_solicitudes').then(function (respuesta) {
            var datos = (respuesta && respuesta.data) || [];
            var modelo = document.getElementById('boton-modelo-estado');

            if (!Array.isArray(datos)) {
                datos = [datos];
            }

            while (botones.firstChild) {
                botones.removeChild(botones.firstChild);
            }

            if (!modelo) {
                return;
            }

            datos.forEach(function (estado) {
                if (!estado || texto(estado.id) === texto(idActual)) {
                    return;
                }

                // Botón diseñado en el modelo del HTML; aquí solo datos del backend.
                var boton = modelo.cloneNode(false);

                boton.removeAttribute('id');
                boton.setAttribute('data-id-estado', texto(estado.id));
                boton.textContent = texto(estado.nombre_estado);
                botones.appendChild(boton);
            });
        });
    }

    if (!id) {
        if (listado) {
            window.location.href = listado;
        }

        return;
    }

    botones.addEventListener('click', function (evento) {
        var boton = evento.target.closest('[data-id-estado]');

        if (!boton) {
            return;
        }

        apiRequest('solicitudes', {
            metodo: 'PUT',
            parametros: { id: id },
            cuerpo: { id_estado_solicitud: boton.getAttribute('data-id-estado') }
        }).then(function (respuesta) {
            return mostrar(id).then(function (solicitud) {
                if (mensaje) {
                    mensaje.textContent = mensajeDeApi(respuesta);
                }

                if (solicitud) {
                    return cargarEstados(solicitud.id_estado_solicitud);
                }

                return null;
            });
        }).catch(function (error) {
            if (mensaje) {
                mensaje.textContent = mensajeDeApi(error);
            }
        });
    });

    mostrar(id).then(function (solicitud) {
        if (!solicitud) {
            if (listado) {
                window.location.href = listado;
            }

            return null;
        }

        return cargarEstados(solicitud.id_estado_solicitud);
    }).catch(function (error) {
        if (mensaje) {
            mensaje.textContent = mensajeDeApi(error);
        }

        if (listado && error && error.status === 404) {
            window.location.href = listado;
        }
    });
});
