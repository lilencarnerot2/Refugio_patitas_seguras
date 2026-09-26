var mensaje = document.getElementById('mensaje-formulario');
var ficha = document.getElementById('ficha-usuario');
var campos = {
    'detalle-id': 'id',
    'detalle-uid': 'uid_pp2',
    'detalle-id-rol': 'id_rol',
    'detalle-nombre-rol': 'nombre_rol',
    'detalle-domicilio': 'id_domicilio',
    'detalle-nombre': 'nombre',
    'detalle-apellido': 'apellido',
    'detalle-nacimiento': 'fecha_nacimiento',
    'detalle-alta': 'fecha_alta',
    'detalle-estado': 'estado'
};

function textoApi(valor) {
    if (valor === undefined || valor === null || valor === '') {
        return '';
    }

    return String(valor);
}

function mostrarFicha(usuario) {
    var id;
    var nodo;

    for (id in campos) {
        if (!Object.prototype.hasOwnProperty.call(campos, id)) {
            continue;
        }

        nodo = document.getElementById(id);

        if (!nodo) {
            continue;
        }

        nodo.textContent = textoApi(usuario[campos[id]]);
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
                    if (mensaje && respuesta.message) {
                        mensaje.textContent = respuesta.message;
                    }
                    return;
                }

                mostrarFicha(respuesta.data);
            })
            .catch(function (error) {
                if (mensaje && error.data && error.data.message) {
                    mensaje.textContent = error.data.message;
                }
            });
    }
}
