var formulario = document.getElementById('formulario-usuario');
var mensaje = document.getElementById('mensaje-formulario');
var selectUsuario = document.getElementById('id_usuario');
var idInicial = new URLSearchParams(window.location.search).get('id');
var campos = [
    'id',
    'uid_pp2',
    'id_rol',
    'nombre_rol',
    'id_domicilio',
    'nombre',
    'apellido',
    'fecha_nacimiento',
    'fecha_alta',
    'estado'
];

function textoApi(valor) {
    if (valor === undefined || valor === null || valor === '') {
        return '';
    }

    return String(valor);
}

function mensajeDeApi(origen) {
    if (origen && origen.message) {
        return origen.message;
    }

    if (origen && origen.data && origen.data.message) {
        return origen.data.message;
    }

    return '';
}

function ponerCampo(nombre, valor) {
    var nodo = formulario.elements[nombre];

    if (!nodo) {
        return;
    }

    nodo.value = textoApi(valor);
}

function mostrarUsuario(usuario) {
    var i;

    for (i = 0; i < campos.length; i++) {
        ponerCampo(campos[i], usuario[campos[i]]);
    }
}

function cargarUsuario(id) {
    if (!id) {
        return;
    }

    apiRequest('usuarios', {
        parametros: { id: id }
    })
        .then(function (respuesta) {
            mostrarUsuario(respuesta.data || {});
        })
        .catch(function (error) {
            if (mensaje) {
                mensaje.textContent = mensajeDeApi(error);
            }
        });
}

if (formulario && selectUsuario) {
    apiRequest('usuarios')
        .then(function (respuesta) {
            var lista = respuesta.data || [];
            var i;
            var usuario;
            var opcion;

            if (!Array.isArray(lista)) {
                lista = [];
            }

            for (i = 0; i < lista.length; i++) {
                usuario = lista[i];
                opcion = document.createElement('option');
                opcion.value = textoApi(usuario.id);
                opcion.textContent = textoApi(usuario.nombre);
                selectUsuario.appendChild(opcion);
            }

            if (idInicial) {
                selectUsuario.value = idInicial;

                if (selectUsuario.value) {
                    cargarUsuario(idInicial);
                }
            }
        })
        .catch(function (error) {
            if (mensaje) {
                mensaje.textContent = mensajeDeApi(error);
            }
        });

    selectUsuario.addEventListener('change', function () {
        if (mensaje) {
            mensaje.textContent = '';
        }

        cargarUsuario(selectUsuario.value);
    });

    formulario.addEventListener('submit', function (evento) {
        evento.preventDefault();

        if (mensaje) {
            mensaje.textContent = '';
        }

        if (!selectUsuario.value) {
            return;
        }

        apiRequest('usuarios', {
            metodo: 'PUT',
            parametros: { id: selectUsuario.value },
            cuerpo: {
                nombre: formulario.elements.nombre.value,
                apellido: formulario.elements.apellido.value,
                fecha_nacimiento: formulario.elements.fecha_nacimiento.value,
                id_rol: formulario.elements.id_rol.value,
                id_domicilio: formulario.elements.id_domicilio.value,
                estado: formulario.elements.estado.value
            }
        })
            .then(function (respuesta) {
                if (mensaje) {
                    mensaje.textContent = mensajeDeApi(respuesta);
                }

                if (respuesta.data) {
                    mostrarUsuario(respuesta.data);
                }

                window.location.href = 'usuarios.php';
            })
            .catch(function (error) {
                if (mensaje) {
                    mensaje.textContent = mensajeDeApi(error);
                }
            });
    });
}
