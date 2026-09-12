document.addEventListener('DOMContentLoaded', function () {
    var contenedor = document.getElementById('contenedor-mascotas');
    var contador = document.getElementById('catalogo-count');
    var filtros = document.querySelector('.catalog-filters');
    var mascotas = [];

    if (!contenedor) {
        return;
    }

    apiRequest('mascotas')
        .then(function (respuesta) {
            mascotas = respuesta.data || [];
            mostrarMascotas(mascotas);
        })
        .catch(function () {
            mostrarAviso('No se pudieron cargar las mascotas', 'Intentá de nuevo más tarde.');
            actualizarContador(0);
        });

    apiRequest('especies')
        .then(function (respuesta) {
            var especies = respuesta.data || [];

            especies.forEach(function (especie) {
                var id = 'filtro-especie-' + especie.id;

                var input = document.createElement('input');
                input.type = 'radio';
                input.name = 'filtro-especie';
                input.id = id;
                input.value = especie.nombre_especie;

                var label = document.createElement('label');
                label.setAttribute('for', id);
                label.textContent = especie.nombre_especie;

                filtros.appendChild(input);
                filtros.appendChild(label);
            });
        });

    filtros.addEventListener('change', function (evento) {
        if (evento.target.name !== 'filtro-especie') {
            return;
        }

        var filtro = evento.target.value;
        var lista = mascotas;

        if (filtro) {
            lista = mascotas.filter(function (mascota) {
                return mascota.especie === filtro;
            });
        }

        mostrarMascotas(lista);
    });

    function mostrarMascotas(lista) {
        contenedor.innerHTML = '';

        if (lista.length === 0) {
            mostrarAviso('Todavía no hay mascotas cargadas', 'No se encontraron mascotas disponibles.');
            actualizarContador(0);
            return;
        }

        lista.forEach(function (mascota) {
            contenedor.appendChild(armarTarjeta(mascota));
        });

        actualizarContador(lista.length);
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
            var sinFoto = document.createElement('div');
            sinFoto.className = 'pet-photo-empty';
            tarjeta.appendChild(sinFoto);
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
            mascota.tamaño
        ].filter(Boolean).join(' · ');

        var acciones = document.createElement('div');
        acciones.className = 'pet-card-actions';

        acciones.appendChild(armarBoton('btn-adopt', 'fa-solid fa-heart', 'Adoptar', '../user/solicitar_adopcion.php?id=' + mascota.id));
        acciones.appendChild(armarBoton('btn-transito', 'fa-solid fa-house-chimney', 'Hogar de tránsito', '../user/solicitar_transito.php?id=' + mascota.id));
        acciones.appendChild(armarBoton('btn-detail', 'fa-solid fa-circle-info', 'Ver detalle', 'mascota-detalle.php?id=' + mascota.id));

        cuerpo.appendChild(nombre);
        cuerpo.appendChild(datos);
        cuerpo.appendChild(acciones);
        tarjeta.appendChild(cuerpo);

        return tarjeta;
    }

    function armarBoton(clase, icono, texto, href) {
        var link = document.createElement('a');
        link.className = clase;
        link.href = href;

        var i = document.createElement('i');
        i.className = icono;

        link.appendChild(i);
        link.appendChild(document.createTextNode(' ' + texto));

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

    function actualizarContador(cantidad) {
        if (!contador) {
            return;
        }

        if (cantidad === 1) {
            contador.textContent = '1 mascota disponible';
        } else {
            contador.textContent = cantidad + ' mascotas disponibles';
        }
    }
});
