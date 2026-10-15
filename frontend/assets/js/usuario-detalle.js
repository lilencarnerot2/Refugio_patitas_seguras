document.addEventListener('DOMContentLoaded', function () {
    var panel = document.getElementById('panel-usuario-detalle');

    if (!panel) {
        return;
    }

    var ficha = document.getElementById('ficha-usuario');
    var mensaje = document.getElementById('mensaje-formulario');
    var cabecera = document.getElementById('cabecera-solicitudes');
    var cuerpo = document.getElementById('cuerpo-solicitudes');
    var id = new URLSearchParams(window.location.search).get('id');
    var listado = panel.getAttribute('data-listado') || '';

    function texto(valor) {
        if (valor === undefined || valor === null) {
            return '';
        }

        return String(valor);
    }

    // carga los datos en data-texto
    function pintarFicha(usuario) {
        ficha.querySelectorAll('[data-campo]').forEach(function (nodo) {
            var campo = nodo.getAttribute('data-campo');
            var valor = campo.indexOf('fecha') === 0 ? fechaCorta(usuario[campo]) : texto(usuario[campo]);

            if (valor !== '') {
                nodo.setAttribute('data-texto', valor);
            } else {
                nodo.removeAttribute('data-texto');
            }

            if (campo === 'estado') {
                nodo.setAttribute('data-estado', valor.toLowerCase());
            }
        });
    }

    function pintarRol(usuario) {
        // si no vino el nombre del rol, lo busca en roles
        if (texto(usuario.nombre_rol) !== '') {
            return Promise.resolve(null);
        }

        if (texto(usuario.id_rol) === '') {
            return Promise.resolve(null);
        }

        return apiRequest('roles').then(function (respuesta) {
            var roles = (respuesta && respuesta.data) || [];
            var lista = Array.isArray(roles) ? roles : [roles];
            var i;

            for (i = 0; i < lista.length; i++) {
                if (lista[i] && texto(lista[i].id) === texto(usuario.id_rol)) {
                    var destino = document.getElementById('detalle-nombre-rol');

                    if (destino) {
                        destino.setAttribute('data-texto', texto(lista[i].nombre_rol || lista[i].nombre));
                    }

                    break;
                }
            }

            return null;
        }).catch(function () {
            return null;
        });
    }

    function pintarSolicitudes(usuario) {
        if (!cuerpo || !cabecera) {
            return Promise.resolve(null);
        }

        return apiRequest('solicitudes').then(function (respuesta) {
            var datos = (respuesta && respuesta.data) || [];
            var todas = Array.isArray(datos) ? datos : [datos];
            var propias = todas.filter(function (solicitud) {
                return solicitud && texto(solicitud.id_usuario) === texto(usuario.id);
            });
            var campos = cabecera.querySelectorAll('th[data-campo]');
            var filas = cuerpo.querySelectorAll('tr');

            Array.prototype.forEach.call(filas, function (tr, indice) {
                var solicitud = propias[indice];

                if (!solicitud) {
                    tr.removeAttribute('data-id');
                    return;
                }

                tr.setAttribute('data-id', texto(solicitud.id));

                Array.prototype.forEach.call(campos, function (th) {
                    var campo = th.getAttribute('data-campo');
                    var td = tr.querySelector('td[data-campo="' + campo + '"]');

                    if (!td) {
                        return;
                    }

                    var valor = campo.indexOf('fecha') === 0 ? fechaCorta(solicitud[campo]) : texto(solicitud[campo]);
                    var enlace = td.querySelector('a');

                    if (enlace) {
                        // le agrega el id al href
                        enlace.setAttribute('href', (enlace.getAttribute('href') || '') + '?id=' + encodeURIComponent(solicitud.id));
                        enlace.textContent = valor;
                    } else if (valor !== '') {
                        td.setAttribute('data-texto', valor);
                    } else {
                        td.removeAttribute('data-texto');
                    }
                });
            });

            return null;
        });
    }

    if (!id) {
        if (listado) {
            window.location.href = listado;
        }

        return;
    }

    apiRequest('usuarios', {
        parametros: { id: id }
    }).then(function (respuesta) {
        var usuario = (respuesta && respuesta.data) || {};

        if (Array.isArray(usuario)) {
            usuario = usuario[0] || {};
        }

        if (!usuario.id) {
            if (mensaje) {
                avisoDeApi(mensaje, respuesta);
            }

            return null;
        }

        pintarFicha(usuario);

        return pintarRol(usuario).then(function () {
            return pintarSolicitudes(usuario);
        });
    }).catch(function (error) {
        if (mensaje) {
            avisoDeApi(mensaje, error);
        }

        if (listado && error && error.status === 404) {
            window.location.href = listado;
        }
    });
});
