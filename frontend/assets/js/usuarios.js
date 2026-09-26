var mensaje = document.getElementById('mensaje-formulario');
var panel = document.getElementById('panel-usuarios');
var cuerpo = document.getElementById('cuerpo-usuarios');
var cabecera = document.getElementById('cabecera-usuarios');
var opcionesRol = document.getElementById('opciones-rol');
var opcionesEstado = document.getElementById('opciones-estado');

function rolesVisiblesDeApi(lista) {
    return rolesOrdenadosDeApi(lista).slice(0, 2);
}

function camposVisibles() {
    var celdas = cabecera.children;
    var lista = [];
    var i;
    var campo;

    for (i = 0; i < celdas.length; i++) {
        campo = celdas[i].getAttribute('data-campo');

        if (campo) {
            lista.push(campo);
        }
    }

    return lista;
}

function rolPos(usuario) {
    var radios = opcionesRol.querySelectorAll('input[value]');
    var i;

    for (i = 0; i < radios.length; i++) {
        if (textoApi(radios[i].value) === textoApi(usuario.id_rol)) {
            return String(i + 1);
        }
    }

    return '';
}

function celdaCampo(usuario, clave) {
    var celda = document.createElement('td');
    var texto = textoApi(usuario[clave]);
    var enlace;

    celda.setAttribute('data-campo', clave);
    celda.setAttribute('data-valor', texto.toLowerCase());

    if (clave === 'estado') {
        celda.setAttribute('data-estado', texto.toLowerCase());
        return celda;
    }

    if (clave === 'id' && texto) {
        enlace = document.createElement('a');
        enlace.href = 'usuario_detalle.php?id=' + encodeURIComponent(texto);
        enlace.textContent = texto;
        celda.appendChild(enlace);
        return celda;
    }

    celda.textContent = texto;

    return celda;
}

function pintarListado(lista) {
    var claves = camposVisibles();
    var i;
    var j;
    var fila;

    vaciar(cuerpo);

    for (i = 0; i < lista.length; i++) {
        fila = document.createElement('tr');
        fila.setAttribute('data-id-rol', textoApi(lista[i].id_rol));
        fila.setAttribute('data-estado', textoApi(lista[i].estado).toLowerCase());
        fila.setAttribute('data-rol-pos', rolPos(lista[i]));

        for (j = 0; j < claves.length; j++) {
            fila.appendChild(celdaCampo(lista[i], claves[j]));
        }

        cuerpo.appendChild(fila);
    }
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

function radiosDe(contenedor) {
    return contenedor.querySelectorAll('input');
}

function limpiarRadios(radios) {
    var i;

    for (i = 0; i < radios.length; i++) {
        radios[i].checked = false;
        radios[i].removeAttribute('value');
        radios[i].removeAttribute('data-estado');

        if (radios[i].parentNode) {
            radios[i].parentNode.removeAttribute('data-nombre');
        }
    }
}

function asignarRadio(radio, valor, texto, usarEstado) {
    var etiqueta = radio.parentNode;

    radio.setAttribute('value', valor);

    if (usarEstado) {
        radio.setAttribute('data-estado', textoApi(valor).toLowerCase());
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

function pintarRadios(contenedor, lista, resumen, usarEstado) {
    var radios = radiosDe(contenedor);
    var i;
    var n = 0;

    limpiarRadios(radios);

    if (resumen) {
        resumen.removeAttribute('data-nombre-1');
        resumen.removeAttribute('data-nombre-2');
    }

    for (i = 0; i < lista.length && n < radios.length; i++) {
        asignarRadio(radios[n], lista[i].valor, lista[i].texto, usarEstado);

        if (resumen) {
            resumen.setAttribute('data-nombre-' + (n + 1), lista[i].texto);
        }

        n += 1;
    }
}

function cargarOpcionesEstado(valores, valorDe, textoDe) {
    pintarRadios(opcionesEstado, listaUnica(valores, valorDe, textoDe, true), null, true);
}

function cargarOpcionesRol(valores, valorDe, textoDe) {
    var resumen = opcionesRol.parentNode && opcionesRol.parentNode.querySelector('.listado-filtro-resumen');

    pintarRadios(opcionesRol, listaUnica(valores, valorDe, textoDe, false), resumen, false);
}

function cargarListado() {
    if (mensaje) {
        mensaje.textContent = '';
    }

    return apiRequest('roles')
        .then(function (rolesRespuesta) {
            var listaRoles = rolesVisiblesDeApi(rolesRespuesta.data || []);
            var idsVisibles = {};
            var i;

            for (i = 0; i < listaRoles.length; i++) {
                idsVisibles[textoApi(listaRoles[i].id)] = true;
            }

            cargarOpcionesRol(listaRoles, function (rol) {
                return textoApi(rol && rol.id);
            }, function (rol) {
                return textoApi(rol && rol.nombre_rol);
            });

            return apiRequest('usuarios').then(function (respuestaUsuarios) {
                return {
                    respuesta: respuestaUsuarios,
                    idsVisibles: idsVisibles
                };
            });
        })
        .then(function (resultado) {
            var respuesta = resultado.respuesta;
            var idsVisibles = resultado.idsVisibles;
            var lista;
            var usuarios = [];
            var i;

            lista = respuesta.data || [];

            if (!Array.isArray(lista)) {
                lista = [];
            }

            for (i = 0; i < lista.length; i++) {
                if (lista[i] && idsVisibles[textoApi(lista[i].id_rol)]) {
                    usuarios.push(lista[i]);
                }
            }

            cargarOpcionesEstado(usuarios, function (usuario) {
                return textoApi(usuario && usuario.estado);
            }, function (usuario) {
                return textoApi(usuario && usuario.estado);
            });
            pintarListado(usuarios);
        })
        .catch(function (error) {
            vaciar(cuerpo);

            if (mensaje) {
                mensaje.textContent = mensajeDeApi(error);
            }
        });
}

if (panel && cuerpo && cabecera && opcionesRol && opcionesEstado) {
    cargarListado();
}
