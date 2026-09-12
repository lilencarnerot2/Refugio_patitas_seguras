document.addEventListener('DOMContentLoaded', function () {
    var contenedor = document.getElementById('contenedor-mascotas');
    var api = (window.APP_CONFIG && window.APP_CONFIG.backendUrl) || '';

    if (!contenedor) {
        return;
    }

    fetch(api + '/public/index.php?recurso=mascotas')
        .then(function (respuesta) {
            return respuesta.json();
        })
        .then(function (json) {
            mostrarMascotas(json.data || []);
        })
        .catch(function () {
            mostrarAviso('No se pudieron cargar las mascotas', 'Intentá de nuevo más tarde.');
        });

    function mostrarMascotas(lista) {
        contenedor.innerHTML = '';

        if (lista.length === 0) {
            mostrarAviso('Todavía no hay mascotas cargadas', 'No se encontraron mascotas disponibles.');
            return;
        }

        var i;
        for (i = 0; i < lista.length; i++) {
            contenedor.appendChild(armarTarjeta(lista[i]));
        }
    }

    function armarTarjeta(mascota) {
        var tarjeta = document.createElement('article');
        tarjeta.className = 'pet-card';

        if (mascota.foto_url) {
            var imagen = document.createElement('img');
            imagen.src = mascota.foto_url;
            imagen.alt = 'Foto de ' + mascota.nombre;
            tarjeta.appendChild(imagen);
        } else {
            var vacia = document.createElement('div');
            vacia.className = 'pet-photo-empty';
            tarjeta.appendChild(vacia);
        }

        var cuerpo = document.createElement('div');
        cuerpo.className = 'pet-card-body';

        var nombre = document.createElement('h4');
        nombre.textContent = mascota.nombre;

        var datos = document.createElement('p');
        datos.className = 'pet-card-meta';
        datos.textContent = [
            mascota.especie,
            mascota.raza,
            mascota.sexo,
            mascota.tamano || mascota['tama\u00f1o']
        ].filter(Boolean).join(' · ');

        var acciones = document.createElement('div');
        acciones.className = 'pet-card-actions';

        acciones.appendChild(armarBoton('btn-adopt', 'Adoptar', '../user/solicitar_adopcion.php?id=' + mascota.id));
        acciones.appendChild(armarBoton('btn-transito', 'Hogar de tránsito', '../user/solicitar_transito.php?id=' + mascota.id));
        acciones.appendChild(armarBoton('btn-detail', 'Ver detalle', 'mascota-detalle.php?id=' + mascota.id));

        cuerpo.appendChild(nombre);
        cuerpo.appendChild(datos);
        cuerpo.appendChild(acciones);
        tarjeta.appendChild(cuerpo);

        return tarjeta;
    }

    function armarBoton(clase, texto, href) {
        var link = document.createElement('a');
        link.className = clase;
        link.href = href;
        link.textContent = texto;
        return link;
    }

    function mostrarAviso(titulo, texto) {
        var aviso = document.createElement('div');
        aviso.className = 'catalog-empty';

        var h4 = document.createElement('h4');
        h4.textContent = titulo;

        var p = document.createElement('p');
        p.textContent = texto;

        aviso.appendChild(h4);
        aviso.appendChild(p);
        contenedor.appendChild(aviso);
    }
});
