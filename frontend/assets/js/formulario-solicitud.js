document.addEventListener('DOMContentLoaded', function () {
    var acceso = document.getElementById('acceso-solicitud');

    if (acceso && acceso.getAttribute('data-listo')) {
        return;
    }

    if (acceso) {
        acceso.setAttribute('data-listo', '1');
    }
    var bloque = document.getElementById('bloque-solicitud');
    var avisoAcceso = document.getElementById('mensaje-solicitud');
    var formulario = document.getElementById('formulario-solicitud');
    var mensaje = document.getElementById('mensaje-formulario');

    if (!acceso || !formulario) {
        return;
    }

    apiRequest('tipos_solicitudes', {
        credentials: /(?:^|;\s*)usuario_id=[^;]+/.test(document.cookie) ? 'include' : 'omit'
    })
        .then(function (respuesta) {
            var tipos = (respuesta && respuesta.data) || [];
            var clave = String(formulario.getAttribute('data-clave') || '').toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
            var tipo = tipos.filter(function (item) {
                var nombre = String((item && item.nombre_tipo) || '').toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
                return clave && nombre.indexOf(clave) !== -1;
            })[0] || tipos[0];
            var idMascota = new URLSearchParams(window.location.search).get('id');

            pegar('id_mascota', idMascota);
            pegar('id_tipo_solicitud', tipo && tipo.id);

            if (idMascota) {
                return apiRequest('mascotas', { parametros: { id: idMascota } }).then(function (ficha) {
                    document.querySelectorAll('#ficha-mascota [data-campo]').forEach(function (nodo) {
                        var valor = ((ficha && ficha.data) || {})[nodo.getAttribute('data-campo')];

                        if (nodo.tagName === 'IMG') {
                            if (valor) {
                                nodo.src = valor;
                                nodo.hidden = false;
                            }
                            return;
                        }

                        nodo.textContent = valor || '';
                    });
                });
            }

            return null;
        })
        .then(cargarCatalogos)
        .then(cargarListas)
        .then(function () {
            var idMascota = formulario.elements.id_mascota && formulario.elements.id_mascota.value;

            if (acceso) {
                acceso.removeAttribute('data-estado');
            }

            if (acceso && !idMascota && document.getElementById('sin-mascota')) {
                acceso.setAttribute('data-estado', 'sin-mascota');
                return;
            }

            if (bloque) {
                bloque.hidden = false;
            }

            aplicarDependencias();
            aplicarFechasMinimas();
            ponerBotonesLimpiar(formulario);
        })
        .catch(function (error) {
            if (acceso) {
                acceso.setAttribute('data-estado', 'error');
            }

            if (bloque) {
                bloque.hidden = true;
            }

            mostrarAviso(avisoAcceso, error);
        });

    formulario.addEventListener('submit', function (evento) {
        evento.preventDefault();
        enviar();
    });

    formulario.addEventListener('mousedown', function (evento) {
        var select = evento.target.closest('select');

        if (!select || evento.target.closest('.btn-limpiar-campo')) {
            return;
        }

        evento.preventDefault();
        abrirLista(select);
    });

    document.addEventListener('mousedown', function (evento) {
        var abierta = document.querySelector('.lista-desplegable[data-abierta="1"]');

        if (abierta && !abierta.contains(evento.target) && !evento.target.closest('select')) {
            cerrarLista();
        }
    });

    formulario.addEventListener('click', function (evento) {
        var quitar = evento.target.closest('.btn-quitar-mascota');
        var fila;
        var boton = evento.target.closest('.btn-limpiar-campo');
        var campo;

        if (quitar) {
            fila = quitar.closest('#lista-mascotas-hogar > *');

            if (fila && fila.parentNode) {
                fila.parentNode.removeChild(fila);
            }

            return;
        }

        if (!boton) {
            return;
        }

        campo = boton.parentNode.querySelector('select, input, textarea');

        if (!campo || campo.type === 'hidden') {
            return;
        }

        if (campo.type === 'checkbox') {
            campo.checked = false;
        } else {
            campo.value = '';
        }

        if (campo.getAttribute('data-carga')) {
            cargarHijo(campo);
        }

        if (campo.name === 'tiene_patio' || campo.name === 'tiene_mascotas') {
            aplicarDependencias();
        }

        if (campo.type === 'date') {
            aplicarFechasMinimas();
        }
    });

    formulario.addEventListener('change', function (evento) {
        var origen = evento.target.closest('select[data-carga]');

        if (evento.target.name === 'tiene_patio' || evento.target.name === 'tiene_mascotas') {
            aplicarDependencias();
        }

        if (evento.target.type === 'date') {
            aplicarFechasMinimas();
        }

        if (origen) {
            cargarHijo(origen);
        }
    });

    var botonMascota = document.getElementById('agregar-mascota-hogar');

    if (botonMascota) {
        botonMascota.addEventListener('click', function () {
            agregarMascotaHogar();
        });
    }

    function mostrarAviso(caja, origen, ok) {
        avisoDeApi(caja, origen, ok, formulario);
    }

    function pegar(nombre, valor) {
        if (formulario.elements[nombre] && valor) {
            formulario.elements[nombre].value = valor;
        }
    }

    function cuerpoDelFormulario() {
        var cuerpo = {};

        Array.prototype.forEach.call(formulario.elements, function (nodo) {
            if (!nodo.name) {
                return;
            }

            if (nodo.closest('[data-lista]') || nodo.closest('#lista-mascotas-hogar') || nodo.disabled) {
                return;
            }

            if (nodo.type === 'checkbox') {
                cuerpo[nodo.name] = nodo.checked;
                return;
            }

            if (nodo.type === 'time') {
                cuerpo[nodo.name] = nodo.value ? parseInt(nodo.value, 10) : null;
                return;
            }

            if (nodo.type === 'number') {
                cuerpo[nodo.name] = nodo.value === '' ? null : Number(nodo.value);
                return;
            }

            cuerpo[nodo.name] = nodo.value || null;
        });



        cuerpo.compromisos = valoresLista('compromisos');
        cuerpo.condiciones_especiales = valoresLista('condiciones_especiales');
        cuerpo.mascotas_hogar = cuerpo.tiene_mascotas ? mascotasHogar() : [];

        if (!cuerpo.tiene_patio) {
            cuerpo.metros_cuadrados_patio = null;
        }

        return cuerpo;
    }

    function valoresLista(nombre) {
        var caja = formulario.querySelector('[data-name="' + nombre + '"]');
        var ids = [];

        if (!caja) {
            return ids;
        }

        caja.querySelectorAll('input:checked').forEach(function (nodo) {
            ids.push(Number(nodo.value));
        });

        return ids;
    }

    function mascotasHogar() {
        var filas = [];
        var lista = document.getElementById('lista-mascotas-hogar');

        if (!lista) {
            return filas;
        }

        Array.prototype.forEach.call(lista.children, function (fila) {
            var tipo = fila.querySelector('[name="id_tipo_mascota"]');
            var item = {};

            Array.prototype.forEach.call(fila.querySelectorAll('[name]'), function (nodo) {
                if (nodo.type === 'checkbox') {
                    item[nodo.name] = nodo.checked;
                    return;
                }

                if (nodo.type === 'number') {
                    item[nodo.name] = nodo.value === '' ? null : Number(nodo.value);
                    return;
                }

                item[nodo.name] = nodo.value || null;
            });

            if (tipo && tipo.value) {
                filas.push(item);
            }
        });

        return filas;
    }

    function listaDe(respuesta) {
        var origen = respuesta && respuesta.data !== undefined ? respuesta.data : respuesta;

        return Array.isArray(origen) ? origen : [];
    }

    function nombreDeItem(item) {
        return textoDeItem(item);
    }

    function vaciarSelect(select) {
        if (!select) {
            return;
        }

        while (select.options.length) {
            select.remove(0);
        }
    }

    function llenarSelect(select, lista) {
        var base = document.getElementById('modelo-opcion-select') || (select && select.querySelector('option'));
        var vacia;

        vaciarSelect(select);

        if (!select || !base) {
            return;
        }

        vacia = base.cloneNode(false);
        vacia.removeAttribute('id');
        vacia.removeAttribute('disabled');
        vacia.setAttribute('hidden', 'hidden');
        vacia.value = '';
        vacia.textContent = '';
        vacia.selected = true;
        select.appendChild(vacia);

        (lista || []).forEach(function (item) {
            var opcion;

            if (!item || item.id === undefined || item.id === null) {
                return;
            }

            opcion = base.cloneNode(false);
            opcion.removeAttribute('id');
            opcion.removeAttribute('selected');
            opcion.removeAttribute('disabled');
            opcion.value = String(item.id);
            opcion.textContent = nombreDeItem(item);
            select.appendChild(opcion);
        });
    }

    function ponerBotonesLimpiar(caja) {
        var modelo = document.getElementById('modelo-limpiar-campo');

        if (!caja || !modelo) {
            return;
        }

        caja.querySelectorAll('select, input.form-control, textarea').forEach(function (nodo) {
            var boton;
            var wrap;

            if (!nodo.name || nodo.type === 'hidden' || nodo.closest('.campo-con-x')) {
                return;
            }

            wrap = document.getElementById('modelo-campo-con-x');

            if (wrap && wrap.content && wrap.content.firstElementChild) {
                wrap = wrap.content.firstElementChild.cloneNode(false);
            } else {
                wrap = document.createElement('div');
            }

            nodo.parentNode.insertBefore(wrap, nodo);
            wrap.appendChild(nodo);
            boton = modelo.cloneNode(true);
            boton.removeAttribute('id');
            wrap.appendChild(boton);
        });
    }

    function fechaDeHoy() {
        var hoy = new Date();

        hoy.setMinutes(hoy.getMinutes() - hoy.getTimezoneOffset());

        return hoy.toISOString().slice(0, 10);
    }

    function diaSiguiente(fecha) {
        var dia = new Date(fecha + 'T00:00:00Z');

        dia.setUTCDate(dia.getUTCDate() + 1);

        return dia.toISOString().slice(0, 10);
    }

    function aplicarFechasMinimas() {
        var hoy = fechaDeHoy();

        formulario.querySelectorAll('input[type="date"][data-min-hoy]').forEach(function (campo) {
            var origen = formulario.elements[campo.getAttribute('data-min-desde') || ''];
            var minimo = origen && origen.value ? diaSiguiente(origen.value) : hoy;

            minimo = minimo > hoy ? minimo : hoy;
            campo.min = minimo;

            if (campo.value && campo.value < minimo) {
                campo.value = minimo;
            }

            if (!campo.getAttribute('data-calendario-enlazado')) {
                campo.setAttribute('data-calendario-enlazado', '1');
                campo.addEventListener('focus', function () {
                    reservarEspacioCalendario(campo);
                });
                campo.addEventListener('click', function () {
                    reservarEspacioCalendario(campo);
                });
            }
        });
    }

    function limpiarFormulario() {
        var lista = document.getElementById('lista-mascotas-hogar');

        formulario.reset();

        while (lista && lista.firstChild) {
            lista.removeChild(lista.firstChild);
        }

        formulario.querySelectorAll('select[data-carga]').forEach(function (select) {
            cargarHijo(select);
        });

        aplicarDependencias();
        aplicarFechasMinimas();
    }

    function aplicarDependencias() {
        var patio = formulario.elements.tiene_patio;
        var metros = formulario.elements.metros_cuadrados_patio;
        var grupoMetros = document.getElementById('grupo-metros-patio');
        var grupoMascotas = document.getElementById('grupo-mascotas-hogar');
        var hayPatio = patio && patio.checked;
        var hayMascotas = formulario.elements.tiene_mascotas && formulario.elements.tiene_mascotas.checked;

        if (metros) {
            metros.disabled = !hayPatio;
            metros.required = hayPatio;

            if (!hayPatio) {
                metros.value = '';
            }
        }

        if (grupoMetros) {
            grupoMetros.hidden = !hayPatio;
        }

        if (grupoMascotas) {
            grupoMascotas.hidden = !hayMascotas;
            grupoMascotas.querySelectorAll('[name]').forEach(function (nodo) {
                nodo.disabled = !hayMascotas;
            });
        }
    }

    function cargarCatalogos() {
        var vistos = {};
        var pedidos = [];

        document.querySelectorAll('#formulario-solicitud [data-catalogo]:not([data-param]), #modelos-adopcion [data-catalogo]:not([data-param])').forEach(function (nodo) {
            var recurso = nodo.getAttribute('data-catalogo');

            if (!recurso || vistos[recurso]) {
                return;
            }

            vistos[recurso] = true;
            pedidos.push(apiRequest(recurso).then(function (respuesta) {
                var lista = listaDe(respuesta);

                document.querySelectorAll('#formulario-solicitud select[data-catalogo="' + recurso + '"]:not([data-param]), #modelos-adopcion select[data-catalogo="' + recurso + '"]').forEach(function (select) {
                    llenarSelect(select, lista);
<<<<<<< Updated upstream
=======
                    // Re‑enable the select after loading
                    select.disabled = false;
                    select.removeAttribute('data-loading');
                    // Add placeholder for motivo de adopción if this is that catalog
                    if (recurso === 'motivos_adopcion') {
                        var placeholder = select.querySelector('option[value=""]');
                        if (placeholder) {
                            placeholder.textContent = 'Seleccione un motivo de adopción';
                            placeholder.disabled = true;
                            placeholder.hidden = false;
                            placeholder.selected = true;
                        }
                    }
>>>>>>> Stashed changes
                });
            }));
        });

        return Promise.all(pedidos);
    }

    function cargarListas() {
        var pedidos = [];

        formulario.querySelectorAll('[data-lista]').forEach(function (caja) {
            var recurso = caja.getAttribute('data-lista');
            var nombre = caja.getAttribute('data-name');

            pedidos.push(apiRequest(recurso).then(function (respuesta) {
                var modelo = document.getElementById('modelo-opcion-lista');

                listaDe(respuesta).forEach(function (item) {
                    var opcion;
                    var entrada;
                    var textoOpcion;

                    if (!item || !modelo) {
                        return;
                    }

                    opcion = modelo.cloneNode(true);
                    opcion.removeAttribute('id');
                    entrada = opcion.querySelector('input');
                    textoOpcion = opcion.querySelector('[data-texto]');
                    entrada.name = nombre;
                    entrada.value = String(item.id);
                    entrada.required = caja.hasAttribute('data-requerido');
                    textoOpcion.textContent = nombreDeItem(item);
                    caja.appendChild(opcion);
                });
            }));
        });

        return Promise.all(pedidos);
    }

    function agregarMascotaHogar() {
        var lista = document.getElementById('lista-mascotas-hogar');
        var modelo = document.getElementById('modelo-mascota-hogar');
        var fila;

        if (!lista || !modelo) {
            return;
        }

        fila = modelo.cloneNode(true);
        fila.removeAttribute('id');
        lista.appendChild(fila);
        ponerBotonesLimpiar(fila);
    }

    function cargarHijo(origen) {
        var hijo = document.getElementById(origen.getAttribute('data-carga'));
        var parametros = {};
        var id = origen.value;

        if (hijo) {
            llenarSelect(hijo, []);
        }

        if (!hijo || !id) {
            return Promise.resolve();
        }

        parametros[hijo.getAttribute('data-param')] = id;

        return apiRequest(hijo.getAttribute('data-catalogo'), { parametros: parametros }).then(function (respuesta) {
            llenarSelect(hijo, listaDe(respuesta));
            return null;
        }).catch(function (error) {
            mostrarAviso(mensaje, error);
        });
    }

    function cerrarLista() {
        var abierta = document.querySelector('.lista-desplegable[data-abierta="1"]');

        if (abierta) {
            abierta.parentNode.removeChild(abierta);
        }
    }

    function abrirLista(select) {
        var modeloLista = document.getElementById('modelo-lista-desplegable');
        var modeloItem = document.getElementById('modelo-item-desplegable');
        var caja;
        var cajaSelect;

        cerrarLista();

        if (!select || !modeloLista || !modeloItem) {
            return;
        }

        caja = modeloLista.cloneNode(false);
        caja.removeAttribute('id');
        caja.setAttribute('data-abierta', '1');

        Array.prototype.forEach.call(select.options, function (opcion) {
            var item;

            if (!opcion.value) {
                return;
            }

            item = modeloItem.cloneNode(true);
            item.removeAttribute('id');
            item.setAttribute('data-valor', opcion.value);
            item.textContent = opcion.textContent;

            if (opcion.selected) {
                item.setAttribute('data-activo', '');
            }

            caja.appendChild(item);
        });

        cajaSelect = select.closest('.campo-con-x') || select.parentNode;
        cajaSelect.appendChild(caja);

        caja.addEventListener('click', function (evento) {
            var item = evento.target.closest('[data-valor]');

            if (!item) {
                return;
            }

            select.value = item.getAttribute('data-valor');
            cerrarLista();

            if (typeof Event === 'function') {
                select.dispatchEvent(new Event('change', { bubbles: true }));
            }
        });
    }

    function enviar() {
        var cuerpo;
        var boton = formulario.querySelector('[type="submit"]');
        var idSolicitud = null;

        if (formulario.getAttribute('data-enviando') === '1') {
            return;
        }

        formulario.setAttribute('data-enviando', '1');

        marcarIntentoEnvio(formulario);
        mostrarAviso(mensaje, '');

        cuerpo = cuerpoDelFormulario();

        if (boton) {
            boton.disabled = true;
        }

        apiRequest('solicitudes', { metodo: 'POST', cuerpo: cuerpo })
            .then(function (respuesta) {
                var solicitud = (respuesta && respuesta.data) || {};
                var recurso = formulario.getAttribute('data-recurso');

                mostrarAviso(mensaje, respuesta, true);
                idSolicitud = solicitud.id;
                cuerpo.id_solicitud = solicitud.id;

                if (!recurso) {
                    return respuesta;
                }

                return apiRequest(recurso, { metodo: 'POST', cuerpo: cuerpo });
            })
            .then(function (respuesta) {
                mostrarAviso(mensaje, respuesta, true);

                return guardarDomicilioYContacto(cuerpo);
            })
            .then(function () {
                avisarEnvio(idSolicitud);
                limpiarFormulario();
            })
            .catch(function (error) {
                mostrarAviso(mensaje, error, false);
            })
            .finally(function () {
                if (boton) {
                    boton.disabled = false;
                }

                formulario.removeAttribute('data-enviando');
            });
    }

    function guardarDomicilioYContacto(cuerpo) {
        var fallo = null;
        var pedido = Promise.resolve();

        function guardar(pedirGuardado) {
            pedido = pedido.then(pedirGuardado).catch(function (error) {
                fallo = fallo || error;
            });
        }

        if (cuerpo.id_provincia) {
            guardar(function () {
                return apiRequest('domicilios', { metodo: 'PUT', cuerpo: cuerpo })
                    .catch(function (error) {
                        if (error && error.status === 404) {
                            return apiRequest('domicilios', { metodo: 'POST', cuerpo: cuerpo });
                        }

                        throw error;
                    });
            });
        }

        if (cuerpo.valor) {
            guardar(function () {
                return apiRequest('contactos', { metodo: 'POST', cuerpo: cuerpo });
            });
        }

        return pedido.then(function () {
            if (fallo) {
                throw fallo;
            }
        });
    }

    function avisarEnvio(idSolicitud) {
        var enviada = document.getElementById('solicitud-enviada');
        var enlace = enviada && enviada.querySelector('a[data-param]');
        var url;

        if (!enviada) {
            return;
        }

        if (enlace && idSolicitud) {
            url = new URL(enlace.getAttribute('href'), window.location.href);
            url.searchParams.set(enlace.getAttribute('data-param'), idSolicitud);
            enlace.href = url.toString();
        }

        acceso.setAttribute('data-estado', 'enviada');
    }
});
