function listaDe(respuesta) {
    var crudo = (respuesta && respuesta.data) || [];

    return (Array.isArray(crudo) ? crudo : [crudo]).filter(function (item) {
        return !!item;
    });
}

function listaMovimientosDe(respuesta) {
    return listaDe(respuesta);
}

function claveTextoDe(item) {
    if (!item) {
        return '';
    }

    var claves = Object.keys(item);
    var i;
    var clave;
    var valor;

    for (i = 0; i < claves.length; i++) {
        clave = claves[i];

        if (clave === 'id' || clave.indexOf('id_') === 0) {
            continue;
        }

        valor = item[clave];

        if (typeof valor === 'string' && valor !== '') {
            return clave;
        }
    }

    return '';
}

function clavesVisibles(item) {
    if (!item) {
        return [];
    }

    return Object.keys(item).filter(function (clave) {
        var valor = item[clave];

        return valor === null || valor === undefined || typeof valor !== 'object';
    });
}

var clavesUsuarioMovimiento = ['nombre_usuario', 'apellido_usuario'];

function movimientoTieneUsuario(item) {
    var id = item && item.id_usuario;

    return id !== undefined && id !== null && id !== '' && Number(id) > 0;
}

function esClaveUsuarioMovimiento(clave) {
    return clavesUsuarioMovimiento.indexOf(clave) !== -1;
}

function esClaveDeTabla(clave) {
    if (!clave) {
        return false;
    }

    if (clave === 'id') {
        return true;
    }

    if (clave === 'estado' || clave.indexOf('id_') === 0) {
        return false;
    }

    if (esClaveUsuarioMovimiento(clave)) {
        return false;
    }

    return true;
}

function claveVisibleEnTablaMovimientos(clave, items) {
    if (!esClaveDeTabla(clave) && clave !== 'id') {
        if (esClaveUsuarioMovimiento(clave)) {
            return Array.isArray(items) && items.length > 0;
        }

        return false;
    }

    return esClaveDeTabla(clave);
}

function clavesDeTabla(item) {
    return clavesVisibles(item).filter(function (clave) {
        if (esClaveUsuarioMovimiento(clave)) {
            return true;
        }

        return esClaveDeTabla(clave);
    });
}

function clavesDeItems(items) {
    var vistas = {};
    var claves = [];
    var i;
    var j;
    var lista;
    var visibles;

    lista = Array.isArray(items) ? items : [];

    for (i = 0; i < lista.length; i++) {
        visibles = clavesDeTabla(lista[i]);

        for (j = 0; j < visibles.length; j++) {
            if (vistas[visibles[j]]) {
                continue;
            }

            vistas[visibles[j]] = true;
            claves.push(visibles[j]);
        }
    }

    return claves;
}

function ordenarPorId(items) {
    return (Array.isArray(items) ? items.slice() : []).sort(function (a, b) {
        return Number(a && a.id) - Number(b && b.id);
    });
}

function valorCelda(valor) {
    if (valor === undefined || valor === null) {
        return '';
    }

    return String(valor);
}

function valorCeldaMovimiento(clave, valor, item) {
    if (esClaveUsuarioMovimiento(clave) && !movimientoTieneUsuario(item)) {
        return clave === 'nombre_usuario' ? 'Donador anónimo' : '';
    }

    return valorCelda(valor);
}

function nombreNormalizado(valor) {
    return String(valor || '').toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
}

function listaParaSelect(select, lista) {
    var incluir = nombreNormalizado(select && select.getAttribute('data-incluir'));
    var excluir = nombreNormalizado(select && select.getAttribute('data-excluir'));

    return (Array.isArray(lista) ? lista : []).filter(function (item) {
        var nombre = nombreNormalizado(textoDeItem(item));

        if (incluir && nombre.indexOf(incluir) === -1) {
            return false;
        }

        if (excluir && nombre.indexOf(excluir) !== -1) {
            return false;
        }

        return true;
    });
}

