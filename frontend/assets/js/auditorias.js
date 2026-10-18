document.addEventListener('DOMContentLoaded', function () {
    var panel = document.getElementById('panel-auditorias');

    if (!panel) {
        return;
    }

    var mensaje = document.getElementById('mensaje-auditorias');
    var mensajeVacio = document.getElementById('mensaje-vacio-auditorias');
    var cuerpo = document.getElementById('cuerpo-auditorias');
    var filtroUsuario = document.getElementById('filtro-auditoria-usuario');
    var filtroTabla = document.getElementById('filtro-auditoria-tabla');
    var filtroAccion = document.getElementById('filtro-auditoria-accion');
    var paginador = panel.querySelector('[data-paginador]');
    var listaPaginas = paginador && paginador.querySelector('.pagination');
    var anterior = paginador && paginador.querySelector('[data-pagina-anterior]');
    var siguiente = paginador && paginador.querySelector('[data-pagina-siguiente]');
    var itemAnterior = anterior && anterior.closest('.page-item');
    var itemSiguiente = siguiente && siguiente.closest('.page-item');
    var recurso = panel.getAttribute('data-recurso');
    var recursoUsuarios = panel.getAttribute('data-recurso-usuarios');
    var formatoFechaHora = panel.getAttribute('data-formato-fecha-hora') || '';
    var lista = [];
    var ultimaRespuesta = null;
    var opcionesTabla = {};
    var opcionesAccion = {};
    var pagina = 1;
    var porPagina = parseInt(panel.getAttribute('data-por-pagina'), 10);
    var listaMaxSelect = parseInt(panel.getAttribute('data-lista-max'), 10);
    var camposFila = [];
    var modeloFila = document.getElementById('fila-modelo-auditoria');

    porPagina = isNaN(porPagina) || porPagina < 1 ? 0 : porPagina;
    listaMaxSelect = isNaN(listaMaxSelect) || listaMaxSelect < 2 ? 8 : listaMaxSelect;

    if (modeloFila) {
        modeloFila.querySelectorAll('td[data-campo]').forEach(function (celda) {
            camposFila.push(celda.getAttribute('data-campo'));
        });
    }

    function texto(valor) {
        return valor === undefined || valor === null ? '' : String(valor);
    }

    function normalizarEtiqueta(valor) {
        return texto(valor).toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '').trim();
    }

    function clonarModelo(id) {
        var modelo = document.getElementById(id);

        if (!modelo) {
            return null;
        }

        var copia = modelo.cloneNode(true);

        copia.removeAttribute('id');
        copia.hidden = false;

        return copia;
    }

    function limpiarOpciones(select) {
        if (!select) {
            return;
        }

        select.querySelectorAll('option').forEach(function (opcion) {
            if (opcion.value !== '') {
                opcion.remove();
            }
        });
    }

    function configurarSelectListaScroll(select) {
        if (!select || select.dataset.listaScroll === '1') {
            return;
        }

        select.dataset.listaScroll = '1';

        function cerrar() {
            select.removeAttribute('size');
            select.classList.remove('is-lista-abierta');
        }

        function abrir() {
            var total = select.options.length;

            if (select.tagName !== 'SELECT' || total <= 1) {
                return;
            }

            select.size = Math.min(total, listaMaxSelect);
            select.classList.add('is-lista-abierta');
        }

        select.addEventListener('focus', abrir);
        select.addEventListener('mousedown', abrir);
        select.addEventListener('blur', function () {
            window.setTimeout(cerrar, 120);
        });
        select.addEventListener('change', cerrar);
        select.addEventListener('keydown', function (evento) {
            if (evento.key === 'Escape') {
                cerrar();
            }
        });
    }

    panel.querySelectorAll('select[data-filtro]').forEach(configurarSelectListaScroll);

    function parametrosFiltros() {
        var datos = {};

        panel.querySelectorAll('[data-filtro]').forEach(function (nodo) {
            var campo;
            var par;
            var valor;

            if (nodo.tagName === 'LABEL') {
                return;
            }

            campo = nodo.getAttribute('data-filtro');
            valor = String(nodo.value || '').trim();

            if (!campo || valor === '') {
                return;
            }

            datos[campo] = valor;
            par = nodo.getAttribute('data-filtro-par');

            if (par) {
                datos[par] = valor;
            }
        });

        return datos;
    }

    function sinDuplicados(items) {
        var vistos = {};

        return items.filter(function (item) {
            var id = texto(item && item.id);

            if (id === '' || vistos[id]) {
                return false;
            }

            vistos[id] = true;

            return true;
        });
    }

    function numerosPaginador(actual, total) {
        var ventana = 2;
        var listaNumeros = [];
        var inicio;
        var fin;
        var numero;

        if (total <= 7) {
            for (numero = 1; numero <= total; numero++) {
                listaNumeros.push(numero);
            }

            return listaNumeros;
        }

        inicio = Math.max(2, actual - ventana);
        fin = Math.min(total - 1, actual + ventana);
        listaNumeros.push(1);

        if (inicio > 2) {
            listaNumeros.push(null);
        }

        for (numero = inicio; numero <= fin; numero++) {
            listaNumeros.push(numero);
        }

        if (fin < total - 1) {
            listaNumeros.push(null);
        }

        listaNumeros.push(total);

        return listaNumeros;
    }

    function insertarSeparadorPaginador() {
        var item = document.createElement('li');

        item.className = 'page-item disabled';
        item.setAttribute('data-pagina-ellipsis', '1');
        item.innerHTML = '<span class="page-link" aria-hidden="true"></span>';
        listaPaginas.insertBefore(item, itemSiguiente);
    }

    function ponerDataTexto(nodo, valor) {
        if (!nodo) {
            return;
        }

        if (valor === undefined || valor === null || valor === '') {
            nodo.removeAttribute('data-texto');
            return;
        }

        nodo.setAttribute('data-texto', texto(valor));
    }

    function valorCelda(item, celda, campo) {
        var valor = item[campo];

        if (celda && formatoFechaHora && celda.getAttribute('data-formato') === formatoFechaHora) {
            valor = fechaHoraLista(valor);
        }

        return valor;
    }

    function filaAuditoria(item) {
        var tr = clonarModelo('fila-modelo-auditoria');
        var enlace;
        var i;
        var campo;
        var celda;

        if (!tr) {
            return null;
        }

        tr.setAttribute('data-id', texto(item.id));

        for (i = 0; i < camposFila.length; i++) {
            campo = camposFila[i];
            celda = tr.querySelector('td[data-campo="' + campo + '"]');
            ponerDataTexto(celda, valorCelda(item, celda, campo));
        }

        enlace = tr.querySelector('.abm-ver');

        if (enlace) {
            enlace.setAttribute('href', (enlace.getAttribute('href') || '') + '?id=' + encodeURIComponent(item.id));
        }

        return tr;
    }

    function mostrarVacio(cantidad, respuesta) {
        var msg = '';

        if (!mensajeVacio) {
            return;
        }

        if (cantidad === 0 && respuesta && respuesta.success === false) {
            msg = mensajeDeApi(respuesta) || '';
        }

        mensajeVacio.textContent = msg;
        mensajeVacio.hidden = msg === '';
    }

    function dibujarPaginador(cantidad) {
        var total;
        var visibles;
        var indice;

        if (!paginador || !listaPaginas) {
            return;
        }

        listaPaginas.querySelectorAll('[data-pagina-numero], [data-pagina-ellipsis]').forEach(function (n) {
            n.remove();
        });

        total = porPagina > 0 ? Math.ceil(cantidad / porPagina) : 1;

        if (porPagina <= 0 || cantidad <= porPagina) {
            paginador.hidden = true;
            return;
        }

        if (pagina > total) {
            pagina = total;
        }

        visibles = numerosPaginador(pagina, total);

        for (indice = 0; indice < visibles.length; indice++) {
            (function (marca) {
                var item;
                var botonPagina;
                var numero;

                if (marca === null) {
                    insertarSeparadorPaginador();
                    return;
                }

                numero = marca;
                item = clonarModelo('pagina-modelo-auditorias');

                if (!item) {
                    return;
                }

                item.setAttribute('data-pagina-numero', String(numero));
                item.dataset.activa = numero === pagina ? 'true' : 'false';
                botonPagina = item.querySelector('.page-link');
                botonPagina.textContent = String(numero);

                if (numero === pagina) {
                    botonPagina.setAttribute('aria-current', 'page');
                } else {
                    botonPagina.removeAttribute('aria-current');
                }

                botonPagina.addEventListener('click', function (evento) {
                    evento.preventDefault();
                    pagina = numero;
                    dibujar();
                });

                listaPaginas.insertBefore(item, itemSiguiente);
            })(visibles[indice]);
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
        var base = porPagina > 0 ? (pagina - 1) * porPagina : 0;
        var visibles = porPagina > 0 ? lista.slice(base, base + porPagina) : lista;

        dibujarPaginador(lista.length);
        cuerpo.querySelectorAll('tr:not(#fila-modelo-auditoria)').forEach(function (fila) {
            fila.remove();
        });
        visibles.forEach(function (item) {
            var fila = filaAuditoria(item);

            if (fila) {
                cuerpo.appendChild(fila);
            }
        });
        mostrarVacio(lista.length, ultimaRespuesta);
    }

    function acumularEnMapa(filtro, mapa) {
        var campo = filtro && filtro.getAttribute('data-filtro');
        var clave;
        var i;

        if (!campo) {
            return;
        }

        for (i = 0; i < lista.length; i++) {
            clave = texto(lista[i][campo]);

            if (clave !== '') {
                mapa[clave] = true;
            }
        }
    }

    function agregarOpcion(select, valor, etiqueta) {
        var opcion = clonarModelo('opcion-modelo-auditorias');

        if (!opcion) {
            return;
        }

        opcion.value = valor;
        opcion.textContent = etiqueta;
        select.appendChild(opcion);
    }

    function pintarSelectDesdeMapa(select, mapa) {
        var elegido;
        var claves;
        var i;

        if (!select) {
            return;
        }

        elegido = select.value;
        limpiarOpciones(select);
        claves = Object.keys(mapa).sort(function (a, b) {
            return a.localeCompare(b);
        });

        for (i = 0; i < claves.length; i++) {
            agregarOpcion(select, claves[i], claves[i]);
        }

        if (elegido) {
            select.value = elegido;

            if (select.value !== elegido && mapa[elegido]) {
                agregarOpcion(select, elegido, elegido);
                select.value = elegido;
            }
        }
    }

    function textoOpcionDesdeCampos(objeto, spec) {
        var partes = [];
        var campos;
        var i;
        var valor;

        if (!spec) {
            return texto(objeto.id);
        }

        campos = spec.split(/\s+/);

        for (i = 0; i < campos.length; i++) {
            valor = texto(objeto[campos[i]]).trim();

            if (valor !== '') {
                partes.push(valor);
            }
        }

        return partes.length ? partes.join(' ') : texto(objeto.id);
    }

    function cargarUsuariosFiltro() {
        if (!filtroUsuario || !recursoUsuarios) {
            return Promise.resolve();
        }

        return apiRequest(recursoUsuarios).then(function (respuesta) {
            var usuarios = (respuesta && respuesta.data) || [];
            var elegido = filtroUsuario.value;
            var spec = filtroUsuario.getAttribute('data-opcion-texto');
            var vistosId = {};
            var vistosEtiqueta = {};
            var filas = [];
            var i;
            var usuario;
            var id;
            var etiqueta;
            var claveEtiqueta;

            if (!Array.isArray(usuarios)) {
                usuarios = [usuarios];
            }

            for (i = 0; i < usuarios.length; i++) {
                usuario = usuarios[i];
                id = texto(usuario.id);

                if (id === '' || vistosId[id]) {
                    continue;
                }

                vistosId[id] = true;
                etiqueta = textoOpcionDesdeCampos(usuario, spec);

                claveEtiqueta = normalizarEtiqueta(etiqueta);

                if (claveEtiqueta === '' || vistosEtiqueta[claveEtiqueta]) {
                    continue;
                }

                vistosEtiqueta[claveEtiqueta] = true;
                filas.push({ id: id, etiqueta: etiqueta });
            }

            filas.sort(function (a, b) {
                return a.etiqueta.localeCompare(b.etiqueta, undefined, { sensitivity: 'base' });
            });
            limpiarOpciones(filtroUsuario);

            for (i = 0; i < filas.length; i++) {
                agregarOpcion(filtroUsuario, filas[i].id, filas[i].etiqueta);
            }

            if (elegido) {
                filtroUsuario.value = elegido;
            }
        }).catch(function () {
            return null;
        });
    }

    function avisoListado(respuesta) {
        if (!mensaje) {
            return;
        }

        if (respuesta.success === false) {
            avisoDeApi(mensaje, respuesta, false);
        } else {
            avisoDeApi(mensaje, '');
        }
    }

    function cargarListado() {
        if (!recurso) {
            return Promise.resolve();
        }

        return apiRequest(recurso, { parametros: parametrosFiltros() }).then(function (respuesta) {
            var datos = (respuesta && respuesta.data) || [];

            ultimaRespuesta = respuesta;
            lista = sinDuplicados(Array.isArray(datos) ? datos.filter(function (item) {
                return item && texto(item.id) !== '';
            }) : []);
            acumularEnMapa(filtroTabla, opcionesTabla);
            acumularEnMapa(filtroAccion, opcionesAccion);
            pintarSelectDesdeMapa(filtroTabla, opcionesTabla);
            pintarSelectDesdeMapa(filtroAccion, opcionesAccion);
            pagina = 1;
            dibujar();
            avisoListado(respuesta);
        }).catch(function (error) {
            ultimaRespuesta = error && error.data ? error.data : null;
            lista = [];
            pintarSelectDesdeMapa(filtroTabla, opcionesTabla);
            pintarSelectDesdeMapa(filtroAccion, opcionesAccion);
            dibujar();

            if (mensaje) {
                avisoDeApi(mensaje, error, false);
            }
        });
    }

    panel.querySelectorAll('[data-filtro]').forEach(function (filtro) {
        if (filtro.tagName !== 'LABEL') {
            filtro.addEventListener('change', cargarListado);
        }
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

    if (anterior) {
        anterior.addEventListener('click', function (evento) {
            evento.preventDefault();

            if (pagina > 1) {
                pagina -= 1;
                dibujar();
            }
        });
    }

    if (siguiente) {
        siguiente.addEventListener('click', function (evento) {
            var total = porPagina > 0 ? Math.ceil(lista.length / porPagina) : 1;

            evento.preventDefault();

            if (pagina < total) {
                pagina += 1;
                dibujar();
            }
        });
    }

    cargarUsuariosFiltro().then(cargarListado);
});
