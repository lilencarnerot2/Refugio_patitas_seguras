document.addEventListener('DOMContentLoaded', function () {
    if (window.__patitasMisMovimientosPerfil) {
        return;
    }

    window.__patitasMisMovimientosPerfil = true;

    var raiz = document.getElementById('seguimiento-movimientos');
    var mensaje;
    var grupo;
    var lista;
    var modelo;

    if (!raiz || !valorCookie('usuario_id')) {
        return;
    }

    mensaje = raiz.querySelector('[data-mensaje-movimientos]');
    grupo = raiz.querySelector('.donaciones-insumos-grupo');
    lista = grupo && grupo.querySelector('[data-lista]');
    modelo = raiz.querySelector('template[data-modelo-donacion]');

    if (!grupo || !lista || !modelo) {
        return;
    }

    cargar();

    function normalizarTexto(valor) {
        return String(valor || '').toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    }

    function esDonacionDeInsumos(item) {
        var usuarioActual = String(valorCookie('usuario_id'));
        var tipoAporte = normalizarTexto(item && item.tipo_aporte);
        var tipoMovimiento = normalizarTexto(item && item.tipo_movimiento);

        return String(item && item.id_usuario) === usuarioActual
            && tipoMovimiento.indexOf('egreso') === -1
            && tipoAporte.indexOf('dinero') === -1;
    }

    function donacionesUnicas(donaciones) {
        var vistos = Object.create(null);

        return donaciones.filter(function (donacion) {
            var id = String(donacion && donacion.id);

            if (!id || id === 'undefined' || vistos[id]) {
                return false;
            }

            vistos[id] = true;
            return true;
        });
    }

    function pintarDonaciones(donaciones) {
        var cantidad = grupo.querySelector('[data-cantidad]');
        var contadorTotal = raiz.querySelector('[data-contador-total]');
        var ultimoAporte = raiz.querySelector('[data-ultimo-aporte]');

        lista.innerHTML = '';
        donaciones.forEach(function (donacion) {
            var copia = modelo.content.cloneNode(true);
            var item = copia.firstElementChild;
            var fecha = item.querySelector('[data-campo="fecha"]');
            var descripcion = item.querySelector('[data-campo="descripcion"]');

            item.setAttribute('data-id', String(donacion.id));
            item.querySelector('[data-campo="tipo_aporte"]').textContent = donacion.tipo_aporte || 'Donación de insumos';
            fecha.textContent = donacion.fecha ? fechaCorta(donacion.fecha) : 'Sin fecha';
            descripcion.textContent = donacion.descripcion || '';
            descripcion.hidden = !donacion.descripcion;
            lista.appendChild(copia);
        });

        grupo.setAttribute('data-total', String(donaciones.length));

        if (cantidad) {
            cantidad.textContent = String(donaciones.length);
        }

        if (contadorTotal) {
            contadorTotal.textContent = String(donaciones.length);
        }

        if (ultimoAporte) {
            ultimoAporte.textContent = donaciones.length && donaciones[0].fecha
                ? fechaCorta(donaciones[0].fecha)
                : '—';
        }
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

    function cargar() {
        avisar(null);
        raiz.setAttribute('data-cargando', '1');
        grupo.removeAttribute('data-error');

        return apiRequest('movimientos').then(function (respuesta) {
            var donaciones = donacionesUnicas(listaMovimientosDe(respuesta)
                .filter(esDonacionDeInsumos)
                .sort(function (a, b) {
                    return String(b.fecha || '').localeCompare(String(a.fecha || ''))
                        || Number(b.id) - Number(a.id);
                }));

            pintarDonaciones(donaciones);
        }).catch(function (error) {
            grupo.setAttribute('data-error', '');
            avisar(error);
        }).then(function () {
            raiz.removeAttribute('data-cargando');
        });
    }
});
