const mensaje = document.getElementById('mensaje-formulario');
const cuerpo = document.getElementById('cuerpo-mascotas-salud');

function mostrarMensaje(texto, ok) {
    mensaje.textContent = texto || '';
    mensaje.classList.remove('is-ok', 'is-error');

    if (!texto) {
        return;
    }

    mensaje.classList.add(ok ? 'is-ok' : 'is-error');
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

function agregarNombres(lista, campo, destino) {
    var i;
    var item;

    vaciar(destino);

    for (i = 0; i < lista.length; i++) {
        item = document.createElement('li');
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
                        message: 'Sin ficha médica'
                    };
                })
                .catch(function (error) {
                    mostrarMensaje(error.message, false);

                    return {
                        ok: true,
                        message: ''
                    };
                });
        })
        .catch(function (error) {
            if (error.status !== 404) {
                mostrarMensaje(error.message, false);
            }

            return {
                ok: false,
                message: error.status === 404
                    ? 'Sin ficha médica'
                    : (error.message || '')
            };
        });
}

function mostrarResumenFichas(estados) {
    var conFicha = 0;
    var total = estados.length;
    var pct;
    var i;

    for (i = 0; i < estados.length; i++) {
        if (estados[i].ok) {
            conFicha += 1;
        }
    }

    pct = porcentaje(conFicha, total);

    document.getElementById('texto-fichas-pct').textContent = pct + '%';
    document.getElementById('donut-fichas').setAttribute('stroke-dasharray', pct + ' 100');
    document.getElementById('texto-con-ficha').textContent = pct + '% (' + conFicha + ')';
    document.getElementById('texto-sin-ficha').textContent =
        porcentaje(total - conFicha, total) + '% (' + (total - conFicha) + ')';
}

function mostrarMascotas(mascotas, estados) {
    var i;
    var mascota;
    var fila;
    var celdaFoto;
    var foto;
    var imagen;
    var celdaNombre;
    var celdaEspecie;
    var celdaNacimiento;
    var celdaFicha;
    var enlace;

    vaciar(cuerpo);

    for (i = 0; i < mascotas.length; i++) {
        mascota = mascotas[i];

        fila = document.createElement('tr');

        celdaFoto = document.createElement('td');
        foto = document.createElement('span');
        foto.className = 'dash-foto';
        imagen = document.createElement('img');
        imagen.alt = mascota.nombre || '';

        if (mascota.foto_url) {
            imagen.src = mascota.foto_url;
        }

        foto.appendChild(imagen);
        celdaFoto.appendChild(foto);

        celdaNombre = document.createElement('td');
        celdaNombre.textContent = mascota.nombre || '';

        celdaEspecie = document.createElement('td');
        celdaEspecie.textContent = mascota.especie || '';

        celdaNacimiento = document.createElement('td');
        celdaNacimiento.textContent = mascota.fecha_nacimiento_estimada || '';

        celdaFicha = document.createElement('td');
        enlace = document.createElement('a');
        enlace.className = 'empty-back-link';
        enlace.href = 'ficha_medica.php?id=' + mascota.id;

        if (estados[i] && estados[i].ok) {
            enlace.textContent = 'Ficha médica';
        } else {
            enlace.textContent = 'Sin ficha médica';
        }

        enlace.classList.add(estados[i] && estados[i].ok ? 'is-ok' : 'is-error');

        celdaFicha.appendChild(enlace);

        fila.appendChild(celdaFoto);
        fila.appendChild(celdaNombre);
        fila.appendChild(celdaEspecie);
        fila.appendChild(celdaNacimiento);
        fila.appendChild(celdaFicha);
        cuerpo.appendChild(fila);
    }
}

apiRequest('mascotas')
    .then(function (resultado) {
        var mascotas = listaDe(resultado);
        var consultas = [];
        var i;

        for (i = 0; i < mascotas.length; i++) {
            consultas.push(consultarFicha(mascotas[i].id));
        }

        return Promise.all(consultas).then(function (estados) {
            mostrarResumenFichas(estados);
            mostrarMascotas(mascotas, estados);
        });
    })
    .catch(function (error) {
        mostrarMensaje(error.message, false);
    });

apiRequest('tipos_vacunas')
    .then(function (resultado) {
        var tipos = listaDe(resultado);

        document.getElementById('texto-tipos-vacunas').textContent = String(tipos.length);
        agregarNombres(tipos, 'nombre_vacuna', document.getElementById('lista-tipos-vacunas'));
    })
    .catch(function (error) {
        mostrarMensaje(error.message, false);
    });

apiRequest('tipos_productos')
    .then(function (resultado) {
        var tipos = listaDe(resultado);

        document.getElementById('texto-tipos-productos').textContent = String(tipos.length);
        agregarNombres(tipos, 'nombre_tipo', document.getElementById('lista-tipos-productos'));
    })
    .catch(function (error) {
        mostrarMensaje(error.message, false);
    });
