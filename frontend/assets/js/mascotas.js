document.addEventListener('DOMContentLoaded', function () {
    if (document.getElementById('contenedor-mascotas')) cargarCatalogo();
    if (document.getElementById('ficha-mascota')) cargarDetalle();
    if (document.getElementById('form-mascota')) iniciarFormularioMascota();
});

function cargarCatalogo() {
    const contenedor = document.getElementById('contenedor-mascotas');
    const contador = document.getElementById('catalogo-count');

    apiRequest('mascotas')
        .then(function (data) {
            const mascotas = Array.isArray(data.data) ? data.data : [];

            if (!mascotas.length) {
                contenedor.innerHTML = mensajeVacio(
                    'Todavía no hay mascotas cargadas',
                    'No se encontraron mascotas disponibles.'
                );
                if (contador) contador.textContent = '0 mascotas disponibles';
                return;
            }

            contenedor.innerHTML = mascotas.map(function (m) {
                const nombre = escapar(texto(m.nombre) || 'Mascota');
                const id = encodeURIComponent(m.id);
                return `
                    <article class="pet-card">
                        ${fotoMascota(m)}
                        <div class="pet-card-body">
                            <h4>${nombre}</h4>
                            <div class="pet-card-actions">
                                <a class="btn-adopt" href="../user/solicitar_adopcion.php?id=${id}">
                                    <i class="fa-solid fa-heart"></i> Adoptar
                                </a>
                                <a class="btn-transito" href="../user/solicitar_transito.php?id=${id}">
                                    <i class="fa-solid fa-house-chimney"></i> Hogar de tránsito
                                </a>
                                <a class="btn-detail" href="mascota-detalle.php?id=${id}">
                                    <i class="fa-solid fa-circle-info"></i> Ver detalle
                                </a>
                            </div>
                        </div>
                    </article>`;
            }).join('');

            if (contador) {
                const n = mascotas.length;
                contador.textContent = n + (n === 1 ? ' mascota disponible' : ' mascotas disponibles');
            }
        })
        .catch(function (error) {
            console.error('Error al cargar mascotas:', error);
            contenedor.innerHTML = mensajeVacio(
                'No se pudieron cargar las mascotas',
                error.message || 'Intentá de nuevo más tarde.'
            );
        });
}

function cargarDetalle() {
    const id = new URLSearchParams(location.search).get('id');

    if (!id || !/^\d+$/.test(id)) {
        mostrarMascotaNoEncontrada('El ID de la mascota no es válido.');
        return;
    }

    apiRequest('mascotas', { parametros: { id: id } })
        .then(function (data) {
            const mascota = data.data;
            if (!mascota || !mascota.id) {
                throw new Error(data.message || 'Mascota no encontrada.');
            }
            mostrarFicha(mascota);
        })
        .catch(function (error) {
            console.error('Error al cargar el detalle:', error);
            mostrarMascotaNoEncontrada(error.message);
        });
}

function mostrarFicha(mascota) {
    const carga = document.getElementById('detalle-carga');
    const vacio = document.getElementById('detalle-vacio');
    const ficha = document.getElementById('ficha-mascota');
    const nombre = texto(mascota.nombre) || 'Mascota';

    if (carga) carga.hidden = true;
    if (vacio) vacio.hidden = true;
    ficha.hidden = false;
    document.title = nombre + ' - Patitas Seguras';

    document.getElementById('detalle-nombre').textContent = nombre;
    document.getElementById('detalle-especie').textContent = texto(mascota.especie);
    document.getElementById('detalle-raza').textContent = texto(mascota.raza);
    document.getElementById('detalle-sexo').textContent = texto(mascota.sexo);
    document.getElementById('detalle-tamano').textContent = texto(mascota.tamano || mascota['tamaño']);
    document.getElementById('detalle-nacimiento').textContent = texto(mascota.fecha_nacimiento_estimada);
    document.getElementById('detalle-historial').textContent = texto(mascota.historial);

    if (mascota.foto_url) {
        const foto = document.getElementById('detalle-foto');
        foto.src = mascota.foto_url;
        foto.alt = 'Foto de ' + nombre;
        foto.hidden = false;
        const fotoVacia = document.getElementById('detalle-foto-vacia');
        if (fotoVacia) fotoVacia.hidden = true;
    }

    document.getElementById('detalle-adoptar').href = '../user/solicitar_adopcion.php?id=' + mascota.id;
    document.getElementById('detalle-transito').href = '../user/solicitar_transito.php?id=' + mascota.id;
}

function mostrarMascotaNoEncontrada(mensaje) {
    const carga = document.getElementById('detalle-carga');
    const vacio = document.getElementById('detalle-vacio');
    const ficha = document.getElementById('ficha-mascota');
    const textoVacio = document.getElementById('detalle-vacio-texto');

    document.title = 'Mascota no encontrada - Patitas Seguras';
    if (carga) carga.hidden = true;
    if (ficha) ficha.hidden = true;
    if (vacio) vacio.hidden = false;
    if (textoVacio && mensaje) textoVacio.textContent = mensaje;
}

function fotoMascota(mascota) {
    if (!mascota.foto_url) {
        return '<div class="pet-photo-empty" aria-hidden="true"></div>';
    }
    return '<img src="' + escapar(mascota.foto_url) + '" alt="Foto de ' + escapar(texto(mascota.nombre) || 'mascota') + '">';
}

function texto(valor) {
    return valor == null ? '' : String(valor);
}

function escapar(valor) {
    return String(valor)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

function mensajeVacio(titulo, textoMsg, conVolver) {
    let html = '<div class="catalog-empty"><i class="fa-solid fa-paw"></i><h4>' + titulo + '</h4><p>' + textoMsg + '</p>';
    if (conVolver) html += '<a class="empty-back-link" href="mascotas.php">Volver a mascotas</a>';
    return html + '</div>';
}

function iniciarFormularioMascota() {
    const selEspecie = document.getElementById('id_especie');
    const selRaza = document.getElementById('id_raza');

    apiRequest('especies')
        .then(function (data) {
            const especies = Array.isArray(data.data) ? data.data : [];
            especies.forEach(function (especie) {
                const opcion = document.createElement('option');
                opcion.value = especie.id;
                opcion.textContent = especie.nombre_especie;
                selEspecie.appendChild(opcion);
            });
        })
        .catch(function (error) {
            console.error('Error al cargar especies:', error);
        });

    selEspecie.addEventListener('change', function () {
        selRaza.innerHTML = '<option value="">Seleccioná una raza</option>';
        if (!selEspecie.value) return;

        apiRequest('razas', { parametros: { id_especie: selEspecie.value } })
            .then(function (data) {
                const razas = Array.isArray(data.data) ? data.data : [];
                razas.forEach(function (raza) {
                    const opcion = document.createElement('option');
                    opcion.value = raza.id;
                    opcion.textContent = raza.nombre_raza;
                    selRaza.appendChild(opcion);
                });
            })
            .catch(function (error) {
                console.error('Error al cargar razas:', error);
            });
    });
}
