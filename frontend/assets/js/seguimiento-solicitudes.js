document.addEventListener('DOMContentLoaded', function () {
    var raiz = document.getElementById('seguimiento-solicitudes');
    var modal = document.getElementById('modal-detalle-solicitud');
    var mensaje = raiz && raiz.querySelector('[data-mensaje-solicitudes]');
    var mensajeDetalle = modal && modal.querySelector('[data-mensaje-detalle]');
    var btnFormulario = modal && modal.querySelector('[data-ver-formulario]');
    var panelFormulario = modal && modal.querySelector('[data-formulario-completado]');
    var grupos = [];
    var abierta = null;

    if (!raiz || !valorCookie('usuario_id')) {
        return;
    }

    raiz.querySelectorAll('[data-clave]').forEach(function (pane) {
        grupos.push({
            pane: pane,
            clave: normalizar(pane.getAttribute('data-clave')),
            recurso: pane.getAttribute('data-recurso-formulario'),
            lista: pane.querySelector('[data-lista]'),
            modelo: pane.querySelector('template[data-modelo]'),
            idTipo: null,
            items: []
        });
    });

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

        return Promise.all([apiRequest('tipos_solicitudes'), apiRequest('solicitudes')])
            .then(function (respuestas) {
                var tipos = listaDe(respuestas[0]);
                var usuario = valorCookie('usuario_id');
                var propias = listaDe(respuestas[1]).filter(function (item) {
                    return item && String(item.id_usuario) === usuario;
                });

                grupos.forEach(function (grupo) {
                    var tipo = tipos.filter(function (item) {
                        return item && normalizar(item.nombre_tipo).indexOf(grupo.clave) !== -1;
                    })[0];

                    grupo.idTipo = tipo ? String(tipo.id) : null;
                    grupo.items = propias.filter(function (item) {
                        return grupo.idTipo !== null && String(item.id_tipo_solicitud) === grupo.idTipo;
                    });
                });

                return sumarMascotas(propias);
            })
            .then(function () {
                grupos.forEach(pintarGrupo);
                pintarContadores();
            })
            .catch(function (error) {
                avisar(mensaje, error);
            })
            .then(function () {
                raiz.removeAttribute('data-cargando');
            });
    }

    function sumarMascotas(solicitudes) {
        var ids = [];

        solicitudes.forEach(function (item) {
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

            grupos.forEach(function (grupo) {
                grupo.items = grupo.items.map(function (item) {
                    var mascota = porId[String(item.id_mascota)] || {};

                    return Object.assign({
                        foto_url: mascota.foto_url,
                        especie: mascota.especie,
                        raza: mascota.raza,
                        'tamaño': mascota['tamaño']
                    }, item);
                });
            });
        });
    }

    function pintarGrupo(grupo) {
        var cantidad = grupo.pane.querySelector('[data-cantidad]');

        if (grupo.lista && grupo.modelo) {
            grupo.lista.innerHTML = '';

            grupo.items.forEach(function (item) {
                var copia = grupo.modelo.content.cloneNode(true);
                var fila = copia.firstElementChild;

                fila.setAttribute('data-id', String(item.id));
                llenarCampos(fila, item);
                grupo.lista.appendChild(copia);
            });
        }

        grupo.pane.setAttribute('data-total', String(grupo.items.length));

        if (cantidad) {
            cantidad.textContent = String(grupo.items.length);
        }
    }

    function pintarContadores() {
        raiz.querySelectorAll('[data-contador]').forEach(function (nodo) {
            var clave = normalizar(nodo.getAttribute('data-contar-clave'));
            var estado = normalizar(nodo.getAttribute('data-contar-estado'));
            var total = 0;

            grupos.forEach(function (grupo) {
                if (clave && grupo.clave !== clave) {
                    return;
                }

                total += grupo.items.filter(function (item) {
                    return !estado || normalizar(item.estado_solicitud) === estado;
                }).length;
            });

            nodo.textContent = String(total);
        });
    }

    function buscar(id) {
        var encontrada = null;

        grupos.forEach(function (grupo) {
            grupo.items.forEach(function (item) {
                if (String(item.id) === String(id)) {
                    encontrada = { grupo: grupo, item: item };
                }
            });
        });

        return encontrada;
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
            llenarCampos(encabezado, datos.item);
        }

        if (resumen) {
            llenarCampos(resumen, datos.item);
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

        if (!modal || !datos || !datos.grupo.recurso) {
            avisar(mensajeDetalle, { message: 'No encontramos el formulario de esta solicitud.' });
            return;
        }

        avisar(mensajeDetalle, null);

        if (btnFormulario) {
            btnFormulario.disabled = true;
        }

        modal.setAttribute('data-cargando', '1');

        apiRequest(datos.grupo.recurso, { parametros: { id_solicitud: id } })
            .then(function (respuesta) {
                var mezcla;

                if (abierta !== String(id)) {
                    return;
                }

                mezcla = Object.assign({}, datoDe(respuesta), datos.item);

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
        var pestana;
        var boton;

        if (!datos || !modal || !window.bootstrap) {
            return;
        }

        pestana = raiz.querySelector('[data-bs-target="#' + datos.grupo.pane.id + '"]');
        boton = datos.grupo.lista.querySelector('[data-id="' + datos.item.id + '"] [data-bs-target="#' + modal.id + '"]');

        if (pestana) {
            bootstrap.Tab.getOrCreateInstance(pestana).show();
        }

        bootstrap.Modal.getOrCreateInstance(modal).show(boton);

        if (boton) {
            abrirDetalle(datos.item.id);
        }
    }

    function llenarCampos(contenedor, datos) {
        contenedor.querySelectorAll('[data-campo]').forEach(function (nodo) {
            var valor = datos[nodo.getAttribute('data-campo')];

            if (nodo.tagName === 'IMG') {
                if (valor) {
                    nodo.setAttribute('src', valor);
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
        var texto;

        if (!caja) {
            return;
        }

        texto = origen ? mensajeDeApi(origen) : '';
        caja.textContent = texto || '';

        if (texto) {
            caja.setAttribute('data-estado', 'error');
        } else {
            caja.removeAttribute('data-estado');
        }
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
