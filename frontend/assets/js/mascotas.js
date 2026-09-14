var mascotas = [];
var filtros = document.getElementById('filtros-especies');
var contenedor = document.getElementById('contenedor-mascotas');

apiRequest('mascotas')
    .then(function (respuesta) {
        mascotas = Array.isArray(respuesta.data) ? respuesta.data : [];
        crearFiltrosEspecies();
        mostrarMascotas(mascotas);
    })
    .catch(function () {
        mostrarAviso('No se pudo cargar el catálogo', 'Intentá nuevamente más tarde.');
    });

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

        filtros.querySelectorAll('.filtro-btn').forEach(function (filtro) {
            filtro.classList.remove('is-activo');
        });
        boton.classList.add('is-activo');
        mostrarMascotas(lista);
    });
}

function crearFiltrosEspecies() {
    var especies = [];

    mascotas.forEach(function (mascota) {
        if (mascota.especie && !especies.includes(mascota.especie)) {
            especies.push(mascota.especie);
        }
    });

    especies.forEach(function (especie) {
        var boton = document.createElement('span');
        boton.className = 'filtro-btn';
        boton.setAttribute('data-especie', especie);
        boton.innerHTML = '<i class="fa-solid fa-paw"></i> ' + especie;
        filtros.appendChild(boton);
    });
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

    tarjeta.querySelector('.btn-adopt').href = '/user/solicitar_adopcion.php?id=' + mascota.id;
    tarjeta.querySelector('.btn-transito').href = '/user/solicitar_transito.php?id=' + mascota.id;
    tarjeta.querySelector('.btn-detail').href = '/public/mascota-detalle.php?id=' + mascota.id;

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