function llenarSelect(select, lista) {
    var i;
    var opcion;
    var base;
    var visibles;

    if (!select) {
        return;
    }

    visibles = listaParaSelect(select, lista);
    base = select.querySelector('option');

    while (select.options.length > 1) {
        select.remove(1);
    }

    if (base) {
        base.value = '';
        base.textContent = '';
        base.selected = true;

        if (!base.hasAttribute('hidden') && base.hasAttribute('disabled')) {
            base.setAttribute('hidden', '');
        }
    }

    for (i = 0; i < visibles.length; i++) {
        if (!visibles[i] || visibles[i].id === undefined || visibles[i].id === null) {
            continue;
        }

        opcion = document.createElement('option');
        opcion.value = String(visibles[i].id);
        opcion.textContent = textoDeItem(visibles[i]);
        select.appendChild(opcion);
    }

    seleccionarOpcionUnica(select);
}

function seleccionarOpcionUnica(select) {
    var reales;
    var i;

    if (!select) {
        return;
    }

    reales = [];
    for (i = 0; i < select.options.length; i++) {
        if (select.options[i].value) {
            reales.push(select.options[i]);
        }
    }

    if (reales.length === 1) {
        select.value = reales[0].value;
    }
}

function cargarCatalogos(raiz) {
    var selects = (raiz || document).querySelectorAll('[data-catalogo]');
    var vistos = {};
    var i;
    var select;
    var recurso;
    var pedidos = [];

    for (i = 0; i < selects.length; i++) {
        select = selects[i];
        recurso = select.getAttribute('data-catalogo');

        if (!recurso || vistos[recurso]) {
            continue;
        }

        vistos[recurso] = true;

        pedidos.push(apiRequest(recurso).then(function (respuesta) {
            return listaDe(respuesta);
        }).then((function (nombre) {
            return function (lista) {
                var mismos = (raiz || document).querySelectorAll('[data-catalogo="' + nombre + '"]');
                var j;

                for (j = 0; j < mismos.length; j++) {
                    llenarSelect(mismos[j], lista);
                }

                return lista;
            };
        })(recurso)).catch(function (error) {
            var caja = document.getElementById('mensaje-aportes');

            if (caja && !caja.textContent) {
                avisoDeApi(caja, error, false);
            }

            return [];
        }));
    }

    return Promise.all(pedidos);
}

function cuerpoDesdeForm(formulario) {
    var datos = {};
    var elementos = formulario.elements;
    var i;
    var campo;
    var nombre;
    var valor;
    var numero;

    for (i = 0; i < elementos.length; i++) {
        campo = elementos[i];
        nombre = campo.name;

        if (!nombre || campo.disabled || campo.type === 'button' || campo.type === 'submit') {
            continue;
        }

        valor = campo.value;

        if (valor === undefined || valor === null || String(valor).trim() === '') {
            continue;
        }

        if (nombre === 'monto' || nombre.indexOf('id_') === 0 || nombre === 'id') {
            numero = Number(valor);

            if (!isNaN(numero)) {
                datos[nombre] = numero;
            }
        } else {
            datos[nombre] = valor;
        }
    }

    delete datos.id_usuario;

    return datos;
}

function pintarTabla(cabecera, cuerpo, items, campos) {
    var primero;
    var claves;
    var i;
    var fila;
    var celda;
    var j;
    var item;

    if (!cuerpo) {
        return;
    }

    cuerpo.innerHTML = '';

    if (cabecera) {
        cabecera.innerHTML = '';
    }

    primero = items[0];
    claves = (Array.isArray(campos) && campos.length
        ? campos.filter(function (campo) {
            return !primero || Object.prototype.hasOwnProperty.call(primero, campo) || items.some(function (fila) {
                return fila && Object.prototype.hasOwnProperty.call(fila, campo);
            });
        })
        : clavesDeItems(items)).filter(function (clave) {
            return claveVisibleEnTablaMovimientos(clave, items);
        });

    if (cabecera) {
        for (i = 0; i < claves.length; i++) {
            celda = document.createElement('th');
            celda.textContent = claves[i].replace(/_/g, ' ');
            cabecera.appendChild(celda);
        }
    }

    for (i = 0; i < items.length; i++) {
        item = items[i];
        fila = document.createElement('tr');

        if (item.id !== undefined) {
            fila.setAttribute('data-id', String(item.id));
        }

        for (j = 0; j < claves.length; j++) {
            celda = document.createElement('td');
            celda.setAttribute('data-campo', claves[j]);
            celda.textContent = valorCeldaMovimiento(claves[j], item[claves[j]], item);
            fila.appendChild(celda);
        }

        cuerpo.appendChild(fila);
    }
}

