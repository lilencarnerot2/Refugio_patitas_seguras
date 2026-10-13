document.addEventListener('DOMContentLoaded', function () {
    var panel = document.getElementById('panel-solicitud-detalle');

    if (!panel || panel.getAttribute('data-listo')) {
        return;
    }

    panel.setAttribute('data-listo', '1');

    var ficha = document.getElementById('ficha-solicitud');
    var mensaje = document.getElementById('mensaje-formulario');
    var botones = document.getElementById('botones-estado');
    var id = new URLSearchParams(window.location.search).get('id');
    var listado = panel.getAttribute('data-listado') || '';

    function mostrarAviso(origen, ok) {
        if (!mensaje) {
            return;
        }

        mensaje.textContent = mensajeDeApi(origen);

        if (ok) {
            mensaje.setAttribute('data-estado', 'ok');
        } else if (origen) {
            mensaje.setAttribute('data-estado', 'error');
        } else {
            mensaje.removeAttribute('data-estado');
        }
    }

    function texto(valor) {
        if (valor === undefined || valor === null) {
            return '';
        }

        return String(valor);
    }

    function esVerdadero(valor) {
        return valor === true || valor === 1 || valor === '1' || valor === 't' || valor === 'true';
    }

    function pintarFicha(solicitud) {
        ficha.querySelectorAll('[data-campo]').forEach(function (nodo) {
            var campo = nodo.getAttribute('data-campo');
            var valor = campo.indexOf('fecha') === 0 ? fechaCorta(solicitud[campo]) : texto(solicitud[campo]);

            if (valor !== '') {
                nodo.setAttribute('data-texto', valor);
            } else {
                nodo.removeAttribute('data-texto');
            }
        });
    }

    function datosDeRespuesta(respuesta) {
        var datos = respuesta && respuesta.data !== undefined ? respuesta.data : respuesta;

        if (Array.isArray(datos)) {
            return datos[0] || {};
        }

        return datos || {};
    }

    function traerFormulario(idSolicitud) {
        return apiRequest('formularios_adopcion', {
            parametros: { id_solicitud: idSolicitud }
        }).then(function (respuesta) {
            pintarFormulario(datosDeRespuesta(respuesta));
        }).catch(function (errorAdopcion) {
            return apiRequest('formularios_transito', {
                parametros: { id_solicitud: idSolicitud }
            }).then(function (respuesta) {
                pintarFormulario(datosDeRespuesta(respuesta));
            }).catch(function (errorTransito) {
                pintarFormulario({});
                mostrarAviso(errorAdopcion || errorTransito);
            });
        });
    }

    function pintarFormulario(datos) {
        var caja = document.getElementById('campos-formulario');
        var grupoMetros = document.getElementById('grupo-metros-patio');
        var grupoMascotas = document.getElementById('grupo-mascotas-hogar');
        var listaMascotas = document.getElementById('lista-mascotas-hogar');
        var modeloMascota = document.getElementById('modelo-mascota-hogar');

        if (!caja) {
            return;
        }

        caja.querySelectorAll('[data-campo]').forEach(function (nodo) {
            var valor = datos[nodo.getAttribute('data-campo')];

            if (nodo.type === 'checkbox') {
                nodo.checked = esVerdadero(valor);
                return;
            }

            nodo.value = texto(valor);
        });

        if (grupoMetros) {
            grupoMetros.hidden = !esVerdadero(datos.tiene_patio);
        }

        pintarLista('compromisos', datos.compromisos);
        pintarLista('condiciones_especiales', datos.condiciones_especiales);

        if (grupoMascotas && listaMascotas && modeloMascota) {
            while (listaMascotas.firstChild) {
                listaMascotas.removeChild(listaMascotas.firstChild);
            }

            grupoMascotas.hidden = !esVerdadero(datos.tiene_mascotas);

            (datos.mascotas_hogar || []).forEach(function (mascota) {
                var fila = modeloMascota.cloneNode(true);

                fila.removeAttribute('id');
                fila.querySelectorAll('[data-campo]').forEach(function (nodo) {
                    var valor = mascota[nodo.getAttribute('data-campo')];

                    if (nodo.type === 'checkbox') {
                        nodo.checked = esVerdadero(valor);
                        return;
                    }

                    nodo.value = texto(valor);
                });
                listaMascotas.appendChild(fila);
            });
        }
    }

    function pintarLista(nombre, items) {
        var caja = document.querySelector('[data-lista-valor="' + nombre + '"]');
        var modelo = document.getElementById('modelo-opcion-lista');

        if (!caja || !modelo) {
            return;
        }

        while (caja.firstChild) {
            caja.removeChild(caja.firstChild);
        }

        (items || []).forEach(function (item) {
            var opcion = modelo.cloneNode(true);
            var textoOpcion = opcion.querySelector('[data-texto]');

            opcion.removeAttribute('id');
            textoOpcion.textContent = textoDeItem(item);
            caja.appendChild(opcion);
        });
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
                mostrarAviso(respuesta);
                return null;
            }

            pintarFicha(solicitud);

            return traerFormulario(idSolicitud).then(function () {
                return solicitud;
            });
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
                var boton;

                if (!estado || texto(estado.id) === texto(idActual)) {
                    return;
                }

                boton = modelo.cloneNode(false);
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
                mostrarAviso(respuesta, true);

                if (solicitud) {
                    return cargarEstados(solicitud.id_estado_solicitud);
                }

                return null;
            });
        }).catch(function (error) {
            mostrarAviso(error);
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
        mostrarAviso(error);

        if (listado && error && error.status === 404) {
            window.location.href = listado;
        }
    });
});
