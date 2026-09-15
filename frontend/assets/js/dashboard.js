const panel = document.getElementById('panel-dashboard');
const mensaje = document.getElementById('mensaje-panel');

function normalizarTexto(texto) {
    return String(texto || '')
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '');
}

function parece(texto, parte) {
    return normalizarTexto(texto).indexOf(normalizarTexto(parte)) !== -1;
}

function listaDe(resultado) {
    var lista = resultado.data || [];

    if (!Array.isArray(lista)) {
        lista = [];
    }

    return lista;
}

function vaciar(nodo) {
    while (nodo.firstChild) {
        nodo.removeChild(nodo.firstChild);
    }
}

function porcentaje(parte, total) {
    if (!total) {
        return 0;
    }

    return Math.round((parte * 100) / total);
}

function pintarArco(id, valor, desplazamiento) {
    var circulo = document.getElementById(id);

    if (!circulo) {
        return;
    }

    circulo.setAttribute('stroke-dasharray', valor + ' 100');

    if (desplazamiento !== undefined) {
        circulo.setAttribute('stroke-dashoffset', String(desplazamiento));
    }
}

function ultimoMovimiento(idMascota, movimientos) {
    var i;

    for (i = 0; i < movimientos.length; i++) {
        if (Number(movimientos[i].id_mascota) === Number(idMascota)) {
            return movimientos[i];
        }
    }

    return null;
}

function nombreMovimiento(mascota, movimientos) {
    var movimiento = ultimoMovimiento(mascota.id, movimientos);

    return movimiento ? movimiento.nombre_movimiento : '';
}

function esTipoAdopcionOTransito(nombre) {
    return parece(nombre, 'adopcion') || parece(nombre, 'transito');
}

function contarPorTipo(solicitudes, nombreTipo) {
    var i;
    var total = 0;

    for (i = 0; i < solicitudes.length; i++) {
        if (solicitudes[i].tipo_solicitud === nombreTipo) {
            total += 1;
        }
    }

    return total;
}

function mostrarTortaSolicitudes(tipos, solicitudes) {
    var leyenda = document.getElementById('leyenda-solicitudes');
    var partes = [];
    var total = 0;
    var i;
    var item;
    var cantidad;
    var pctPrimera;
    var pctSegunda;

    vaciar(leyenda);

    for (i = 0; i < tipos.length; i++) {
        if (!esTipoAdopcionOTransito(tipos[i].nombre_tipo)) {
            continue;
        }

        cantidad = contarPorTipo(solicitudes, tipos[i].nombre_tipo);
        partes.push({
            nombre: tipos[i].nombre_tipo,
            cantidad: cantidad
        });
        total += cantidad;
    }

    pctPrimera = porcentaje(partes[0] ? partes[0].cantidad : 0, total);
    pctSegunda = porcentaje(partes[1] ? partes[1].cantidad : 0, total);

    pintarArco('torta-solicitudes', pctPrimera);
    pintarArco('torta-solicitudes-resto', pctSegunda, -pctPrimera);

    for (i = 0; i < partes.length; i++) {
        item = document.createElement('li');
        item.textContent = partes[i].nombre + ' ' + porcentaje(partes[i].cantidad, total) + '% (' + partes[i].cantidad + ')';
        leyenda.appendChild(item);
    }
}

function mostrarMascotasTransito(mascotas, movimientos) {
    var enTransito = 0;
    var disponibles = 0;
    var i;
    var nombre;
    var total;
    var pct;

    for (i = 0; i < mascotas.length; i++) {
        nombre = nombreMovimiento(mascotas[i], movimientos);

        if (parece(nombre, 'transito')) {
            enTransito += 1;
        } else if (!parece(nombre, 'adopcion') && !parece(nombre, 'fallec')) {
            disponibles += 1;
        }
    }

    total = enTransito + disponibles;
    pct = porcentaje(enTransito, total);

    pintarArco('donut-transito', pct);
    document.getElementById('texto-transito-pct').textContent = pct + '%';
    document.getElementById('texto-en-transito').textContent = pct + '% (' + enTransito + ')';
    document.getElementById('texto-disponibles').textContent = porcentaje(disponibles, total) + '% (' + disponibles + ')';
}

function pintarBarras(idContenedor, valores) {
    var grupos = document.getElementById(idContenedor).children;
    var maximo = 1;
    var i;
    var barra;

    for (i = 0; i < valores.length; i++) {
        if (valores[i] > maximo) {
            maximo = valores[i];
        }
    }

    for (i = 0; i < grupos.length; i++) {
        barra = grupos[i].getElementsByTagName('progress')[0];
        barra.max = maximo;
        barra.value = valores[i] || 0;
    }
}