function parametrosDeFiltros(raiz) {
    var datos = {};
    var nodos = (raiz || document).querySelectorAll('[data-filtro], [data-busqueda]');
    var i;
    var nodo;
    var campo;
    var valor;

    for (i = 0; i < nodos.length; i++) {
        nodo = nodos[i];
        campo = nodo.getAttribute('data-filtro');

        if (!campo && nodo.hasAttribute('data-busqueda')) {
            campo = 'busqueda';
        }

        if (!campo || campo === 'id') {
            continue;
        }

        valor = nodo.value;

        if (valor === undefined || valor === null || String(valor).trim() === '') {
            continue;
        }

        datos[campo] = String(valor).trim();
    }

    return datos;
}

function coincideFiltros(item, raiz) {
    var filtros = (raiz || document).querySelectorAll('[data-filtro]');
    var i;
    var filtro;
    var campo;
    var valor;
    var actual;

    for (i = 0; i < filtros.length; i++) {
        filtro = filtros[i];
        campo = filtro.getAttribute('data-filtro');
        valor = filtro.value;

        if (!campo || valor === '') {
            continue;
        }

        actual = item[campo];

        if (actual === undefined || actual === null) {
            return false;
        }

        if (String(actual) !== String(valor)) {
            return false;
        }
    }

    return true;
}

function coincideBusqueda(item, input) {
    var palabras;
    var texto;
    var i;

    if (!input || input.value.trim() === '') {
        return true;
    }

    palabras = input.value.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '').split(/\s+/);
    texto = JSON.stringify(item).toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');

    for (i = 0; i < palabras.length; i++) {
        if (palabras[i] && texto.indexOf(palabras[i]) === -1) {
            return false;
        }
    }

    return true;
}

function armarPaginador(panel, cantidad, pagina, dibujar) {
    var paginador = panel.querySelector('[data-paginador]');
    var listaPaginas;
    var anterior;
    var siguiente;
    var itemAnterior;
    var itemSiguiente;
    var porPagina = parseInt(panel.getAttribute('data-por-pagina'), 10);
    var total;
    var numero;
    var modelo;
    var item;
    var boton;

    if (!paginador) {
        return { desde: 0, hasta: cantidad, pagina: pagina };
    }

    listaPaginas = paginador.querySelector('.pagination');
    anterior = paginador.querySelector('[data-pagina-anterior]');
    siguiente = paginador.querySelector('[data-pagina-siguiente]');
    itemAnterior = anterior && anterior.closest ? anterior.closest('.page-item') : null;
    itemSiguiente = siguiente && siguiente.closest ? siguiente.closest('.page-item') : null;

    if (isNaN(porPagina) || porPagina < 1) {
        paginador.hidden = true;
        return { desde: 0, hasta: cantidad, pagina: 1 };
    }

    total = Math.ceil(cantidad / porPagina) || 1;

    if (pagina > total) {
        pagina = total;
    }

    if (pagina < 1) {
        pagina = 1;
    }

    listaPaginas.querySelectorAll('[data-pagina-numero]').forEach(function (existente) {
        existente.remove();
    });

    if (cantidad <= porPagina) {
        paginador.hidden = true;
        return { desde: 0, hasta: cantidad, pagina: pagina };
    }

    modelo = panel.querySelector('[data-pagina-modelo]') || document.querySelector('[data-pagina-modelo]') || document.getElementById('pagina-modelo-movimientos');

    if (!modelo) {
        paginador.hidden = cantidad <= porPagina;
        return {
            desde: (pagina - 1) * porPagina,
            hasta: pagina * porPagina,
            pagina: pagina
        };
    }

    for (numero = 1; numero <= total; numero++) {
        item = modelo.cloneNode(true);
        item.removeAttribute('id');
        item.hidden = false;
        item.setAttribute('data-pagina-numero', String(numero));
        item.dataset.activa = numero === pagina ? 'true' : 'false';
        boton = item.querySelector('.page-link');
        boton.textContent = String(numero);

        if (numero === pagina) {
            boton.setAttribute('aria-current', 'page');
        }

        (function (destino) {
            boton.addEventListener('click', function () {
                dibujar(destino);
            });
        })(numero);

        listaPaginas.insertBefore(item, itemSiguiente);
    }

    if (itemAnterior) {
        itemAnterior.dataset.deshabilitado = pagina <= 1 ? 'true' : 'false';
    }

    if (anterior) {
        anterior.onclick = function () {
            if (pagina > 1) {
                dibujar(pagina - 1);
            }
        };
    }

    if (itemSiguiente) {
        itemSiguiente.dataset.deshabilitado = pagina >= total ? 'true' : 'false';
    }

    if (siguiente) {
        siguiente.onclick = function () {
            if (pagina < total) {
                dibujar(pagina + 1);
            }
        };
    }

    paginador.hidden = false;

    return {
        desde: (pagina - 1) * porPagina,
        hasta: pagina * porPagina,
        pagina: pagina
    };
}

