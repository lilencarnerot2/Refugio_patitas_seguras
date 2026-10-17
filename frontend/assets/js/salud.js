const mensaje = document.getElementById('mensaje-formulario');
const cuerpo = document.getElementById('cuerpo-mascotas-salud');

function mostrarMensaje(origen, ok) {
    avisoDeApi(mensaje, origen, ok);
}

function listaDe(resultado) {
    var lista = resultado.data || [];

    if (!Array.isArray(lista)) {
        lista = [];
    }

    return lista;
}

function vaciar(nodo) {
    while (nodo.firstChild) {
        nodo.removeChild(nodo.firstChild);
    }
}

function porcentaje(parte, total) {
    if (!total) {
        return 0;
    }

    return Math.round((parte * 100) / total);
}

function clonarModelo(id, profunda) {
    // clona el modelo oculto
    var modelo = document.getElementById(id);

    if (!modelo) {
        return null;
    }

    var copia = modelo.cloneNode(profunda === true);

    copia.removeAttribute('id');
    copia.hidden = false;

    return copia;
}

function agregarNombres(lista, campo, destino) {
    // clona el li del modelo
    var i;
    var item;

    vaciar(destino);

    for (i = 0; i < lista.length; i++) {
        item = clonarModelo('item-modelo-salud', false);

        if (!item) {
            return;
        }

        item.textContent = lista[i][campo] || '';
        destino.appendChild(item);
    }
}

function listaConDatos(resultado) {
    return listaDe(resultado).length > 0;
}

function fichaTieneDatos(ficha) {
    if (ficha.observaciones) {
        return Promise.resolve(true);
    }

    return Promise.all([
        apiRequest('controles_medicos', {
            parametros: { id_ficha_medica: ficha.id }
        }).then(listaConDatos),
        apiRequest('vacunas', {
            parametros: { id_ficha_medica: ficha.id }
        }).then(listaConDatos),
        apiRequest('desparasitaciones', {
            parametros: { id_ficha_medica: ficha.id }
        }).then(listaConDatos),
        apiRequest('castraciones', {
            parametros: { id_ficha_medica: ficha.id }
        })
            .then(function () {
                return true;
            })
            .catch(function (error) {
                if (error.status === 404) {
                    return false;
                }

                throw error;
            })
    ]).then(function (partes) {
        var i;

        for (i = 0; i < partes.length; i++) {
            if (partes[i]) {
                return true;
            }
        }

        return false;
    });
}

function textosSalud() {
    // Etiquetas de estado en los data-* del HTML; el mensaje real viene del backend.
    var datos = cuerpo.dataset || {};

    return {
        ok: datos.textoOk || '',
        vacio: datos.textoVacio || ''
    };
}

function consultarFicha(idMascota) {
    return apiRequest('fichas_medicas', {
        parametros: { id_mascota: idMascota }
    })
        .then(function (resultado) {
            var ficha = resultado.data || {};

            return fichaTieneDatos(ficha)
                .then(function (tieneDatos) {
                    if (tieneDatos) {
                        return {
                            ok: true,
                            message: ''
                        };
                    }

                    return {
                        ok: false,
                        message: ''
                    };
                })
                .catch(function () {
                    // Si fallan los detalle (controles/vacunas/etc),
                    // igual existe ficha: no se bloquea la fila.
                    return {
                        ok: true,
                        message: ''
                    };
                });
        })
        .catch(function (error) {
            // 404 = la mascota no tiene ficha (mensaje viene del backend).
            // Otro error = se marca la fila sin bloquear el resto.
            return {
                ok: false,
                message: mensajeDeApi(error)
            };
        });
}

