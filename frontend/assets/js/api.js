const API_URL = (window.APP_CONFIG && window.APP_CONFIG.backendUrl) || '';

function apiRequest(recurso, opciones) {
    opciones = opciones || {};

    if (!API_URL) {
        return Promise.reject(new Error('No está configurada la URL del backend.'));
    }

    const url = new URL(API_URL.replace(/\/$/, '') + '/');
    url.searchParams.set('recurso', recurso);

    const parametros = opciones.parametros || {};
    Object.keys(parametros).forEach(function (clave) {
        if (parametros[clave] !== undefined && parametros[clave] !== null && parametros[clave] !== '') {
            url.searchParams.set(clave, parametros[clave]);
        }
    });

    const config = {
        method: opciones.metodo || 'GET',
        headers: {},
        credentials: 'include'
    };

    if (opciones.cuerpo !== undefined) {
        config.headers['Content-Type'] = 'application/json';
        config.body = JSON.stringify(opciones.cuerpo);
    }

    return fetch(url.toString(), config).then(function (response) {
        return response.json().catch(function () {
            throw new Error('El servidor no devolvió una respuesta válida.');
        }).then(function (data) {
            if (!response.ok || data.success === false) {
                const error = new Error(data.message || ('Error HTTP: ' + response.status));
                error.status = response.status;
                error.data = data;
                throw error;
            }

            return data;
        });
    });
}

function textoApi(valor) {
    if (valor === undefined || valor === null || valor === '') {
        return '';
    }

    return String(valor);
}

function rolesOrdenadosDeApi(lista) {
    if (!Array.isArray(lista)) {
        return [];
    }

    return lista.slice().filter(function (rol) {
        return rol && rol.id !== undefined && rol.id !== null;
    }).sort(function (a, b) {
        return Number(a.id) - Number(b.id);
    });
}

function rolesListadoDeApi(lista) {
    return rolesOrdenadosDeApi(lista).slice(0, 2);
}

function mapaValoresDeApi(lista, campo) {
    var mapa = {};
    var clave = campo || 'id';
    var i;

    if (!Array.isArray(lista)) {
        return mapa;
    }

    for (i = 0; i < lista.length; i++) {
        if (lista[i]) {
            mapa[textoApi(lista[i][clave])] = true;
        }
    }

    return mapa;
}

function listaDeApi(origen) {
    var lista = origen && origen.data;

    if (Array.isArray(lista)) {
        return lista;
    }

    if (lista && typeof lista === 'object') {
        return [lista];
    }

    return [];
}

function hrefBase(href) {
    var texto = href || '';
    var corte = texto.indexOf('?');

    if (corte === -1) {
        return texto;
    }

    return texto.slice(0, corte);
}

function hrefDeApi(href, clave, valor) {
    var base = hrefBase(href);

    if (!clave || !valor) {
        return base;
    }

    return base + '?' + clave + '=' + encodeURIComponent(valor);
}

function limpiarEnlace(enlace) {
    if (!enlace) {
        return;
    }

    enlace.removeAttribute('data-texto');
    enlace.setAttribute('href', hrefBase(enlace.getAttribute('href')));
}

function limpiarFilaDeApi(fila) {
    var celdas = fila.children;
    var i;

    fila.removeAttribute('data-id');
    fila.removeAttribute('data-id-rol');
    fila.removeAttribute('data-estado');
    fila.removeAttribute('data-rol-pos');
    fila.removeAttribute('data-estado-pos');

    for (i = 0; i < celdas.length; i++) {
        celdas[i].removeAttribute('data-texto');
        celdas[i].removeAttribute('data-valor');
        celdas[i].removeAttribute('data-estado');
        limpiarEnlace(celdas[i].querySelector('a'));
    }
}

function asignarEnlacesFila(fila, href, clave, valor) {
    var enlaces = fila.querySelectorAll('a');
    var destino = hrefDeApi(href, clave, valor);
    var i;

    for (i = 0; i < enlaces.length; i++) {
        enlaces[i].setAttribute('href', destino);
    }
}

function asignarCeldasDeApi(fila, cabecera, item, clave) {
    var ths = cabecera.children;
    var id = textoApi(item[clave]);
    var i;
    var th;
    var td;
    var campo;
    var href;
    var texto;

    fila.setAttribute('data-id', id);

    for (i = 0; i < ths.length; i++) {
        th = ths[i];
        td = fila.children[i];
        campo = th.getAttribute('data-campo');
        href = th.getAttribute('data-href');
        texto = campo ? textoApi(item[campo]) : id;

        if (!td) {
            continue;
        }

        if (th.hasAttribute('data-buscar')) {
            td.setAttribute('data-valor', texto.toLowerCase());
        }

        if (th.hasAttribute('data-badge')) {
            td.setAttribute('data-estado', texto.toLowerCase());
            continue;
        }

        if (href) {
            asignarEnlacesFila(fila, href, clave, texto);
        }

        if (campo) {
            td.setAttribute('data-texto', texto);
        }
    }
}

