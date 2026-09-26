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

function celdaSolicitud(clave, valor) {
    var celda = document.createElement('td');

    celda.setAttribute('data-campo', clave);
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
        celdaId.setAttribute('data-campo', 'id');

        if (textoId) {
            enlace = document.createElement('a');
            enlace.href = 'solicitudes_detalle.php?id=' + encodeURIComponent(textoId);
            enlace.textContent = textoId;
            celdaId.appendChild(enlace);
        }

        fila.appendChild(celdaId);
        fila.appendChild(celdaSolicitud('tipo_solicitud', lista[i].tipo_solicitud));
        fila.appendChild(celdaSolicitud('nombre_mascota', lista[i].nombre_mascota));
        fila.appendChild(celdaSolicitud('estado_solicitud', lista[i].estado_solicitud));
        fila.appendChild(celdaSolicitud('fecha_solicitud', lista[i].fecha_solicitud));
        cuerpoSolicitudes.appendChild(fila);
    }
}

if (ficha) {
    var id = new URLSearchParams(window.location.search).get('id');

    if (id) {
        apiRequest('usuarios', {
            parametros: { id: id }
        })
            .then(function (respuesta) {
                if (!respuesta.data) {
                    if (mensaje) {
                        mensaje.textContent = mensajeDeApi(respuesta);
                    }
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
            })
            .catch(function (error) {
                if (mensaje) {
                    mensaje.textContent = mensajeDeApi(error);
                }
            });
    }
}
