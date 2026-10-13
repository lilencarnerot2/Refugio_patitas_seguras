function listaDe(respuesta) {
    var crudo = (respuesta && respuesta.data) || [];

    return (Array.isArray(crudo) ? crudo : [crudo]).filter(function (item) {
        return !!item;
    });
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

function clavesDeItems(items) {
    var vistas = {};
    var claves = [];
    var i;
    var j;
    var lista;
    var visibles;

    lista = Array.isArray(items) ? items : [];

    for (i = 0; i < lista.length; i++) {
        visibles = clavesVisibles(lista[i]);

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

function valorCelda(valor) {
    if (valor === undefined || valor === null) {
        return '';
    }

    return String(valor);
}

function llenarSelect(select, lista) {
    var i;
    var opcion;
    var base;

    if (!select) {
        return;
    }

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

    for (i = 0; i < lista.length; i++) {
        opcion = document.createElement('option');
        opcion.value = lista[i].id;
        opcion.textContent = textoDeItem(lista[i]);
        select.appendChild(opcion);
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
                caja.textContent = mensajeDeApi(error);
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
    claves = Array.isArray(campos) && campos.length
        ? campos.filter(function (campo) {
            return !primero || Object.prototype.hasOwnProperty.call(primero, campo) || items.some(function (fila) {
                return fila && Object.prototype.hasOwnProperty.call(fila, campo);
            });
        })
        : clavesDeItems(items);

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
            celda.textContent = valorCelda(item[claves[j]]);
            fila.appendChild(celda);
        }

        cuerpo.appendChild(fila);
    }
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

function enlazarFormularioMovimiento(formulario, recargar) {
    var origenes;
    var i;

    if (!formulario) {
        return;
    }

    actualizarCamposDependientes(formulario);
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

function campoVisible(campo) {
    return !!(campo && !campo.disabled && !campo.hidden && !campo.closest('[hidden]'));
}

function validarFormularioMovimiento(formulario, caja) {
    var campos;
    var i;
    var campo;
    var monto;

    if (!formulario) {
        return false;
    }

    actualizarCamposDependientes(formulario);

    if (caja) {
        caja.textContent = '';
        caja.removeAttribute('data-estado');
    }

    campos = formulario.querySelectorAll('[required], [data-a-seleccionar]');

    for (i = 0; i < campos.length; i++) {
        campo = campos[i];

        if (!campoVisible(campo)) {
            continue;
        }

        if (String(campo.value || '').trim() === '') {
            if (caja) {
                caja.textContent = 'Completá los campos obligatorios.';
                caja.setAttribute('data-estado', 'error');
            }

            if (typeof formulario.reportValidity === 'function') {
                formulario.reportValidity();
            }

            campo.focus();
            return false;
        }
    }

    monto = formulario.elements.monto;

    if (campoVisible(monto)) {
        if (String(monto.value || '').trim() === '' || isNaN(Number(monto.value)) || Number(monto.value) <= 0) {
            if (caja) {
                caja.textContent = 'Indicá un monto válido.';
                caja.setAttribute('data-estado', 'error');
            }

            monto.focus();
            return false;
        }
    }

    return true;
}

function enviarMovimiento(formulario, recargar) {
    var caja = document.getElementById('mensaje-aportes');
    var cuerpo;

    if (!validarFormularioMovimiento(formulario, caja)) {
        return Promise.resolve();
    }

    cuerpo = cuerpoDesdeForm(formulario);

    return apiRequest('movimientos', {
        metodo: 'POST',
        cuerpo: cuerpo
    }).then(function (respuesta) {
        if (caja) {
            caja.textContent = mensajeDeApi(respuesta);
            caja.setAttribute('data-estado', 'ok');
        }

        formulario.reset();
        actualizarCamposDependientes(formulario);

        if (recargar) {
            recargar();
        }
    }).catch(function (error) {
        if (caja) {
            caja.textContent = mensajeDeApi(error);
            caja.setAttribute('data-estado', 'error');
        }
    });
}
