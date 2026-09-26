var mensaje = document.getElementById('mensaje-formulario');
var cuerpo = document.getElementById('cuerpo-usuarios');
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

function vaciar(nodo) {
    while (nodo.firstChild) {
        nodo.removeChild(nodo.firstChild);
    }
}

function celdaCampo(usuario, campo) {
    var celda = document.createElement('td');
    var texto = textoApi(usuario[campo]);

    if (campo === 'id' && texto) {
        var enlace = document.createElement('a');
        enlace.href = 'usuario_detalle.php?id=' + encodeURIComponent(texto);
        enlace.textContent = texto;
        celda.appendChild(enlace);
        return celda;
    }

    celda.textContent = texto;
    return celda;
}

function mostrarUsuarios(lista) {
    var i;
    var j;
    var fila;

    vaciar(cuerpo);

    for (i = 0; i < lista.length; i++) {
        fila = document.createElement('tr');

        for (j = 0; j < campos.length; j++) {
            fila.appendChild(celdaCampo(lista[i], campos[j]));
        }

        cuerpo.appendChild(fila);
    }
}

if (cuerpo) {
    apiRequest('usuarios')
        .then(function (respuesta) {
            var lista = respuesta.data || [];

            if (!Array.isArray(lista)) {
                lista = [];
            }

            mostrarUsuarios(lista);
        })
        .catch(function (error) {
            if (mensaje && error.data && error.data.message) {
                mensaje.textContent = error.data.message;
            }
        });
}