function actualizarCamposDependientes(formulario) {
    var grupos;
    var i;
    var grupo;
    var origen;
    var campo;
    var visible;

    if (!formulario) {
        return;
    }

    grupos = formulario.querySelectorAll('[data-depende]');

    for (i = 0; i < grupos.length; i++) {
        grupo = grupos[i];
        origen = formulario.querySelector('[name="' + grupo.getAttribute('data-depende') + '"]');
        visible = !!(origen && String(origen.value).trim() !== '');
        grupo.hidden = !visible;
        campo = grupo.querySelector('input, select, textarea');

        if (!campo) {
            continue;
        }

        campo.disabled = !visible;

        if (!visible) {
            campo.value = '';
        }
    }
}

function aplicarFechasMinimasFormulario(formulario) {
    var hoy = fechaDeHoyIso();
    var origen;
    var minimo;
    var dia;

    if (!formulario) {
        return;
    }

    formulario.querySelectorAll('input[type="date"][data-min-hoy]').forEach(function (campo) {
        origen = formulario.elements[campo.getAttribute('data-min-desde') || ''];
        minimo = hoy;

        if (origen && origen.value) {
            dia = new Date(origen.value + 'T00:00:00Z');
            dia.setUTCDate(dia.getUTCDate() + 1);
            minimo = dia.toISOString().slice(0, 10);

            if (minimo < hoy) {
                minimo = hoy;
            }
        }

        campo.min = minimo;

        if (campo.value && campo.value < minimo) {
            campo.value = minimo;
        }
    });
}

function enlazarFechasMinimasFormulario(formulario) {
    if (!formulario) {
        return;
    }

    aplicarFechasMinimasFormulario(formulario);

    formulario.querySelectorAll('input[type="date"][data-min-hoy]').forEach(function (campo) {
        if (campo.getAttribute('data-calendario-enlazado')) {
            return;
        }

        campo.setAttribute('data-calendario-enlazado', '1');
        campo.addEventListener('focus', function () {
            reservarEspacioCalendario(campo);
        });
        campo.addEventListener('click', function () {
            reservarEspacioCalendario(campo);
        });
    });

    formulario.querySelectorAll('input[type="date"][data-min-desde]').forEach(function (campo) {
        var origenId = campo.getAttribute('data-min-desde');
        var origen = origenId && formulario.elements[origenId];

        if (!origen || origen.getAttribute('data-min-desde-enlazado')) {
            return;
        }

        origen.setAttribute('data-min-desde-enlazado', '1');
        origen.addEventListener('change', function () {
            aplicarFechasMinimasFormulario(formulario);
        });
    });
}

