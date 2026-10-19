document.addEventListener('DOMContentLoaded', function () {
    var raiz = document.getElementById('seguimiento-solicitudes');
    var modal = document.getElementById('modal-detalle-solicitud');
    var mensaje = raiz && raiz.querySelector('[data-mensaje-solicitudes]');
    var mensajeDetalle = modal && modal.querySelector('[data-mensaje-detalle]');
    var btnFormulario = modal && modal.querySelector('[data-ver-formulario]');
    var panelFormulario = modal && modal.querySelector('[data-formulario-completado]');
    var grupo = raiz && raiz.querySelector('[data-grupo]');
    var lista = grupo && grupo.querySelector('[data-lista]');
    var modelo = raiz && raiz.querySelector('template[data-modelo]');
    var solicitudes = [];
    var abierta = null;

    if (!raiz || !valorCookie('usuario_id')) {
        return;
    }

    cargar().then(abrirDesdeUrl);

    if (modal) {
        modal.addEventListener('show.bs.modal', function (evento) {
            var fila = evento.relatedTarget && evento.relatedTarget.closest('[data-id]');

            if (fila) {
                prepararModal(fila.getAttribute('data-id'));
            }
        });

        modal.addEventListener('hidden.bs.modal', function () {
            abierta = null;
            resetFormularioModal();
        });
    }

    if (btnFormulario) {
        btnFormulario.addEventListener('click', function () {
            if (abierta) {
                cargarFormularioCompletado(abierta);
            }
        });
    }

    function cargar() {
        avisar(mensaje, null);
        raiz.setAttribute('data-cargando', '1');

        return apiRequest('solicitudes')
            .then(function (respuesta) {
                var usuario = valorCookie('usuario_id');

                solicitudes = listaDe(respuesta).filter(function (item) {
                    return item && String(item.id_usuario) === usuario;
                }).map(function (item) {
                    return Object.assign({ clave: claveDeTipo(item) }, item);
                }).sort(ordenarPorFecha);

                return sumarMascotas(solicitudes);
            })
            .then(function () {
                pintarLista();
                pintarContadores();
            })
            .catch(function (error) {
                avisar(mensaje, error);
            })
            .then(function () {
                raiz.removeAttribute('data-cargando');
            });
    }

    function claveDeTipo(solicitud) {
        return esTipoSolicitudTransito(solicitud.tipo_solicitud) ? 'transito' : 'adopcion';
    }

    function ordenarPorFecha(a, b) {
        var porFecha = String(b.fecha_solicitud || '').localeCompare(String(a.fecha_solicitud || ''));

        return porFecha !== 0 ? porFecha : Number(b.id) - Number(a.id);
    }

    function sumarMascotas(lista) {
        var ids = [];

        lista.forEach(function (item) {
            var id = item.id_mascota;

            if (id !== undefined && id !== null && ids.indexOf(String(id)) === -1) {
                ids.push(String(id));
            }
        });

        return Promise.all(ids.map(function (id) {
            return apiRequest('mascotas', { parametros: { id: id } })
                .then(function (respuesta) {
                    return datoDe(respuesta);
                })
                .catch(function () {
                    return null;
                });
        })).then(function (mascotas) {
            var porId = {};

            ids.forEach(function (id, i) {
                porId[id] = mascotas[i] || {};
            });

            solicitudes = solicitudes.map(function (item) {
                var mascota = porId[String(item.id_mascota)] || {};

                return Object.assign({
                    foto_url: mascota.foto_url,
                    especie: mascota.especie,
                    raza: mascota.raza,
                    'tamaño': mascota['tamaño']
                }, item);
            });
        });
    }

    function pintarLista() {
        var cantidad = grupo && grupo.querySelector('[data-cantidad]');

        if (lista && modelo) {
            lista.innerHTML = '';

            solicitudes.forEach(function (item) {
                var copia = modelo.content.cloneNode(true);
                var fila = copia.firstElementChild;

                fila.setAttribute('data-id', String(item.id));
                fila.setAttribute('data-tipo', item.clave);
                llenarCampos(fila, item);
                lista.appendChild(copia);
            });
        }

        if (grupo) {
            grupo.setAttribute('data-total', String(solicitudes.length));
        }

        if (cantidad) {
            cantidad.textContent = String(solicitudes.length);
        }
    }

    function pintarContadores() {
        raiz.querySelectorAll('[data-contador]').forEach(function (nodo) {
            var estado = normalizar(nodo.getAttribute('data-contar-estado'));

            nodo.textContent = String(solicitudes.filter(function (item) {
                return !estado || normalizar(item.estado_solicitud) === estado;
            }).length);
        });
    }

    function buscar(id) {
        var encontrada = null;

        solicitudes.forEach(function (item) {
            if (String(item.id) === String(id)) {
                encontrada = item;
            }
        });

        return encontrada;
    }

    function recursoDeFormulario(item) {
        return item.clave === 'transito' ? 'formularios_transito' : 'formularios_adopcion';
    }

    function prepararModal(id) {
        var datos = buscar(id);
        var encabezado;
        var resumen;

        if (!modal || !datos) {
            return;
        }

        abierta = String(id);
        avisar(mensajeDetalle, null);
        resetFormularioModal();

        encabezado = modal.querySelector('.modal-header');
        resumen = modal.querySelector('.seguimiento-estado');

        if (encabezado) {
            llenarCampos(encabezado, datos);
        }

        if (resumen) {
            llenarCampos(resumen, datos);
        }
    }

    function resetFormularioModal() {
        if (panelFormulario) {
            panelFormulario.hidden = true;
            panelFormulario.querySelectorAll('[data-campo]').forEach(function (nodo) {
                nodo.textContent = '';

                if (nodo.classList.contains('estado-solicitud')) {
                    nodo.removeAttribute('data-estado-solicitud');
                }
            });
        }

        if (btnFormulario) {
            btnFormulario.hidden = false;
            btnFormulario.disabled = false;
        }

        if (modal) {
            modal.removeAttribute('data-cargando');
        }
    }

    function cargarFormularioCompletado(id) {
        var datos = buscar(id);

        if (!modal || !datos) {
            var textoSinFormulario = modal && modal.getAttribute('data-aviso-sin-formulario');

            avisar(mensajeDetalle, textoSinFormulario ? { success: false, message: textoSinFormulario } : null);
            return;
        }

        avisar(mensajeDetalle, null);

        if (btnFormulario) {
            btnFormulario.disabled = true;
        }

        modal.setAttribute('data-cargando', '1');

        apiRequest(recursoDeFormulario(datos), { parametros: { id_solicitud: id } })
            .then(function (respuesta) {
                var mezcla;

                if (abierta !== String(id)) {
                    return;
                }

                mezcla = Object.assign({}, datosFormularioDeRespuesta(respuesta, {
                    id_solicitud: id,
                    id_usuario: datos.id_usuario
                }) || {}, datos);

                if (panelFormulario) {
                    llenarCampos(panelFormulario, mezcla);
                    panelFormulario.hidden = false;
                }

                if (btnFormulario) {
                    btnFormulario.hidden = true;
                }
            })
            .catch(function (error) {
                if (abierta === String(id)) {
                    avisar(mensajeDetalle, error);

                    if (btnFormulario) {
                        btnFormulario.disabled = false;
                    }
                }
            })
            .then(function () {
                if (abierta === String(id) && modal) {
                    modal.removeAttribute('data-cargando');
                }
            });
    }

    function abrirDetalle(id) {
        prepararModal(id);
        cargarFormularioCompletado(id);
    }

    function abrirDesdeUrl() {
        var param = raiz.getAttribute('data-param-detalle');
        var id = param && new URLSearchParams(window.location.search).get(param);
        var datos = id && buscar(id);
        var boton;

        if (!datos || !lista || !modal || !window.bootstrap) {
            return;
        }

        boton = lista.querySelector('[data-id="' + datos.id + '"] [data-bs-target="#' + modal.id + '"]');

        bootstrap.Modal.getOrCreateInstance(modal).show(boton);

        if (boton) {
            abrirDetalle(datos.id);
        }
    }

    function llenarCampos(contenedor, datos) {
        contenedor.querySelectorAll('[data-campo]').forEach(function (nodo) {
            var valor = datos[nodo.getAttribute('data-campo')];

            if (nodo.tagName === 'IMG') {
                if (valor) {
                    nodo.setAttribute('src', urlFotoMascota(valor));
                    nodo.setAttribute('alt', datos.nombre_mascota || '');
                    nodo.addEventListener('error', function () {
                        nodo.removeAttribute('src');
                    });
                } else {
                    nodo.removeAttribute('src');
                }
                return;
            }

            nodo.textContent = formatear(nodo, valor);

            if (nodo.classList.contains('estado-solicitud')) {
                nodo.setAttribute('data-estado-solicitud', normalizar(valor));
            }
        });
    }

    function formatear(nodo, valor) {
        var textos = modal || raiz;

        if (valor === undefined || valor === null || valor === '') {
            return '';
        }

        if (typeof valor === 'boolean') {
            return textos.getAttribute(valor ? 'data-si' : 'data-no') || '';
        }

        if (Array.isArray(valor)) {
            return valor.map(function (item) {
                if (item && item.descripcion) {
                    return String(item.descripcion);
                }

                if (item && item.nombre) {
                    return String(item.nombre);
                }

                return textoDeItem(item);
            }).filter(Boolean).join(', ');
        }

        if (nodo.getAttribute('data-formato') === 'fecha') {
            return fechaCorta(valor);
        }

        return String(valor);
    }

    function avisar(caja, origen) {
        if (!caja) {
            return;
        }

        if (!origen) {
            avisoDeApi(caja, '', true);
            return;
        }

        avisoDeApi(caja, origen, origen.success !== false && !(origen instanceof Error));
    }

    function listaDe(respuesta) {
        var origen = respuesta && respuesta.data !== undefined ? respuesta.data : respuesta;

        return Array.isArray(origen) ? origen : [];
    }

    function datoDe(respuesta) {
        var origen = respuesta && respuesta.data !== undefined ? respuesta.data : respuesta;

        if (Array.isArray(origen)) {
            return origen[0] || {};
        }

        return origen || {};
    }

    function normalizar(texto) {
        return String(texto || '').trim().toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    }

    function valorCookie(nombre) {
        var partes = document.cookie.split(';');
        var clave = nombre + '=';
        var i;
        var parte;

        for (i = 0; i < partes.length; i++) {
            parte = partes[i].trim();

            if (parte.indexOf(clave) === 0) {
                return decodeURIComponent(parte.slice(clave.length));
            }
        }

        return '';
    }
});
