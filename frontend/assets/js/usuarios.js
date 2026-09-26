var mensaje = document.getElementById('mensaje-formulario');
var cuerpo = document.getElementById('cuerpo-usuarios');
var campos = [
    'id',
    'nombre',
    'apellido',
    'fecha_nacimiento',
    'fecha_alta',
    'estado'
];

function camposListado() {
    if (!document.getElementById('columna-uid')) {
        return campos;
    }

    return [
        'id',
        'uid_pp2',
        'id_rol',
        'id_domicilio',
        'nombre',
        'apellido',
        'fecha_nacimiento',
        'fecha_alta',
        'estado'
    ];
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

    if (campo === 'estado') {
        celda.setAttribute('data-estado', texto.toLowerCase());
        return celda;
    }

    celda.textContent = texto;
    return celda;
}

function celdaEditar(id) {
    var celda = document.createElement('td');
    var texto = textoApi(id);

    if (!texto) {
        return celda;
    }

    var enlace = document.createElement('a');
    enlace.href = 'usuario_editar.php?id=' + encodeURIComponent(texto);
    enlace.textContent = 'Editar';
    celda.appendChild(enlace);
    return celda;
}

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

function mensajeDeApi(origen) {
    if (origen && origen.message) {
        return origen.message;
    }

    if (origen && origen.data && origen.data.message) {
        return origen.data.message;
    }

    return '';
}

function idRolBase(roles) {
    if (!Array.isArray(roles) || !roles[0] || roles[0].id === undefined || roles[0].id === null) {
        return '';
    }

    return String(roles[0].id);
}

function cargarListado() {
    return apiRequest('roles')
        .then(function (rolesRespuesta) {
            var roles = rolesRespuesta.data || [];
            var base = idRolBase(roles);

            return apiRequest('usuarios').then(function (respuesta) {
                var lista = respuesta.data || [];

                if (!Array.isArray(lista)) {
                    lista = [];
                }

                if (!esSuperadmin() && base) {
                    lista = lista.filter(function (usuario) {
                        return usuario && String(usuario.id_rol) === base;
                    });
                }

                mostrarUsuarios(lista);
            });
        })
        .catch(function (error) {
            if (mensaje) {
                mensaje.textContent = mensajeDeApi(error);
            }
        });
}

function darAlta(id) {
    var texto = textoApi(id);

    if (!texto) {
        return;
    }

    if (mensaje) {
        mensaje.textContent = '';
    }

    apiRequest('usuarios', {
        metodo: 'PUT',
        parametros: { id: texto },
        cuerpo: {
            estado: true
        }
    })
        .then(function (respuesta) {
            if (mensaje) {
                mensaje.textContent = mensajeDeApi(respuesta);
            }

            cargarListado();
        })
        .catch(function (error) {
            if (mensaje) {
                mensaje.textContent = mensajeDeApi(error);
            }
        });
}

function celdaAlta(id) {
    var celda = document.createElement('td');
    var boton = document.createElement('button');
    boton.type = 'button';
    boton.textContent = 'Alta';
    boton.addEventListener('click', function () {
        darAlta(id);
    });
    celda.appendChild(boton);
    return celda;
}

function darBaja(id) {
    var texto = textoApi(id);

    if (!texto) {
        return;
    }

    if (mensaje) {
        mensaje.textContent = '';
    }

    apiRequest('usuarios', {
        metodo: 'PUT',
        parametros: { id: texto },
        cuerpo: {
            estado: false
        }
    })
        .then(function (respuesta) {
            if (mensaje) {
                mensaje.textContent = mensajeDeApi(respuesta);
            }

            cargarListado();
        })
        .catch(function (error) {
            if (mensaje) {
                mensaje.textContent = mensajeDeApi(error);
            }
        });
}

function celdaBaja(id) {
    var celda = document.createElement('td');
    var boton = document.createElement('button');
    boton.type = 'button';
    boton.textContent = 'Baja';
    boton.addEventListener('click', function () {
        darBaja(id);
    });
    celda.appendChild(boton);
    return celda;
}

function mostrarUsuarios(lista) {
    var i;
    var j;
    var fila;

    vaciar(cuerpo);

    for (i = 0; i < lista.length; i++) {
        fila = document.createElement('tr');

        var listaCampos = camposListado();

        for (j = 0; j < listaCampos.length; j++) {
            fila.appendChild(celdaCampo(lista[i], listaCampos[j]));
        }

        if (document.getElementById('columna-editar')) {
            fila.appendChild(celdaEditar(lista[i].id));
        }

        if (document.getElementById('columna-alta')) {
            fila.appendChild(celdaAlta(lista[i].id));
        }

        if (document.getElementById('columna-baja')) {
            fila.appendChild(celdaBaja(lista[i].id));
        }

        cuerpo.appendChild(fila);
    }
}

if (cuerpo) {
    cargarListado();
}