function mostrarAdopcionesMes(movimientos) {
    var ahora = new Date();
    var semanas = [0, 0, 0, 0];
    var total = 0;
    var i;
    var fecha;

    for (i = 0; i < movimientos.length; i++) {
        if (!parece(movimientos[i].nombre_movimiento, 'adopcion')) {
            continue;
        }

        fecha = new Date(movimientos[i].fecha_movimiento);

        if (fecha.getFullYear() !== ahora.getFullYear() || fecha.getMonth() !== ahora.getMonth()) {
            continue;
        }

        semanas[Math.min(3, Math.ceil(fecha.getDate() / 7) - 1)] += 1;
        total += 1;
    }

    document.getElementById('texto-adopciones-total').textContent = String(total);
    pintarBarras('adopciones-semanas', semanas);
}

function mostrarMascotasRecientes(mascotas, movimientos) {
    var filas = document.getElementById('mascotas-recientes-cuerpo').rows;
    var lista = mascotas.slice();
    var i;
    var fila;
    var celdas;
    var imagen;
    var mascota;

    lista.sort(function (a, b) {
        return Number(b.id) - Number(a.id);
    });

    for (i = 0; i < filas.length; i++) {
        fila = filas[i];
        celdas = fila.cells;
        imagen = celdas[0].getElementsByTagName('img')[0];
        mascota = lista[i];

        if (!mascota) {
            imagen.removeAttribute('src');
            imagen.alt = '';
            celdas[1].textContent = '';
            celdas[2].textContent = '';
            celdas[3].textContent = '';
            continue;
        }

        if (mascota.foto_url) {
            imagen.src = mascota.foto_url;
        } else {
            imagen.removeAttribute('src');
        }

        imagen.alt = mascota.nombre || '';
        celdas[1].textContent = mascota.nombre || '';
        celdas[2].textContent = mascota.fecha_nacimiento_estimada || '';
        celdas[3].textContent = nombreMovimiento(mascota, movimientos);
    }
}

function tipoDeAporte(item) {
    return item.tipo_aporte || item.tipo || item.tipo_donacion || '';
}

function contarAportesPorParte(donaciones, parte) {
    var i;
    var total = 0;

    for (i = 0; i < donaciones.length; i++) {
        if (parece(tipoDeAporte(donaciones[i]), parte)) {
            total += 1;
        }
    }

    return total;
}

function mostrarDonaciones(donaciones) {
    var insumos = contarAportesPorParte(donaciones, 'insumo');
    var medicamentos = contarAportesPorParte(donaciones, 'medicament');
    var monetarias = contarAportesPorParte(donaciones, 'monetar');

    document.getElementById('texto-donaciones-total').textContent = String(insumos + medicamentos + monetarias);
    pintarBarras('donaciones-tipos', [insumos, medicamentos, monetarias]);
}

function mostrarHogares(solicitudes, mascotas, movimientos) {
    var hogares = 0;
    var ocupados = 0;
    var i;
    var disponibles;
    var pctDisponibles;
    var pctOcupados;

    for (i = 0; i < solicitudes.length; i++) {
        if (parece(solicitudes[i].tipo_solicitud, 'transito') && parece(solicitudes[i].estado_solicitud, 'aprobad')) {
            hogares += 1;
        }
    }

    for (i = 0; i < mascotas.length; i++) {
        if (parece(nombreMovimiento(mascotas[i], movimientos), 'transito')) {
            ocupados += 1;
        }
    }

    if (!hogares) {
        hogares = ocupados;
    }

    disponibles = Math.max(hogares - ocupados, 0);
    pctDisponibles = porcentaje(disponibles, hogares);
    pctOcupados = porcentaje(ocupados, hogares);

    document.getElementById('texto-hogares-disponibles').textContent = String(pctDisponibles);
    document.getElementById('texto-hogares-ocupados').textContent = String(pctOcupados);
    document.getElementById('barra-hogares-disponibles').value = pctDisponibles;
    document.getElementById('barra-hogares-ocupados').value = pctOcupados;
}

function cargarPanel() {
    Promise.all([
        apiRequest('mascotas'),
        apiRequest('solicitudes'),
        apiRequest('movimientos_mascotas'),
        apiRequest('tipos_solicitudes')
    ])
        .then(function (respuestas) {
            var mascotas = listaDe(respuestas[0]);
            var solicitudes = listaDe(respuestas[1]);
            var movimientos = listaDe(respuestas[2]);
            var tipos = listaDe(respuestas[3]);

            mostrarTortaSolicitudes(tipos, solicitudes);
            mostrarMascotasTransito(mascotas, movimientos);
            mostrarAdopcionesMes(movimientos);
            mostrarMascotasRecientes(mascotas, movimientos);
            mostrarHogares(solicitudes, mascotas, movimientos);
        })
        .catch(function () {
            mensaje.textContent = 'No se pudo cargar el panel.';
        });

    apiRequest('donaciones')
        .then(function (resultado) {
            mostrarDonaciones(listaDe(resultado));
        })
        .catch(function () {
            mostrarDonaciones([]);
        });
}

if (panel) {
    cargarPanel();
}
