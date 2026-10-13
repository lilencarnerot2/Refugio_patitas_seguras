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
            if (acceso) {
                acceso.removeAttribute('data-estado');
            }

            if (bloque) {
                bloque.hidden = false;
            }

            aplicarDependencias();
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
        var abierta = document.querySelector('.lista-desplegable-abierta');

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
    });

    formulario.addEventListener('change', function (evento) {
        var origen = evento.target.closest('select[data-carga]');

        if (evento.target.name === 'tiene_patio' || evento.target.name === 'tiene_mascotas') {
            aplicarDependencias();
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
        if (!caja) {
            return;
        }

        caja.textContent = typeof origen === 'string' ? origen : mensajeDeApi(origen);

        if (ok) {
            caja.setAttribute('data-estado', 'ok');
        } else if (origen) {
            caja.setAttribute('data-estado', 'error');
        } else {
            caja.removeAttribute('data-estado');
        }

        if (origen && origen.status) {
            caja.setAttribute('data-codigo', String(origen.status));
        } else {
            caja.removeAttribute('data-codigo');
        }
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

        if (formulario.elements.id_barrio_zona) {
            var barrio = formulario.elements.id_barrio_zona;
            var opcion = barrio.options[barrio.selectedIndex];

            if (opcion && opcion.value) {
                cuerpo.nombre_barrio = opcion.textContent;
            }
        }

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

            wrap = document.createElement('div');
            wrap.className = 'campo-con-x';
            nodo.parentNode.insertBefore(wrap, nodo);
            wrap.appendChild(nodo);
            boton = modelo.cloneNode(true);
            boton.removeAttribute('id');
            wrap.appendChild(boton);
        });
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
        var abierta = document.querySelector('.lista-desplegable-abierta');

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
        caja.className = 'lista-desplegable lista-desplegable-abierta';

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

    function validar() {
        mostrarAviso(mensaje, '');

        if (typeof formulario.checkValidity === 'function' && !formulario.checkValidity()) {
            if (typeof formulario.reportValidity === 'function') {
                formulario.reportValidity();
            }

            return false;
        }

        return true;
    }

    function enviar() {
        var cuerpo;

        if (!validar()) {
            return;
        }

        cuerpo = cuerpoDelFormulario();

        apiRequest('solicitudes', { metodo: 'POST', cuerpo: cuerpo })
            .then(function (respuesta) {
                var solicitud = (respuesta && respuesta.data) || {};

                mostrarAviso(mensaje, respuesta, true);
                cuerpo.id_solicitud = solicitud.id;

                var recurso = formulario.getAttribute('data-recurso');

                if (!recurso) {
                    return respuesta;
                }

                return apiRequest(recurso, { metodo: 'POST', cuerpo: cuerpo });
            })
            .then(function (respuesta) {
                mostrarAviso(mensaje, respuesta, true);

                if (!formulario.elements.id_provincia && !formulario.elements.valor) {
                    return respuesta;
                }

                return apiRequest('domicilios', { metodo: 'POST', cuerpo: cuerpo }).catch(function (error) {
                    mostrarAviso(mensaje, error);
                }).then(function () {
                    return apiRequest('contactos', { metodo: 'POST', cuerpo: cuerpo });
                });
            })
            .catch(function (error) {
                mostrarAviso(mensaje, error);
            });
    }
});