function mostrarResumenFichas(estados) {
    var conFicha = 0;
    var total = estados.length;
    var pct;
    var arco;
    var escala;
    var i;

    for (i = 0; i < estados.length; i++) {
        if (estados[i].ok) {
            conFicha += 1;
        }
    }

    // Solo números del backend; el "%" y los paréntesis los pone el HTML/CSS.
    pct = porcentaje(conFicha, total);
    arco = document.getElementById('donut-fichas');

    document.getElementById('texto-fichas-pct').textContent = String(pct);

    if (arco) {
        // Solo números del backend en atributos del SVG;
        // colores y forma los define el CSS.
        escala = parseInt(arco.getAttribute('pathLength') || '100', 10);
        arco.setAttribute('stroke-dasharray', pct + ' ' + (escala - pct));
    }
    document.getElementById('texto-con-ficha-pct').textContent = String(pct);
    document.getElementById('texto-con-ficha-num').textContent = String(conFicha);
    document.getElementById('texto-sin-ficha-pct').textContent = String(porcentaje(total - conFicha, total));
    document.getElementById('texto-sin-ficha-num').textContent = String(total - conFicha);
}

function mostrarMascotas(mascotas, estados) {
        // clona la fila modelo
    // Aquí solo se clona y se asignan datos del backend (texto, foto, href).
    var i;
    var mascota;
    var enlaces = [];
    var aviso;
    var fila;
    var imagen;
    var enlace;

    cuerpo.querySelectorAll('tr:not(#fila-modelo-salud)').forEach(function (existente) {
        existente.remove();
    });

    if (!mascotas.length) {
        aviso = document.getElementById('mensaje-vacio-salud');
        if (aviso) {
            aviso.hidden = false;
        }
        return [];
    }

    aviso = document.getElementById('mensaje-vacio-salud');
    if (aviso) {
        aviso.hidden = true;
    }

    for (i = 0; i < mascotas.length; i++) {
        mascota = mascotas[i];

        fila = clonarModelo('fila-modelo-salud', true);

        if (!fila) {
            return [];
        }

        imagen = fila.querySelector('img');
        imagen.alt = mascota.nombre || '';

        if (mascota.foto_url) {
            imagen.src = mascota.foto_url;
        } else {
            imagen.removeAttribute('src');
        }

        fila.querySelector('[data-celda="nombre"]').textContent = mascota.nombre || '';
        fila.querySelector('[data-celda="especie"]').textContent = mascota.especie || '';
        fila.querySelector('[data-celda="nacimiento"]').textContent = fechaCorta(mascota.fecha_nacimiento_estimada);

        enlace = fila.querySelector('a');

            // le agrega el id al href
        enlace.setAttribute('href', (enlace.getAttribute('href') || '') + '?id=' + encodeURIComponent(mascota.id));
        // data-estado lo pinta el CSS; el texto inicial lo define el HTML.
        enlace.dataset.estado = 'pendiente';
        enlaces.push(enlace);

        cuerpo.appendChild(fila);
    }

    // Aplica los estados ya resueltos (si se pasaron).
    if (estados && estados.length) {
        actualizarEstados(estados, enlaces);
    }

    return enlaces;
}

function actualizarEstados(estados, enlaces) {
    // Solo asigna datos/estado; textos en el HTML y estilo en CSS/Bootstrap.
    var i;
    var enlace;
    var estado;
    var textos = textosSalud();

    for (i = 0; i < enlaces.length; i++) {
        enlace = enlaces[i];
        estado = estados[i];

        if (!estado || estado.pendiente) {
            enlace.dataset.estado = 'pendiente';
            continue;
        }

        if (estado.ok) {
            enlace.textContent = textos.ok;
            enlace.dataset.estado = 'ok';
        } else {
            enlace.textContent = textos.vacio;
            enlace.dataset.estado = 'error';
        }
    }
}

function resumenSoloResueltos(estados) {
    return estados.filter(function (e) { return e && !e.pendiente; });
}

// Filtros con la misma idea que el resto de listas: texto + selects del HTML.
var panelSalud = document.getElementById('panel-salud');
var buscarSalud = document.getElementById('buscar-salud');
var filtroEspecieSalud = document.getElementById('filtro-especie-salud');
var filtroFichaSalud = document.getElementById('filtro-ficha-salud');
var mascotasTodas = [];
var estadosTodas = [];

function normalizarSalud(valor) {
    var texto = (valor === undefined || valor === null) ? '' : String(valor);

    return texto.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
}

