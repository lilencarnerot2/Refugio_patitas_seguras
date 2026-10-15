document.addEventListener('DOMContentLoaded', function () {
    var raiz = document.getElementById('seguimiento-movimientos');
    var mensaje;
    var cabecera;
    var cuerpo;
    var grupo;
    var lista;
    var cargado;
    var campos;
    var titulos;

    if (!raiz || !valorCookie('usuario_id')) {
        return;
    }

    mensaje = raiz.querySelector('[data-mensaje-movimientos]');
    cabecera = document.getElementById('cabecera-movimientos-perfil');
    cuerpo = document.getElementById('cuerpo-movimientos-perfil');
    grupo = raiz.querySelector('.movimientos-donaciones-grupo');
    lista = [];
    cargado = false;

    campos = [
        'tipo_movimiento',
        'tipo_aporte',
        'metodo_pago',
        'frecuencia_aporte',
        'fecha',
        'monto',
        'descripcion'
    ];

    titulos = {
        tipo_movimiento: 'Tipo movimiento',
        tipo_aporte: 'Tipo aporte',
        metodo_pago: 'Método pago',
        frecuencia_aporte: 'Frecuencia',
        fecha: 'Fecha',
        monto: 'Monto',
        descripcion: 'Descripción'
    };

    if (document.getElementById('vista-movimientos').classList.contains('active')) {
        cargar();
    }

    document.querySelectorAll('[data-bs-target="#vista-movimientos"]').forEach(function (tab) {
        tab.addEventListener('shown.bs.tab', function () {
            if (!cargado) {
                cargar();
            }
        });
    });

    function esDinero(item) {
        var texto = String(item && item.tipo_aporte || '').toLowerCase();

        return texto.indexOf('dinero') !== -1;
    }

    function valorCelda(clave, valor) {
        if (valor === undefined || valor === null || valor === '') {
            return '';
        }

        if (clave === 'fecha') {
            return fechaCorta(valor);
        }

        if (clave === 'monto') {
            if (valor === 0 || valor === '0') {
                return '0.00';
            }

            if (!isNaN(Number(valor))) {
                return Number(valor).toFixed(2);
            }
        }

        return String(valor);
    }

    function pintarTablaPerfil(items) {
        var i;
        var j;
        var fila;
        var celda;
        var item;

        if (!cuerpo) {
            return;
        }

        cuerpo.innerHTML = '';

        if (cabecera) {
            cabecera.innerHTML = '';
            for (i = 0; i < campos.length; i++) {
                celda = document.createElement('th');
                celda.textContent = titulos[campos[i]] || campos[i];
                cabecera.appendChild(celda);
            }
        }

        for (i = 0; i < items.length; i++) {
            item = items[i];
            fila = document.createElement('tr');

            if (item.id !== undefined) {
                fila.setAttribute('data-id', String(item.id));
            }

            for (j = 0; j < campos.length; j++) {
                celda = document.createElement('td');
                celda.setAttribute('data-campo', campos[j]);
                celda.textContent = valorCelda(campos[j], item[campos[j]]);
                fila.appendChild(celda);
            }

            cuerpo.appendChild(fila);
        }
    }

    function pintarResumen(items) {
        var total;
        var dinero;
        var insumos;
        var cantidad;

        total = items.length;
        dinero = 0;
        insumos = 0;

        items.forEach(function (item) {
            if (esDinero(item)) {
                dinero += 1;
            } else {
                insumos += 1;
            }
        });

        if (grupo) {
            grupo.setAttribute('data-total', String(total));
        }

        cantidad = raiz.querySelector('[data-cantidad]');
        if (cantidad) {
            cantidad.textContent = String(total);
        }

        raiz.querySelectorAll('[data-contador-total]').forEach(function (nodo) {
            nodo.textContent = String(total);
        });
        raiz.querySelectorAll('[data-contador-dinero]').forEach(function (nodo) {
            nodo.textContent = String(dinero);
        });
        raiz.querySelectorAll('[data-contador-insumos]').forEach(function (nodo) {
            nodo.textContent = String(insumos);
        });
    }

    function dibujar(nuevaPagina) {
        var corte;

        if (!grupo) {
            return;
        }

        corte = armarPaginador(grupo, lista.length, nuevaPagina, dibujar);
        pintarTablaPerfil(lista.slice(corte.desde, corte.hasta));
    }

    function avisar(origen) {
        var texto;

        if (!mensaje) {
            return;
        }

        texto = origen ? mensajeDeApi(origen) : '';
        mensaje.textContent = texto || '';

        if (texto) {
            mensaje.setAttribute('data-estado', 'error');
        } else {
            mensaje.removeAttribute('data-estado');
        }
    }

    function cargar() {
        avisar(null);
        raiz.setAttribute('data-cargando', '1');

        return apiRequest('movimientos').then(function (respuesta) {
            lista = listaDe(respuesta);
            pintarResumen(lista);
            dibujar(1);
            cargado = true;
        }).catch(function (error) {
            avisar(error);
            pintarResumen([]);
            dibujar(1);
            cargado = true;
        }).then(function () {
            raiz.removeAttribute('data-cargando');
        });
    }
});
