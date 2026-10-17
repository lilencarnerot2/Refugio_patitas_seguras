document.addEventListener('DOMContentLoaded', function () {
    if (window.__patitasMisMovimientosPerfil) {
        return;
    }

    window.__patitasMisMovimientosPerfil = true;

    var raiz = document.getElementById('seguimiento-movimientos');
    var mensaje;
    var grupos = [];
    var lista = [];
    var cargado = false;

    function normalizarTexto(valor) {
        return String(valor || '').toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    }

    function itemEnGrupo(item, card) {
        var incluir = normalizarTexto(card.getAttribute('data-incluir-tipo-aporte'));
        var excluir = normalizarTexto(card.getAttribute('data-excluir-tipo-aporte'));
        var tipo = normalizarTexto(item && item.tipo_aporte);

        if (incluir && tipo.indexOf(incluir) === -1) {
            return false;
        }

        if (excluir && tipo.indexOf(excluir) !== -1) {
            return false;
        }

        return true;
    }

    function columnasDeGrupo(card) {
        var modelo = card && card.querySelector('template[data-modelo-cabecera-movimientos]');
        var celdas;
        var resultado = [];
        var i;
        var celda;

        if (!modelo || !modelo.content) {
            return resultado;
        }

        celdas = modelo.content.querySelectorAll('[data-campo]');

        for (i = 0; i < celdas.length; i++) {
            celda = celdas[i];
            resultado.push({
                campo: celda.getAttribute('data-campo'),
                etiqueta: celda.getAttribute('data-etiqueta') || celda.getAttribute('data-campo')
            });
        }

        return resultado;
    }

    if (!raiz || !valorCookie('usuario_id')) {
        return;
    }

    mensaje = raiz.querySelector('[data-mensaje-movimientos]');

    raiz.querySelectorAll('.tab-pane[data-clave]').forEach(function (pane) {
        var card = pane.querySelector('.movimientos-donaciones-grupo');

        if (!card) {
            return;
        }

        grupos.push({
            clave: pane.getAttribute('data-clave'),
            card: card,
            columnas: columnasDeGrupo(card),
            cabecera: card.querySelector('[data-cabecera-movimientos]'),
            cuerpo: card.querySelector('[data-cuerpo-movimientos]'),
            paginador: card.querySelector('[data-paginador]'),
            cantidad: card.querySelector('[data-cantidad]'),
            items: [],
            pagina: 1
        });
    });

    cargar();

    function itemsDeGrupo(grupo) {
        return lista.filter(function (item) {
            return itemEnGrupo(item, grupo.card);
        });
    }

    function valorCelda(clave, valor) {
        if (valor === undefined || valor === null || valor === '') {
            return '';
        }

        if (clave === 'fecha') {
            return fechaCorta(valor);
        }

        return String(valor);
    }

    function pintarTabla(grupo, items) {
        var columnas = grupo.columnas;
        var i;
        var j;
        var fila;
        var celda;
        var item;
        var col;

        if (!grupo.cuerpo) {
            return;
        }

        grupo.cuerpo.innerHTML = '';

        if (grupo.cabecera) {
            grupo.cabecera.innerHTML = '';

            for (i = 0; i < columnas.length; i++) {
                col = columnas[i];
                celda = document.createElement('th');
                celda.setAttribute('data-campo', col.campo);
                celda.textContent = col.etiqueta;
                grupo.cabecera.appendChild(celda);
            }
        }

        for (i = 0; i < items.length; i++) {
            item = items[i];
            fila = document.createElement('tr');

            if (item.id !== undefined) {
                fila.setAttribute('data-id', String(item.id));
            }

            for (j = 0; j < columnas.length; j++) {
                col = columnas[j];
                celda = document.createElement('td');
                celda.setAttribute('data-campo', col.campo);
                celda.textContent = valorCelda(col.campo, item[col.campo]);
                fila.appendChild(celda);
            }

            grupo.cuerpo.appendChild(fila);
        }
    }

    function pintarGrupo(grupo, nuevaPagina) {
        var visibles;
        var corte;

        visibles = itemsDeGrupo(grupo);
        grupo.items = visibles;

        if (nuevaPagina) {
            grupo.pagina = nuevaPagina;
        }

        corte = armarPaginador(grupo.card, visibles.length, grupo.pagina, function (pagina) {
            pintarGrupo(grupo, pagina);
        });

        grupo.pagina = corte.pagina;
        grupo.card.setAttribute('data-total', String(visibles.length));

        if (grupo.cantidad) {
            grupo.cantidad.textContent = String(visibles.length);
        }

        if (grupo.paginador) {
            grupo.paginador.hidden = visibles.length <= corte.porPagina;
        }

        pintarTabla(grupo, visibles.slice(corte.desde, corte.hasta));
    }

    function pintarResumen() {
        var total;
        var monetarias;
        var insumos;
        var cardMonetaria;
        var cardInsumos;

        total = lista.length;
        cardMonetaria = grupos.length ? grupos.find(function (g) {
            return g.clave === 'monetaria';
        }) : null;
        cardInsumos = grupos.length ? grupos.find(function (g) {
            return g.clave === 'insumos';
        }) : null;
        monetarias = cardMonetaria ? itemsDeGrupo(cardMonetaria).length : 0;
        insumos = cardInsumos ? itemsDeGrupo(cardInsumos).length : 0;

        raiz.querySelectorAll('[data-contador-total]').forEach(function (nodo) {
            nodo.textContent = String(total);
        });
        raiz.querySelectorAll('[data-contador-monetaria]').forEach(function (nodo) {
            nodo.textContent = String(monetarias);
        });
        raiz.querySelectorAll('[data-contador-insumos]').forEach(function (nodo) {
            nodo.textContent = String(insumos);
        });
    }

    function avisar(origen) {
        if (!mensaje) {
            return;
        }

        if (!origen) {
            avisoDeApi(mensaje, '', true);
            return;
        }

        avisoDeApi(mensaje, origen, false);
    }

    function repartirYpintar() {
        pintarResumen();
        grupos.forEach(function (grupo) {
            pintarGrupo(grupo, 1);
        });
    }

    function cargar() {
        if (cargado) {
            return Promise.resolve();
        }

        avisar(null);
        raiz.setAttribute('data-cargando', '1');

        return apiRequest('movimientos').then(function (respuesta) {
            lista = listaMovimientosDe(respuesta);
            repartirYpintar();
            cargado = true;
        }).catch(function (error) {
            avisar(error);
            lista = [];
            repartirYpintar();
            cargado = true;
        }).then(function () {
            raiz.removeAttribute('data-cargando');
        });
    }
});
