var mensaje = document.getElementById('mensaje-formulario');
var panel = document.getElementById('panel-usuarios');
var cuerpo = document.getElementById('cuerpo-usuarios');
var cabecera = document.getElementById('cabecera-usuarios');
var opcionesRol = document.getElementById('opciones-rol');
var opcionesEstado = document.getElementById('opciones-estado');

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

function hrefBase(enlace) {
    var href = enlace.getAttribute('href') || '';
    var corte = href.indexOf('?');

    if (corte === -1) {
        return href;
    }

    return href.slice(0, corte);
}

function limpiarCelda(celda) {
    var enlace = celda.querySelector('a');

    celda.removeAttribute('data-texto');
    celda.removeAttribute('data-valor');
    celda.removeAttribute('data-estado');

    if (!enlace) {
        return;
    }

    enlace.removeAttribute('data-texto');
    enlace.setAttribute('href', hrefBase(enlace));
}

function limpiarFila(fila) {
    var celdas = fila.children;
    var i;

    fila.removeAttribute('data-id');
    fila.removeAttribute('data-id-rol');
    fila.removeAttribute('data-estado');
    fila.removeAttribute('data-rol-pos');

    for (i = 0; i < celdas.length; i++) {
        limpiarCelda(celdas[i]);
    }
}

function claveId() {
    var th = cabecera.querySelector('[data-campo]');

    return th ? th.getAttribute('data-campo') : '';
}

function asignarFila(fila, usuario) {
    var ths = cabecera.children;
    var clave = claveId();
    var id = textoApi(usuario[clave]);
    var i;
    var th;
    var td;
    var campo;
    var href;
    var texto;
    var enlace;

    fila.setAttribute('data-id', id);
    fila.setAttribute('data-id-rol', textoApi(usuario.id_rol));
    fila.setAttribute('data-estado', textoApi(usuario.estado).toLowerCase());
    fila.setAttribute('data-rol-pos', rolPos(usuario));

    for (i = 0; i < ths.length; i++) {
        th = ths[i];
        td = fila.children[i];
        campo = th.getAttribute('data-campo');
        href = th.getAttribute('data-href');
        texto = campo ? textoApi(usuario[campo]) : id;
        enlace = td && td.querySelector('a');

        if (!td) {
            continue;
        }

        if (th.hasAttribute('data-buscar')) {
            td.setAttribute('data-valor', texto.toLowerCase());
        }

        if (th.id === 'columna-estado') {
            td.setAttribute('data-estado', texto.toLowerCase());
            continue;
        }

        if (enlace && href) {
            if (texto) {
                enlace.setAttribute('href', href + (href.indexOf('?') === -1 ? '?' : '&') + clave + '=' + encodeURIComponent(texto));
            }

            if (campo) {
                enlace.setAttribute('data-texto', texto);
            }

            continue;
        }

        if (campo) {
            td.setAttribute('data-texto', texto);
        }
    }
}

function pintarListado(lista) {
    var filas = cuerpo.querySelectorAll('tr');
    var i;

    for (i = 0; i < filas.length; i++) {
        limpiarFila(filas[i]);
    }

    for (i = 0; i < lista.length && i < filas.length; i++) {
        asignarFila(filas[i], lista[i]);
    }
}

function cambiarEstado(id, estado) {
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
            estado: estado
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

function radiosDe(contenedor) {
    return contenedor.querySelectorAll('input');
}

function limpiarRadios(radios) {
    var i;

    for (i = 0; i < radios.length; i++) {
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

function listaUnica(valores, campoValor, campoTexto, completarPar) {
    var i;
    var valor;
    var texto;
    var par;
    var vistos = {};
    var lista = [];

    for (i = 0; i < valores.length; i++) {
        valor = textoApi(valores[i] && valores[i][campoValor]);
        texto = textoApi(valores[i] && valores[i][campoTexto]);

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
        for (i = 0; i < radios.length; i++) {
            resumen.removeAttribute('data-nombre-' + (i + 1));
        }
    }

    for (i = 0; i < lista.length && n < radios.length; i++) {
        asignarRadio(radios[n], lista[i].valor, lista[i].texto, usarEstado);

        if (resumen) {
            resumen.setAttribute('data-nombre-' + (n + 1), lista[i].texto);
        }

        n += 1;
    }
}

function cargarOpcionesEstado(valores) {
    var th = document.getElementById('columna-estado');
    var campo = th ? th.getAttribute('data-campo') : '';

    pintarRadios(opcionesEstado, listaUnica(valores, campo, campo, true), null, true);
}

function cargarOpcionesRol(valores) {
    var resumen = opcionesRol.parentNode && opcionesRol.parentNode.querySelector('.listado-filtro-resumen');
    var thNombre = document.getElementById('columna-rol');
    var campoNombre = thNombre ? thNombre.getAttribute('data-campo') : '';

    pintarRadios(opcionesRol, listaUnica(valores, 'id', campoNombre), resumen, false);
}

function cargarListado() {
    if (mensaje) {
        mensaje.textContent = '';
    }

    return apiRequest('roles')
        .then(function (rolesRespuesta) {
            var roles = rolesListadoDeApi(rolesRespuesta.data || []);
            var idsVisibles = mapaValoresDeApi(roles);

            cargarOpcionesRol(roles);

            return apiRequest('usuarios').then(function (respuesta) {
                return {
                    respuesta: respuesta,
                    idsVisibles: idsVisibles
                };
            });
        })
        .then(function (resultado) {
            var lista = resultado.respuesta.data || [];
            var usuarios = [];
            var i;

            if (!Array.isArray(lista)) {
                lista = [];
            }

            for (i = 0; i < lista.length; i++) {
                if (!resultado.idsVisibles[textoApi(lista[i] && lista[i].id_rol)]) {
                    continue;
                }

                usuarios.push(lista[i]);
            }

            cargarOpcionesEstado(usuarios);
            pintarListado(usuarios);
        })
        .catch(function (error) {
            pintarListado([]);

            if (mensaje) {
                mensaje.textContent = mensajeDeApi(error);
            }
        });
}

if (panel && cuerpo && cabecera && opcionesRol && opcionesEstado) {
    cuerpo.addEventListener('click', function (evento) {
        var boton = evento.target.closest('[data-accion]');
        var fila;
        var estado;

        if (!boton || !cuerpo.contains(boton)) {
            return;
        }

        fila = boton.closest('tr');
        estado = boton.getAttribute('data-estado');

        if (!fila || estado === null || estado === '') {
            return;
        }

        cambiarEstado(fila.getAttribute('data-id'), JSON.parse(estado));
    });

    cargarListado();
}