function enlazarFormularioMovimiento(formulario, recargar) {
    var origenes;
    var i;

    if (!formulario) {
        return;
    }

    if (formulario.getAttribute('data-submit-enlazado') === '1') {
        return;
    }

    formulario.setAttribute('data-submit-enlazado', '1');

    actualizarCamposDependientes(formulario);
    enlazarFechasMinimasFormulario(formulario);
    origenes = formulario.querySelectorAll('select, input');

    for (i = 0; i < origenes.length; i++) {
        origenes[i].addEventListener('change', function () {
            actualizarCamposDependientes(formulario);
        });
    }

    formulario.addEventListener('submit', function (evento) {
        evento.preventDefault();
        enviarMovimiento(formulario, recargar);
    });
}

function ponerFechaDeHoy(formulario) {
    var campo = formulario.querySelector('input[data-fecha-hoy]');

    if (campo) {
        campo.value = fechaDeHoyIso();
    }
}

function nombreDeCampoMovimiento(formulario, campo) {
    var etiqueta = campo.id ? formulario.querySelector('label[for="' + campo.id + '"]') : null;

    return etiqueta ? etiqueta.textContent.replace(/\s+/g, ' ').trim() : campo.name;
}

function movimientoValido(formulario, caja) {
    var vacios = [];
    var nombres = [];
    var avisos = [];
    var campoMonto = formulario.querySelector('[name="monto"]');
    var montoInvalido = false;

    avisoDeApi(caja, '', true, formulario);

    Array.prototype.forEach.call(formulario.elements, function (campo) {
        if (!campo.name || !campo.required || campo.disabled || campo.type === 'hidden' || campo.closest('[hidden]')) {
            return;
        }

        if (String(campo.value || '').trim() === '') {
            vacios.push(campo);
            nombres.push(nombreDeCampoMovimiento(formulario, campo));
        }
    });

    if (campoMonto && String(campoMonto.value || '').trim() !== '' && !(Number(campoMonto.value) > 0)) {
        montoInvalido = true;
    }

    if (!vacios.length && !montoInvalido) {
        return true;
    }

    if (vacios.length) {
        avisos.push('Completá los campos obligatorios: ' + nombres.join(', ') + '.');
    }

    if (montoInvalido) {
        campoMonto.setAttribute('data-campo-estado', 'error');
        avisos.push('El monto tiene que ser mayor a 0.');
    }

    vacios.forEach(function (campo) {
        campo.setAttribute('data-campo-estado', 'error');
    });

    marcarIntentoEnvio(formulario);
    avisoDeApi(caja, avisos.join(' '), false);

    return false;
}

function enviarMovimiento(formulario, recargar) {
    var caja = document.getElementById('mensaje-aportes');

    if (!formulario) {
        return Promise.resolve();
    }

    if (formulario.getAttribute('data-enviando') !== '1' && !movimientoValido(formulario, caja)) {
        return Promise.resolve();
    }

    ponerFechaDeHoy(formulario);

    actualizarCamposDependientes(formulario);

    return enviarFormularioApi(formulario, caja, 'movimientos', {
        metodo: 'POST',
        cuerpo: cuerpoDesdeForm(formulario)
    }).then(function () {
        formulario.reset();
        formulario.removeAttribute('data-intento-envio');
        formulario.querySelectorAll('select[data-catalogo]').forEach(seleccionarOpcionUnica);
        actualizarCamposDependientes(formulario);
        aplicarFechasMinimasFormulario(formulario);

        if (recargar) {
            recargar();
        }
    }).catch(function () {
        return null;
    });
}

