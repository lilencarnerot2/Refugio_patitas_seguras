document.addEventListener('DOMContentLoaded', function () {
    var contenedor = document.getElementById('contenedor-mascotas');

    if (!contenedor) {
        return;
    }

    var mascotas = [];
    var filtros = document.querySelector('.catalog-filters');

    apiRequest('mascotas')
        .then(function (data) {
            mascotas = data.data || [];
            mostrarMascotas(mascotas);
        })
        .catch(function () {
            contenedor.innerHTML = '<div class="catalog-empty"><h4>No se pudieron cargar las mascotas</h4><p>Intentá de nuevo más tarde.</p></div>';
            actualizarContador(0);
        });

    apiRequest('especies')
        .then(function (data) {
            var especies = data.data || [];

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

    if (filtros) {
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
    }

    function mostrarMascotas(lista) {
        if (!lista.length) {
            contenedor.innerHTML = '<div class="catalog-empty"><h4>Todavía no hay mascotas cargadas</h4><p>No se encontraron mascotas disponibles.</p></div>';
            actualizarContador(0);
            return;
        }

        contenedor.innerHTML = '';

        lista.forEach(function (mascota) {
            var tamano = mascota.tamano || mascota['tamaño'] || '';
            var foto = mascota.foto_url
                ? '<img src="' + mascota.foto_url + '" alt="Foto de ' + mascota.nombre + '">'
                : '<div class="pet-photo-empty"></div>';

            contenedor.innerHTML +=
                '<article class="pet-card">' +
                    foto +
                    '<div class="pet-card-body">' +
                        '<h4>' + mascota.nombre + '</h4>' +
                        '<p class="pet-card-meta">' +
                            mascota.especie + ' · ' + mascota.raza + ' · ' + mascota.sexo + ' · ' + tamano +
                        '</p>' +
                        '<div class="pet-card-actions">' +
                            '<a class="btn-adopt" href="../user/solicitar_adopcion.php?id=' + mascota.id + '">' +
                                '<i class="fa-solid fa-heart"></i> Adoptar' +
                            '</a>' +
                            '<a class="btn-transito" href="../user/solicitar_transito.php?id=' + mascota.id + '">' +
                                '<i class="fa-solid fa-house-chimney"></i> Hogar de tránsito' +
                            '</a>' +
                            '<a class="btn-detail" href="mascota-detalle.php?id=' + mascota.id + '">' +
                                '<i class="fa-solid fa-circle-info"></i> Ver detalle' +
                            '</a>' +
                        '</div>' +
                    '</div>' +
                '</article>';
        });

        actualizarContador(lista.length);
    }

    function actualizarContador(cantidad) {
        var contador = document.getElementById('catalogo-count');

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
