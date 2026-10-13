document.addEventListener('DOMContentLoaded', function () {
    var panel = document.getElementById('panel-aportes-admin');

    if (!panel) {
        return;
    }

    var cabecera = document.getElementById('cabecera-movimientos');
    var cuerpo = document.getElementById('cuerpo-movimientos');
    var filtroAporte = document.getElementById('filtro-aporte');
    var filtroTipo = document.getElementById('filtro-tipo-aporte');
    var caja = document.getElementById('mensaje-aportes');
    var lista = [];
    var pagina = 1;

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
        apiRequest('movimientos').then(function (respuesta) {
            lista = listaDe(respuesta);
            sincronizarAportes();
            dibujar(1);

            if (caja && mensajeDeApi(respuesta)) {
                caja.textContent = mensajeDeApi(respuesta);
                caja.setAttribute('data-estado', 'ok');
            }
        }).catch(function (error) {
            if (caja) {
                caja.textContent = mensajeDeApi(error);
                caja.setAttribute('data-estado', 'error');
            }
        });
    }

    cargarCatalogos(panel).then(cargarListado);

    if (filtroTipo) {
        filtroTipo.addEventListener('change', function () {
            sincronizarAportes();
            dibujar(1);
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

            if (campo) {
                campo.value = '';
            }

            if (boton.getAttribute('data-limpiar') === 'filtro-tipo-aporte') {
                sincronizarAportes();
            }

            dibujar(1);
        });
    });
});
