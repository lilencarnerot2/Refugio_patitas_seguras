var carga = document.getElementById('detalle-carga');
var vacio = document.getElementById('detalle-vacio');
var ficha = document.getElementById('ficha-mascota');

if (ficha) {
    var id = new URLSearchParams(window.location.search).get('id');

    if (!id) {
        mostrarVacio();
    } else {
        apiRequest('mascotas', {
            parametros: { id: id }
        })
            .then(function (respuesta) {
                if (!respuesta.success || !respuesta.data) {
                    mostrarVacio(respuesta);
                    return;
                }

                mostrarFicha(respuesta.data);
            })
            .catch(function (error) {
                mostrarVacio(error);
            });
    }
}

function mostrarFicha(mascota) {
    var foto = document.getElementById('detalle-foto');
    var fotoVacia = document.getElementById('detalle-foto-vacia');
    var nacimiento = document.getElementById('detalle-nacimiento');
    var historialCaja = document.getElementById('detalle-historial-caja');
    var historial = document.getElementById('detalle-historial');
    var subtitulo = document.getElementById('detalle-subtitulo');
    var fecha = fechaCorta(mascota.fecha_nacimiento_estimada);
    var partes = [mascota.especie, mascota.raza].filter(Boolean);

    carga.hidden = true;
    vacio.hidden = true;
    ficha.hidden = false;

    document.getElementById('detalle-nombre').textContent = mascota.nombre || '';
    document.getElementById('detalle-sexo').textContent = mascota.sexo || '';
    document.getElementById('detalle-tamano').textContent = mascota.tamaño || '';
    document.getElementById('detalle-especie').textContent = mascota.especie || '';
    document.getElementById('detalle-raza').textContent = mascota.raza || '';

    if (subtitulo) {
        subtitulo.textContent = partes.join(' · ');
    }

    // si hay fecha, pisa el texto que ya está en el html
    if (fecha) {
        nacimiento.textContent = fecha;
    }

    historial.textContent = mascota.historial || '';
    historialCaja.hidden = !historial.textContent;
    ponerDestino('detalle-adoptar', mascota.id);
    ponerDestino('detalle-transito', mascota.id);

    if (mascota.foto_url) {
        foto.src = mascota.foto_url;
        foto.alt = mascota.nombre || '';
        foto.hidden = false;
        fotoVacia.hidden = true;
    } else {
        foto.hidden = true;
        fotoVacia.hidden = false;
    }

    if (mascota.nombre) {
        document.title = mascota.nombre;
    }
}

// le agrega el id al href
function ponerDestino(idEnlace, id) {
    var enlace = document.getElementById(idEnlace);

    if (!enlace) {
        return;
    }

    enlace.setAttribute('href', (enlace.getAttribute('href') || '') + '?id=' + encodeURIComponent(id));
}

function mostrarVacio(origen) {
    var parrafo = vacio.querySelector('p');

    carga.hidden = true;
    vacio.hidden = false;
    ficha.hidden = true;

    if (parrafo && origen) {
        avisoDeApi(parrafo, origen, false);
    }
}
