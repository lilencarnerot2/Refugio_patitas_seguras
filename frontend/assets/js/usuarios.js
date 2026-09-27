var mensaje = document.getElementById('mensaje-formulario');
var panel = document.getElementById('panel-usuarios');
var cuerpo = document.getElementById('cuerpo-usuarios');
var cabecera = document.getElementById('cabecera-usuarios');
var opcionesRol = document.getElementById('opciones-rol');
var opcionesEstado = document.getElementById('opciones-estado');

function claveId() {
    var th = cabecera.querySelector('[data-campo]');

    return th ? th.getAttribute('data-campo') : '';
}

function rolPos(usuario) {
    var opciones = opcionesRol.querySelectorAll('input[value]');
    var i;

    for (i = 0; i < opciones.length; i++) {
        if (textoApi(opciones[i].value) === textoApi(usuario.id_rol)) {
            return String(i + 1);
        }
    }

    return '';
}

function asignarFila(fila, usuario) {
    asignarCeldasDeApi(fila, cabecera, usuario, claveId());
    fila.setAttribute('data-id-rol', textoApi(usuario.id_rol));
    fila.setAttribute('data-estado', textoApi(usuario.estado).toLowerCase());
    fila.setAttribute('data-rol-pos', rolPos(usuario));
}

function parEstado(valor) {
    var texto = textoApi(valor).toLowerCase();

    if (texto === 'true') {
        return 'false';
    }

    if (texto === 'false') {
        return 'true';
    }

    if (texto === 't') {
        return 'f';
    }

    if (texto === 'f') {
        return 't';
    }

    if (texto === '1') {
        return '0';
    }

    if (texto === '0') {
        return '1';
    }

    return '';
}

function limpiarOpciones(contenedor) {
    var entradas = contenedor.querySelectorAll('input');
    var i;

    for (i = 0; i < entradas.length; i++) {
        entradas[i].removeAttribute('value');
        entradas[i].removeAttribute('data-estado');

        if (entradas[i].parentNode) {
            entradas[i].parentNode.removeAttribute('data-nombre');
        }
    }
}

function asignarOpcion(entrada, valor, texto, usarEstado) {
    var etiqueta = entrada.parentNode;

    entrada.setAttribute('value', valor);

    if (usarEstado) {
        entrada.setAttribute('data-estado', textoApi(valor).toLowerCase());
        return;
    }

    if (etiqueta) {
        etiqueta.setAttribute('data-nombre', texto);
    }
}

function listaUnica(valores, valorDe, textoDe, completarPar) {
    var i;
    var valor;
    var texto;
    var par;
    var vistos = {};
    var lista = [];

    for (i = 0; i < valores.length; i++) {
        valor = valorDe(valores[i]);
        texto = textoDe(valores[i]);

        if (valor === '' || vistos[valor]) {
            continue;
        }

        vistos[valor] = true;
        lista.push({ valor: valor, texto: texto });

        if (!completarPar) {
            continue;
        }

        par = parEstado(valor);

        if (par && !vistos[par]) {
            vistos[par] = true;
            lista.push({ valor: par, texto: par });
        }
    }

    return lista;
}

function pintarOpciones(contenedor, lista, resumen, usarEstado) {
    var entradas = contenedor.querySelectorAll('input');
    var i;
    var n = 0;

    limpiarOpciones(contenedor);

    if (resumen) {
        resumen.removeAttribute('data-nombre-1');
        resumen.removeAttribute('data-nombre-2');
    }

    for (i = 0; i < lista.length && n < entradas.length; i++) {
        asignarOpcion(entradas[n], lista[i].valor, lista[i].texto, usarEstado);

        if (resumen) {
            resumen.setAttribute('data-nombre-' + (n + 1), lista[i].texto);
        }

        n += 1;
    }
}

function cargarOpciones(contenedor, valores, valorDe, textoDe, completarPar, resumen, usarEstado) {
    pintarOpciones(contenedor, listaUnica(valores, valorDe, textoDe, completarPar), resumen, usarEstado);
}

function campoDe(item, campo) {
    return textoApi(item && item[campo]);
}

function cargarListado() {
    if (mensaje) {
        mensaje.textContent = '';
    }

    return apiRequest('roles')
        .then(function (rolesRespuesta) {
            var listaRoles = rolesListadoDeApi(listaDeApi(rolesRespuesta));
            var idsVisibles = mapaValoresDeApi(listaRoles);

            cargarOpciones(opcionesRol, listaRoles, function (rol) {
                return campoDe(rol, 'id');
            }, function (rol) {
                return campoDe(rol, 'nombre_rol');
            }, false, opcionesRol.parentNode && opcionesRol.parentNode.querySelector('.listado-filtro-resumen'), false);

            return apiRequest('usuarios').then(function (respuestaUsuarios) {
                return {
                    usuarios: listaDeApi(respuestaUsuarios).filter(function (usuario) {
                        return usuario && idsVisibles[campoDe(usuario, 'id_rol')];
                    })
                };
            });
        })
        .then(function (resultado) {
            cargarOpciones(opcionesEstado, resultado.usuarios, function (usuario) {
                return campoDe(usuario, 'estado');
            }, function (usuario) {
                return campoDe(usuario, 'estado');
            }, true, null, true);
            pintarFilasDeApi(cuerpo, resultado.usuarios, asignarFila);
        })
        .catch(function (error) {
            pintarFilasDeApi(cuerpo, [], asignarFila);

            if (mensaje) {
                mensaje.textContent = mensajeDeApi(error);
            }
        });
}

if (panel && cuerpo && cabecera && opcionesRol && opcionesEstado) {
    cargarListado();
}
