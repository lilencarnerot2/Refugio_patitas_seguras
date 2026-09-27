var panel = document.getElementById('panel-usuario-detalle');
var ficha = document.getElementById('ficha-usuario');
var mensaje = document.getElementById('mensaje-formulario');

function cookieValor(nombre) {
    var partes = document.cookie.split(';');
    var i;
    var parte;
    var prefijo = nombre + '=';

    for (i = 0; i < partes.length; i++) {
        parte = partes[i].replace(/^\s+/, '');

        if (parte.indexOf(prefijo) === 0) {
            return decodeURIComponent(parte.slice(prefijo.length));
        }
    }

    return '';
}

function esSuperadmin() {
    return cookieValor('usuario_acceso_superadmin') === '1';
}

function claveId() {
    var nodo = ficha.querySelector('.pet-detail-meta [data-campo]');

    return nodo ? nodo.getAttribute('data-campo') : '';
}

function asignarUsuario(usuario) {
    var nodos = ficha.querySelectorAll('[data-campo]');
    var clave = claveId();
    var id = textoApi(usuario[clave]);
    var i;
    var nodo;
    var campo;
    var texto;
    var enlace;
    var href;

    for (i = 0; i < nodos.length; i++) {
        nodo = nodos[i];
        campo = nodo.getAttribute('data-campo');
        texto = textoApi(usuario[campo]);

        if (nodo.hasAttribute('data-badge')) {
            nodo.setAttribute('data-estado', texto.toLowerCase());
            continue;
        }

        nodo.setAttribute('data-texto', texto);
    }

    enlace = ficha.querySelector('[data-href]');

    if (!enlace || !id) {
        return;
    }

    href = enlace.getAttribute('data-href') || '';
    enlace.setAttribute('href', href + (href.indexOf('?') === -1 ? '?' : '&') + clave + '=' + encodeURIComponent(id));
}

if (panel && ficha) {
    var clave = claveId();
    var id = clave ? new URLSearchParams(window.location.search).get(clave) : '';
    var listado = panel.getAttribute('data-listado') || '';

    if (id) {
        apiRequest('roles')
            .then(function (rolesRespuesta) {
                var idsVisibles = mapaValoresDeApi(rolesListadoDeApi(rolesRespuesta.data || []));

                return apiRequest('usuarios', {
                    parametros: { id: id }
                }).then(function (respuesta) {
                    var usuario = respuesta.data;

                    if (!usuario) {
                        if (mensaje) {
                            mensaje.textContent = mensajeDeApi(respuesta);
                        }
                        return;
                    }

                    if (!esSuperadmin() && !idsVisibles[textoApi(usuario.id_rol)]) {
                        if (listado) {
                            window.location.href = listado;
                        }
                        return;
                    }

                    asignarUsuario(usuario);
                });
            })
            .catch(function (error) {
                if (mensaje) {
                    mensaje.textContent = mensajeDeApi(error);
                }
            });
    }
}
