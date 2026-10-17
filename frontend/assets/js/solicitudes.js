document.addEventListener('DOMContentLoaded', function () {
    var panel = document.getElementById('panel-solicitudes');

    if (!panel) {
        return;
    }

    var mensaje = document.getElementById('mensaje-formulario');
    var cuerpo = document.getElementById('cuerpo-solicitudes');
    var buscar = document.getElementById('buscar-solicitudes');
    var filtroEstado = document.getElementById('filtro-estado-solicitud');
    var filtroEspecie = document.getElementById('filtro-especie-solicitud');
    var resumen = document.getElementById('resumen-solicitudes');
    var paginador = panel.querySelector('[data-paginador]');
    var listaPaginas = paginador ? paginador.querySelector('.pagination') : null;
    var anterior = paginador ? paginador.querySelector('[data-pagina-anterior]') : null;
    var siguiente = paginador ? paginador.querySelector('[data-pagina-siguiente]') : null;
    var itemAnterior = anterior && anterior.closest ? anterior.closest('.page-item') : null;
    var itemSiguiente = siguiente && siguiente.closest ? siguiente.closest('.page-item') : null;
    var lista = [];
    var pagina = 1;
    var porPagina = parseInt(panel.getAttribute('data-por-pagina'), 10);
    var tipoPedido = panel.getAttribute('data-tipo') || '';

    if (isNaN(porPagina) || porPagina < 1) {
        porPagina = 0;
    }

    function esTransito(solicitud) {
        var tipo = normalizar(solicitud && solicitud.tipo_solicitud);

        return tipo.indexOf('transito') !== -1 || tipo.indexOf('hogar') !== -1 && tipo.indexOf('trans') !== -1;
    }

    function coincideTipo(solicitud) {
        if (!tipoPedido) {
            return true;
        }

        if (tipoPedido === 'transito') {
            return esTransito(solicitud);
        }

        return normalizar(solicitud.tipo_solicitud).indexOf(normalizar(tipoPedido)) !== -1;
    }

    function texto(valor) {
        if (valor === undefined || valor === null) {
            return '';
        }

        return String(valor);
    }

    function normalizar(valor) {
        return texto(valor).toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    }

    function urlDetalleSolicitud(solicitud) {
        var panel = document.getElementById('panel-solicitudes');
        var detalleListado = panel && panel.getAttribute('data-detalle');

        if (detalleListado) {
            return detalleListado.replace(/\?.*$/, '');
        }

        return enlaceDetalleSolicitud(solicitud);
    }

    function coincide(solicitud) {
        var palabras = normalizar(buscar ? buscar.value : '').split(/\s+/).filter(function (palabra) {
            return palabra !== '';
        });
        var fila = normalizar([
            solicitud.nombre_usuario,
            solicitud.apellido_usuario,
            solicitud.solicitante,
            solicitud.nombre_mascota,
            solicitud.especie,
            solicitud.tipo_solicitud,
            solicitud.estado_solicitud
        ].join(' '));

        for (var i = 0; i < palabras.length; i++) {
            if (fila.indexOf(palabras[i]) === -1) {
                return false;
            }
        }

        if (filtroEstado && filtroEstado.value !== '' && normalizar(solicitud.estado_solicitud) !== normalizar(filtroEstado.value)) {
            return false;
        }

        if (filtroEspecie && filtroEspecie.value !== '' && normalizar(solicitud.especie) !== normalizar(filtroEspecie.value)) {
            return false;
        }

        return true;
    }

    function clonarModelo(id) {
        // clona la fila modelo
        var modelo = document.getElementById(id);

        if (!modelo) {
            return null;
        }

        var copia = modelo.cloneNode(true);

        copia.removeAttribute('id');
        copia.hidden = false;

        return copia;
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
            // Diseño (li.page-item > button.page-link) en la fila modelo; aquí solo datos.
            var item = clonarModelo('pagina-modelo-solicitud');

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
                botonPagina.addEventListener('click', function () {
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
        cuerpo.querySelectorAll('tr:not(#fila-modelo-solicitud)').forEach(function (fila) {
            fila.remove();
        });

        var filas = lista.filter(coincide).sort(function (a, b) {
            return Number(a.id) - Number(b.id);
        });

        dibujarPaginador(filas.length);

        var base = porPagina > 0 ? (pagina - 1) * porPagina : 0;
        var visibles = porPagina > 0 ? filas.slice(base, base + porPagina) : filas;

        visibles.forEach(function (solicitud) {
            // Estructura de la fila en la fila modelo del HTML; aquí solo datos del backend.
            var tr = clonarModelo('fila-modelo-solicitud');

            if (!tr) {
                return;
            }

            tr.setAttribute('data-id', texto(solicitud.id));

            var valores = {
                id: texto(solicitud.id),
                solicitante: texto(solicitud.solicitante),
                nombre_mascota: texto(solicitud.nombre_mascota),
                tipo_solicitud: texto(solicitud.tipo_solicitud),
                estado_solicitud: texto(solicitud.estado_solicitud)
            };

            if (tipoPedido === 'transito') {
                delete valores.especie;
                delete valores.tipo_solicitud;
                delete valores.fecha_solicitud;
            }

            Object.keys(valores).forEach(function (campo) {
                var td = tr.querySelector('td[data-campo="' + campo + '"]');

                if (td) {
                    td.setAttribute('data-texto', valores[campo]);
                }
            });

            var enlace = tr.querySelector('a');

            if (enlace) {
                enlace.setAttribute('href', urlDetalleSolicitud(solicitud) + '?id=' + encodeURIComponent(solicitud.id));
            }

            cuerpo.appendChild(tr);
        });
    }

    function agregarOpcion(select, valor) {
        // clona la opción del modelo
        var opcion = clonarModelo('opcion-modelo-solicitud');

        if (!opcion) {
            return;
        }

        opcion.value = valor;
        opcion.textContent = valor;
        select.appendChild(opcion);
    }

    function tarjetaTipo(tipo, cantidad, total) {
        // Tarjeta diseñada en el modelo del HTML; aquí solo datos del backend.
        var tarjeta = clonarModelo('tarjeta-modelo-tipo');
        var porcentaje = total > 0 ? Math.round((cantidad * 100) / total) : 0;
        var arco = tarjeta.querySelector('.dash-donut-valor');
        var numero = tarjeta.querySelector('.dash-kpi-numero');
        var parteCantidad = tarjeta.querySelector('[data-parte="cantidad"]');
        var parteTotal = tarjeta.querySelector('[data-parte="total"]');

        tarjeta.querySelector('h3').setAttribute('data-texto', tipo.nombre_tipo || '');

        if (arco) {
            // Solo números del backend en atributos del SVG;
            // colores y forma los define el CSS.
            var escala = parseInt(arco.getAttribute('pathLength') || '100', 10);
            arco.setAttribute('stroke-dasharray', porcentaje + ' ' + (escala - porcentaje));
        }

        // Solo números; el "%" lo agrega el CSS y el "/" está en el HTML.
        if (numero) {
            numero.setAttribute('data-texto', String(porcentaje));
        }

        if (parteCantidad) {
            parteCantidad.textContent = String(cantidad);
        }

        if (parteTotal) {
            parteTotal.textContent = String(total);
        }

        return tarjeta;
    }

    function dibujarResumen(tipos) {
        if (!resumen || tipoPedido) {
            return;
        }

        while (resumen.firstChild) {
            resumen.removeChild(resumen.firstChild);
        }

        tipos.forEach(function (tipo) {
            if (!tipo) {
                return;
            }

            var cantidad = lista.filter(function (solicitud) {
                return String(solicitud.id_tipo_solicitud) === String(tipo.id);
            }).length;

            resumen.appendChild(tarjetaTipo(tipo, cantidad, lista.length));
        });
    }

    function cargarListado() {
        if (mensaje) {
            avisoDeApi(mensaje, '');
        }

        var especiesPorMascota = {};

        return apiRequest('tipos_solicitudes').then(function (respuestaTipos) {
            var tipos = (respuestaTipos && respuestaTipos.data) || [];

            enlazarModelosDetalleSolicitud(Array.isArray(tipos) ? tipos : [tipos]);

            return apiRequest('mascotas');
        }).then(function (respuestaMascotas) {
            var mascotas = (respuestaMascotas && respuestaMascotas.data) || [];

            (Array.isArray(mascotas) ? mascotas : [mascotas]).forEach(function (mascota) {
                if (mascota) {
                    especiesPorMascota[String(mascota.id)] = texto(mascota.especie);
                }
            });

            var vistas = {};

            if (filtroEspecie) {
                while (filtroEspecie.options.length > 1) {
                    filtroEspecie.remove(1);
                }

                Object.keys(especiesPorMascota).forEach(function (id) {
                    var especie = especiesPorMascota[id];

                    if (especie === '' || vistas[especie]) {
                        return;
                    }

                    vistas[especie] = true;
                    agregarOpcion(filtroEspecie, especie);
                });
            }

            return apiRequest('solicitudes');
        }).then(function (respuesta) {
            var datos = (respuesta && respuesta.data) || [];

            lista = (Array.isArray(datos) ? datos : [datos]).filter(function (solicitud) {
                return !!solicitud;
            }).filter(function (solicitud) {
                var sinSolicitante = texto(solicitud.nombre_usuario) === '' && texto(solicitud.apellido_usuario) === '';
                var sinMascota = texto(solicitud.nombre_mascota) === '';

                return texto(solicitud.id) !== '' && !sinSolicitante && !sinMascota;
            }).map(function (solicitud) {
                solicitud.solicitante = [texto(solicitud.nombre_usuario), texto(solicitud.apellido_usuario)].filter(function (parte) {
                    return parte !== '';
                }).join(' ');
                solicitud.especie = especiesPorMascota[String(solicitud.id_mascota)] || '';

                return solicitud;
            }).filter(coincideTipo);
            dibujar();

            if (mensaje) {
                avisoDeApi(mensaje, respuesta);
            }

            return apiRequest('estados_solicitudes');
        }).then(function (respuestaEstados) {
            var estados = (respuestaEstados && respuestaEstados.data) || [];

            if (filtroEstado) {
                while (filtroEstado.options.length > 1) {
                    filtroEstado.remove(1);
                }

                (Array.isArray(estados) ? estados : [estados]).forEach(function (estado) {
                    if (estado) {
                        agregarOpcion(filtroEstado, texto(estado.nombre_estado));
                    }
                });
            }

            if (!resumen) {
                return;
            }

            return apiRequest('tipos_solicitudes');
        }).then(function (respuestaTipos) {
            var tipos = (respuestaTipos && respuestaTipos.data) || [];

            tipos = Array.isArray(tipos) ? tipos : [tipos];
            enlazarModelosDetalleSolicitud(tipos);
            dibujarResumen(tipos);
        });
    }

    if (buscar) {
        buscar.addEventListener('input', function () {
            pagina = 1;
            dibujar();
        });
    }

    if (filtroEstado) {
        filtroEstado.addEventListener('change', function () {
            pagina = 1;
            dibujar();
        });
    }

    if (filtroEspecie) {
        filtroEspecie.addEventListener('change', function () {
            pagina = 1;
            dibujar();
        });
    }

    if (anterior) {
        anterior.addEventListener('click', function () {
            if (pagina > 1) {
                pagina = pagina - 1;
                dibujar();
            }
        });
    }

    if (siguiente) {
        siguiente.addEventListener('click', function () {
            pagina = pagina + 1;
            dibujar();
        });
    }

    panel.querySelectorAll('[data-limpiar]').forEach(function (boton) {
        boton.addEventListener('click', function () {
            var campo = document.getElementById(boton.getAttribute('data-limpiar'));

            if (campo) {
                campo.value = '';
            }

            pagina = 1;
            dibujar();
        });
    });

    cargarListado().catch(function (error) {
        if (mensaje) {
            avisoDeApi(mensaje, error);
        }
    });
});
