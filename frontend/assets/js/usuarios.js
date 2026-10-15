document.addEventListener('DOMContentLoaded', function () {
    var panel = document.getElementById('panel-usuarios');

    if (!panel) {
        return;
    }

    var mensaje = document.getElementById('mensaje-formulario');
    var cuerpo = document.getElementById('cuerpo-usuarios');
    var buscar = document.getElementById('buscar-usuarios');
    var filtroRol = document.getElementById('filtro-rol');
    var filtroEstado = document.getElementById('filtro-estado');
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

    function filaUsuario(usuario, nro) {
        // Estructura de la fila en la fila modelo de usuarios.php; aquí solo datos.
        var tr = clonarModelo('fila-modelo-usuario');

        if (!tr) {
            return null;
        }

        tr.setAttribute('data-id', texto(usuario.id));

        var valores = {
            id: nro,
            nombre: usuario.nombre,
            apellido: usuario.apellido,
            fecha_nacimiento: fechaCorta(usuario.fecha_nacimiento),
            fecha_alta: fechaCorta(usuario.fecha_alta),
            nombre_rol: usuario.nombre_rol
        };

        Object.keys(valores).forEach(function (campo) {
            var td = tr.querySelector('td[data-campo="' + campo + '"]');

            if (td) {
                td.setAttribute('data-texto', texto(valores[campo]));
            }
        });

        var estado = tr.querySelector('td[data-campo="estado"]');

        if (estado) {
            estado.setAttribute('data-estado', texto(usuario.estado).toLowerCase());
        }

        ponerDestino(tr, '.abm-ver', usuario.id);
        ponerDestino(tr, '.abm-editar', usuario.id);

        var baja = tr.querySelector('.abm-baja');

        if (baja) {
            baja.setAttribute('data-id', texto(usuario.id));
        }

        return tr;
    }

    function coincide(usuario) {
        var palabras = normalizar(buscar ? buscar.value : '').split(/\s+/).filter(function (palabra) {
            return palabra !== '';
        });
        var fila = normalizar([
            usuario.nombre,
            usuario.apellido,
            usuario.fecha_nacimiento,
            usuario.fecha_alta,
            usuario.nombre_rol
        ].join(' '));

        for (var i = 0; i < palabras.length; i++) {
            if (fila.indexOf(palabras[i]) === -1) {
                return false;
            }
        }

        if (filtroRol && filtroRol.value !== '' && texto(usuario.id_rol) !== filtroRol.value) {
            return false;
        }

        if (filtroEstado && filtroEstado.value !== '' && texto(usuario.estado).toLowerCase() !== filtroEstado.value) {
            return false;
        }

        return true;
    }

    // le agrega el id al href del modelo
    function ponerDestino(fila, selector, id) {
        var enlace = fila.querySelector(selector);

        if (!enlace) {
            return;
        }

        enlace.setAttribute('href', (enlace.getAttribute('href') || '') + '?id=' + encodeURIComponent(id));
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
            var item = clonarModelo('pagina-modelo-usuarios');

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
        var filtrada = lista.filter(coincide).sort(function (a, b) {
            return Number(a.id) - Number(b.id);
        });

        dibujarPaginador(filtrada.length);

        var base = porPagina > 0 ? (pagina - 1) * porPagina : 0;
        var visibles = porPagina > 0 ? filtrada.slice(base, base + porPagina) : filtrada;

        cuerpo.querySelectorAll('tr:not(#fila-modelo-usuario)').forEach(function (fila) {
            fila.remove();
        });

        visibles.forEach(function (usuario, indice) {
            var fila = filaUsuario(usuario, base + indice + 1);

            if (fila) {
                cuerpo.appendChild(fila);
            }
        });
    }

    function llenarRoles() {
        if (!filtroRol) {
            return;
        }

        var elegido = filtroRol.value;
        var vistos = {};

        filtroRol.querySelectorAll('option').forEach(function (opcion) {
            if (opcion.value !== '') {
                opcion.remove();
            }
        });

        lista.forEach(function (usuario) {
            var idRol = texto(usuario.id_rol);
            var nombreRol = texto(usuario.nombre_rol);
            var opcion;

            if (idRol === '' || vistos[idRol]) {
                return;
            }

            vistos[idRol] = true;
            opcion = clonarModelo('opcion-modelo-usuarios');

            if (!opcion) {
                return;
            }

            opcion.value = idRol;
            opcion.textContent = nombreRol || idRol;
            filtroRol.appendChild(opcion);
        });

        if (elegido) {
            filtroRol.value = elegido;
        }
    }

    function cargarListado() {
        if (mensaje) {
            avisoDeApi(mensaje, '');
        }

        apiRequest('usuarios').then(function (respuesta) {
            var datos = (respuesta && respuesta.data) || [];

            lista = Array.isArray(datos) ? datos.filter(function (usuario) {
                if (!usuario) {
                    return false;
                }

                var sinNombre = texto(usuario.nombre) === '' && texto(usuario.apellido) === '';

                return texto(usuario.id) !== '' && !sinNombre;
            }) : [datos];

            llenarRoles();
            dibujar();
        }).catch(function (error) {
            if (mensaje) {
                avisoDeApi(mensaje, error);
            }
        });
    }

    if (buscar) {
        buscar.addEventListener('input', function () {
            pagina = 1;
            dibujar();
        });
    }

    [filtroRol, filtroEstado].forEach(function (filtro) {
        if (filtro) {
            filtro.addEventListener('change', function () {
                pagina = 1;
                dibujar();
            });
        }
    });

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

        apiRequest('usuarios', {
            metodo: 'DELETE',
            parametros: { id: boton.getAttribute('data-id') }
        }).then(function (respuesta) {
            return cargarListado().then(function () {
                if (mensaje) {
                    avisoDeApi(mensaje, respuesta);
                }
            });
        }).catch(function (error) {
            if (mensaje) {
                avisoDeApi(mensaje, error);
            }
        });
    });

    cargarListado();
});
