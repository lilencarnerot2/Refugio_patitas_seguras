var mascotas = [];
var contenedor = document.getElementById('contenedor-mascotas');
var filtros = document.getElementById('filtros-especies');
var selectEspecie = document.getElementById('id_especie');

if (contenedor) {
    apiRequest('mascotas')
        .then(function (respuesta) {
            mascotas = respuesta.data || [];
            mostrarMascotas(mascotas);
        })
        .catch(function () {
            mostrarAviso('No se pudieron cargar las mascotas', 'Intentá de nuevo más tarde.');
        });
}

if (filtros || selectEspecie) {
    apiRequest('especies')
        .then(function (respuesta) {
            mostrarEspecies(respuesta.data || []);
        })
        .catch(function () {
            if (selectEspecie) {
                var aviso = document.createElement('option');
                aviso.textContent = 'No se pudieron cargar las especies';
                selectEspecie.appendChild(aviso);
            }
        });
}

if (filtros) {
    filtros.addEventListener('click', function (evento) {
        var boton = evento.target.closest('.filtro-btn');
        var lista = [];
        var especie;
        var i;

        if (!boton) {
            return;
        }

        especie = boton.getAttribute('data-especie');

        if (especie) {
            for (i = 0; i < mascotas.length; i++) {
                if (mascotas[i].especie === especie) {
                    lista.push(mascotas[i]);
                }
            }
        } else {
            lista = mascotas;
        }

        marcarFiltro(boton);
        mostrarMascotas(lista);
    });
}

function mostrarEspecies(lista) {
    var i;
    var especie;

    for (i = 0; i < lista.length; i++) {
        especie = lista[i];

        if (selectEspecie) {
            var opcion = document.createElement('option');
            opcion.value = especie.id;
            opcion.textContent = especie.nombre_especie;
            selectEspecie.appendChild(opcion);
        }

        if (filtros) {
            var boton = document.createElement('span');
            var icono = document.createElement('i');

            boton.className = 'filtro-btn';
            boton.setAttribute('data-especie', especie.nombre_especie);
            icono.className = 'fa-solid fa-paw';
            boton.appendChild(icono);
            boton.appendChild(document.createTextNode(' ' + especie.nombre_especie));
            filtros.appendChild(boton);
        }
    }
}

function marcarFiltro(activo) {
    var botones = filtros.querySelectorAll('.filtro-btn');
    var i;

    for (i = 0; i < botones.length; i++) {
        botones[i].classList.remove('is-activo');
    }

    activo.classList.add('is-activo');
}

function mostrarMascotas(lista) {
    var i;

    if (!contenedor) {
        return;
    }

    contenedor.innerHTML = '';

    if (lista.length === 0) {
        mostrarAviso('Todavía no hay mascotas cargadas', 'No se encontraron mascotas disponibles.');
        return;
    }

    for (i = 0; i < lista.length; i++) {
        contenedor.appendChild(armarTarjeta(lista[i]));
    }
}

function armarTarjeta(mascota) {
    var plantilla = document.getElementById('plantilla-tarjeta');
    var tarjeta = plantilla.content.firstElementChild.cloneNode(true);
    var imagen = tarjeta.querySelector('img');
    var vacia = tarjeta.querySelector('.pet-photo-empty');
    var datos = tarjeta.querySelector('.pet-card-meta');

    if (mascota.foto_url) {
        imagen.src = mascota.foto_url;
        imagen.alt = 'Foto de ' + mascota.nombre;
        imagen.hidden = false;
        vacia.hidden = true;
    }

    tarjeta.querySelector('h4').textContent = mascota.nombre;
    datos.textContent = [
        mascota.especie,
        mascota.raza,
        mascota.sexo,
        mascota.tamano || mascota['tama\u00f1o']
    ].filter(Boolean).join(' · ');

    tarjeta.querySelector('.btn-adopt').href = '../user/solicitar_adopcion.php?id=' + mascota.id;
    tarjeta.querySelector('.btn-transito').href = '../user/solicitar_transito.php?id=' + mascota.id;
    tarjeta.querySelector('.btn-detail').href = 'mascota-detalle.php?id=' + mascota.id;

    return tarjeta;
}

function mostrarAviso(titulo, texto) {
    var aviso = document.createElement('div');
    var h4 = document.createElement('h4');
    var p = document.createElement('p');

    aviso.className = 'catalog-empty';
    h4.textContent = titulo;
    p.textContent = texto;
    aviso.appendChild(h4);
    aviso.appendChild(p);
    contenedor.appendChild(aviso);
}
