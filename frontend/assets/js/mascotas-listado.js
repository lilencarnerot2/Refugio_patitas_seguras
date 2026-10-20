document.addEventListener('DOMContentLoaded', function () {
    var panel = document.getElementById('panel-mascotas');

    if (!panel) {
        return;
    }

    var mensaje = document.getElementById('mensaje-formulario');
    var cuerpo = document.getElementById('cuerpo-mascotas');
    var buscar = document.getElementById('buscar-mascotas');
    var filtroEspecie = document.getElementById('filtro-especie');
    var paginador = panel.querySelector('[data-paginador]');
    var listaPaginas = paginador ? paginador.querySelector('.pagination') : null;
    var anterior = paginador ? paginador.querySelector('[data-pagina-anterior]') : null;
    var siguiente = paginador ? paginador.querySelector('[data-pagina-siguiente]') : null;
    var itemAnterior = anterior && anterior.closest ? anterior.closest('.page-item') : null;
    var itemSiguiente = siguiente && siguiente.closest ? siguiente.closest('.page-item') : null;
    var lista = [];
    var pagina = 1;
    var porPagina = parseInt(panel.getAttribute('data-por-pagina'), 10);

    if (isNaN(porPagina) || porPagina < 1) {
        porPagina = 0;
    }

    function texto(valor) {
        if (valor === undefined || valor === null) {
            return '';
        }

        return String(valor);
    }

    function normalizar(valor) {
        return texto(valor).toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    }

    function coincide(mascota) {
        var palabras = normalizar(buscar ? buscar.value : '').split(/\s+/).filter(function (palabra) {
            return palabra !== '';
        });
        var fila = normalizar(mascota.nombre);

        for (var i = 0; i < palabras.length; i++) {
            if (fila.indexOf(palabras[i]) === -1) {
                return false;
            }
        }

        if (filtroEspecie && filtroEspecie.value !== '' && normalizar(mascota.especie) !== normalizar(filtroEspecie.value)) {
            return false;
        }

        return true;
    }

    function clonarModelo(id) {
        // clona la fila modelo
        var modelo = document.getElementById(id);

        if (!modelo) {
            return null;
        }

        var copia = modelo.cloneNode(true);

        copia.removeAttribute('id');
        copia.hidden = false;

        return copia;
    }

    function dibujarPaginador(cantidad) {
        if (!paginador || !listaPaginas) {
            return;
        }

        listaPaginas.querySelectorAll('[data-pagina-numero]').forEach(function (existente) {
            existente.remove();
        });

        var total = porPagina > 0 ? Math.ceil(cantidad / porPagina) : 1;

        if (porPagina <= 0 || cantidad <= porPagina) {
            paginador.hidden = true;
            return;
        }

        if (pagina > total) {
            pagina = total;
        }

        for (var numero = 1; numero <= total; numero++) {
            var item = clonarModelo('pagina-modelo-mascotas');

            if (!item) {
                return;
            }

            item.setAttribute('data-pagina-numero', String(numero));
            item.dataset.activa = numero === pagina ? 'true' : 'false';

            var botonPagina = item.querySelector('.page-link');

            botonPagina.textContent = String(numero);

            if (numero === pagina) {
                botonPagina.setAttribute('aria-current', 'page');
            }

            (function (destino) {
                botonPagina.addEventListener('click', function () {
                    pagina = destino;
                    dibujar();
                });
            })(numero);

            listaPaginas.insertBefore(item, itemSiguiente);
        }

        if (itemAnterior) {
            itemAnterior.dataset.deshabilitado = pagina <= 1 ? 'true' : 'false';
        }

        if (itemSiguiente) {
            itemSiguiente.dataset.deshabilitado = pagina >= total ? 'true' : 'false';
        }

        paginador.hidden = false;
    }

    function dibujar() {
        cuerpo.querySelectorAll('tr:not(#fila-modelo-mascota)').forEach(function (fila) {
            fila.remove();
        });

        var filas = lista.filter(coincide).sort(function (a, b) {
            return Number(a.id) - Number(b.id);
        });

        dibujarPaginador(filas.length);

        var base = porPagina > 0 ? (pagina - 1) * porPagina : 0;
        var visibles = porPagina > 0 ? filas.slice(base, base + porPagina) : filas;

        visibles.forEach(function (mascota, indice) {
            // Estructura en la fila modelo del HTML; aquí solo datos del backend.
            var tr = clonarModelo('fila-modelo-mascota');

            if (!tr) {
                return;
            }

            tr.setAttribute('data-id', texto(mascota.id));

            var valores = [
                texto(base + indice + 1),
                texto(mascota.nombre),
                texto(mascota.especie),
                texto(mascota.raza),
                texto(mascota.sexo),
                texto(mascota.tamaño)
            ];

            var celdas = tr.querySelectorAll('[data-celda]');

            Array.prototype.forEach.call(celdas, function (td, i) {
                if (valores[i] !== undefined) {
                    td.textContent = valores[i];
                }
            });

            var enlace = tr.querySelector('.abm-ver');

            if (enlace) {
                // le agrega el id al href
                enlace.setAttribute('href', (enlace.getAttribute('href') || '') + '?id=' + encodeURIComponent(mascota.id));
            }

            var botonEditar = tr.querySelector('.abm-editar');

            if (botonEditar) {
                botonEditar.setAttribute('data-id', texto(mascota.id));
            }

            var boton = tr.querySelector('.abm-baja');

            if (boton) {
                boton.setAttribute('data-id', texto(mascota.id));
            }

            cuerpo.appendChild(tr);
        });
    }

    function cargarListado() {
        if (mensaje) {
            avisoDeApi(mensaje, '');
        }

        return apiRequest('mascotas').then(function (respuesta) {
            var datos = (respuesta && respuesta.data) || [];

            lista = Array.isArray(datos) ? datos.filter(function (mascota) {
                return !!mascota;
            }) : [datos];

            var vistas = {};
            var especieElegida = filtroEspecie.value;

            filtroEspecie.options.length = 1;

            lista.forEach(function (mascota) {
                var especie = texto(mascota.especie);

                if (especie === '' || vistas[especie]) {
                    return;
                }

                vistas[especie] = true;

                var opcion = clonarModelo('opcion-modelo-mascotas');

                if (!opcion) {
                    return;
                }

                opcion.value = especie;
                opcion.textContent = especie;
                filtroEspecie.appendChild(opcion);
            });

            filtroEspecie.value = especieElegida;

            dibujar();

            if (mensaje) {
                avisoDeApi(mensaje, respuesta);
            }
        });
    }

    if (buscar) {
        buscar.addEventListener('input', function () {
            pagina = 1;
            dibujar();
        });
    }

    if (filtroEspecie) {
        filtroEspecie.addEventListener('change', function () {
            pagina = 1;
            dibujar();
        });
    }

    if (anterior) {
        anterior.addEventListener('click', function () {
            if (pagina > 1) {
                pagina = pagina - 1;
                dibujar();
            }
        });
    }

    if (siguiente) {
        siguiente.addEventListener('click', function () {
            pagina = pagina + 1;
            dibujar();
        });
    }

    panel.querySelectorAll('[data-limpiar]').forEach(function (boton) {
        boton.addEventListener('click', function () {
            var campo = document.getElementById(boton.getAttribute('data-limpiar'));

            if (campo) {
                campo.value = '';
            }

            pagina = 1;
            dibujar();
        });
    });

    var modalMascota = document.getElementById('modal-mascota');
    var formulario = document.getElementById('form-mascota');
    var mensajeModal = document.getElementById('mensaje-modal-mascota');
    var tituloModal = document.getElementById('titulo-modal-mascota');
    var botonGuardar = document.getElementById('boton-guardar-mascota');
    var selectEspecie = document.getElementById('mascota-especie');
    var selectRaza = document.getElementById('mascota-raza');
    var botonAlta = panel.querySelector('[data-abrir-alta]');
    var inputFoto = document.getElementById('mascota-foto');
    var campoFotoActual = document.getElementById('mascota-foto-actual');
    var bloqueFoto = document.getElementById('mascota-foto-bloque');
    var vistaFoto = document.getElementById('mascota-foto-vista');
    var inputFotosAdicionales = document.getElementById('mascota-fotos-adicionales');
    var listaFotosAdicionales = document.getElementById('mascota-fotos-adicionales-lista');
    var mensajeFotosAdicionales = document.getElementById('mascota-fotos-adicionales-mensaje');
    var ayudaFotosAdicionales = document.getElementById('mascota-fotos-adicionales-ayuda');
    var pesoMaximoFoto = 5 * 1024 * 1024;
    var tiposFotoPermitidos = ['image/jpeg', 'image/png', 'image/webp'];
    var vistaTemporal = '';
    var especies = [];
    var especiesListas = Promise.resolve();
    var idEditando = '';
    var fotosAdicionales = [];
    var fotosAdicionalesPendientes = [];

    function mostrarVistaFoto(url) {
        if (vistaTemporal !== '' && url !== vistaTemporal) {
            URL.revokeObjectURL(vistaTemporal);
            vistaTemporal = '';
        }

        if (!bloqueFoto || !vistaFoto) {
            return;
        }

        if (!url) {
            vistaFoto.removeAttribute('src');
            bloqueFoto.hidden = true;
            return;
        }

        vistaFoto.src = urlFotoMascota(url);
        bloqueFoto.hidden = false;
    }

    function motivoFotoInvalida(archivo) {
        if (tiposFotoPermitidos.indexOf(archivo.type) === -1) {
            return 'La foto tiene que ser una imagen JPG, PNG o WebP.';
        }

        if (archivo.size > pesoMaximoFoto) {
            return 'La foto pesa ' + (archivo.size / 1024 / 1024).toFixed(1).replace('.', ',') + ' MB y el máximo es 5 MB.';
        }

        return '';
    }

    function elegirFoto() {
        var archivo = inputFoto.files && inputFoto.files[0] ? inputFoto.files[0] : null;
        var motivo;

        avisoDeApi(mensajeModal, '', true, formulario);

        if (!archivo) {
            mostrarVistaFoto(campoFotoActual.value);
            return;
        }

        motivo = motivoFotoInvalida(archivo);

        if (motivo !== '') {
            inputFoto.value = '';
            mostrarVistaFoto(campoFotoActual.value);
            marcarIntentoEnvio(formulario);
            avisoDeApi(mensajeModal, motivo, false);
            return;
        }

        mostrarVistaFoto('');
        vistaTemporal = URL.createObjectURL(archivo);
        mostrarVistaFoto(vistaTemporal);
    }

    function subirFoto(id, archivo, tipo) {
        var datos = new FormData();

        datos.append('foto', archivo);

        return apiRequest('fotos_mascota', {
            metodo: 'POST',
            parametros: { tipo: tipo, id_mascota: id },
            cuerpo: datos
        });
    }

    function leerFotosAdicionales(respuesta) {
        var datos = respuesta && respuesta.data;
        var i;

        if (!Array.isArray(datos)) {
            if (datos && Array.isArray(datos.fotos)) {
                datos = datos.fotos;
            } else if (datos && Array.isArray(datos.fotos_adicionales)) {
                datos = datos.fotos_adicionales;
            } else if (datos === null || datos === undefined) {
                datos = [];
            } else {
                throw new Error('La respuesta de fotos adicionales no tiene el formato esperado.');
            }
        }

        for (i = 0; i < datos.length; i++) {
            if (
                !datos[i]
                || datos[i].id_foto === undefined
                || datos[i].id_foto === null
                || !urlDeFotoAdicional(datos[i])
            ) {
                throw new Error('Una foto adicional no contiene su identificador o ruta.');
            }
        }

        return datos;
    }

    function urlDeFotoAdicional(foto) {
        return texto(foto && (foto.foto_url || foto.url));
    }

    function cargarFotosAdicionales(id) {
        if (!listaFotosAdicionales || !id) {
            fotosAdicionales = [];
            dibujarFotosAdicionales();
            return Promise.resolve();
        }

        return apiRequest('fotos_mascota', {
            parametros: { id_mascota: id }
        }).then(function (respuesta) {
            fotosAdicionales = leerFotosAdicionales(respuesta);
            dibujarFotosAdicionales();
        });
    }

    function dibujarFotosAdicionales() {
        if (!listaFotosAdicionales) {
            return;
        }

        listaFotosAdicionales.textContent = '';

        fotosAdicionales.forEach(function (foto) {
            var url = urlDeFotoAdicional(foto);
            var idFoto = foto && foto.id_foto;
            var item = document.createElement('div');
            var imagen = document.createElement('img');
            var borrar = document.createElement('button');
            var icono = document.createElement('i');

            item.className = 'mascota-foto-admin-item';
            imagen.src = urlFotoMascota(url);
            imagen.alt = 'Foto adicional';
            borrar.type = 'button';
            borrar.className = 'btn-eliminar-foto-adicional';
            borrar.setAttribute('data-borrar-foto', texto(idFoto));
            borrar.setAttribute('aria-label', 'Eliminar foto adicional');
            icono.className = 'fa-solid fa-trash';
            icono.setAttribute('aria-hidden', 'true');
            borrar.appendChild(icono);
            item.appendChild(imagen);
            item.appendChild(borrar);
            listaFotosAdicionales.appendChild(item);
        });

        if (inputFotosAdicionales) {
            inputFotosAdicionales.disabled = fotosAdicionalesPendientes.length > 0;
        }

        if (ayudaFotosAdicionales) {
            ayudaFotosAdicionales.textContent = 'Podés agregar ' + Math.max(0, 6 - fotosAdicionales.length)
                + ' foto' + (6 - fotosAdicionales.length === 1 ? '' : 's') + ' adicional' + (6 - fotosAdicionales.length === 1 ? '' : 'es') + '. JPG, PNG o WebP. Máximo 5 MB por foto.';
        }
    }

    function validarFotosAdicionales(archivos) {
        var limite = Math.max(0, 6 - fotosAdicionales.length);
        var i;
        var motivo;

        if (archivos.length > limite) {
            return 'Solo podés agregar ' + limite + ' foto' + (limite === 1 ? '' : 's') + ' adicional' + (limite === 1 ? '' : 'es') + ' más.';
        }

        for (i = 0; i < archivos.length; i++) {
            motivo = motivoFotoInvalida(archivos[i]);

            if (motivo) {
                return motivo;
            }
        }

        return '';
    }

    function subirFotosAdicionales(id) {
        if (fotosAdicionalesPendientes.length === 0 && inputFotosAdicionales && inputFotosAdicionales.files) {
            fotosAdicionalesPendientes = Array.prototype.slice.call(inputFotosAdicionales.files);
        }

        if (!fotosAdicionalesPendientes.length) {
            return Promise.resolve();
        }

        if (inputFotosAdicionales) {
            inputFotosAdicionales.value = '';
            inputFotosAdicionales.disabled = true;
        }

        function subirSiguiente() {
            var archivo = fotosAdicionalesPendientes[0];

            if (!archivo) {
                fotosAdicionalesPendientes = [];

                if (inputFotosAdicionales) {
                    inputFotosAdicionales.disabled = false;
                }

                return cargarFotosAdicionales(id);
            }

            return subirFoto(id, archivo, 'adicional').then(function () {
                fotosAdicionalesPendientes.shift();
                return subirSiguiente();
            });
        }

        return subirSiguiente();
    }

    function sincronizarFotoPrincipal(id, respuesta) {
        var datos = respuesta && respuesta.data ? respuesta.data : {};
        var fotoUrl = datos.foto_url;
        var mascota;

        if (fotoUrl) {
            campoFotoActual.value = fotoUrl;
            mostrarVistaFoto(fotoUrl);
            return Promise.resolve();
        }

        return apiRequest('mascotas', { parametros: { id: id } }).then(function (respuestaMascota) {
            datos = respuestaMascota && respuestaMascota.data ? respuestaMascota.data : {};
            mascota = Array.isArray(datos) ? (datos[0] || {}) : datos;

            if (!mascota.foto_url) {
                throw new Error('No se pudo confirmar la ruta de la foto principal guardada.');
            }

            campoFotoActual.value = mascota.foto_url;
            mostrarVistaFoto(mascota.foto_url);
        });
    }

    function modalDe(nodo) {
        return nodo && window.bootstrap ? window.bootstrap.Modal.getOrCreateInstance(nodo) : null;
    }

    function idPorNombre(items, campo, nombre) {
        var i;

        for (i = 0; i < items.length; i++) {
            if (items[i][campo] === nombre) {
                return items[i].id;
            }
        }

        return '';
    }

    function nuevaOpcion(select) {
        var opcion = select.querySelector('option').cloneNode(false);

        opcion.removeAttribute('selected');
        opcion.removeAttribute('disabled');
        opcion.removeAttribute('hidden');
        opcion.value = '';
        opcion.textContent = '';

        return opcion;
    }

    function agregarOpciones(select, items, campo) {
        var i;
        var opcion;

        for (i = 0; i < items.length; i++) {
            opcion = nuevaOpcion(select);
            opcion.value = items[i].id;
            opcion.textContent = items[i][campo];
            select.appendChild(opcion);
        }
    }

    function vaciarRazas() {
        selectRaza.options.length = 1;
        selectRaza.selectedIndex = 0;
    }

    function cargarEspecies() {
        return apiRequest('especies').then(function (respuesta) {
            especies = (respuesta && respuesta.data) || [];
            agregarOpciones(selectEspecie, especies, 'nombre_especie');
        }).catch(function (error) {
            avisoDeApi(mensajeModal, error, false, formulario);
        });
    }

    function cargarRazas(idEspecie, razaSeleccionada) {
        vaciarRazas();

        if (!idEspecie) {
            return Promise.resolve();
        }

        return apiRequest('razas', { parametros: { id_especie: idEspecie } }).then(function (respuesta) {
            var razas = (respuesta && respuesta.data) || [];

            agregarOpciones(selectRaza, razas, 'nombre_raza');

            if (razaSeleccionada) {
                selectRaza.value = idPorNombre(razas, 'nombre_raza', razaSeleccionada);
            }
        }).catch(function (error) {
            avisoDeApi(mensajeModal, error, false, formulario);
        });
    }

    function prepararModal(titulo, textoBoton) {
        tituloModal.textContent = titulo;
        botonGuardar.textContent = textoBoton;
        formulario.removeAttribute('data-intento-envio');
        avisoDeApi(mensajeModal, '', true, formulario);
    }

    function abrirAlta() {
        idEditando = '';
        fotosAdicionales = [];
        fotosAdicionalesPendientes = [];
        formulario.reset();
        vaciarRazas();
        mostrarVistaFoto('');
        if (mensajeFotosAdicionales) {
            mensajeFotosAdicionales.textContent = '';
        }
        dibujarFotosAdicionales();
        prepararModal('Nueva mascota', 'Guardar mascota');
        modalDe(modalMascota).show();
    }

    function abrirEdicion(id) {
        avisoDeApi(mensaje, '');
        fotosAdicionales = [];
        fotosAdicionalesPendientes = [];

        if (inputFotosAdicionales) {
            inputFotosAdicionales.value = '';
            inputFotosAdicionales.disabled = false;
        }
        if (mensajeFotosAdicionales) {
            mensajeFotosAdicionales.textContent = '';
        }
        dibujarFotosAdicionales();

        especiesListas.then(function () {
            return apiRequest('mascotas', { parametros: { id: id } });
        }).then(function (respuesta) {
            var datos = respuesta && respuesta.data ? respuesta.data : {};
            var mascota = Array.isArray(datos) ? (datos[0] || {}) : datos;
            var idEspecie = idPorNombre(especies, 'nombre_especie', mascota.especie);

            idEditando = texto(id);
            formulario.reset();
            prepararModal('Editar mascota', 'Guardar cambios');

            formulario.elements.nombre.value = mascota.nombre || '';
            formulario.elements.sexo.value = mascota.sexo || '';
            formulario.elements['tamaño'].value = mascota['tamaño'] || '';
            formulario.elements.fecha_nacimiento_estimada.value = mascota.fecha_nacimiento_estimada || '';
            formulario.elements.historial.value = mascota.historial || '';
            campoFotoActual.value = mascota.foto_url || '';
            mostrarVistaFoto(campoFotoActual.value);
            selectEspecie.value = idEspecie;

            return Promise.all([
                cargarRazas(idEspecie, mascota.raza),
                cargarFotosAdicionales(idEditando)
            ]);
        }).then(function () {
            modalDe(modalMascota).show();
        }).catch(function (error) {
            avisoDeApi(mensaje, error);
            if (idEditando && mensajeFotosAdicionales) {
                mensajeFotosAdicionales.textContent = 'No se pudieron cargar las fotos adicionales: ' + mensajeDeApi(error);
                modalDe(modalMascota).show();
            }
        });
    }

    function cuerpoDelFormulario() {
        var datos = {
            id_especie: Number(formulario.elements.id_especie.value),
            id_raza: Number(formulario.elements.id_raza.value),
            nombre: formulario.elements.nombre.value,
            sexo: formulario.elements.sexo.value,
            'tamaño': formulario.elements['tamaño'].value
        };
        var fecha = formulario.elements.fecha_nacimiento_estimada.value;
        var historial = formulario.elements.historial.value;
        var foto = campoFotoActual.value;

        if (fecha) {
            datos.fecha_nacimiento_estimada = fecha;
        }

        if (historial) {
            datos.historial = historial;
        }

        if (foto) {
            datos.foto_url = foto;
        }

        return datos;
    }

    function cerrarModalGuardado(respuesta) {
        modalDe(modalMascota).hide();
        formulario.reset();
        vaciarRazas();
        mostrarVistaFoto('');
        fotosAdicionales = [];
        fotosAdicionalesPendientes = [];
        if (inputFotosAdicionales) {
            inputFotosAdicionales.disabled = false;
        }
        if (mensajeFotosAdicionales) {
            mensajeFotosAdicionales.textContent = '';
        }
        dibujarFotosAdicionales();
        idEditando = '';

        return cargarListado().then(function () {
            avisoDeApi(mensaje, respuesta);
        }).catch(function (error) {
            avisoDeApi(mensaje, error);
        });
    }

    function avisarFotoNoSubida(idMascota, error, tipoFoto) {
        var detalle = mensajeDeApi(error);
        var aviso = 'La mascota se guardó, pero ' + tipoFoto + ' no se pudo subir' + (detalle ? ': ' + detalle : '.');

        if (idMascota !== '') {
            idEditando = idMascota;
            tituloModal.textContent = 'Editar mascota';
            botonGuardar.textContent = 'Guardar cambios';
            aviso += ' Podés volver a intentarlo con "Guardar cambios".';
        }

        marcarIntentoEnvio(formulario);
        avisoDeApi(mensajeModal, aviso, false);

        return Promise.all([
            cargarListado(),
            idMascota ? cargarFotosAdicionales(idMascota) : Promise.resolve()
        ]).catch(function () {
            return null;
        });
    }

    function guardarMascota(evento) {
        var opciones = { cuerpo: cuerpoDelFormulario() };
        var archivo = inputFoto.files && inputFoto.files[0] ? inputFoto.files[0] : null;
        var archivosAdicionales = inputFotosAdicionales && inputFotosAdicionales.files
            ? Array.prototype.slice.call(inputFotosAdicionales.files)
            : [];
        var motivo = archivo ? motivoFotoInvalida(archivo) : '';
        var idMascota = idEditando;
        var motivoAdicional;
        var fotoPrincipalGuardada = false;
        var fotoPrincipalSincronizada = false;

        evento.preventDefault();

        if (motivo !== '') {
            marcarIntentoEnvio(formulario);
            avisoDeApi(mensajeModal, motivo, false);
            return;
        }

        if (fotosAdicionalesPendientes.length === 0) {
            motivoAdicional = validarFotosAdicionales(archivosAdicionales);

            if (motivoAdicional) {
                marcarIntentoEnvio(formulario);
                avisoDeApi(mensajeModal, motivoAdicional, false);
                return;
            }
        }

        if (botonGuardar.disabled) {
            return;
        }

        if (archivo) {
            delete opciones.cuerpo.foto_url;
        }

        if (idEditando === '') {
            opciones.metodo = 'POST';
        } else {
            opciones.metodo = 'PUT';
            opciones.parametros = { id: idEditando };
        }

        botonGuardar.disabled = true;

        enviarFormularioApi(formulario, mensajeModal, 'mascotas', opciones).then(function (respuesta) {
            var datos = respuesta && respuesta.data ? respuesta.data : {};

            if (idMascota === '') {
                idMascota = datos.id === undefined || datos.id === null ? '' : texto(datos.id);
            }

            if (idMascota === '') {
                if (archivo || archivosAdicionales.length) {
                    return avisarFotoNoSubida('', new Error('No se recibió el ID de la mascota para guardar sus fotos.'), 'las fotos');
                }

                return cerrarModalGuardado(respuesta);
            }

            idEditando = idMascota;

            var subirPrincipal = archivo
                ? subirFoto(idMascota, archivo, 'principal').then(function (respuestaFoto) {
                    fotoPrincipalGuardada = true;
                    return sincronizarFotoPrincipal(idMascota, respuestaFoto).then(function () {
                        fotoPrincipalSincronizada = true;
                        inputFoto.value = '';
                        return respuestaFoto;
                    });
                })
                : Promise.resolve(respuesta);

            return subirPrincipal.then(function (respuestaFoto) {
                return subirFotosAdicionales(idMascota).then(function () {
                    return cerrarModalGuardado(respuestaFoto && respuestaFoto.message ? respuestaFoto : respuesta);
                });
            }).catch(function (error) {
                var tipoFoto = !fotoPrincipalGuardada && archivo
                    ? 'la foto principal'
                    : (fotoPrincipalGuardada && !fotoPrincipalSincronizada
                        ? 'la foto principal (no se pudo confirmar la ruta)'
                        : 'las fotos adicionales');

                return avisarFotoNoSubida(idMascota, error, tipoFoto);
            });
        }).catch(function () {
            return null;
        }).then(function () {
            botonGuardar.disabled = false;
        });
    }

    if (modalMascota && formulario && selectEspecie && selectRaza && tituloModal && botonGuardar && inputFoto && campoFotoActual) {
        especiesListas = cargarEspecies();

        selectEspecie.addEventListener('change', function () {
            cargarRazas(selectEspecie.value);
        });

        formulario.addEventListener('submit', guardarMascota);

        inputFoto.addEventListener('change', elegirFoto);

        if (inputFotosAdicionales) {
            inputFotosAdicionales.addEventListener('change', function () {
                var archivos = Array.prototype.slice.call(inputFotosAdicionales.files || []);
                var motivo = validarFotosAdicionales(archivos);

                if (motivo) {
                    inputFotosAdicionales.value = '';
                    avisoDeApi(mensajeModal, motivo, false);
                    return;
                }

                if (mensajeFotosAdicionales) {
                    mensajeFotosAdicionales.textContent = archivos.length
                        ? archivos.length + ' foto' + (archivos.length === 1 ? '' : 's') + ' lista' + (archivos.length === 1 ? '' : 's') + ' para subir.'
                        : '';
                }
            });
        }

        if (listaFotosAdicionales) {
            listaFotosAdicionales.addEventListener('click', function (evento) {
                var botonBorrar = evento.target.closest('[data-borrar-foto]');

                if (!botonBorrar || !idEditando) {
                    return;
                }

                botonBorrar.disabled = true;
                apiRequest('fotos_mascota', {
                    metodo: 'DELETE',
                    parametros: {
                        id_mascota: idEditando,
                        id_foto: botonBorrar.getAttribute('data-borrar-foto')
                    }
                }).then(function (respuesta) {
                    return cargarFotosAdicionales(idEditando).then(function () {
                        avisoDeApi(mensajeModal, respuesta);
                    });
                }).catch(function (error) {
                    botonBorrar.disabled = false;
                    avisoDeApi(mensajeModal, error, false);
                });
            });
        }

        if (botonAlta) {
            botonAlta.addEventListener('click', abrirAlta);
        }
    }

    cuerpo.addEventListener('click', function (evento) {
        var botonEdicion = evento.target.closest('[data-id].abm-editar');
        var boton = evento.target.closest('[data-id].abm-baja');

        if (botonEdicion) {
            abrirEdicion(botonEdicion.getAttribute('data-id'));
            return;
        }

        if (!boton) {
            return;
        }

        apiRequest('mascotas', {
            metodo: 'DELETE',
            parametros: { id: boton.getAttribute('data-id') }
        }).then(function (respuesta) {
            return cargarListado().then(function () {
                if (mensaje) {
                    avisoDeApi(mensaje, respuesta);
                }
            });
        }).catch(function (error) {
            if (mensaje) {
                avisoDeApi(mensaje, error);
            }
        });
    });

    cargarListado().catch(function (error) {
        if (mensaje) {
            avisoDeApi(mensaje, error);
        }
    });
});