document.addEventListener('DOMContentLoaded', function () {
    var sitio = document.getElementById('panel-aportes-sitio');
    var bloque;
    var caja;
    var formulario;

    if (!sitio || !document.getElementById('form-movimiento')) {
        return;
    }

    bloque = document.getElementById('bloque-aportes');
    caja = document.getElementById('mensaje-aportes');
    formulario = document.getElementById('form-movimiento');

    function pintarAliases() {
        sitio.querySelectorAll('[data-alias]').forEach(function (card) {
            var medio = card.querySelector('[data-campo="medio"]');
            var alias = card.querySelector('[data-campo="alias"]');

            if (medio) {
                medio.textContent = card.getAttribute('data-medio') || '';
            }

            if (alias) {
                alias.textContent = card.getAttribute('data-alias') || '';
            }
        });
    }

    cargarCatalogos(sitio).then(function () {
        if (caja && caja.getAttribute('data-estado') === 'error') {
            if (bloque) {
                bloque.hidden = true;
            }

            return;
        }

        pintarAliases();
        enlazarFormularioMovimiento(formulario);

        if (bloque) {
            bloque.hidden = false;
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {
    var panel = document.getElementById('panel-aportes-admin');
    var cabecera;
    var cuerpo;
    var filtroAporte;
    var filtroTipo;
    var caja;
    var lista;
    var pagina;

    if (!panel) {
        return;
    }

    cabecera = document.getElementById('cabecera-movimientos');
    cuerpo = document.getElementById('cuerpo-movimientos');
    filtroAporte = document.getElementById('filtro-aporte');
    filtroTipo = document.getElementById('filtro-tipo-aporte');
    caja = document.getElementById('mensaje-aportes');
    lista = [];
    pagina = 1;

    function aportesDelTipo() {
        var tipo = filtroTipo ? filtroTipo.value : '';

        return lista.filter(function (item) {
            if (!tipo) {
                return true;
            }

            return String(item.id_tipo_aporte) === String(tipo);
        });
    }

    function sincronizarAportes() {
        var elegido;

        if (!filtroAporte) {
            return;
        }

        elegido = filtroAporte.value;
        llenarSelect(filtroAporte, aportesDelTipo());

        if (elegido) {
            filtroAporte.value = elegido;

            if (filtroAporte.value !== elegido) {
                filtroAporte.value = '';
            }
        }
    }

    function filtrados() {
        return lista.filter(function (item) {
            return coincideFiltros(item, panel);
        });
    }

    function dibujar(nuevaPagina) {
        var visibles;
        var corte;

        if (nuevaPagina) {
            pagina = nuevaPagina;
        }

        visibles = filtrados();
        corte = armarPaginador(panel, visibles.length, pagina, dibujar);
        pagina = corte.pagina;
        pintarTabla(cabecera, cuerpo, visibles.slice(corte.desde, corte.hasta), clavesDeItems(visibles));
    }

    function cargarListado() {
        apiRequest('movimientos', { parametros: parametrosDeFiltros(panel) }).then(function (respuesta) {
            lista = ordenarPorId(listaMovimientosDe(respuesta));
            sincronizarAportes();
            dibujar(1);

            if (caja && mensajeDeApi(respuesta)) {
                avisoDeApi(caja, respuesta, true);
            }
        }).catch(function (error) {
            avisoDeApi(caja, error, false);
        });
    }

    cargarCatalogos(panel).then(function () {
        cargarListado();
        enlazarFormularioMovimiento(document.getElementById('form-egreso'), cargarListado);
    });

    if (filtroTipo) {
        filtroTipo.addEventListener('change', function () {
            cargarListado();
        });
    }

    if (filtroAporte) {
        filtroAporte.addEventListener('change', function () {
            dibujar(1);
        });
    }

    panel.querySelectorAll('[data-limpiar]').forEach(function (boton) {
        boton.addEventListener('click', function () {
            var campo = document.getElementById(boton.getAttribute('data-limpiar'));
            var clave = boton.getAttribute('data-limpiar');

            if (campo) {
                campo.value = '';
            }

            if (clave === 'filtro-aporte') {
                dibujar(1);
                return;
            }

            cargarListado();
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    var panel = document.getElementById('panel-aportes-reportes');
    var cabecera;
    var cuerpo;
    var resumen;
    var caja;
    var formulario;
    var zonaCampos;
    var lista;
    var pagina;
    var formArmado;
    var clavesArmadas;
    var datosListos;

    if (!panel) {
        return;
    }

    cabecera = document.getElementById('cabecera-movimientos');
    cuerpo = document.getElementById('cuerpo-movimientos');
    resumen = document.getElementById('resumen-aportes');
    caja = document.getElementById('mensaje-aportes');
    formulario = document.getElementById('form-movimiento-reporte');
    zonaCampos = formulario ? formulario.querySelector('[data-campos-form]') : null;
    lista = [];
    pagina = 1;
    formArmado = false;
    clavesArmadas = '';
    datosListos = false;

    function avisar(origen, ok) {
        avisoDeApi(caja, origen, ok, formulario);
    }

    function catalogoDeCampo(nombre) {
        var origen = panel.querySelector('[data-filtro="' + nombre + '"][data-catalogo]');

        return origen ? origen.getAttribute('data-catalogo') : '';
    }

    function modeloDesdeFiltros() {
        var modelo = {};
        var filtros = panel.querySelectorAll('[data-filtro][data-catalogo]');
        var i;
        var clave;

        for (i = 0; i < filtros.length; i++) {
            clave = filtros[i].getAttribute('data-filtro');

            if (clave && clave !== 'id') {
                modelo[clave] = '';
            }
        }

        modelo.monto = '';
        modelo.fecha = '';
        modelo.descripcion = '';

        return modelo;
    }

    function pintarResumen(datos) {
        var claves;
        var i;
        var clave;
        var card;
        var filas;

        if (!resumen) {
            return;
        }

        resumen.innerHTML = '';

        if (!datos) {
            return;
        }

        if (Array.isArray(datos)) {
            filas = datos;
            for (i = 0; i < filas.length; i++) {
                card = document.createElement('article');
                card.className = 'info-card';
                claves = clavesVisibles(filas[i]);
                card.innerHTML = '<p class="dash-kpi-numero"></p><p></p>';
                card.querySelector('.dash-kpi-numero').textContent = valorCelda(filas[i][claves[0]]);
                card.querySelector('p:last-child').textContent = claves.slice(1).map(function (k) {
                    return k.replace(/_/g, ' ') + ': ' + valorCelda(filas[i][k]);
                }).join(' · ');
                resumen.appendChild(card);
            }
            return;
        }

        claves = clavesVisibles(datos);

        for (i = 0; i < claves.length; i++) {
            clave = claves[i];
            card = document.createElement('article');
            card.className = 'info-card';
            card.innerHTML = '<p class="dash-kpi-numero"></p><p></p>';
            card.querySelector('.dash-kpi-numero').textContent = valorCelda(datos[clave]);
            card.querySelector('p:last-child').textContent = clave.replace(/_/g, ' ');
            resumen.appendChild(card);
        }
    }

    function dibujar(nuevaPagina) {
        var visibles;
        var corte;
        var claves;

        if (nuevaPagina) {
            pagina = nuevaPagina;
        }

        visibles = ordenarPorId(lista);
        corte = armarPaginador(panel, visibles.length, pagina, dibujar);
        pagina = corte.pagina;
        claves = clavesDeItems(visibles).filter(function (clave) {
            return clave !== 'descripcion';
        });
        pintarTabla(cabecera, cuerpo, visibles.slice(corte.desde, corte.hasta), claves);
    }

    function armarCampos(modelo) {
        var claves;
        var i;
        var clave;
        var grupo;
        var etiqueta;
        var campo;
        var catalogo;

        claves = clavesVisibles(modelo);

        if (!zonaCampos || !modelo) {
            return;
        }

        if (formArmado && clavesArmadas === claves.join(',')) {
            return;
        }

        zonaCampos.innerHTML = '';
        clavesArmadas = claves.join(',');
        formArmado = false;

        for (i = 0; i < claves.length; i++) {
            clave = claves[i];

            if (clave === 'id' || clave === 'id_usuario' || Object.prototype.hasOwnProperty.call(modelo, 'id_' + clave)) {
                continue;
            }

            grupo = document.createElement('div');
            grupo.className = 'col-12 col-md-6 min-w-0';
            etiqueta = document.createElement('label');
            etiqueta.className = 'form-label';
            etiqueta.setAttribute('for', 'reporte-' + clave);
            etiqueta.textContent = clave.replace(/_/g, ' ');
            catalogo = catalogoDeCampo(clave);

            if (catalogo) {
                campo = document.createElement('select');
                campo.className = 'form-select';
                campo.setAttribute('data-catalogo', catalogo);
                campo.setAttribute('required', '');
                campo.appendChild(document.createElement('option'));
            } else {
                campo = document.createElement('input');
                campo.className = 'form-control';
                campo.type = clave === 'monto' || clave.indexOf('id_') === 0 ? 'number' : 'text';

                if (clave === 'monto') {
                    campo.setAttribute('step', 'any');
                    campo.setAttribute('min', '0');

                    if (modelo.id_metodo_pago !== undefined) {
                        grupo.setAttribute('data-depende', 'id_metodo_pago');
                    }
                }

                if (clave.indexOf('id_') === 0 || clave === 'monto') {
                    campo.setAttribute('required', '');
                }
            }

            campo.id = 'reporte-' + clave;
            campo.name = clave;
            grupo.appendChild(etiqueta);
            grupo.appendChild(campo);
            zonaCampos.appendChild(grupo);
        }

        formArmado = true;
        cargarCatalogos(formulario);
        actualizarCamposDependientes(formulario);
    }

    function limpiarForm() {
        if (!formulario) {
            return;
        }

        formulario.reset();
        formulario.querySelector('[name="id"]').value = '';
        actualizarCamposDependientes(formulario);
        formulario.hidden = true;
    }

    function cargarListado() {
        var filtros = parametrosDeFiltros(panel);

        return apiRequest('movimientos', { parametros: filtros }).then(function (respuesta) {
            lista = ordenarPorId(listaMovimientosDe(respuesta));
            datosListos = true;
            armarCampos(lista[0] || modeloDesdeFiltros());
            dibujar(1);

            if (mensajeDeApi(respuesta)) {
                avisar(respuesta, true);
            }

            return apiRequest('reportes_movimientos', { parametros: filtros }).then(function (reporte) {
                pintarResumen((reporte && reporte.data) || reporte);
            }).catch(function (error) {
                avisar(error, false);
            });
        }).catch(function (error) {
            datosListos = false;
            avisar(error, false);
        });
    }

    function guardarMovimiento() {
        var id;
        var opciones;

        actualizarCamposDependientes(formulario);

        id = formulario.querySelector('[name="id"]').value;
        opciones = {
            metodo: id ? 'PUT' : 'POST',
            cuerpo: cuerpoDesdeForm(formulario)
        };

        if (id) {
            opciones.parametros = { id: id };
        }

        return enviarFormularioApi(formulario, caja, 'movimientos', opciones).then(function () {
            limpiarForm();
            return cargarListado();
        }).catch(function () {
            return null;
        });
    }

    cargarCatalogos(panel).then(cargarListado);

    panel.querySelectorAll('[data-filtro]').forEach(function (filtro) {
        filtro.addEventListener('change', function () {
            cargarListado();
        });
    });

    panel.querySelectorAll('[data-limpiar]').forEach(function (boton) {
        boton.addEventListener('click', function () {
            var campo = document.getElementById(boton.getAttribute('data-limpiar'));

            if (campo) {
                campo.value = '';
            }

            cargarListado();
        });
    });

    if (formulario) {
        formulario.addEventListener('submit', function (evento) {
            evento.preventDefault();
            guardarMovimiento();
        });

        formulario.querySelector('[data-limpiar-form]').addEventListener('click', limpiarForm);

        formulario.addEventListener('change', function () {
            actualizarCamposDependientes(formulario);
        });
    }
});
