document.addEventListener('DOMContentLoaded', function () {
    var panel = document.getElementById('panel-mascotas');

    if (!panel) {
        return;
    }

    var mensaje = document.getElementById('mensaje-formulario');
    var cuerpo = document.getElementById('cuerpo-mascotas');
    var buscar = document.getElementById('buscar-mascotas');
    var filtroEspecie = document.getElementById('filtro-especie');
    var paginador = panel.querySelector('[data-paginador]');
    var listaPaginas = paginador ? paginador.querySelector('.pagination') : null;
    var anterior = paginador ? paginador.querySelector('[data-pagina-anterior]') : null;
    var siguiente = paginador ? paginador.querySelector('[data-pagina-siguiente]') : null;
    var itemAnterior = anterior && anterior.closest ? anterior.closest('.page-item') : null;
    var itemSiguiente = siguiente && siguiente.closest ? siguiente.closest('.page-item') : null;
    var lista = [];
    var pagina = 1;
    var porPagina = parseInt(panel.getAttribute('data-por-pagina'), 10);

    if (isNaN(porPagina) || porPagina < 1) {
        porPagina = 0;
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

    function coincide(mascota) {
        var palabras = normalizar(buscar ? buscar.value : '').split(/\s+/).filter(function (palabra) {
            return palabra !== '';
        });
        var fila = normalizar(mascota.nombre);

        for (var i = 0; i < palabras.length; i++) {
            if (fila.indexOf(palabras[i]) === -1) {
                return false;
            }
        }

        if (filtroEspecie && filtroEspecie.value !== '' && normalizar(mascota.especie) !== normalizar(filtroEspecie.value)) {
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
            var item = clonarModelo('pagina-modelo-mascotas');

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

        if (anterior) {
            anterior.disabled = pagina <= 1;
        }

        if (itemSiguiente) {
            itemSiguiente.dataset.deshabilitado = pagina >= total ? 'true' : 'false';
        }

        if (siguiente) {
            siguiente.disabled = pagina >= total;
        }

        paginador.hidden = false;
    }

    function dibujar() {
        cuerpo.querySelectorAll('tr:not(#fila-modelo-mascota)').forEach(function (fila) {
            fila.remove();
        });

        var filas = lista.filter(coincide).sort(function (a, b) {
            return Number(a.id) - Number(b.id);
        });

        dibujarPaginador(filas.length);

        var base = porPagina > 0 ? (pagina - 1) * porPagina : 0;
        var visibles = porPagina > 0 ? filas.slice(base, base + porPagina) : filas;

        visibles.forEach(function (mascota, indice) {
            // Estructura en la fila modelo del HTML; aquí solo datos del backend.
            var tr = clonarModelo('fila-modelo-mascota');

            if (!tr) {
                return;
            }

            tr.setAttribute('data-id', texto(mascota.id));

            var valores = [
                texto(base + indice + 1),
                texto(mascota.nombre),
                texto(mascota.especie),
                texto(mascota.raza),
                texto(mascota.sexo),
                texto(mascota.tamaño)
            ];

            var celdas = tr.querySelectorAll('[data-celda]');

            Array.prototype.forEach.call(celdas, function (td, i) {
                if (valores[i] !== undefined) {
                    td.textContent = valores[i];
                }
            });

            var enlace = tr.querySelector('.abm-ver');

            if (enlace) {
                // le agrega el id al href
                enlace.setAttribute('href', (enlace.getAttribute('href') || '') + '?id=' + encodeURIComponent(mascota.id));
            }

            var boton = tr.querySelector('.abm-baja');

            if (boton) {
                boton.setAttribute('data-id', texto(mascota.id));
            }

            cuerpo.appendChild(tr);
        });
    }

    function cargarListado() {
        if (mensaje) {
            mensaje.textContent = '';
        }

        return apiRequest('mascotas').then(function (respuesta) {
            var datos = (respuesta && respuesta.data) || [];

            lista = Array.isArray(datos) ? datos.filter(function (mascota) {
                return !!mascota;
            }) : [datos];

            var vistas = {};

            lista.forEach(function (mascota) {
                var especie = texto(mascota.especie);

                if (especie === '' || vistas[especie]) {
                    return;
                }

                vistas[especie] = true;

                var opcion = clonarModelo('opcion-modelo-mascotas');

                if (!opcion) {
                    return;
                }

                opcion.value = especie;
                opcion.textContent = especie;
                filtroEspecie.appendChild(opcion);
            });

            dibujar();

            if (mensaje) {
                mensaje.textContent = mensajeDeApi(respuesta);
            }
        });
    }

    if (buscar) {
        buscar.addEventListener('input', function () {
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

    cuerpo.addEventListener('click', function (evento) {
        var boton = evento.target.closest('[data-id].abm-baja');

        if (!boton) {
            return;
        }

        apiRequest('mascotas', {
            metodo: 'DELETE',
            parametros: { id: boton.getAttribute('data-id') }
        }).then(function (respuesta) {
            return cargarListado().then(function () {
                if (mensaje) {
                    mensaje.textContent = mensajeDeApi(respuesta);
                }
            });
        }).catch(function (error) {
            if (mensaje) {
                mensaje.textContent = mensajeDeApi(error);
            }
        });
    });

    cargarListado().catch(function (error) {
        if (mensaje) {
            mensaje.textContent = mensajeDeApi(error);
        }
    });
});
