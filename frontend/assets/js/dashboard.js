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

    function numeroDeItem(item) {
        var claves;
        var i;
        var clave;
        var valor;
        var numero;

        if (!item) {
            return 0;
        }

        claves = Object.keys(item);

        for (i = 0; i < claves.length; i++) {
            clave = claves[i];

            if (clave === 'id' || clave.indexOf('id_') === 0 || clave === 'estado') {
                continue;
            }

            valor = item[clave];

            if (valor === undefined || valor === null || valor === '' || typeof valor === 'boolean' || typeof valor === 'object') {
                continue;
            }

            numero = Number(valor);

            if (!isNaN(numero)) {
                return numero;
            }
        }

        return 0;
    }

    function partesDeBarras(items) {
        return (items || []).map(function (item) {
            return {
                nombre: typeof textoDeItem === 'function' ? textoDeItem(item) : texto(item),
                cantidad: numeroDeItem(item)
            };
        }).filter(function (parte) {
            return parte.nombre !== '';
        });
    }

    function pintarBarras(items) {
        var contenedor = document.getElementById('barras-donaciones');
        var partes;
        var maximo = 1;

        if (!contenedor) {
            return;
        }

        partes = partesDeBarras(items);
        contenedor.innerHTML = '';

        partes.forEach(function (parte) {
            if (parte.cantidad > maximo) {
                maximo = parte.cantidad;
            }
        });

        partes.forEach(function (parte) {
            var grupo = document.createElement('div');
            var nombre = document.createElement('span');
            var barra = document.createElement('progress');
            var cantidad = document.createElement('span');

            nombre.setAttribute('data-barra', 'nombre');
            nombre.textContent = parte.nombre;
            barra.max = maximo;
            barra.value = parte.cantidad;
            cantidad.setAttribute('data-barra', 'cantidad');
            cantidad.textContent = String(parte.cantidad);
            grupo.appendChild(nombre);
            grupo.appendChild(barra);
            grupo.appendChild(cantidad);
            contenedor.appendChild(grupo);
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
        var contenedor = document.getElementById('barras-donaciones');
        var numero = document.getElementById('numero-donaciones');
        var agrupado = contenedor ? contenedor.getAttribute('data-agrupado') : '';
        var campo = numero ? numero.getAttribute('data-campo') : '';
        var parametros = {};

        if (agrupado) {
            parametros.agrupado_por = agrupado;
        }

        apiRequest('reportes_movimientos').then(function (respuesta) {
            var data = respuesta && respuesta.data;

            if (!data || Array.isArray(data)) {
                return;
            }

            if (campo && data[campo] !== undefined) {
                pintarNumero('numero-donaciones', data[campo]);
            }
        }).catch(function (error) {
            if (mensaje && !mensaje.textContent) {
                avisoDeApi(mensaje, error);
            }
        });

        apiRequest('reportes_movimientos', { parametros: parametros }).then(function (respuesta) {
            pintarBarras(listaDe(respuesta));
        }).catch(function (error) {
            if (mensaje && !mensaje.textContent) {
                avisoDeApi(mensaje, error);
            }
        });
    }

    function pedir(recurso, clave, mostrar) {
        return apiRequest(recurso).then(function (respuesta) {
            datos[clave] = listaDe(respuesta);
            mostrar();
        }).catch(function (error) {
            if (mensaje && !mensaje.textContent) {
                avisoDeApi(mensaje, error);
            }
        });
    }

    if (mensaje) {
        avisoDeApi(mensaje, '');
    }

    pedir('tipos_solicitudes', 'tipos', mostrarSolicitudes);
    pedir('estados_solicitudes', 'estados', mostrarPendientes);
    pedir('solicitudes', 'solicitudes', function () {
        mostrarSolicitudes();
        mostrarPendientes();
    });
    pedir('mascotas', 'mascotas', mostrarMascotas);
    mostrarDonaciones();
});