function coincideSalud(mascota, indice) {
    var i;
    var estado;
    var token;
    var palabras = normalizarSalud(buscarSalud ? buscarSalud.value : '').split(/\s+/);
    var fila = normalizarSalud((mascota || {}).nombre);

    for (i = 0; i < palabras.length; i++) {
        if (palabras[i] !== '' && fila.indexOf(palabras[i]) === -1) {
            return false;
        }
    }

    if (filtroEspecieSalud && filtroEspecieSalud.value !== '' &&
        normalizarSalud(mascota.especie) !== normalizarSalud(filtroEspecieSalud.value)) {
        return false;
    }

    if (filtroFichaSalud && filtroFichaSalud.value !== '') {
        estado = estadosTodas[indice] || {};

        if (estado.pendiente) {
            return false;
        }

            // compara contra las opciones del select
        token = estado.ok ? 'ok' : 'error';

        if (token !== filtroFichaSalud.value) {
            return false;
        }
    }

    return true;
}

function aplicarFiltrosSalud() {
    var mascotas = [];
    var estados = [];
    var i;

    for (i = 0; i < mascotasTodas.length; i++) {
        if (coincideSalud(mascotasTodas[i], i)) {
            mascotas.push(mascotasTodas[i]);
            estados.push(estadosTodas[i] || { pendiente: true });
        }
    }

    mostrarMascotas(mascotas, estados);
}

function poblarEspeciesSalud() {
    var vistas = {};
    var i;
    var especie;
    var opcion;

    if (!filtroEspecieSalud) {
        return;
    }

    Array.prototype.forEach.call(filtroEspecieSalud.querySelectorAll('option'), function (actual) {
        if (actual.value !== '') {
            actual.remove();
        }
    });

    for (i = 0; i < mascotasTodas.length; i++) {
        especie = (mascotasTodas[i] || {}).especie || '';

        if (especie === '' || vistas[especie]) {
            continue;
        }

        vistas[especie] = true;
        opcion = clonarModelo('opcion-modelo-salud', false);

        if (!opcion) {
            return;
        }

        opcion.value = especie;
        opcion.textContent = especie;
        filtroEspecieSalud.appendChild(opcion);
    }
}

if (buscarSalud) {
    buscarSalud.addEventListener('input', aplicarFiltrosSalud);
}

[filtroEspecieSalud, filtroFichaSalud].forEach(function (select) {
    if (select) {
        select.addEventListener('change', aplicarFiltrosSalud);
    }
});

if (panelSalud) {
    panelSalud.querySelectorAll('[data-limpiar]').forEach(function (boton) {
        boton.addEventListener('click', function () {
            var campo = document.getElementById(boton.getAttribute('data-limpiar'));

            if (campo) {
                campo.value = '';
            }

            aplicarFiltrosSalud();
        });
    });
}

apiRequest('mascotas')
    .then(function (resultado) {
        var i;

        mascotasTodas = listaDe(resultado);
        estadosTodas = [];

        for (i = 0; i < mascotasTodas.length; i++) {
            estadosTodas.push({ pendiente: true });
        }

        // pinta la tabla apenas llega la lista, las fichas se completan después
        poblarEspeciesSalud();
        aplicarFiltrosSalud();

        if (!mascotasTodas.length) {
            mostrarResumenFichas([]);
            return;
        }

        mostrarResumenFichas([]);

        mascotasTodas.forEach(function (mascota, indice) {
            consultarFicha(mascota.id).then(function (estado) {
                estadosTodas[indice] = estado;
                aplicarFiltrosSalud();
                mostrarResumenFichas(resumenSoloResueltos(estadosTodas));
            });
        });
    })
    .catch(function (error) {
        mostrarMensaje(error, false);
    });

apiRequest('tipos_vacunas')
    .then(function (resultado) {
        var tipos = listaDe(resultado);

        document.getElementById('texto-tipos-vacunas').textContent = String(tipos.length);
        agregarNombres(tipos, 'nombre_vacuna', document.getElementById('lista-tipos-vacunas'));
    })
    .catch(function (error) {
        mostrarMensaje(error, false);
    });

apiRequest('tipos_productos')
    .then(function (resultado) {
        var tipos = listaDe(resultado);

        document.getElementById('texto-tipos-productos').textContent = String(tipos.length);
        agregarNombres(tipos, 'nombre_tipo', document.getElementById('lista-tipos-productos'));
    })
    .catch(function (error) {
        mostrarMensaje(error, false);
    });
