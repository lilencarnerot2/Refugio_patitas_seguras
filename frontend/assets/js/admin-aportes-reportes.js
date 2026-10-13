document.addEventListener('DOMContentLoaded', function () {
    var panel = document.getElementById('panel-aportes-reportes');

    if (!panel) {
        return;
    }

    var cabecera = document.getElementById('cabecera-movimientos');
    var cuerpo = document.getElementById('cuerpo-movimientos');
    var resumen = document.getElementById('resumen-aportes');
    var caja = document.getElementById('mensaje-aportes');
    var formulario = document.getElementById('form-movimiento-reporte');
    var zonaCampos = formulario ? formulario.querySelector('[data-campos-form]') : null;
    var lista = [];
    var pagina = 1;
    var formArmado = false;
    var clavesArmadas = '';
    var datosListos = false;

    function avisar(origen, ok) {
        if (!caja) {
            return;
        }

        caja.textContent = mensajeDeApi(origen);
        caja.setAttribute('data-estado', ok ? 'ok' : 'error');
    }

    function filtrados() {
        return lista.filter(function (item) {
            return coincideFiltros(item, panel);
        });
    }

    function itemPorId(id) {
        var i;

        for (i = 0; i < lista.length; i++) {
            if (String(lista[i].id) === String(id)) {
                return lista[i];
            }
        }

        return null;
    }

    function catalogoDeCampo(nombre) {
        var origen = panel.querySelector('[data-filtro="' + nombre + '"][data-catalogo]');

        return origen ? origen.getAttribute('data-catalogo') : '';
    }

    function pintarResumen(visibles) {
        var total = 0;
        var i;
        var monto;

        if (!resumen) {
            return;
        }

        for (i = 0; i < visibles.length; i++) {
            monto = Number(visibles[i].monto);

            if (!isNaN(monto)) {
                total = total + monto;
            }
        }

        resumen.innerHTML = '';

        var card = document.createElement('article');
        card.className = 'info-card';
        card.innerHTML = '<p class="dash-kpi-numero"></p><p></p>';
        card.querySelector('.dash-kpi-numero').textContent = String(visibles.length);
        card.querySelector('p:last-child').textContent = valorCelda(total);
        resumen.appendChild(card);
    }

    function celdaBoton(clase, id, etiqueta) {
        var celda = document.createElement('td');
        var boton = document.createElement('button');

        boton.type = 'button';
        boton.className = clase;
        boton.setAttribute('data-id', id);
        boton.setAttribute('aria-label', etiqueta);
        boton.disabled = !datosListos;
        celda.appendChild(boton);

        return celda;
    }

    function ponerAcciones() {
        var i;
        var fila;
        var id;
        var filas;

        if (!cuerpo || !datosListos) {
            return;
        }

        if (cabecera) {
            for (i = 0; i < 3; i++) {
                cabecera.appendChild(document.createElement('th'));
            }
        }

        filas = cuerpo.querySelectorAll('tr[data-id]');

        for (i = 0; i < filas.length; i++) {
            fila = filas[i];
            id = fila.getAttribute('data-id');
            fila.appendChild(celdaBoton('abm-alta', id, 'Alta'));
            fila.appendChild(celdaBoton('abm-editar', id, 'Edición'));
            fila.appendChild(celdaBoton('abm-baja', id, 'Baja'));
        }
    }

    function dibujar(nuevaPagina) {
        var visibles;
        var corte;

        if (nuevaPagina) {
            pagina = nuevaPagina;
        }

        visibles = filtrados();
        pintarResumen(visibles);
        corte = armarPaginador(panel, visibles.length, pagina, dibujar);
        pagina = corte.pagina;
        pintarTabla(cabecera, cuerpo, visibles.slice(corte.desde, corte.hasta), clavesDeItems(visibles));
        ponerAcciones();
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

            if (clave === 'id') {
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

    function mostrarForm(item) {
        var claves;
        var i;
        var campo;

        if (!formulario || !datosListos) {
            return;
        }

        armarCampos(item || lista[0]);

        if (!formArmado) {
            return;
        }

        formulario.reset();
        formulario.querySelector('[name="id"]').value = item && item.id !== undefined ? String(item.id) : '';

        if (item) {
            claves = clavesVisibles(item);

            for (i = 0; i < claves.length; i++) {
                campo = formulario.elements[claves[i]];

                if (campo && claves[i] !== 'id' && item[claves[i]] !== undefined && item[claves[i]] !== null) {
                    campo.value = String(item[claves[i]]);
                }
            }
        }

        actualizarCamposDependientes(formulario);
        formulario.hidden = false;
    }

    function cargarListado() {
        return apiRequest('movimientos').then(function (respuesta) {
            lista = listaDe(respuesta);
            datosListos = lista.length > 0;
            armarCampos(lista[0]);
            dibujar(1);

            if (mensajeDeApi(respuesta)) {
                avisar(respuesta, true);
            }
        }).catch(function (error) {
            datosListos = false;
            avisar(error, false);
        });
    }

    function guardarMovimiento() {
        var id;
        var opciones;

        if (!validarFormularioMovimiento(formulario, caja)) {
            return Promise.resolve();
        }

        id = formulario.querySelector('[name="id"]').value;
        opciones = {
            metodo: id ? 'PUT' : 'POST',
            cuerpo: cuerpoDesdeForm(formulario)
        };

        if (id) {
            opciones.parametros = { id: id };
        }

        return apiRequest('movimientos', opciones).then(function (respuesta) {
            avisar(respuesta, true);
            limpiarForm();
            return cargarListado();
        }).catch(function (error) {
            avisar(error, false);
        });
    }

    cargarCatalogos(panel).then(cargarListado);

    panel.querySelectorAll('[data-filtro]').forEach(function (filtro) {
        filtro.addEventListener('change', function () {
            dibujar(1);
        });
    });

    panel.querySelectorAll('[data-limpiar]').forEach(function (boton) {
        boton.addEventListener('click', function () {
            var campo = document.getElementById(boton.getAttribute('data-limpiar'));

            if (campo) {
                campo.value = '';
            }

            dibujar(1);
        });
    });

    if (cuerpo) {
        cuerpo.addEventListener('click', function (evento) {
            var alta = evento.target.closest('.abm-alta');
            var editar = evento.target.closest('.abm-editar');
            var baja = evento.target.closest('.abm-baja');
            var id;

            if (!datosListos) {
                return;
            }

            if (alta) {
                mostrarForm(null);
                return;
            }

            if (editar) {
                mostrarForm(itemPorId(editar.getAttribute('data-id')));
                return;
            }

            if (!baja) {
                return;
            }

            id = baja.getAttribute('data-id');

            apiRequest('movimientos', {
                metodo: 'DELETE',
                parametros: { id: id }
            }).then(function (respuesta) {
                avisar(respuesta, true);
                limpiarForm();
                return cargarListado();
            }).catch(function (error) {
                avisar(error, false);
            });
        });
    }

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
