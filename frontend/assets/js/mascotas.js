var mascotas = [];
var filtros = document.getElementById('filtros-especies');
var contenedor = document.getElementById('contenedor-mascotas');

function mascotasUnicasPorId(lista) {
    var vistos = {};
    var resultado = [];
    var i;
    var mascota;
    var id;

    for (i = 0; i < lista.length; i++) {
        mascota = lista[i];
        id = mascota && mascota.id;

        if (id === undefined || id === null || id === '') {
            resultado.push(mascota);
            continue;
        }

        id = String(id);

        if (vistos[id]) {
            continue;
        }

        vistos[id] = true;
        resultado.push(mascota);
    }

    return resultado;
}

if (!window.__patitasCatalogoMascotasCargando) {
    window.__patitasCatalogoMascotasCargando = true;

    apiRequest('mascotas')
        .then(function (respuesta) {
            var datos = respuesta.data;

            if (Array.isArray(datos)) {
                mascotas = mascotasUnicasPorId(datos);
            } else if (datos && typeof datos === 'object' && datos.id !== undefined) {
                mascotas = [datos];
            } else {
                mascotas = [];
            }

            crearFiltrosEspecies();
            mostrarMascotas(mascotas);
        })
        .catch(function (error) {
            mostrarAviso(error);
        });
}

if (filtros) {
    filtros.addEventListener('click', function (evento) {
        var boton = evento.target.closest('.filtro-btn');
        var especie;
        var lista;

        if (!boton) {
            return;
        }

        especie = boton.getAttribute('data-especie');
        lista = especie ? mascotas.filter(function (mascota) {
            return mascota.especie === especie;
        }) : mascotas;

        filtros.querySelectorAll('.filtro-btn:not(#filtro-modelo-especie)').forEach(function (filtro) {
            filtro.dataset.activa = 'false';
        });
        boton.dataset.activa = 'true';
        mostrarMascotas(lista);
    });
}

function crearFiltrosEspecies() {
    var especies = [];
    var modelo = document.getElementById('filtro-modelo-especie');

    if (!filtros || !modelo) {
        return;
    }

    mascotas.forEach(function (mascota) {
        if (mascota.especie && !especies.includes(mascota.especie)) {
            especies.push(mascota.especie);
        }
    });

    especies.forEach(function (especie) {
        // clona el botón modelo y le pone la especie
        var boton = modelo.cloneNode(true);

        boton.removeAttribute('id');
        boton.hidden = false;
        boton.setAttribute('data-especie', especie);
        boton.dataset.activa = 'false';
        boton.querySelector('[data-texto-filtro]').textContent = especie;
        filtros.appendChild(boton);
    });
}

function mostrarMascotas(lista) {
    var i;
    var aviso = document.getElementById('aviso-catalogo');

    if (!contenedor) {
        return;
    }

    while (contenedor.firstChild) {
        contenedor.removeChild(contenedor.firstChild);
    }

    if (lista.length === 0) {
        mostrarAvisoVacio();
        return;
    }

    if (aviso) {
        aviso.hidden = true;
    }

    for (i = 0; i < lista.length; i++) {
        contenedor.appendChild(armarTarjeta(lista[i]));
    }
}

function armarTarjeta(mascota) {
    var modelo = document.getElementById('tarjeta-modelo');
    var tarjeta = modelo.cloneNode(true);
    var imagen = tarjeta.querySelector('img');
    var vacia = tarjeta.querySelector('.pet-photo-empty');
    var datos = tarjeta.querySelector('.pet-card-meta');
    // el separador está en el modelo del html
    var separador = modelo.getAttribute('data-separador') || '';

    tarjeta.removeAttribute('id');

    if (mascota.foto_url) {
        imagen.src = mascota.foto_url;
        imagen.alt = mascota.nombre || '';
        imagen.hidden = false;
        vacia.hidden = true;
    }

    tarjeta.querySelector('h4').textContent = mascota.nombre;
    datos.textContent = [
        mascota.especie,
        mascota.raza,
        mascota.sexo,
        mascota.tamano || mascota['tama\u00f1o']
    ].filter(Boolean).join(separador);

    ponerDestino(tarjeta, '.btn-adopt', mascota.id);
    ponerDestino(tarjeta, '.btn-transito', mascota.id);
    ponerDestino(tarjeta, '.btn-detail', mascota.id);

    return tarjeta;
}

// le agrega el id al href
function ponerDestino(tarjeta, selector, id) {
    var enlace = tarjeta.querySelector(selector);

    if (!enlace) {
        return;
    }

    enlace.setAttribute('href', (enlace.getAttribute('href') || '') + '?id=' + encodeURIComponent(id));
}

function mostrarAvisoVacio() {
    // Textos en el HTML (#aviso-catalogo); aquí solo visibilidad.
    var aviso = document.getElementById('aviso-catalogo');

    if (!aviso || !contenedor) {
        return;
    }

    while (contenedor.firstChild) {
        contenedor.removeChild(contenedor.firstChild);
    }

    aviso.hidden = false;
}

function mostrarAviso(error) {
    var aviso = document.getElementById('aviso-catalogo');
    var titulo;
    var parrafo;

    if (!aviso || !contenedor) {
        return;
    }

    while (contenedor.firstChild) {
        contenedor.removeChild(contenedor.firstChild);
    }

    aviso.hidden = false;
    titulo = aviso.querySelector('h4');
    parrafo = aviso.querySelector('p');

    if (titulo) {
        titulo.textContent = '';
    }

    if (parrafo) {
        parrafo.textContent = mensajeDeApi(error);
    }
}