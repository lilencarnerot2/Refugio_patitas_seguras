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
    var idSolicitudExistente = new URLSearchParams(window.location.search).get('id_solicitud');

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
                                nodo.src = urlFotoMascota(valor);
                                nodo.hidden = false;
                            }
                            return;
                        }

                        if (nodo.getAttribute('data-campo') && String(nodo.getAttribute('data-campo')).indexOf('fecha') !== -1) {
                            nodo.textContent = typeof fechaCorta === 'function' ? fechaCorta(valor) : (valor || '');
                        } else {
                            nodo.textContent = valor || '';
                        }
                    });
                });
            }

            return null;
        })
        .then(cargarCatalogos)
        .then(cargarTiposContacto)
        .then(cargarListas)
        .then(rellenarDesdeDatosPrevios)
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

    function quitarMarcaDeCampo(evento) {
        if (evento.target && evento.target.removeAttribute) {
            evento.target.removeAttribute('data-campo-estado');
        }
    }

    formulario.addEventListener('input', quitarMarcaDeCampo);
    formulario.addEventListener('change', quitarMarcaDeCampo);

    var cacheCatalogos = {};
    var idsTipoContacto = {
        movil: null,
        emergencia: null
    };

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

            if (
                (nodo.hasAttribute('data-solo-contacto') && nodo.name !== 'telefono') ||
                nodo.closest('[data-lista]') ||
                nodo.closest('#lista-mascotas-hogar') ||
                nodo.disabled
            ) {
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
        var todos;
        var acepta;

        if (!caja) {
            return ids;
        }

        if (caja.hasAttribute('data-compromisos-unico')) {
            acepta = caja.querySelector('input[type="checkbox"]');

            if (!acepta || !acepta.checked) {
                return ids;
            }

            try {
                todos = JSON.parse(caja.getAttribute('data-ids-compromisos') || '[]');
            } catch (errorLista) {
                todos = [];
            }

            return todos.map(function (id) {
                return Number(id);
            });
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

        if (select.hasAttribute('data-placeholder')) {
            vacia.textContent = select.getAttribute('data-placeholder');
            vacia.disabled = true;
            vacia.removeAttribute('hidden');
        }

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
        formulario.removeAttribute('data-intento-envio');

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

                cacheCatalogos[recurso] = lista;

                document.querySelectorAll('#formulario-solicitud select[data-catalogo="' + recurso + '"]:not([data-param]), #modelos-adopcion select[data-catalogo="' + recurso + '"]').forEach(function (select) {
                    llenarSelect(select, lista);
                });
            }));
        });

        return Promise.all(pedidos);
    }

    function cargarTiposContacto() {
        return apiRequest('tipos_contacto').then(function (respuesta) {
            resolverTiposContacto(listaDe(respuesta));
        }).catch(function () {
            return null;
        });
    }

    function resolverTiposContacto(lista) {
        (lista || []).forEach(function (tipo) {
            var nombre = String((tipo && (tipo.nombre_tipo || tipo.descripcion)) || '').toLowerCase();

            if (nombre.indexOf('emerg') !== -1) {
                idsTipoContacto.emergencia = Number(tipo.id);
                return;
            }

            if (
                idsTipoContacto.movil === null &&
                (nombre.indexOf('movil') !== -1 || nombre.indexOf('móvil') !== -1 || nombre.indexOf('telefono') !== -1 || nombre.indexOf('tel') !== -1)
            ) {
                idsTipoContacto.movil = Number(tipo.id);
            }
        });
    }

    function cargarListas() {
        var pedidos = [];

        formulario.querySelectorAll('[data-lista]').forEach(function (caja) {
            var recurso = caja.getAttribute('data-lista');
            var nombre = caja.getAttribute('data-name');

            pedidos.push(apiRequest(recurso).then(function (respuesta) {
                var modelo = document.getElementById('modelo-opcion-lista');
                var items = listaDe(respuesta);

                if (caja.hasAttribute('data-compromisos-unico')) {
                    montarCompromisosUnico(caja, items);
                    return;
                }

                items.forEach(function (item) {
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

    function montarCompromisosUnico(caja, items) {
        var ids = [];
        var listaTextos;
        var modelo = document.getElementById('modelo-opcion-lista');
        var opcion;
        var entrada;

        (items || []).forEach(function (item) {
            if (item && item.id !== undefined && item.id !== null) {
                ids.push(Number(item.id));
            }
        });

        caja.setAttribute('data-ids-compromisos', JSON.stringify(ids));
        caja.innerHTML = '';

        listaTextos = document.createElement('ul');
        listaTextos.className = 'mb-2 ps-3';

        (items || []).forEach(function (item) {
            var li = document.createElement('li');

            li.textContent = nombreDeItem(item);
            listaTextos.appendChild(li);
        });

        caja.appendChild(listaTextos);

        if (!modelo) {
            return;
        }

        opcion = modelo.cloneNode(true);
        opcion.removeAttribute('id');
        entrada = opcion.querySelector('input');
        entrada.name = 'acepta_compromisos_adopcion';
        entrada.value = '1';
        entrada.required = caja.hasAttribute('data-requerido');
        opcion.querySelector('[data-texto]').textContent = 'Acepto todos los compromisos de adopción indicados';
        caja.appendChild(opcion);
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
        fila.querySelectorAll('select[data-catalogo]:not([data-param])').forEach(function (select) {
            var recurso = select.getAttribute('data-catalogo');

            if (recurso && cacheCatalogos[recurso]) {
                llenarSelect(select, cacheCatalogos[recurso]);
            }
        });
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

    function asignarCampo(nombre, valor) {
        var nodo = formulario.elements[nombre];

        if (!nodo || valor === undefined || valor === null || valor === '') {
            return;
        }

        if (nodo.type === 'checkbox') {
            nodo.checked = !!valor;
            return;
        }

        nodo.value = String(valor);
    }

    function encadenarSelectsDomicilio(domicilio) {
        var provincia = formulario.elements.id_provincia;
        var comuna = formulario.elements.id_comuna_municipio;

        if (!domicilio || !provincia) {
            return Promise.resolve();
        }

        asignarCampo('id_provincia', domicilio.id_provincia);

        return cargarHijo(provincia).then(function () {
            asignarCampo('id_comuna_municipio', domicilio.id_comuna_municipio);

            if (!comuna) {
                return null;
            }

            return cargarHijo(comuna);
        }).then(function () {
            asignarCampo('id_localidad', domicilio.id_localidad);
            asignarCampo('nombre_barrio', domicilio.barrio_zona === barrioSinDato ? '' : domicilio.barrio_zona);

            [
                'calle',
                'altura',
                'piso',
                'departamento',
                'ruta_nombre',
                'kilometro',
                'entre_calle_1',
                'entre_calle_2',
                'indicadores_referencia',
                'codigo_postal'
            ].forEach(function (clave) {
                asignarCampo(clave, domicilio[clave]);
            });
        });
    }

    function aplicarContactosAlFormulario(contactos) {
        (contactos || []).forEach(function (item) {
            var tipo = String((item && (item.tipo_contacto || item.nombre_tipo)) || '').toLowerCase();
            var valor = item && item.valor;

            if (!valor) {
                return;
            }

            if (tipo.indexOf('emerg') !== -1 && formulario.elements.contacto_emergencia) {
                formulario.elements.contacto_emergencia.value = valor;
                return;
            }

            if (
                (item.es_principal || tipo.indexOf('movil') !== -1 || tipo.indexOf('móvil') !== -1 || tipo.indexOf('tel') !== -1) &&
                formulario.elements.telefono &&
                !formulario.elements.telefono.value
            ) {
                formulario.elements.telefono.value = valor;
            }
        });
    }

    function ultimoFormularioDelUsuario(lista) {
        var items = (lista || []).slice();

        if (!items.length) {
            return null;
        }

        items.sort(function (a, b) {
            return Number(b.id || 0) - Number(a.id || 0);
        });

        return items[0];
    }

    function datosComunesFormularioPrevio(datos) {
        if (!datos) {
            return null;
        }

        return {
            id_tipo_vivienda: datos.id_tipo_vivienda,
            id_condicion_vivienda: datos.id_condicion_vivienda,
            tiene_patio: datos.tiene_patio,
            metros_cuadrados_patio: datos.metros_cuadrados_patio,
            cantidad_integrantes: datos.cantidad_integrantes,
            horas_solo: datos.horas_solo,
            hay_ninos: datos.hay_ninos,
            tiene_mascotas: datos.tiene_mascotas,
            mascotas_hogar: datos.mascotas_hogar
        };
    }

    function aplicarFormularioPrevio(datos) {
        var claves = [
            'id_tipo_vivienda',
            'id_condicion_vivienda',
            'tiene_patio',
            'metros_cuadrados_patio',
            'id_destino_mascota',
            'cantidad_integrantes',
            'horas_solo',
            'hay_ninos',
            'tiene_mascotas',
            'responsable_principal',
            'id_motivo_adopcion',
            'puede_realizar_paseos',
            'puede_realizar_traslados',
            'cuenta_con_apoyo',
            'cuenta_con_recursos',
            'limitaciones_cuidado',
            'experiencia_cuidado',
            'cantidad_maxima_mascotas',
            'disponibilidad_desde',
            'disponibilidad_hasta',
            'puede_administrar_medicacion',
            'observaciones'
        ];

        if (!datos) {
            return;
        }

        claves.forEach(function (clave) {
            if (datos[clave] !== undefined && datos[clave] !== null && formulario.elements[clave]) {
                asignarCampo(clave, datos[clave]);
            }
        });

        aplicarDependencias();

        if (datos.tiene_mascotas && Array.isArray(datos.mascotas_hogar)) {
            datos.mascotas_hogar.forEach(function (mascota) {
                agregarMascotaHogar();
                var fila = document.getElementById('lista-mascotas-hogar').lastElementChild;

                if (!fila) {
                    return;
                }

                Array.prototype.forEach.call(fila.querySelectorAll('[name]'), function (nodo) {
                    var valor = mascota[nodo.name];

                    if (valor === undefined || valor === null) {
                        return;
                    }

                    if (nodo.type === 'checkbox') {
                        nodo.checked = !!valor;
                    } else {
                        nodo.value = String(valor);
                    }
                });
            });
        }
    }

    function rellenarDesdeDatosPrevios() {
        var recurso = formulario.getAttribute('data-recurso');
        var sesion = /(?:^|;\s*)usuario_id=[^;]+/.test(document.cookie);
        var errores = [];
        var recursoAlternativo;

        if (!sesion || !recurso) {
            return Promise.resolve();
        }

        if (recurso === 'formularios_adopcion') {
            recursoAlternativo = 'formularios_transito';
        } else if (recurso === 'formularios_transito') {
            recursoAlternativo = 'formularios_adopcion';
        } else {
            recursoAlternativo = '';
        }

        return Promise.all([
            apiRequest('domicilios', { credentials: 'include' }).catch(function (error) {
                if (!error || error.status !== 404) {
                    errores.push(error);
                }

                return null;
            }),

            apiRequest('contactos', { credentials: 'include' }).catch(function (error) {
                if (!error || error.status !== 404) {
                    errores.push(error);
                }

                return null;
            }),

            apiRequest(recurso, { credentials: 'include' }).catch(function (error) {
                if (!error || error.status !== 404) {
                    errores.push(error);
                }

                return null;
            }),

            recursoAlternativo
                ? apiRequest(recursoAlternativo, { credentials: 'include' }).catch(function (error) {
                    if (!error || error.status !== 404) {
                        errores.push(error);
                    }

                    return null;
                })
                : Promise.resolve(null)

        ]).then(function (respuestas) {
            var domicilio = objetoDomicilioDePerfil(
                respuestas[0] && respuestas[0].data !== undefined
                    ? respuestas[0].data
                    : respuestas[0]
            );

            var contactos = listaContactosDePerfil(
                respuestas[1] && respuestas[1].data !== undefined
                    ? respuestas[1].data
                    : respuestas[1]
            );

            var formulariosActuales = listaDe(respuestas[2]);
            var formulariosAlternativos = listaDe(respuestas[3]);

            var ultimoActual = ultimoFormularioDelUsuario(formulariosActuales);
            var ultimoAlternativo = ultimoFormularioDelUsuario(formulariosAlternativos);

            var formularioParaAplicar;

            if (ultimoActual) {
                // Si ya hizo antes este mismo tipo de formulario,
                // reutilizamos todos sus datos.
                formularioParaAplicar = ultimoActual;
            } else if (ultimoAlternativo) {
                // Si solamente hizo el otro formulario,
                // reutilizamos únicamente los campos comunes.
                formularioParaAplicar = datosComunesFormularioPrevio(
                ultimoAlternativo
                );
            } else {
                formularioParaAplicar = null;
            }

            aplicarContactosAlFormulario(contactos);

            return encadenarSelectsDomicilio(domicilio).then(function () {
                aplicarFormularioPrevio(formularioParaAplicar);
                aplicarDependencias();
                aplicarFechasMinimas();
            });

        }).then(function () {
            if (errores.length) {
                mostrarAviso(mensaje, errores[0], false);
            }

        }).catch(function (error) {
            mostrarAviso(mensaje, error, false);
            return null;
        });
    }

    function enviar() {
        var cuerpo;
        var boton = formulario.querySelector('[type="submit"]');
        var idSolicitud = null;
        var recurso = formulario.getAttribute('data-recurso');
        var solicitud;

        if (formulario.getAttribute('data-enviando') === '1') {
            return;
        }

        marcarIntentoEnvio(formulario);

        if (!formulario.reportValidity()) {
            return;
        }

        formulario.setAttribute('data-enviando', '1');

        mostrarAviso(mensaje, '');

        cuerpo = cuerpoDelFormulario();

        if (boton) {
            boton.disabled = true;
        }

        (idSolicitudExistente
            ? Promise.resolve(null)
            : apiRequest('solicitudes', { metodo: 'POST', cuerpo: cuerpo }))
            .then(function (respuesta) {
                solicitud = (respuesta && respuesta.data) || {};
                idSolicitudExistente = idSolicitudExistente || solicitud.id;
                idSolicitud = idSolicitudExistente;

                if (solicitud.id) {
                    var url = new URL(window.location.href);
                    url.searchParams.set('id_solicitud', solicitud.id);
                    window.history.replaceState(window.history.state, '', url.toString());
                }

                if (!recurso) {
                    mostrarAviso(mensaje, respuesta, true);
                    return respuesta;
                }

                cuerpo.id_solicitud = idSolicitudExistente;

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
        var cuerpoDomicilio = {};

        Object.keys(cuerpo).forEach(function (clave) {
            cuerpoDomicilio[clave] = cuerpo[clave];
        });

        if (!String(cuerpoDomicilio.nombre_barrio || '').trim()) {
            cuerpoDomicilio.nombre_barrio = barrioSinDato;
        }

        function guardar(pedirGuardado) {
            pedido = pedido.then(pedirGuardado).catch(function (error) {
                fallo = fallo || error;
            });
        }

        if (cuerpo.id_provincia) {
            guardar(function () {
                return apiRequest('domicilios', { metodo: 'PUT', cuerpo: cuerpoDomicilio })
                    .catch(function (error) {
                        if (error && error.status === 404) {
                            return apiRequest('domicilios', { metodo: 'POST', cuerpo: cuerpoDomicilio });
                        }

                        throw error;
                    });
            });
        }

        if (formulario.elements.telefono && formulario.elements.telefono.value.trim() && idsTipoContacto.movil) {
            guardar(function () {
                return apiRequest('contactos', {
                    metodo: 'POST',
                    cuerpo: {
                        id_tipo_contacto: idsTipoContacto.movil,
                        valor: formulario.elements.telefono.value.trim(),
                        es_principal: true
                    }
                });
            });
        }

        if (formulario.elements.contacto_emergencia && formulario.elements.contacto_emergencia.value.trim() && idsTipoContacto.emergencia) {
            guardar(function () {
                return apiRequest('contactos', {
                    metodo: 'POST',
                    cuerpo: {
                        id_tipo_contacto: idsTipoContacto.emergencia,
                        valor: formulario.elements.contacto_emergencia.value.trim(),
                        es_principal: false
                    }
                });
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