function asignarFilasDeApi(cuerpo, lista, asignar) {
    var filas = cuerpo.querySelectorAll('tr');
    var i;

    for (i = 0; i < filas.length; i++) {
        limpiarFilaDeApi(filas[i]);
    }

    for (i = 0; i < lista.length && i < filas.length; i++) {
        asignar(filas[i], lista[i]);
    }
}

function porcentajeDeApi(parte, total) {
    if (!total) {
        return 0;
    }

    return Math.round((parte * 100) / total);
}

function asignarArcoDeApi(circulo, valor) {
    if (!circulo) {
        return;
    }

    circulo.setAttribute('stroke-dasharray', textoApi(valor) + ' 100');
}

function contarSiDeApi(lista, coincide) {
    var i;
    var total = 0;

    if (!Array.isArray(lista)) {
        return 0;
    }

    for (i = 0; i < lista.length; i++) {
        if (coincide(lista[i])) {
            total += 1;
        }
    }

    return total;
}

function mensajeDeApi(origen) {
    if (origen && origen.message) {
        return origen.message;
    }

    if (origen && origen.data && origen.data.message) {
        return origen.data.message;
    }

    return '';
}

function vaciar(nodo) {
    while (nodo.firstChild) {
        nodo.removeChild(nodo.firstChild);
    }
}

function campoDe(item, campo) {
    return textoApi(item && item[campo]);
}

function claveDeApi(contenedor, selector) {
    var nodo = contenedor && contenedor.querySelector(selector || '[data-campo]');

    return nodo ? nodo.getAttribute('data-campo') : '';
}

function resumenFiltroDeApi(contenedor) {
    return contenedor && contenedor.parentNode && contenedor.parentNode.querySelector('.listado-filtro-resumen');
}

function asignarCamposDeApi(contenedor, item) {
    var nodos = contenedor.querySelectorAll('[data-campo]');
    var i;
    var nodo;
    var texto;

    for (i = 0; i < nodos.length; i++) {
        nodo = nodos[i];
        texto = textoApi(item[nodo.getAttribute('data-campo')]);

        if (nodo.hasAttribute('data-badge')) {
            nodo.setAttribute('data-estado', texto.toLowerCase());
            continue;
        }

        nodo.setAttribute('data-texto', texto);
    }
}

function posicionDeApi(contenedor, valor) {
    var opciones = contenedor.querySelectorAll('input[value]');
    var i;

    for (i = 0; i < opciones.length; i++) {
        if (textoApi(opciones[i].value) === textoApi(valor)) {
            return String(i + 1);
        }
    }

    return '';
}

function limpiarOpcionesDeApi(contenedor) {
    var entradas = contenedor.querySelectorAll('input');
    var i;

    for (i = 0; i < entradas.length; i++) {
        entradas[i].removeAttribute('value');
        entradas[i].removeAttribute('data-estado');

        if (entradas[i].parentNode) {
            entradas[i].parentNode.removeAttribute('data-nombre');
        }
    }
}

function asignarOpcionDeApi(entrada, valor, texto, usarEstado) {
    var etiqueta = entrada.parentNode;

    entrada.setAttribute('value', valor);

    if (usarEstado) {
        entrada.setAttribute('data-estado', textoApi(valor).toLowerCase());
        return;
    }

    if (etiqueta) {
        etiqueta.setAttribute('data-nombre', texto);
    }
}

function listaUnicaDeApi(valores, valorDe, textoDe, parDe) {
    var i;
    var valor;
    var texto;
    var par;
    var vistos = {};
    var lista = [];

    for (i = 0; i < valores.length; i++) {
        valor = valorDe(valores[i]);
        texto = textoDe(valores[i]);

        if (valor === '' || vistos[valor]) {
            continue;
        }

        vistos[valor] = true;
        lista.push({ valor: valor, texto: texto });

        if (!parDe) {
            continue;
        }

        par = parDe(valor);

        if (par && !vistos[par]) {
            vistos[par] = true;
            lista.push({ valor: par, texto: par });
        }
    }

    return lista;
}

function asignarOpcionesDeApi(contenedor, lista, resumen, usarEstado) {
    var entradas = contenedor.querySelectorAll('input');
    var i;
    var n = 0;

    limpiarOpcionesDeApi(contenedor);

    if (resumen) {
        resumen.removeAttribute('data-nombre-1');
        resumen.removeAttribute('data-nombre-2');
    }

    for (i = 0; i < lista.length && n < entradas.length; i++) {
        asignarOpcionDeApi(entradas[n], lista[i].valor, lista[i].texto, usarEstado);

        if (resumen) {
            resumen.setAttribute('data-nombre-' + (n + 1), lista[i].texto);
        }

        n += 1;
    }
}

function cargarOpcionesDeApi(contenedor, valores, valorDe, textoDe, parDe, resumen, usarEstado) {
    asignarOpcionesDeApi(contenedor, listaUnicaDeApi(valores, valorDe, textoDe, parDe), resumen, usarEstado);
}
