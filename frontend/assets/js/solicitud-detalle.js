document.addEventListener('DOMContentLoaded', function () {
    var panel = document.getElementById('panel-solicitud-detalle');

    if (!panel || panel.getAttribute('data-listo')) {
        return;
    }

    panel.setAttribute('data-listo', '1');

    var ficha = document.getElementById('ficha-solicitud');
    var fichaMascota = document.getElementById('ficha-mascota');
    var fichaPostulante = document.getElementById('ficha-postulante');
    var fichaEvaluacion = document.getElementById('ficha-evaluacion');
    var mensaje = document.getElementById('mensaje-formulario');
    var botones = document.getElementById('botones-estado');
    var id = new URLSearchParams(window.location.search).get('id');
    var listado = panel.getAttribute('data-listado') || '';
    var tipoPedido = panel.getAttribute('data-tipo') || '';

    function mostrarAviso(origen, ok) {
        avisoDeApi(mensaje, origen, ok);
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

    function esBooleano(valor) {
        return valor === true || valor === false || valor === 't' || valor === 'f' || valor === 'true' || valor === 'false';
    }

    function datoDe(obj, campo) {
        if (!obj) {
            return '';
        }

        if (obj[campo] !== undefined && obj[campo] !== null && obj[campo] !== '') {
            return obj[campo];
        }

        if (campo === 'tamaño') {
            return obj.tamano || obj.tamaño || '';
        }

        return '';
    }

    function siNo(valor) {
        if (valor === true || valor === 1 || valor === '1' || valor === 't' || valor === 'true') {
            return 'Sí';
        }

        if (valor === false || valor === 0 || valor === '0' || valor === 'f' || valor === 'false') {
            return 'No';
        }

        return texto(valor);
    }

    function pintarCaja(caja, datos) {
        if (!caja || !datos) {
            return;
        }

        caja.querySelectorAll('[data-campo]').forEach(function (nodo) {
            var campo = nodo.getAttribute('data-campo');
            var valor = datoDe(datos, campo);

            if (valor && typeof valor === 'object') {
                valor = '';
            }

            if (campo.indexOf('fecha') === 0) {
                valor = fechaCorta(valor);
            }

            if (nodo.type === 'checkbox') {
                nodo.checked = esVerdadero(valor);
                return;
            }

            if (nodo.tagName === 'INPUT' || nodo.tagName === 'TEXTAREA') {
                nodo.value = esBooleano(valor) ? siNo(valor) : texto(valor);
                return;
            }

            if (texto(valor) !== '') {
                nodo.setAttribute('data-texto', texto(valor));
            } else {
                nodo.removeAttribute('data-texto');
            }
        });
    }

    function pintarFicha(solicitud) {
        if (ficha) {
            pintarCaja(ficha, solicitud);
        }

        var estadoResumen = document.querySelector('#ficha-resolucion [data-campo="estado_solicitud"]');

        if (estadoResumen) {
            if (texto(solicitud.estado_solicitud) !== '') {
                estadoResumen.setAttribute('data-texto', texto(solicitud.estado_solicitud));
            } else {
                estadoResumen.removeAttribute('data-texto');
            }
        }
    }

    function datosDeRespuesta(respuesta) {
        var datos = respuesta && respuesta.data !== undefined ? respuesta.data : respuesta;

        if (Array.isArray(datos)) {
            return datos[0] || {};
        }

        return datos || {};
    }

    function traerFormulario(idSolicitud) {
        var pedido = normalizarTipo(tipoPedido);

        function vacio(error) {
            pintarFormulario({});

            if (error) {
                mostrarAviso(error);
            }
        }

        if (pedido.indexOf('transito') !== -1) {
            return apiRequest('formularios_transito', {
                parametros: { id_solicitud: idSolicitud }
            }).then(function (respuesta) {
                pintarFormulario(datosDeRespuesta(respuesta));
            }).catch(vacio);
        }

        return apiRequest('formularios_adopcion', {
            parametros: { id_solicitud: idSolicitud }
        }).then(function (respuesta) {
            pintarFormulario(datosDeRespuesta(respuesta));
        }).catch(function (errorAdopcion) {
            if (pedido.indexOf('adopcion') !== -1) {
                vacio(errorAdopcion);
                return;
            }

            return apiRequest('formularios_transito', {
                parametros: { id_solicitud: idSolicitud }
            }).then(function (respuesta) {
                pintarFormulario(datosDeRespuesta(respuesta));
            }).catch(function (errorTransito) {
                vacio(errorAdopcion || errorTransito);
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

            nodo.value = esBooleano(valor) ? siNo(valor) : texto(valor);
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
                mostrarAviso(respuesta, false);
                return null;
            }

            if (tipoPedido && normalizarTipo(solicitud.tipo_solicitud).indexOf(normalizarTipo(tipoPedido)) === -1) {
                if (listado) {
                    window.location.href = listado;
                }

                return null;
            }

            pintarFicha(solicitud);

            return Promise.all([
                traerFormulario(idSolicitud),
                traerMascota(solicitud.id_mascota),
                traerEvaluacion(idSolicitud),
                traerPostulante(solicitud, solicitud.id_usuario)
            ]).then(function () {
                return solicitud;
            });
        });
    }

    function normalizarTipo(valor) {
        return texto(valor).toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    }

    function listaDe(respuesta) {
        var origen = respuesta && respuesta.data !== undefined ? respuesta.data : respuesta;

        return Array.isArray(origen) ? origen : (origen ? [origen] : []);
    }

    function clonarModelo(id) {
        var modelo = document.getElementById(id);

        if (!modelo) {
            return null;
        }

        var copia = modelo.cloneNode(true);

        copia.removeAttribute('id');
        copia.hidden = false;

        return copia;
    }

    function pintarContactosPostulante(contactos) {
        var lista = document.getElementById('lista-contacto-postulante');
        var seccion = document.getElementById('seccion-contacto-postulante');

        if (!lista) {
            return;
        }

        lista.innerHTML = '';

        (contactos || []).forEach(function (item) {
            var tipo = texto(item.tipo_contacto) || 'Contacto';
            var valor = texto(item.valor);
            var fila;

            if (valor === '') {
                return;
            }

            fila = clonarModelo('modelo-item-contacto-postulante');

            if (!fila) {
                return;
            }

            fila.querySelector('.detalle-label').textContent = tipo;
            fila.querySelector('span:last-child').setAttribute('data-texto', valor);
            lista.appendChild(fila);
        });

        if (seccion) {
            seccion.hidden = lista.children.length === 0;
        }
    }

    function ocultarCamposVaciosPostulante() {
        var seccionDomicilio = document.getElementById('seccion-domicilio-postulante');

        if (!seccionDomicilio) {
            return;
        }

        seccionDomicilio.hidden = !seccionDomicilio.querySelector('.pet-detail-meta [data-texto]');
    }

    function traerPostulante(solicitud, idUsuario) {
        if (!fichaPostulante) {
            return Promise.resolve();
        }

        var base = {
            nombre: texto(solicitud.nombre_usuario),
            apellido: texto(solicitud.apellido_usuario)
        };

        pintarCaja(fichaPostulante, base);
        pintarContactosPostulante([]);
        ocultarCamposVaciosPostulante();

        if (!idUsuario) {
            return Promise.resolve();
        }

        return Promise.all([
            apiRequest('usuarios', { parametros: { id: idUsuario } }).catch(function () {
                return null;
            }),
            apiRequest('contactos', { parametros: { id_usuario: idUsuario } }).catch(function () {
                return null;
            }),
            apiRequest('domicilios', { parametros: { id_usuario: idUsuario } }).catch(function () {
                return null;
            })
        ]).then(function (respuestas) {
            var usuario = datosDeRespuesta(respuestas[0] || {});
            var contactos = listaDe(respuestas[1]);
            var domicilio = datosDeRespuesta(respuestas[2] || {});

            pintarCaja(fichaPostulante, Object.assign({}, domicilio, {
                nombre: texto(usuario.nombre) || base.nombre,
                apellido: texto(usuario.apellido) || base.apellido
            }));
            pintarContactosPostulante(contactos);
            ocultarCamposVaciosPostulante();
        });
    }

    function traerMascota(idMascota) {
        if (!fichaMascota || !idMascota) {
            return Promise.resolve();
        }

        return apiRequest('mascotas', { parametros: { id: idMascota } }).then(function (respuesta) {
            var mascota = datosDeRespuesta(respuesta);
            var foto = document.getElementById('detalle-foto');
            var vacia = document.getElementById('detalle-foto-vacia');

            pintarCaja(fichaMascota, mascota);
            pintarIconoEspecieMascota(fichaMascota, mascota);

            if (foto && texto(mascota.foto_url) !== '') {
                foto.src = mascota.foto_url;
                foto.alt = texto(mascota.nombre);
                foto.hidden = false;

                if (vacia) {
                    vacia.hidden = true;
                }
            }
        }).catch(function () {
            return null;
        });
    }

    function traerEvaluacion(idSolicitud) {
        var listaRazones = document.getElementById('lista-razones');
        var avisoEval = document.getElementById('aviso-evaluacion');

        if (!fichaEvaluacion) {
            return Promise.resolve();
        }

        return apiRequest('compatibilidad', {
            parametros: { id_solicitud: idSolicitud }
        }).then(function (respuesta) {
            var datos = datosDeRespuesta(respuesta);
            var info = datos.compatibilidad || datos;

            pintarCaja(fichaEvaluacion, info);
            fichaEvaluacion.setAttribute('data-cargado', '1');

            if (!listaRazones) {
                return;
            }

            while (listaRazones.firstChild) {
                listaRazones.removeChild(listaRazones.firstChild);
            }

            var razones = info.razones;

            if (!Array.isArray(razones)) {
                razones = textoDeItem(razones) ? [razones] : [];
            }

            razones.forEach(function (razon) {
                var item = document.createElement('li');
                var detalle = textoDeItem(razon);

                if (detalle === '') {
                    return;
                }

                item.textContent = detalle;
                listaRazones.appendChild(item);
            });
        }).catch(function (error) {
            if (avisoEval) {
                avisoEval.textContent = mensajeDeApi(error);
            }

            return null;
        });
    }

    function cargarEstados(idActual) {
        if (!botones) {
            return Promise.resolve();
        }

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
                var nombre = texto(estado && estado.nombre_estado);
                var clave = normalizarTipo(nombre);

                if (!estado || texto(estado.id) === texto(idActual)) {
                    return;
                }

                if (botones.hasAttribute('data-resolver') && clave.indexOf('aprob') === -1 && clave.indexOf('rechaz') === -1) {
                    return;
                }

                boton = modelo.cloneNode(false);
                boton.removeAttribute('id');
                boton.setAttribute('data-id-estado', texto(estado.id));
                boton.textContent = nombre;

                if (clave.indexOf('rechaz') !== -1) {
                    boton.className = 'btn btn-outline';
                } else if (clave.indexOf('aprob') !== -1) {
                    boton.className = 'btn btn-primary';
                }

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

    if (botones) {
        botones.addEventListener('click', function (evento) {
            var boton = evento.target.closest('[data-id-estado]');

            if (!boton) {
                return;
            }

            avisoEsperandoApi(mensaje);

            apiRequest('solicitudes', {
                metodo: 'PUT',
                parametros: { id: id },
                cuerpo: { id_estado_solicitud: boton.getAttribute('data-id-estado') }
            }).then(function (respuesta) {
                var solicitud = (respuesta && respuesta.data) || {};

                if (Array.isArray(solicitud)) {
                    solicitud = solicitud[0] || {};
                }

                mostrarAviso(respuesta, true);

                if (solicitud.id) {
                    pintarFicha(solicitud);
                    cargarEstados(solicitud.id_estado_solicitud);
                }
            }).catch(function (error) {
                mostrarAviso(error, false);
            });
        });
    }

    mostrar(id).then(function (solicitud) {
        if (solicitud) {
            return cargarEstados(solicitud.id_estado_solicitud);
        }

        if (mensaje && texto(mensaje.textContent).trim() !== '') {
            return null;
        }

        if (listado) {
            window.location.href = listado;
        }

        return null;
    }).catch(function (error) {
        mostrarAviso(error, false);

        if (listado && error && error.status === 404 && (!mensaje || texto(mensaje.textContent).trim() === '')) {
            window.location.href = listado;
        }
    });
});
