document.addEventListener('DOMContentLoaded', function () {
    var panel = document.getElementById('panel-seguimientos-admin');

    if (!panel) {
        return;
    }

    var recurso = panel.getAttribute('data-recurso') || '';
    var claveSol = panel.getAttribute('data-clave-solicitud') || '';
    var mensaje = document.getElementById('mensaje-seguimientos');
    var cuerpo = document.getElementById('cuerpo-seguimientos');
    var filtroTipo = document.getElementById('filtro-tipo-seguimiento');
    var filtroEstado = document.getElementById('filtro-estado-seguimiento');
    var filtroFechaProgramada = document.getElementById('filtro-fecha-programada');
    var formAlta = document.getElementById('form-seguimiento-alta');
    var formRealizar = document.getElementById('form-seguimiento-realizar');
    var formReprog = document.getElementById('form-seguimiento-reprogramar');
    var selectSol = document.getElementById('alta-id-solicitud');
    var selectTipoAlta = document.getElementById('alta-id-tipo');
    var modalAlta = document.getElementById('modal-seguimiento-alta');
    var modalRealizar = document.getElementById('modal-seguimiento-realizar');
    var modalReprog = document.getElementById('modal-seguimiento-reprogramar');
    var modalVer = document.getElementById('modal-seguimiento-ver');
    var idSolicitudUrl = new URLSearchParams(window.location.search).get('id_solicitud');
    var lista = [];
    var ultimaRealizacionPorSol = {};
    var tipos = {};
    var solicitudContexto = null;
    var pagina = 1;
    var porPagina = parseInt(panel.getAttribute('data-por-pagina'), 10);

    if (isNaN(porPagina) || porPagina < 1) {
        porPagina = 0;
    }
    var volverSolicitud = document.getElementById('seguimientos-volver-solicitud');
    var paginador = panel.querySelector('[data-paginador]');
    var listaPaginas = paginador ? paginador.querySelector('.pagination') : null;
    var anterior = paginador ? paginador.querySelector('[data-pagina-anterior]') : null;
    var siguiente = paginador ? paginador.querySelector('[data-pagina-siguiente]') : null;
    var itemAnterior = anterior && anterior.closest ? anterior.closest('.page-item') : null;
    var itemSiguiente = siguiente && siguiente.closest ? siguiente.closest('.page-item') : null;
    function t(v) {
        return v === undefined || v === null ? '' : String(v);
    }

    function mensajePanel(atributo) {
        return t(panel.getAttribute(atributo));
    }

    function avisoPanel(atributo) {
        var message = mensajePanel(atributo);

        if (message !== '') {
            aviso({ success: false, message: message }, false);
        }

        return message !== '';
    }

    function observacionesParaApi(valor) {
        var texto = t(valor).trim();

        return texto === '' ? null : texto;
    }

    function observacionesParaMostrar(valor) {
        var texto = t(valor).trim();

        if (texto === mensajePanel('data-observacion-oculta')) {
            return '';
        }

        return texto;
    }

    function textoEstadoSeguimiento(estado) {
        if (typeof esValorVerdaderoApi === 'function' && esValorVerdaderoApi(estado)) {
            return mensajePanel('data-texto-seguimiento-activo');
        }

        if (t(estado) !== '') {
            return mensajePanel('data-texto-seguimiento-realizado');
        }

        return '';
    }

    function normalizarClave(valor) {
        return t(valor).toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    }

    function solicitudEsTransito(solicitud) {
        if (typeof esTipoSolicitudTransito !== 'function') {
            return false;
        }

        return esTipoSolicitudTransito(solicitud && solicitud.tipo_solicitud);
    }

    function datosSolicitudDe(res) {
        var datos = res && res.data !== undefined ? res.data : res;

        if (Array.isArray(datos)) {
            return datos[0] || {};
        }

        return datos || {};
    }

    function asignarCampo(contenedor, campo, valor) {
        var nodo;

        if (!contenedor) {
            return;
        }

        nodo = contenedor.querySelector('[data-campo="' + campo + '"]');

        if (!nodo) {
            return;
        }

        if (t(valor) !== '') {
            nodo.setAttribute('data-texto', t(valor));
        } else {
            nodo.removeAttribute('data-texto');
        }
    }

    function hrefDetalleSeguimientosPanel(esTransito) {
        if (typeof hrefDetallePorTransito === 'function') {
            return t(hrefDetallePorTransito(esTransito));
        }

        return '';
    }

    function enlaceVolverDetalleSolicitud(solicitud) {
        var id = solicitud && solicitud.id ? t(solicitud.id) : t(idSolicitudUrl);
        var href = '';

        if (!id) {
            return '';
        }

        if (solicitud && typeof enlaceDetalleSolicitud === 'function') {
            href = t(enlaceDetalleSolicitud(solicitud));
        }

        if (href === '') {
            href = hrefDetalleSeguimientosPanel(claveSol === 'transito');
        }

        return href + '?id=' + encodeURIComponent(id);
    }

    function pintarContextoSolicitud(solicitud) {
        if (!volverSolicitud) {
            return;
        }

        if (!solicitud || !solicitud.id) {
            if (idSolicitudUrl) {
                volverSolicitud.href = enlaceVolverDetalleSolicitud(null);
                volverSolicitud.hidden = false;
            }

            return;
        }

        volverSolicitud.href = enlaceVolverDetalleSolicitud(solicitud);
        volverSolicitud.hidden = false;
    }

    function contextualizarSolicitudDesdeUrl() {
        if (!idSolicitudUrl) {
            return Promise.resolve();
        }

        var pedido = typeof apiSolicitudDetalleAdmin === 'function'
            ? apiSolicitudDetalleAdmin(idSolicitudUrl)
            : apiRequest('solicitudes', { parametros: { id: idSolicitudUrl } });

        return pedido.then(function (res) {
            var solicitud = datosSolicitudDe(res);
            var esTrans;
            var hrefOtro;

            if (!solicitud.id) {
                return;
            }

            esTrans = solicitudEsTransito(solicitud);
            hrefOtro = typeof hrefSeguimientosPorTransito === 'function'
                ? t(hrefSeguimientosPorTransito(esTrans))
                : '';

            if (hrefOtro && ((esTrans && claveSol !== 'transito') || (!esTrans && claveSol === 'transito'))) {
                window.location.replace(hrefOtro + '?id_solicitud=' + encodeURIComponent(String(idSolicitudUrl)));
                return;
            }

            if (typeof estadoSolicitudPermiteSeguimiento === 'function'
                && !estadoSolicitudPermiteSeguimiento(solicitud.estado_solicitud)) {
                window.location.replace(enlaceDetalleSolicitud(solicitud) + '?id=' + encodeURIComponent(t(solicitud.id)));
                return;
            }

            if (!solicitud.id_mascota) {
                window.location.replace(enlaceDetalleSolicitud(solicitud) + '?id=' + encodeURIComponent(t(solicitud.id)));
                return;
            }

            solicitudContexto = solicitud;
            pintarContextoSolicitud(solicitud);
        }).catch(function () {
            pintarContextoSolicitud(null);
        });
    }

    function itemsDeSolicitudActual(items) {
        if (!idSolicitudUrl) {
            return items;
        }

        return items.filter(function (item) {
            return t(item.id_solicitud) === String(idSolicitudUrl);
        });
    }

    function listaDe(res) {
        var d = res && res.data;

        return Array.isArray(d) ? d : d ? [d] : [];
    }

    function seguimientoActivo(estado) {
        if (typeof esValorVerdaderoApi === 'function') {
            return esValorVerdaderoApi(estado);
        }

        return estado === true || estado === 't' || estado === '1' || estado === 1 || t(estado).toLowerCase() === 'true';
    }

    function recalcularUltimaRealizacionPorSolicitud(items) {
        ultimaRealizacionPorSol = {};

        items.forEach(function (item) {
            var sid;
            var fecha;

            if (!item || seguimientoActivo(item.estado)) {
                return;
            }

            fecha = t(item.fecha_realizada);

            if (fecha === '') {
                return;
            }

            sid = t(item.id_solicitud);

            if (!ultimaRealizacionPorSol[sid] || fecha > ultimaRealizacionPorSol[sid]) {
                ultimaRealizacionPorSol[sid] = fecha;
            }
        });
    }

    function textoUltimoRealizadoFila(item) {
        return ultimaRealizacionPorSol[t(item.id_solicitud)] || '';
    }

    function clonar(id) {
        var m = document.getElementById(id);

        if (!m) {
            return null;
        }

        var c = m.cloneNode(true);

        c.removeAttribute('id');
        c.hidden = false;

        return c;
    }

    function aviso(origen, ok) {
        avisoDeApi(mensaje, origen, ok);
    }

    function toggleHidden(nodo, visible) {
        if (!nodo) {
            return;
        }

        if (visible) {
            nodo.removeAttribute('hidden');
        } else {
            nodo.setAttribute('hidden', '');
        }
    }

    function actualizarPlaceholderFechaFiltro() {
        var grupo;

        if (!filtroFechaProgramada) {
            return;
        }

        grupo = filtroFechaProgramada.closest('.listado-filtro-grupo-fecha');

        if (grupo) {
            grupo.dataset.conValor = t(filtroFechaProgramada.value) !== '' ? 'true' : 'false';
        }
    }

    function paramsFiltro() {
        var p = {};

        if (filtroTipo && filtroTipo.value) {
            p.id_tipo_seguimiento = filtroTipo.value;
        }

        if (filtroEstado && filtroEstado.value !== '') {
            p.estado = filtroEstado.value;
        }

        if (filtroFechaProgramada && filtroFechaProgramada.value) {
            p.fecha_desde = filtroFechaProgramada.value;
            p.fecha_hasta = filtroFechaProgramada.value;
        }

        return p;
    }

    function paramsListadoRealizadosBackend() {
        return { estado: 'false' };
    }

    function limpiarCampoFiltro(campo) {
        if (!campo) {
            return;
        }

        if (campo.tagName === 'SELECT') {
            if (campo.querySelector('option[value=""]')) {
                campo.value = '';
            } else {
                campo.selectedIndex = 0;
            }
        } else {
            campo.value = '';
        }
    }

    function textoTipoSeguimiento(item) {
        if (!item || typeof item !== 'object') {
            return '';
        }

        if (t(item.tipo_seguimiento) !== '') {
            return t(item.tipo_seguimiento);
        }

        if (t(item.descripcion) !== '') {
            return t(item.descripcion);
        }

        return '';
    }

    function registrarTiposDesdeSeguimientos(items) {
        items.forEach(function (item) {
            var idTipo;
            var etiqueta;

            if (!item || item.id_tipo_seguimiento === undefined || item.id_tipo_seguimiento === null) {
                return;
            }

            idTipo = String(item.id_tipo_seguimiento);
            etiqueta = textoTipoSeguimiento(item);

            if (etiqueta !== '') {
                tipos[idTipo] = etiqueta;
            }
        });
    }

    function pintarSelectTipos(select, vacio) {
        var seleccion;

        if (!select) {
            return;
        }

        seleccion = t(select.value);

        while (select.options.length > (vacio ? 1 : 0)) {
            select.remove(select.options.length - 1);
        }

        Object.keys(tipos).sort(function (a, b) {
            return tipos[a].localeCompare(tipos[b], 'es');
        }).forEach(function (id) {
            var op = document.createElement('option');

            op.value = id;
            op.textContent = tipos[id];
            select.appendChild(op);
        });

        if (seleccion && Object.prototype.hasOwnProperty.call(tipos, seleccion)) {
            select.value = seleccion;
        } else if (vacio) {
            select.value = '';
        }
    }

    function idSolicitudDesdeFormulario(fd) {
        var valor = fd.get('id_solicitud');

        if (valor !== null && t(valor) !== '') {
            return t(valor);
        }

        if (selectSol && t(selectSol.value) !== '') {
            return t(selectSol.value);
        }

        if (idSolicitudUrl) {
            return String(idSolicitudUrl);
        }

        return '';
    }

    function fijarSolicitudAltaSoloLectura(activo) {
        if (!selectSol) {
            return;
        }

        selectSol.disabled = false;

        if (activo) {
            selectSol.classList.add('pe-none');
            selectSol.setAttribute('tabindex', '-1');
            selectSol.setAttribute('aria-readonly', 'true');
        } else {
            selectSol.classList.remove('pe-none');
            selectSol.removeAttribute('tabindex');
            selectSol.removeAttribute('aria-readonly');
        }
    }

    function fila(item) {
        var tr = clonar('fila-modelo-seguimiento');

        if (!tr) {
            return null;
        }

        var idSol = t(item.id_solicitud);
        var responsable = [t(item.nombre_usuario), t(item.apellido_usuario)].filter(Boolean).join(' ');

        tr.setAttribute('data-id', t(item.id));
        tr.setAttribute('data-situacion', t(item.situacion));

        tr.querySelector('[data-campo="nombre_mascota"]').setAttribute('data-texto', t(item.nombre_mascota));
        tr.querySelector('[data-campo="responsable"]').setAttribute('data-texto', responsable);
        tr.querySelector('[data-campo="fecha_programada"]').setAttribute('data-texto', fechaCorta(item.fecha_programada));
        tr.querySelector('[data-campo="situacion"]').setAttribute('data-texto', t(item.situacion));
        (function () {
            var celdaUltimo = tr.querySelector('[data-campo="ultimo_realizado"]');
            var textoUltimo = fechaCorta(textoUltimoRealizadoFila(item));

            if (!celdaUltimo) {
                return;
            }

            if (textoUltimo !== '') {
                celdaUltimo.setAttribute('data-texto', textoUltimo);
            } else {
                celdaUltimo.removeAttribute('data-texto');
            }
        }());
        tr.querySelector('[data-campo="tipo_seguimiento"]').setAttribute('data-texto', textoTipoSeguimiento(item));

        toggleHidden(tr.querySelector('[data-accion="realizar"]'), seguimientoActivo(item.estado));
        toggleHidden(tr.querySelector('[data-accion="reprogramar"]'), seguimientoActivo(item.estado));

        return tr;
    }

    function itemsFiltrados() {
        return itemsDeSolicitudActual(lista);
    }

    function listaVisible() {
        var items = itemsFiltrados();
        var base = porPagina > 0 ? (pagina - 1) * porPagina : 0;

        return porPagina > 0 ? items.slice(base, base + porPagina) : items;
    }

    function dibujarPaginador(cantidad) {
        if (!paginador || !listaPaginas) {
            return;
        }

        listaPaginas.querySelectorAll('[data-pagina-numero]').forEach(function (existente) {
            existente.remove();
        });

        var total = porPagina > 0 ? Math.ceil(cantidad / porPagina) : 1;

        if (porPagina <= 0 || cantidad <= porPagina) {
            paginador.hidden = true;
            return;
        }

        if (pagina > total) {
            pagina = total;
        }

        for (var numero = 1; numero <= total; numero++) {
            var item = clonar('pagina-modelo-seguimiento');

            if (!item) {
                return;
            }

            item.setAttribute('data-pagina-numero', String(numero));
            item.dataset.activa = numero === pagina ? 'true' : 'false';

            var botonPagina = item.querySelector('.page-link');

            botonPagina.textContent = String(numero);

            if (numero === pagina) {
                botonPagina.setAttribute('aria-current', 'page');
            }

            (function (destino) {
                botonPagina.addEventListener('click', function (ev) {
                    ev.preventDefault();
                    pagina = destino;
                    dibujar();
                });
            })(numero);

            listaPaginas.insertBefore(item, itemSiguiente);
        }

        if (itemAnterior) {
            itemAnterior.dataset.deshabilitado = pagina <= 1 ? 'true' : 'false';
        }

        if (itemSiguiente) {
            itemSiguiente.dataset.deshabilitado = pagina >= total ? 'true' : 'false';
        }

        paginador.hidden = false;
    }

    function dibujar() {
        var cantidad = itemsFiltrados().length;
        var visibles = listaVisible();
        var avisoVacio = panel.getAttribute('data-aviso-sin-seguimientos');

        dibujarPaginador(cantidad);

        cuerpo.querySelectorAll('tr:not(#fila-modelo-seguimiento)').forEach(function (tr) {
            tr.remove();
        });

        visibles.forEach(function (item) {
            var tr = fila(item);

            if (tr) {
                cuerpo.appendChild(tr);
            }
        });

        if (idSolicitudUrl && avisoVacio) {
            if (visibles.length === 0) {
                aviso({ success: false, message: avisoVacio }, false);
            } else if (mensaje && t(mensaje.textContent) === avisoVacio) {
                aviso('', true);
            }
        }
    }

    function hayTiposSeguimientoDisponibles() {
        return Object.keys(tipos).length > 0;
    }

    function cargarLista() {
        if (!recurso) {
            return Promise.resolve();
        }

        aviso('', true);

        var peticionLista = apiRequest(recurso, { parametros: paramsFiltro() });
        var peticionRealizados = apiRequest(recurso, { parametros: paramsListadoRealizadosBackend() });

        return Promise.all([peticionLista, peticionRealizados]).then(function (resultados) {
            lista = listaDe(resultados[0]);
            recalcularUltimaRealizacionPorSolicitud(itemsDeSolicitudActual(listaDe(resultados[1])));
            registrarTiposDesdeSeguimientos(listaDe(resultados[1]));
            registrarTiposDesdeSeguimientos(lista);
            pintarSelectTipos(filtroTipo, true);
            pintarSelectTipos(selectTipoAlta, false);
            dibujar();
            aviso('', true);
        }).catch(function (err) {
            lista = [];
            ultimaRealizacionPorSol = {};
            dibujar();
            aviso(err, false);
        });
    }

    function solicitudesParaAlta() {
        return apiRequest('solicitudes').then(function (res) {
            var todas = listaDe(res);

            return todas.filter(function (s) {
                if (!s) {
                    return false;
                }

                if (claveSol === 'transito') {
                    return esTipoSolicitudTransito(s.tipo_solicitud);
                }

                return !esTipoSolicitudTransito(s.tipo_solicitud);
            });
        });
    }

    function llenarSolicitudes() {
        if (!selectSol) {
            return Promise.resolve();
        }

        selectSol.innerHTML = '';

        return solicitudesParaAlta().then(function (sols) {
            if (idSolicitudUrl) {
                sols = sols.filter(function (s) {
                    return t(s.id) === String(idSolicitudUrl);
                });
            }

            sols.forEach(function (s) {
                var op = document.createElement('option');
                var mascota = t(s.nombre_mascota);
                var usr = [t(s.nombre_usuario), t(s.apellido_usuario)].filter(Boolean).join(' ');

                op.value = t(s.id);
                op.textContent = '#' + t(s.id) + (mascota ? ' · ' + mascota : '') + (usr ? ' · ' + usr : '');
                selectSol.appendChild(op);
            });

            if (idSolicitudUrl && sols.length) {
                selectSol.value = String(idSolicitudUrl);
                fijarSolicitudAltaSoloLectura(true);
            } else {
                fijarSolicitudAltaSoloLectura(false);
            }
        }).catch(function (err) {
            aviso(err, false);
        });
    }

    function modalDe(nodo) {
        return nodo && window.bootstrap ? window.bootstrap.Modal.getOrCreateInstance(nodo) : null;
    }

    function seguimientoEnLista(id) {
        var buscado = String(id);

        return lista.find(function (item) {
            return t(item.id) === buscado;
        }) || {};
    }

    function mezclarSeguimientoDetalle(filaLista, detalleApi) {
        var resultado = Object.assign({}, filaLista || {});
        var extra = detalleApi || {};

        Object.keys(extra).forEach(function (clave) {
            if (extra[clave] !== undefined && extra[clave] !== null) {
                resultado[clave] = extra[clave];
            }
        });

        return resultado;
    }

    function valorDetalleSeguimiento(item, clave) {
        var valor = item[clave];

        if (clave === 'responsable') {
            return [t(item.nombre_usuario), t(item.apellido_usuario)].filter(Boolean).join(' ');
        }

        if (clave === 'id_solicitud' && t(valor) !== '') {
            return '#' + t(valor);
        }

        if (clave === 'tipo_seguimiento') {
            return textoTipoSeguimiento(item);
        }

        if (clave === 'estado') {
            return textoEstadoSeguimiento(valor);
        }

        if (clave === 'situacion') {
            return t(valor);
        }

        if (clave === 'observaciones') {
            return observacionesParaMostrar(valor);
        }

        if (clave === 'ultimo_realizado') {
            return fechaCorta(textoUltimoRealizadoFila(item));
        }

        if (clave === 'fecha_realizada' || clave === 'fecha_programada') {
            return t(valor) !== '' ? fechaCorta(valor) : '';
        }

        if (clave.indexOf('fecha') === 0) {
            return t(valor) !== '' ? fechaCorta(valor) : '';
        }

        return t(valor);
    }

    function pintarDetalleSeguimiento(item) {
        var panelDetalle = document.getElementById('detalle-seguimiento-ver');
        var badge;

        if (!panelDetalle || !item) {
            return;
        }

        panelDetalle.querySelectorAll('[data-detalle]').forEach(function (nodo) {
            var clave = nodo.getAttribute('data-detalle');
            var textoValor = valorDetalleSeguimiento(item, clave);

            if (nodo.classList.contains('seguimiento-detalle-situacion')) {
                nodo.textContent = textoValor;
                nodo.setAttribute('data-situacion', normalizarClave(textoValor));
                return;
            }

            nodo.textContent = textoValor;
        });

        panelDetalle.querySelectorAll('.seguimiento-detalle-observaciones').forEach(function (nodo) {
            var obs = panelDetalle.querySelector('[data-detalle="observaciones"]');
            var vacio = !obs || t(obs.textContent) === '';

            nodo.hidden = vacio;
        });

        panelDetalle.querySelectorAll('.seguimiento-detalle-ultimo-realizado').forEach(function (nodo) {
            var ultimo = panelDetalle.querySelector('[data-detalle="ultimo_realizado"]');
            var vacio = !ultimo || t(ultimo.textContent) === '';

            nodo.hidden = vacio;
        });

        badge = panelDetalle.querySelector('.seguimiento-detalle-situacion');

        if (badge && !t(badge.textContent)) {
            badge.removeAttribute('data-situacion');
        }
    }

    function enviarPut(id, cuerpo) {
        return apiRequest(recurso, {
            metodo: 'PUT',
            parametros: { id: id },
            cuerpo: cuerpo
        });
    }

    if (anterior) {
        anterior.addEventListener('click', function (ev) {
            ev.preventDefault();

            if (pagina > 1) {
                pagina = pagina - 1;
                dibujar();
            }
        });
    }

    if (siguiente) {
        siguiente.addEventListener('click', function (ev) {
            ev.preventDefault();
            var total = porPagina > 0 ? Math.ceil(itemsFiltrados().length / porPagina) : 1;

            if (pagina < total) {
                pagina = pagina + 1;
                dibujar();
            }
        });
    }

    panel.querySelector('[data-refrescar]').addEventListener('click', function () {
        cargarLista();
    });

    [filtroTipo, filtroEstado, filtroFechaProgramada].forEach(function (n) {
        if (n) {
            n.addEventListener('change', function () {
                actualizarPlaceholderFechaFiltro();
                pagina = 1;
                cargarLista();
            });
        }
    });

    actualizarPlaceholderFechaFiltro();

    panel.querySelectorAll('[data-limpiar]').forEach(function (boton) {
        boton.addEventListener('click', function (ev) {
            ev.preventDefault();
            limpiarCampoFiltro(document.getElementById(boton.getAttribute('data-limpiar')));
            actualizarPlaceholderFechaFiltro();
            pagina = 1;
            cargarLista();
        });
    });

    panel.querySelector('[data-abrir-alta]').addEventListener('click', function () {
        llenarSolicitudes().then(function () {
            pintarSelectTipos(selectTipoAlta, false);

            if (!hayTiposSeguimientoDisponibles()) {
                avisoPanel('data-aviso-sin-tipos-seguimiento');
                return;
            }

            var m = modalDe(modalAlta);

            if (m) {
                m.show();
            }
        });
    });

    cuerpo.addEventListener('click', function (ev) {
        var btn = ev.target.closest('[data-accion]');
        var tr;
        var id;

        if (!btn) {
            return;
        }

        ev.preventDefault();
        ev.stopPropagation();

        tr = btn.closest('tr[data-id]');
        id = tr && tr.getAttribute('data-id');

        if (!id) {
            return;
        }

        if (btn.getAttribute('data-accion') === 'ver') {
            var filaLista = seguimientoEnLista(id);

            apiRequest(recurso, { parametros: { id: id } }).then(function (res) {
                var item = res && res.data;

                if (Array.isArray(item)) {
                    item = item[0] || {};
                }

                item = mezclarSeguimientoDetalle(filaLista, item || {});

                pintarDetalleSeguimiento(item);
                aviso('', true);
                modalDe(modalVer).show();
            }).catch(function (err) {
                if (t(filaLista.id) !== '') {
                    pintarDetalleSeguimiento(filaLista);
                    aviso('', true);
                    modalDe(modalVer).show();
                    return;
                }

                aviso(err, false);
            });

            return;
        }

        if (btn.getAttribute('data-accion') === 'realizar') {
            var inputRealizada = formRealizar.elements.fecha_realizada;

            formRealizar.reset();
            formRealizar.elements.id.value = id;

            if (inputRealizada && typeof fechaDeHoyIso === 'function') {
                inputRealizada.value = fechaDeHoyIso();
            }

            modalDe(modalRealizar).show();
        }

        if (btn.getAttribute('data-accion') === 'reprogramar') {
            var segReprog = seguimientoEnLista(id);
            var inputReprog = formReprog.elements.fecha_programada;

            formReprog.elements.id.value = id;

            if (inputReprog && t(segReprog.fecha_programada) !== '') {
                inputReprog.value = t(segReprog.fecha_programada);
            }

            modalDe(modalReprog).show();
        }
    });

    if (formAlta) {
        formAlta.addEventListener('submit', function (ev) {
            ev.preventDefault();
            var fd = new FormData(formAlta);
            var idSol = idSolicitudDesdeFormulario(fd);
            var idTipo = fd.get('id_tipo_seguimiento');
            var cuerpo = {
                id_solicitud: parseInt(idSol, 10),
                fecha_programada: fd.get('fecha_programada'),
                id_tipo_seguimiento: parseInt(idTipo, 10),
                observaciones: observacionesParaApi(fd.get('observaciones'))
            };

            apiRequest(recurso, { metodo: 'POST', cuerpo: cuerpo }).then(function (res) {
                modalDe(modalAlta).hide();
                formAlta.reset();
                aviso(res, true);
                cargarLista();
            }).catch(function (err) {
                aviso(err, false);
            });
        });
    }

    if (formRealizar) {
        formRealizar.addEventListener('submit', function (ev) {
            ev.preventDefault();
            var fd = new FormData(formRealizar);
            var id = fd.get('id');
            var fechaRealizada = fd.get('fecha_realizada');

            var cuerpoRealizar = {
                accion: 'realizar',
                observaciones: observacionesParaApi(fd.get('observaciones'))
            };

            if (t(fechaRealizada) !== '') {
                cuerpoRealizar.fecha_realizada = fechaRealizada;
            }

            enviarPut(id, cuerpoRealizar).then(function (res) {
                modalDe(modalRealizar).hide();
                formRealizar.reset();
                aviso(res, true);
                cargarLista();
            }).catch(function (err) {
                aviso(err, false);
            });
        });
    }

    if (formReprog) {
        formReprog.addEventListener('submit', function (ev) {
            ev.preventDefault();
            var fd = new FormData(formReprog);
            var id = fd.get('id');
            var fechaReprogramada = fd.get('fecha_programada');

            enviarPut(id, {
                accion: 'reprogramar',
                fecha_programada: fechaReprogramada
            }).then(function (res) {
                modalDe(modalReprog).hide();
                formReprog.reset();
                aviso(res, true);
                cargarLista();
            }).catch(function (err) {
                aviso(err, false);
            });
        });
    }

    if (!API_URL) {
        avisoPanel('data-aviso-backend');
        return;
    }

    if (idSolicitudUrl) {
        pintarContextoSolicitud(null);
    }

    contextualizarSolicitudDesdeUrl().then(cargarLista);

    if (idSolicitudUrl) {
        llenarSolicitudes();
    }
});
