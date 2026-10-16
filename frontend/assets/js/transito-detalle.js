document.addEventListener('DOMContentLoaded', function () {
    var panel = document.getElementById('panel-solicitud-detalle');

    if (!panel || panel.getAttribute('data-listo')) {
        return;
    }

    panel.setAttribute('data-listo', '1');

    var fichaMascota = document.getElementById('ficha-mascota');
    var fichaPostulante = document.getElementById('ficha-postulante');
    var fichaEvaluacion = document.getElementById('ficha-evaluacion');
    var fichaResolucion = document.getElementById('ficha-resolucion');
    var fichaSolicitudMeta = document.getElementById('ficha-solicitud-meta');
    var mensaje = document.getElementById('mensaje-formulario');
    var botones = document.getElementById('botones-estado');
    var id = new URLSearchParams(window.location.search).get('id');
    var listado = panel.getAttribute('data-listado') || 'solicitudes.php';
    var compatibilidadLista = false;

    function texto(valor) {
        if (valor === undefined || valor === null) {
            return '';
        }

        return String(valor);
    }

    function estiloBotonResolucion(btn, bloqueado) {
        btn.disabled = !!bloqueado;
        btn.classList.toggle('btn-resolucion-bloqueada', !!bloqueado);

        if (bloqueado) {
            btn.classList.remove('btn-outline-primary');
            btn.classList.add('btn-secondary');
            btn.style.backgroundColor = '#e8e8e8';
            btn.style.borderColor = '#d0d0d0';
            btn.style.color = '#9a9a9a';
            btn.style.opacity = '1';
            return;
        }

        btn.classList.remove('btn-secondary');
        btn.classList.add('btn-outline-primary');
        btn.style.backgroundColor = '';
        btn.style.borderColor = '';
        btn.style.color = '';
        btn.style.opacity = '';
    }

    function bloquearBotonesResolucion(bloqueado) {
        if (!botones) {
            return;
        }

        botones.querySelectorAll('.btn-estado-solicitud').forEach(function (btn) {
            estiloBotonResolucion(btn, bloqueado);
        });
    }

    function actualizarVistaResolucion() {
        var aviso = document.getElementById('resolucion-espera-compatibilidad');
        var hayBotones = botones && botones.querySelectorAll('.btn-estado-solicitud').length > 0;

        if (!botones) {
            return;
        }

        if (compatibilidadLista && hayBotones) {
            if (aviso) {
                aviso.hidden = true;
            }

            botones.hidden = false;
            bloquearBotonesResolucion(false);
            return;
        }

        if (hayBotones) {
            if (aviso) {
                aviso.hidden = false;
            }

            botones.hidden = false;
            bloquearBotonesResolucion(true);
            return;
        }

        if (aviso) {
            aviso.hidden = false;
        }

        botones.hidden = true;
    }

    function evaluacionCompatibilidadValida(evaluacion) {
        if (!evaluacion || typeof evaluacion !== 'object') {
            return false;
        }

        if (texto(evaluacion.clasificacion).trim() !== '') {
            return true;
        }

        var puntaje = evaluacion.puntaje;

        return puntaje !== undefined && puntaje !== null && texto(puntaje).trim() !== '';
    }

    function marcarCompatibilidadCargando(cargando) {
        if (fichaResolucion) {
            fichaResolucion.setAttribute('data-compatibilidad-cargando', cargando ? '1' : '0');
        }
    }

    function limpiarBotonesResolucion() {
        if (!botones) {
            return;
        }

        while (botones.firstChild) {
            botones.removeChild(botones.firstChild);
        }

        botones.hidden = true;
    }

    function marcarCompatibilidadListo(ok) {
        compatibilidadLista = !!ok;

        if (fichaResolucion) {
            fichaResolucion.setAttribute('data-compatibilidad-lista', compatibilidadLista ? '1' : '0');
        }

        if (botones) {
            botones.setAttribute('data-compatibilidad-lista', compatibilidadLista ? '1' : '0');
        }

        actualizarVistaResolucion();
    }

    function mostrarEvaluacionPendiente() {
        var espera = document.getElementById('evaluacion-espera');
        var resultado = document.getElementById('evaluacion-resultado');
        var listaRazones = document.getElementById('lista-razones');

        if (fichaEvaluacion) {
            fichaEvaluacion.setAttribute('data-evaluacion-lista', '0');
        }

        if (espera) {
            espera.hidden = false;
        }

        if (resultado) {
            resultado.hidden = true;
            resultado.querySelectorAll('[data-campo]').forEach(function (nodo) {
                nodo.removeAttribute('data-texto');
            });
        }

        if (listaRazones) {
            listaRazones.innerHTML = '';
        }
    }

    function mostrarEvaluacionConDatos(evaluacion) {
        var espera = document.getElementById('evaluacion-espera');
        var resultado = document.getElementById('evaluacion-resultado');
        var listaRazones = document.getElementById('lista-razones');

        if (!fichaEvaluacion || !resultado) {
            return;
        }

        pintarCaja(resultado, {
            puntaje: evaluacion.puntaje,
            clasificacion: evaluacion.clasificacion
        });

        if (listaRazones) {
            listaRazones.innerHTML = '';
            (evaluacion.razones || []).forEach(function (razon) {
                var item = document.createElement('li');

                item.className = 'detalle-razon-item';
                item.textContent = textoDeItem(razon);
                listaRazones.appendChild(item);
            });
        }

        fichaEvaluacion.setAttribute('data-evaluacion-lista', '1');

        if (espera) {
            espera.hidden = true;
        }

        resultado.hidden = false;
    }

    function esVerdadero(valor) {
        return valor === true || valor === 1 || valor === '1' || valor === 't' || valor === 'true';
    }

    function esBooleano(valor) {
        return valor === true || valor === false || valor === 't' || valor === 'f' || valor === 'true' || valor === 'false';
    }

    function siNo(valor) {
        if (esVerdadero(valor)) {
            return 'Sí';
        }

        if (valor === false || valor === 0 || valor === '0' || valor === 'f' || valor === 'false') {
            return 'No';
        }

        return texto(valor);
    }

    function datoDe(obj, campo) {
        if (!obj) {
            return '';
        }

        if (obj[campo] !== undefined && obj[campo] !== null && obj[campo] !== '') {
            return obj[campo];
        }

        if (campo === 'tamaño') {
            return obj.tamano || obj.tamaño || '';
        }

        return '';
    }

    function mostrarAviso(origen, ok) {
        avisoDeApi(mensaje, origen, ok);
    }

    function avisoSiError(error) {
        if (error) {
            mostrarAviso(error, false);
        }
    }

    function pedirApi(recurso, opciones) {
        return apiRequest(recurso, opciones).catch(function (error) {
            avisoSiError(error);
            return null;
        });
    }

    function datosDeRespuesta(respuesta) {
        var datos = respuesta && respuesta.data !== undefined ? respuesta.data : respuesta;

        if (Array.isArray(datos)) {
            return datos[0] || {};
        }

        return datos || {};
    }

    function listaDe(respuesta) {
        var origen = respuesta && respuesta.data !== undefined ? respuesta.data : respuesta;

        return Array.isArray(origen) ? origen : (origen ? [origen] : []);
    }

    function clonarModelo(id) {
        var modelo = document.getElementById(id);

        if (!modelo) {
            return null;
        }

        var copia = modelo.cloneNode(true);

        copia.removeAttribute('id');
        copia.hidden = false;

        return copia;
    }

    function marcarNodoContenido(nodo) {
        if (!nodo) {
            return;
        }

        nodo.style.maxWidth = '100%';
        nodo.style.minWidth = '0';
        nodo.style.overflowWrap = 'anywhere';
        nodo.style.wordBreak = 'break-word';

        if (nodo.hasAttribute('data-texto') || nodo.tagName === 'INPUT' || nodo.tagName === 'TEXTAREA') {
            nodo.style.display = 'block';
        }
    }

    function contenerDatosDetalle() {
        if (!panel) {
            return;
        }

        panel.querySelectorAll('.info-card, .pet-detail-meta, .postulante-layout, .postulante-panel-contenido, .formulario-detalle-scroll, .evaluacion-detalle-scroll').forEach(function (bloque) {
            bloque.style.maxWidth = '100%';
            bloque.style.minWidth = '0';
        });

        panel.querySelectorAll('[data-campo]').forEach(marcarNodoContenido);
    }

    function pintarCaja(caja, datos) {
        if (!caja || !datos) {
            return;
        }

        caja.querySelectorAll('[data-campo]').forEach(function (nodo) {
            var campo = nodo.getAttribute('data-campo');
            var valor = datoDe(datos, campo);

            if (valor && typeof valor === 'object') {
                valor = '';
            }

            if (campo.indexOf('fecha') === 0) {
                valor = fechaCorta(valor);
            }

            if (nodo.type === 'checkbox') {
                nodo.checked = esVerdadero(valor);
                return;
            }

            if (nodo.tagName === 'INPUT' || nodo.tagName === 'TEXTAREA') {
                nodo.value = esBooleano(valor) ? siNo(valor) : texto(valor);
                return;
            }

            if (texto(valor) !== '') {
                nodo.setAttribute('data-texto', texto(valor));
            } else {
                nodo.removeAttribute('data-texto');
            }

            marcarNodoContenido(nodo);
        });

        contenerDatosDetalle();
    }

    function pintarResolucion(solicitud) {
        if (fichaSolicitudMeta) {
            pintarCaja(fichaSolicitudMeta, {
                fecha_solicitud: solicitud.fecha_solicitud
            });
        }

        if (!fichaResolucion) {
            return;
        }

        fichaResolucion.setAttribute('data-estado-actual', texto(solicitud.estado_solicitud));
        fichaResolucion.setAttribute('data-id-estado-actual', texto(solicitud.id_estado_solicitud));

        pintarCaja(fichaResolucion, {
            estado_solicitud: solicitud.estado_solicitud
        });

        if (botones) {
            botones.setAttribute('data-id-estado-actual', texto(solicitud.id_estado_solicitud));
        }
    }

    function idEstadoActualResolucion() {
        if (fichaResolucion) {
            return texto(fichaResolucion.getAttribute('data-id-estado-actual'));
        }

        if (botones) {
            return texto(botones.getAttribute('data-id-estado-actual'));
        }

        return '';
    }

    function cargarEstados(idActual) {
        return apiRequest('estados_solicitudes').then(function (respuesta) {
            var datos = (respuesta && respuesta.data) || [];
            var modelo = document.getElementById('boton-modelo-estado');

            if (!botones || !modelo) {
                return;
            }

            if (!Array.isArray(datos)) {
                datos = [datos];
            }

            while (botones.firstChild) {
                botones.removeChild(botones.firstChild);
            }

            datos.forEach(function (estado) {
                var boton;
                var nombreEstado;

                if (!estado || texto(estado.id) === texto(idActual)) {
                    return;
                }

                nombreEstado = texto(estado.nombre_estado);
                boton = modelo.cloneNode(false);
                boton.removeAttribute('id');
                boton.hidden = false;
                boton.classList.add('btn-estado-solicitud');
                boton.setAttribute('data-id-estado', texto(estado.id));
                boton.setAttribute('data-nombre-estado', nombreEstado);
                boton.textContent = nombreEstado;
                estiloBotonResolucion(boton, !compatibilidadLista);
                botones.appendChild(boton);
            });

            actualizarVistaResolucion();
        }).catch(function (error) {
            if (error && error.data) {
                avisoSiError(error);
            }

            actualizarVistaResolucion();
        });
    }

    function traerEvaluacion(idSolicitud) {
        marcarCompatibilidadListo(false);
        marcarCompatibilidadCargando(true);

        if (fichaEvaluacion) {
            mostrarEvaluacionPendiente();
        }

        return apiRequest('compatibilidad', {
            parametros: { id_solicitud: idSolicitud }
        }).then(function (respuesta) {
            var datos = datosDeRespuesta(respuesta);
            var evaluacion = datos && datos.compatibilidad;

            if (!evaluacionCompatibilidadValida(evaluacion)) {
                if (fichaEvaluacion) {
                    mostrarEvaluacionPendiente();
                }

                marcarCompatibilidadListo(false);

                if (mensajeDeApi(respuesta)) {
                    mostrarAviso(respuesta, false);
                }

                return false;
            }

            if (fichaEvaluacion) {
                mostrarEvaluacionConDatos(evaluacion);
            }

            marcarCompatibilidadListo(true);
            return true;
        }).catch(function (error) {
            if (fichaEvaluacion) {
                mostrarEvaluacionPendiente();
            }

            marcarCompatibilidadListo(false);
            mostrarAviso(error, false);

            return false;
        }).finally(function () {
            marcarCompatibilidadCargando(false);
        });
    }

    function activarSeccionPostulante(seccionId) {
        var nav = document.getElementById('nav-postulante');

        if (!nav) {
            return;
        }

        nav.querySelectorAll('[data-postulante-seccion]').forEach(function (boton) {
            boton.classList.toggle('active', boton.getAttribute('data-postulante-seccion') === seccionId);
        });

        document.querySelectorAll('[data-panel-postulante]').forEach(function (panel) {
            panel.classList.toggle('d-none', panel.getAttribute('data-panel-postulante') !== seccionId);
        });
    }

    function pintarContactosPostulante(contactos) {
        var lista = document.getElementById('lista-contacto-postulante');

        if (!lista) {
            return;
        }

        lista.innerHTML = '';

        (contactos || []).forEach(function (item) {
            var tipo = texto(item.tipo_contacto);
            var valor = texto(item.valor);
            var fila;

            if (valor === '') {
                return;
            }

            fila = clonarModelo('modelo-item-contacto-postulante');

            if (!fila) {
                return;
            }

            fila.querySelector('.detalle-label').textContent = tipo;
            fila.querySelector('span:last-child').setAttribute('data-texto', valor);
            lista.appendChild(fila);
        });
    }

    function datosMascotaSolicitud(solicitud) {
        if (!solicitud) {
            return {};
        }

        return {
            nombre: solicitud.nombre_mascota
        };
    }

    function pintarMascota(mascota) {
        pintarCaja(fichaMascota, mascota);
        pintarIconoEspecieMascota(fichaMascota, mascota);
        pintarFotoMascota(mascota);
    }

    function pintarFotoMascota(mascota) {
        var foto = document.getElementById('detalle-foto');
        var vacia = document.getElementById('detalle-foto-vacia');

        if (!foto) {
            return;
        }

        if (mascota && mascota.foto_url) {
            foto.src = mascota.foto_url;
            foto.alt = texto(mascota.nombre);
            foto.removeAttribute('hidden');

            if (vacia) {
                vacia.hidden = true;
            }

            return;
        }

        foto.hidden = true;
        foto.removeAttribute('src');

        if (vacia) {
            vacia.hidden = false;
        }
    }

    function traerPostulante(solicitud, idUsuario) {
        if (!fichaPostulante) {
            return Promise.resolve();
        }

        var base = {
            nombre: texto(solicitud.nombre_usuario),
            apellido: texto(solicitud.apellido_usuario)
        };

        pintarCaja(fichaPostulante, base);
        pintarContactosPostulante([]);

        if (!idUsuario) {
            return Promise.resolve();
        }

        return Promise.all([
            pedirApi('usuarios', { parametros: { id: idUsuario } }),
            pedirApi('contactos', { parametros: { id_usuario: idUsuario } }),
            pedirApi('domicilios', { parametros: { id_usuario: idUsuario } })
        ]).then(function (respuestas) {
            var usuario = datosDeRespuesta(respuestas[0] || {});
            var contactos = listaDe(respuestas[1] || {});
            var domicilio = datosDeRespuesta(respuestas[2] || {});

            pintarCaja(fichaPostulante, Object.assign({}, domicilio, {
                nombre: texto(usuario.nombre) || base.nombre,
                apellido: texto(usuario.apellido) || base.apellido,
                fecha_nacimiento: usuario.fecha_nacimiento
            }));
            pintarContactosPostulante(contactos);
        });
    }

    function traerMascota(idMascota, solicitud) {
        if (!fichaMascota) {
            return Promise.resolve();
        }

        pintarMascota(datosMascotaSolicitud(solicitud));

        if (!idMascota) {
            return Promise.resolve();
        }

        return apiRequest('mascotas', { parametros: { id: idMascota } }).then(function (respuesta) {
            pintarMascota(datosDeRespuesta(respuesta));
        }).catch(function (error) {
            var respaldo = datosMascotaSolicitud(solicitud);

            pintarMascota(respaldo);

            if (!texto(respaldo.nombre)) {
                avisoSiError(error);
            }
        });
    }

    function pintarLista(nombre, items) {
        var caja = document.querySelector('[data-lista-valor="' + nombre + '"]');
        var modelo = document.getElementById('modelo-opcion-lista');

        if (!caja || !modelo) {
            return;
        }

        while (caja.firstChild) {
            caja.removeChild(caja.firstChild);
        }

        (items || []).forEach(function (item) {
            var opcion = modelo.cloneNode(true);
            var textoOpcion = opcion.querySelector('[data-texto]');

            opcion.removeAttribute('id');
            textoOpcion.textContent = textoDeItem(item);
            caja.appendChild(opcion);
        });
    }

    function pintarFormulario(datos) {
        var caja = document.getElementById('campos-formulario');
        var grupoMetros = document.getElementById('grupo-metros-patio');
        var grupoMascotas = document.getElementById('grupo-mascotas-hogar');
        var listaMascotas = document.getElementById('lista-mascotas-hogar');
        var modeloMascota = document.getElementById('modelo-mascota-hogar');
        var origen = datos || {};

        if (!caja) {
            return;
        }

        caja.querySelectorAll('[data-campo]').forEach(function (nodo) {
            var campo = nodo.getAttribute('data-campo');
            var valor = datoDe(origen, campo);

            if (nodo.type === 'checkbox') {
                nodo.checked = esVerdadero(valor);
                return;
            }

            if (campo.indexOf('fecha') === 0 || campo.indexOf('disponibilidad') === 0) {
                nodo.value = fechaCorta(valor);
                return;
            }

            nodo.value = esBooleano(valor) ? siNo(valor) : texto(valor);
        });

        if (grupoMetros) {
            grupoMetros.hidden = !esVerdadero(datos.tiene_patio);
        }

        pintarLista('condiciones_especiales', datos.condiciones_especiales);

        if (grupoMascotas && listaMascotas && modeloMascota) {
            while (listaMascotas.firstChild) {
                listaMascotas.removeChild(listaMascotas.firstChild);
            }

            grupoMascotas.hidden = !esVerdadero(datos.tiene_mascotas);

            (datos.mascotas_hogar || []).forEach(function (mascota) {
                var fila = modeloMascota.cloneNode(true);

                fila.removeAttribute('id');
                fila.querySelectorAll('[data-campo]').forEach(function (nodo) {
                    var valor = mascota[nodo.getAttribute('data-campo')];

                    nodo.value = texto(valor);
                });
                listaMascotas.appendChild(fila);
            });
        }
    }

    function traerFormulario(idSolicitud) {
        return apiRequest('formularios_transito', {
            parametros: { id_solicitud: idSolicitud }
        }).then(function (respuesta) {
            pintarFormulario(datosDeRespuesta(respuesta));
        }).catch(function (error) {
            pintarFormulario({});
            avisoSiError(error);
        });
    }

    function aplicarCambioEstadoEnVista(solicitud) {
        if (!solicitud || !solicitud.id) {
            return;
        }

        pintarResolucion(solicitud);
        cargarEstados(solicitud.id_estado_solicitud);
        contenerDatosDetalle();
    }

    function enviarCambioEstado(idEstado) {
        avisoEsperandoApi(mensaje);

        return apiRequest('solicitudes', {
            metodo: 'PUT',
            parametros: { id: id },
            cuerpo: { id_estado_solicitud: idEstado }
        }).then(function (respuesta) {
            mostrarAviso(respuesta, true);
            aplicarCambioEstadoEnVista(datosDeRespuesta(respuesta));
            traerEvaluacion(id);
        }).catch(function (error) {
            mostrarAviso(error, false);
        });
    }

    function mostrar(idSolicitud) {
        return apiRequest('solicitudes', {
            parametros: { id: idSolicitud }
        }).then(function (respuesta) {
            var solicitud = datosDeRespuesta(respuesta);

            if (!solicitud.id) {
                mostrarAviso(respuesta, false);
                return null;
            }

            pintarResolucion(solicitud);
            marcarCompatibilidadListo(false);
            limpiarBotonesResolucion();
            mostrarEvaluacionPendiente();
            marcarCompatibilidadCargando(true);

            var avisoCompat = document.getElementById('resolucion-espera-compatibilidad');

            if (avisoCompat) {
                avisoCompat.hidden = false;
            }

            traerEvaluacion(idSolicitud);

            return Promise.all([
                cargarEstados(solicitud.id_estado_solicitud),
                traerMascota(solicitud.id_mascota, solicitud),
                traerPostulante(solicitud, solicitud.id_usuario),
                traerFormulario(idSolicitud)
            ]).then(function () {
                contenerDatosDetalle();
                return { solicitud: solicitud };
            });
        });
    }

    var redimensionDetalle;

    window.addEventListener('resize', function () {
        if (redimensionDetalle) {
            window.cancelAnimationFrame(redimensionDetalle);
        }

        redimensionDetalle = window.requestAnimationFrame(contenerDatosDetalle);
    });

    if (!id) {
        window.location.href = listado;
        return;
    }

    if (mensaje) {
        mensaje.textContent = '';
        mensaje.removeAttribute('data-estado');
    }

    mostrarEvaluacionPendiente();
    marcarCompatibilidadListo(false);
    marcarCompatibilidadCargando(true);
    limpiarBotonesResolucion();
    actualizarVistaResolucion();

    var navPostulante = document.getElementById('nav-postulante');

    if (navPostulante) {
        navPostulante.addEventListener('click', function (evento) {
            var boton = evento.target.closest('[data-postulante-seccion]');

            if (!boton) {
                return;
            }

            activarSeccionPostulante(boton.getAttribute('data-postulante-seccion'));
        });
        activarSeccionPostulante('personal');
    }

    if (botones) {
        var modalConfirmarEstado = document.getElementById('modal-confirmar-estado');
        var modalConfirmarInstancia = modalConfirmarEstado && window.bootstrap
            ? window.bootstrap.Modal.getOrCreateInstance(modalConfirmarEstado)
            : null;
        var botonConfirmarEstado = document.getElementById('modal-confirmar-estado-aceptar');
        var destinoConfirmarEstado = document.getElementById('modal-confirmar-estado-destino');
        var idEstadoConfirmar = null;

        botones.addEventListener('click', function (evento) {
            var boton = evento.target.closest('[data-id-estado]');

            if (!boton || boton.disabled || !compatibilidadLista) {
                return;
            }

            if (boton.hasAttribute('data-confirmar-estado') && modalConfirmarInstancia) {
                idEstadoConfirmar = boton.getAttribute('data-id-estado');

                if (destinoConfirmarEstado) {
                    destinoConfirmarEstado.textContent = boton.textContent;
                }

                modalConfirmarInstancia.show();
                return;
            }

            enviarCambioEstado(boton.getAttribute('data-id-estado'));
        });

        if (botonConfirmarEstado) {
            botonConfirmarEstado.addEventListener('click', function () {
                var idEstado = idEstadoConfirmar;

                idEstadoConfirmar = null;

                if (modalConfirmarInstancia) {
                    modalConfirmarInstancia.hide();
                }

                if (idEstado) {
                    enviarCambioEstado(idEstado);
                }
            });
        }

        if (modalConfirmarEstado) {
            modalConfirmarEstado.addEventListener('hidden.bs.modal', function () {
                idEstadoConfirmar = null;
            });
        }
    }

    mostrar(id).then(function (resultado) {
        if (resultado && resultado.solicitud) {
            return null;
        }

        if (mensaje && texto(mensaje.textContent).trim() !== '') {
            return null;
        }

        window.location.href = listado;
        return null;
    }).catch(function (error) {
        mostrarAviso(error, false);

        if (error && error.status === 404 && (!mensaje || texto(mensaje.textContent).trim() === '')) {
            window.location.href = listado;
        }
    });
});
