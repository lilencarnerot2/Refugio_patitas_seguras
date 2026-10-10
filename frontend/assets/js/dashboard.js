document.addEventListener('DOMContentLoaded', function () {
    var panel = document.getElementById('panel-dashboard');

    if (!panel) {
        return;
    }

    var mensaje = document.getElementById('mensaje-formulario');
    var datos = {};

    function texto(valor) {
        if (valor === undefined || valor === null) {
            return '';
        }

        return String(valor);
    }

    function listaDe(respuesta) {
        var crudo = (respuesta && respuesta.data) || [];

        return (Array.isArray(crudo) ? crudo : [crudo]).filter(function (item) {
            return !!item;
        });
    }

    function contar(items, coincide) {
        var total = 0;

        items.forEach(function (item) {
            if (coincide(item)) {
                total += 1;
            }
        });

        return total;
    }

    function partesPor(items, etiqueta, clave) {
        var vistos = {};
        var partes = [];

        items.forEach(function (item) {
            var nombre = texto(etiqueta(item));

            if (nombre === '' || vistos[nombre]) {
                return;
            }

            vistos[nombre] = true;
            partes.push({
                nombre: nombre,
                cantidad: contar(items, function (otro) {
                    return texto(clave(otro)) === nombre;
                })
            });
        });

        return partes;
    }

    function partesDeCatalogo(catalogo, campoNombre, items, campoId) {
        return (catalogo || []).filter(function (entrada) {
            return !!entrada;
        }).map(function (entrada) {
            return {
                nombre: texto(entrada[campoNombre]),
                cantidad: contar(items, function (item) {
                    return texto(item[campoId]) === texto(entrada.id);
                })
            };
        }).filter(function (parte) {
            return parte.nombre !== '';
        });
    }

    function pintarTorta(idTorta, idLeyenda, partes, total) {
        // arma los segmentos de la torta con los números del backend
        var torta = document.getElementById(idTorta);
        var leyenda = document.getElementById(idLeyenda);

        if (!torta || !leyenda) {
            return;
        }

        var tope = leyenda.children.length;
        var acumulado = 0;
        var i;
        var parte;
        var puntos;
        var largo;
        var escala;
        var segmento;

        for (i = 0; i < tope; i++) {
            parte = partes[i];
            puntos = total > 0 && parte ? (parte.cantidad * 100) / total : 0;
            largo = Math.round(puntos);
            segmento = torta.querySelector('[data-seg="' + (i + 1) + '"]');

            if (segmento) {
                // Solo números del backend en atributos del SVG;
                // colores y forma los define el CSS.
                escala = parseInt(segmento.getAttribute('pathLength') || '100', 10);
                segmento.setAttribute('stroke-dasharray', largo + ' ' + (escala - largo));
                segmento.setAttribute('stroke-dashoffset', String(-Math.round(acumulado)));
            }

            acumulado += puntos;
        }

        Array.prototype.forEach.call(leyenda.children, function (nodo, indice) {
            var actual = partes[indice];
            var nombre = nodo.querySelector('[data-leyenda="nombre"]');
            var pct = nodo.querySelector('[data-leyenda="pct"]');
            var cant = nodo.querySelector('[data-leyenda="cantidad"]');

            if (!actual) {
                if (nombre) {
                    nombre.textContent = '';
                }

                if (pct) {
                    pct.textContent = '';
                }

                if (cant) {
                    cant.textContent = '';
                }

                nodo.removeAttribute('data-cantidad');

                return;
            }

            var porcentaje = total > 0 ? Math.round((actual.cantidad * 100) / total) : 0;

            // Solo datos del backend; el "%" y los paréntesis los pone el CSS.
            if (nombre) {
                nombre.textContent = actual.nombre;
            }

            if (pct) {
                pct.textContent = String(porcentaje);
            }

            if (cant) {
                cant.textContent = String(actual.cantidad);
            }

            nodo.setAttribute('data-cantidad', String(actual.cantidad));
        });
    }

    function pintarNumero(id, valor) {
        var nodo = document.getElementById(id);

        if (nodo) {
            nodo.textContent = String(valor);
        }
    }

    function pintarFilas(idCuerpo, idCabecera, items) {
        // llena las filas que ya están en el html
        var cuerpo = document.getElementById(idCuerpo);
        var cabecera = document.getElementById(idCabecera);

        if (!cuerpo || !cabecera) {
            return;
        }

        var campos = cabecera.querySelectorAll('th[data-campo]');
        var filas = cuerpo.querySelectorAll('tr');
        var ordenados = (items || []).slice().sort(function (a, b) {
            return Number(a.id) - Number(b.id);
        });

        Array.prototype.forEach.call(filas, function (tr, indice) {
            var item = ordenados[indice];

            if (!item) {
                tr.hidden = true;
                tr.removeAttribute('data-id');
                return;
            }

            tr.hidden = false;
            tr.setAttribute('data-id', texto(item.id));

            Array.prototype.forEach.call(campos, function (th) {
                var campo = th.getAttribute('data-campo');
                var td = tr.querySelector('td[data-campo="' + campo + '"]');

                if (!td) {
                    return;
                }

                var valor = campo.indexOf('fecha') === 0 ? fechaCorta(item[campo]) : texto(item[campo]);
                var destino = th.getAttribute('data-href');

                if (campo === 'id' && destino) {
                    var enlace = td.querySelector('a');

                    if (enlace) {
                        // arma el href con el id
                        enlace.setAttribute('href', destino + encodeURIComponent(item.id));
                        enlace.textContent = valor;
                    } else {
                        td.textContent = valor;
                    }
                } else {
                    td.setAttribute('data-texto', valor);
                }
            });
        });
    }

    function pintarBarras(items, campo) {
        var contenedor = document.getElementById('barras-donaciones');

        if (!contenedor) {
            return;
        }

        var partes = partesPor(items, function (item) {
            return item[campo];
        }, function (item) {
            return item[campo];
        });
        var maximo = 1;

        partes.forEach(function (parte) {
            if (parte.cantidad > maximo) {
                maximo = parte.cantidad;
            }
        });

        Array.prototype.forEach.call(contenedor.children, function (grupo, indice) {
            var barra = grupo.querySelector('progress');
            var etiqueta = grupo.querySelector('span');
            var parte = partes[indice];

            if (barra) {
                barra.max = maximo;
                barra.value = parte ? parte.cantidad : 0;
            }

            if (etiqueta) {
                etiqueta.textContent = parte ? parte.nombre : '';
            }
        });
    }

    function mostrarSolicitudes() {
        if (!datos.tipos || !datos.solicitudes) {
            return;
        }

        pintarTorta('torta-solicitudes', 'leyenda-tipos', partesDeCatalogo(datos.tipos, 'nombre_tipo', datos.solicitudes, 'id_tipo_solicitud'), datos.solicitudes.length);
        pintarFilas('cuerpo-dashboard-solicitudes', 'cabecera-dashboard-solicitudes', datos.solicitudes);
    }

    function mostrarPendientes() {
        if (!datos.estados || !datos.solicitudes) {
            return;
        }

        pintarNumero('numero-pendientes', datos.solicitudes.length);
        pintarTorta('torta-pendientes', 'leyenda-estados', partesDeCatalogo(datos.estados, 'nombre_estado', datos.solicitudes, 'id_estado_solicitud'), datos.solicitudes.length);
    }

    function mostrarMascotas() {
        if (!datos.mascotas) {
            return;
        }

        pintarNumero('numero-mascotas', datos.mascotas.length);
        pintarTorta('torta-mascotas', 'leyenda-movimientos', partesPor(datos.mascotas, function (mascota) {
            return mascota.especie;
        }, function (mascota) {
            return mascota.especie;
        }), datos.mascotas.length);
        pintarFilas('cuerpo-dashboard-mascotas', 'cabecera-dashboard-mascotas', datos.mascotas);
    }

    function mostrarDonaciones() {
        if (!datos.donaciones) {
            return;
        }

        var contenedor = document.getElementById('barras-donaciones');
        var campo = contenedor ? contenedor.getAttribute('data-campo') : null;

        pintarNumero('numero-donaciones', datos.donaciones.length);

        if (campo) {
            pintarBarras(datos.donaciones, campo);
        }
    }

    function pedir(recurso, clave, mostrar) {
        return apiRequest(recurso).then(function (respuesta) {
            datos[clave] = listaDe(respuesta);
            mostrar();
        }).catch(function (error) {
            if (mensaje && !mensaje.textContent) {
                mensaje.textContent = mensajeDeApi(error);
            }
        });
    }

    if (mensaje) {
        mensaje.textContent = '';
    }

    pedir('tipos_solicitudes', 'tipos', mostrarSolicitudes);
    pedir('estados_solicitudes', 'estados', mostrarPendientes);
    pedir('solicitudes', 'solicitudes', function () {
        mostrarSolicitudes();
        mostrarPendientes();
    });
    pedir('mascotas', 'mascotas', mostrarMascotas);
    pedir('donaciones', 'donaciones', mostrarDonaciones);
});
