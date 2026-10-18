document.addEventListener('DOMContentLoaded', function () {
    var panel = document.getElementById('panel-auditoria-detalle');

    if (!panel) {
        return;
    }

    var mensaje = document.getElementById('mensaje-formulario');
    var id = new URLSearchParams(window.location.search).get('id');
    var listado = panel.getAttribute('data-listado');
    var recurso = panel.getAttribute('data-recurso');
    var formatoFechaHora = panel.getAttribute('data-formato-fecha-hora') || '';

    function texto(valor) {
        return valor === undefined || valor === null ? '' : String(valor);
    }

    function jsonDesdeBackend(valor) {
        var bruto;

        if (valor === undefined || valor === null) {
            return '';
        }

        if (typeof valor === 'object') {
            return JSON.stringify(valor, null, 2);
        }

        bruto = texto(valor).trim();

        if (bruto === '') {
            return '';
        }

        try {
            return JSON.stringify(JSON.parse(bruto), null, 2);
        } catch (error) {
            return bruto;
        }
    }

    function valorCampo(item, campo) {
        var valor = item[campo];

        if (valor === undefined || valor === null) {
            return '';
        }

        return texto(valor);
    }

    function pintarDetalle(item) {
        panel.querySelectorAll('[data-campo]').forEach(function (nodo) {
            var valor = valorCampo(item, nodo.getAttribute('data-campo'));

            if (nodo.tagName === 'PRE') {
                nodo.textContent = jsonDesdeBackend(item[nodo.getAttribute('data-campo')]);
                return;
            }

            if (formatoFechaHora && nodo.getAttribute('data-formato') === formatoFechaHora) {
                valor = fechaHoraLista(valor);
            }

            if (valor === '') {
                nodo.removeAttribute('data-texto');
            } else {
                nodo.setAttribute('data-texto', valor);
            }
        });
    }

    if (!recurso || !id) {
        return;
    }

    apiRequest(recurso, { parametros: { id: id } }).then(function (respuesta) {
        if (!respuesta || !respuesta.data) {
            throw respuesta;
        }

        if (mensaje) {
            avisoDeApi(mensaje, '');
        }

        pintarDetalle(respuesta.data);
    }).catch(function (error) {
        if (mensaje) {
            avisoDeApi(mensaje, error, false);
        }

        if (error && error.status === 404 && listado) {
            window.location.href = listado;
        }
    });
});
