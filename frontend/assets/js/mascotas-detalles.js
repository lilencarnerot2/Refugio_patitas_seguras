var carga = document.getElementById('detalle-carga');
var vacio = document.getElementById('detalle-vacio');
var ficha = document.getElementById('ficha-mascota');

if (ficha) {
    var id = new URLSearchParams(window.location.search).get('id');

    if (!id) {
        mostrarVacio();
    } else {
        fetch(API_URL + '/?recurso=mascotas&id=' + encodeURIComponent(id))
            .then(function (response) {
                return response.json();
            })
            .then(function (respuesta) {
                if (!respuesta.success || !respuesta.data) {
                    mostrarVacio();
                    return;
                }

                mostrarFicha(respuesta.data);
            })
            .catch(function () {
                mostrarVacio();
            });
    }
}

function mostrarFicha(mascota) {
    var foto = document.getElementById('detalle-foto');
    var fotoVacia = document.getElementById('detalle-foto-vacia');

    carga.hidden = true;
    vacio.hidden = true;
    ficha.hidden = false;

    document.getElementById('detalle-nombre').textContent = mascota.nombre || '';
    document.getElementById('detalle-especie').textContent = mascota.especie || '';
    document.getElementById('detalle-raza').textContent = mascota.raza || '';
    document.getElementById('detalle-sexo').textContent = mascota.sexo || '';
    document.getElementById('detalle-tamano').textContent = mascota.tamaño || '';
    document.getElementById('detalle-nacimiento').textContent = mascota.fecha_nacimiento_estimada || 'No informado';
    document.getElementById('detalle-historial').textContent = mascota.historial || '';
    document.getElementById('detalle-adoptar').href = '../user/solicitar_adopcion.php?id=' + mascota.id;
    document.getElementById('detalle-transito').href = '../user/solicitar_transito.php?id=' + mascota.id;

    if (mascota.foto_url) {
        foto.src = mascota.foto_url;
        foto.alt = 'Foto de ' + mascota.nombre;
        foto.hidden = false;
        fotoVacia.hidden = true;
    } else {
        foto.hidden = true;
        fotoVacia.hidden = false;
    }

    document.title = (mascota.nombre || 'Mascota') + ' - Patitas Seguras';
}

function mostrarVacio() {
    carga.hidden = true;
    vacio.hidden = false;
    ficha.hidden = true;
}
