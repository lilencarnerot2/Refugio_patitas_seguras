var mensaje = document.getElementById('mensaje-formulario');
var ficha = document.getElementById('ficha-usuario');
var cuerpoSolicitudes = document.getElementById('cuerpo-solicitudes');
var campos = {
    'detalle-id': 'id',
    'detalle-uid': 'uid_pp2',
    'detalle-id-rol': 'id_rol',
    'detalle-domicilio': 'id_domicilio',
    'detalle-nombre': 'nombre',
    'detalle-apellido': 'apellido',
    'detalle-nacimiento': 'fecha_nacimiento',
    'detalle-alta': 'fecha_alta',
    'detalle-nombre-rol': 'nombre_rol',
    'detalle-estado': 'estado'
};

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

function textoApi(valor) {
    if (valor === undefined || valor === null || valor === '') {
        return '';
    }

    return String(valor);
}

function vaciar(nodo) {
    while (nodo.firstChild) {
        nodo.removeChild(nodo.firstChild);
    }
}

function mostrarFicha(usuario) {
    var id;
    var nodo;
    var nombre = document.getElementById('ficha-nombre');

    for (id in campos) {
        if (!Object.prototype.hasOwnProperty.call(campos, id)) {
            continue;
        }

        nodo = document.getElementById(id);

        if (!nodo) {
            continue;
        }

        if (id === 'detalle-estado') {
            nodo.setAttribute('data-estado', textoApi(usuario[campos[id]]).toLowerCase());
        } else {
            nodo.textContent = textoApi(usuario[campos[id]]);
        }
    }

    if (nombre) {
        nombre.textContent = textoApi(usuario.nombre);
    }
}

function celdaTexto(valor) {
    var celda = document.createElement('td');
    celda.textContent = textoApi(valor);
    return celda;
}

function mostrarSolicitudes(lista, idUsuario) {
    var i;
    var fila;
    var enlace;
    var celdaId;
    var textoId;

    if (!cuerpoSolicitudes) {
        return;
    }

    vaciar(cuerpoSolicitudes);

    for (i = 0; i < lista.length; i++) {
        if (textoApi(lista[i].id_usuario) !== textoApi(idUsuario)) {
            continue;
        }

        fila = document.createElement('tr');
        textoId = textoApi(lista[i].id);
        celdaId = document.createElement('td');

        if (textoId) {
            enlace = document.createElement('a');
            enlace.href = 'solicitudes_detalle.php?id=' + encodeURIComponent(textoId);
            enlace.textContent = textoId;
            celdaId.appendChild(enlace);
        }

        fila.appendChild(celdaId);
        fila.appendChild(celdaTexto(lista[i].tipo_solicitud));
        fila.appendChild(celdaTexto(lista[i].nombre_mascota));
        fila.appendChild(celdaTexto(lista[i].estado_solicitud));
        fila.appendChild(celdaTexto(lista[i].fecha_solicitud));
        cuerpoSolicitudes.appendChild(fila);
    }
}

if (ficha) {
    var id = new URLSearchParams(window.location.search).get('id');

    if (id) {
        apiRequest('roles')
            .then(function (rolesRespuesta) {
                var roles = rolesRespuesta.data || [];
                var idBase = '';

                if (Array.isArray(roles) && roles[0] && roles[0].id !== undefined && roles[0].id !== null) {
                    idBase = String(roles[0].id);
                }

                return apiRequest('usuarios', {
                    parametros: { id: id }
                }).then(function (respuesta) {
                    if (!respuesta.data) {
                        if (mensaje && respuesta.message) {
                            mensaje.textContent = respuesta.message;
                        }
                        return;
                    }

                    if (!esSuperadmin() && idBase && String(respuesta.data.id_rol) !== idBase) {
                        window.location.href = 'usuarios.php';
                        return;
                    }

                    mostrarFicha(respuesta.data);

                    return apiRequest('solicitudes').then(function (solicitudesRespuesta) {
                        var lista = solicitudesRespuesta.data || [];

                        if (!Array.isArray(lista)) {
                            lista = [];
                        }

                        mostrarSolicitudes(lista, respuesta.data.id);
                    });
                });
            })
            .catch(function (error) {
                if (mensaje) {
                    if (error.message) {
                        mensaje.textContent = error.message;
                    } else if (error.data && error.data.message) {
                        mensaje.textContent = error.data.message;
                    }
                }
            });
    }
}
