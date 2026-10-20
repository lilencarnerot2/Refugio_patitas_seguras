var carga = document.getElementById('detalle-carga');
var vacio = document.getElementById('detalle-vacio');
var ficha = document.getElementById('ficha-mascota');
var fotoPrincipalGaleria = '';
var fotosGaleria = [];
var nombreGaleria = '';

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
    var nacimientoCaja = document.getElementById('detalle-nacimiento-caja');
    var historialCaja = document.getElementById('detalle-historial-caja');
    var historial = document.getElementById('detalle-historial');
    var fecha = fechaCorta(mascota.fecha_nacimiento_estimada);

    carga.hidden = true;
    vacio.hidden = true;
    ficha.hidden = false;

    document.getElementById('detalle-nombre').textContent = mascota.nombre || '';
    document.getElementById('detalle-sexo').textContent = mascota.sexo || '';
    document.getElementById('detalle-tamano').textContent = mascota.tamaño || '';
    document.getElementById('detalle-especie').textContent = mascota.especie || '';
    document.getElementById('detalle-raza').textContent = mascota.raza || '';

    if (nacimiento) {
        nacimiento.textContent = fecha;
    }
    if (nacimientoCaja) {
        nacimientoCaja.hidden = !fecha;
    }

    historial.textContent = mascota.historial || '';
    historialCaja.hidden = !historial.textContent;
    ponerDestino('detalle-adoptar', mascota.id);
    ponerDestino('detalle-transito', mascota.id);

    fotoPrincipalGaleria = mascota.foto_url || '';
    fotosGaleria = [];
    nombreGaleria = mascota.nombre || '';
    mostrarFotoGaleria(fotoPrincipalGaleria);
    cargarGaleriaAdicional(mascota.id);

    if (mascota.nombre) {
        document.title = mascota.nombre;
    }
}

function cargarGaleriaAdicional(idMascota) {
    var miniaturas = document.getElementById('detalle-fotos-adicionales');
    var mensaje = document.getElementById('detalle-fotos-mensaje');

    if (!idMascota || !miniaturas) {
        return;
    }

    apiRequest('fotos_mascota', {
        parametros: { id_mascota: idMascota }
    }).then(function (respuesta) {
        var datos = respuesta && respuesta.data;

        if (Array.isArray(datos)) {
            fotosGaleria = datos;
        } else if (datos && Array.isArray(datos.fotos)) {
            fotosGaleria = datos.fotos;
        } else if (datos && Array.isArray(datos.fotos_adicionales)) {
            fotosGaleria = datos.fotos_adicionales;
        } else if (datos === null || datos === undefined) {
            fotosGaleria = [];
        } else {
            throw new Error('La respuesta de fotos adicionales no tiene el formato esperado.');
        }

        fotosGaleria.forEach(function (foto) {
            if (!foto || !urlFotoAdicional(foto)) {
                throw new Error('Una foto adicional no contiene su ruta.');
            }
        });

        if (mensaje) {
            mensaje.hidden = true;
            mensaje.textContent = '';
        }

        dibujarGaleria();
    }).catch(function (error) {
        var galeria = document.getElementById('detalle-galeria-adicional');

        if (mensaje) {
            var detalle = mensajeDeApi(error);
            mensaje.textContent = 'No se pudieron cargar las fotos adicionales.' + (detalle ? ' ' + detalle : '');
            mensaje.hidden = false;
        }

        if (galeria) {
            galeria.hidden = false;
        }
    });
}

function urlFotoAdicional(foto) {
    return String(foto && (foto.foto_url || foto.url) || '').trim();
}

function mostrarFotoGaleria(url) {
    var foto = document.getElementById('detalle-foto');
    var fotoVacia = document.getElementById('detalle-foto-vacia');

    if (url) {
        foto.src = urlFotoMascota(url);
        foto.alt = nombreGaleria || 'Foto de la mascota';
        foto.hidden = false;
        fotoVacia.hidden = true;
    } else {
        foto.removeAttribute('src');
        foto.hidden = true;
        fotoVacia.hidden = false;
    }
}

function dibujarGaleria() {
    var miniaturas = document.getElementById('detalle-fotos-adicionales');
    var galeria = document.getElementById('detalle-galeria-adicional');
    var fotos = [];

    if (!miniaturas || !galeria) {
        return;
    }

    if (fotoPrincipalGaleria) {
        fotos.push({ url: fotoPrincipalGaleria, principal: true });
    }

    fotosGaleria.forEach(function (foto) {
        var url = urlFotoAdicional(foto);

        if (url) {
            fotos.push({ url: url, principal: false });
        }
    });

    if (!fotoPrincipalGaleria && fotos.length) {
        fotoPrincipalGaleria = fotos[0].url;
        mostrarFotoGaleria(fotoPrincipalGaleria);
    }

    miniaturas.textContent = '';
    galeria.hidden = fotos.length < 2;

    fotos.forEach(function (foto) {
        var boton = document.createElement('button');
        var imagen = document.createElement('img');
        var seleccionada = foto.url === fotoPrincipalGaleria;

        boton.type = 'button';
        boton.className = 'pet-detail-thumbnail';
        boton.setAttribute('aria-label', foto.principal ? 'Ver foto principal' : 'Ver foto adicional');
        boton.setAttribute('aria-pressed', seleccionada ? 'true' : 'false');
        imagen.src = urlFotoMascota(foto.url);
        imagen.alt = '';
        imagen.loading = 'lazy';
        boton.appendChild(imagen);
        boton.addEventListener('click', function () {
            miniaturas.querySelectorAll('.pet-detail-thumbnail').forEach(function (otra) {
                otra.setAttribute('aria-pressed', 'false');
            });
            boton.setAttribute('aria-pressed', 'true');
            mostrarFotoGaleria(foto.url);
        });
        miniaturas.appendChild(boton);
    });
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
