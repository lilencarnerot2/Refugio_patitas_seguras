document.addEventListener('DOMContentLoaded', function () {
    var panel = document.getElementById('panel-solicitud-detalle');

    if (!panel || panel.getAttribute('data-listo')) {
        return;
    }

    panel.setAttribute('data-listo', '1');

    var ficha = document.getElementById('ficha-solicitud');
    var fichaMascota = document.getElementById('ficha-mascota');
    var fichaPostulante = document.getElementById('ficha-postulante');
    var fichaEvaluacion = document.getElementById('ficha-evaluacion');
    var fichaResolucion = document.getElementById('ficha-resolucion');
    var fichaSolicitudMeta = document.getElementById('ficha-solicitud-meta');
    var mensaje = document.getElementById('mensaje-formulario');
    var botones = document.getElementById('botones-estado');
    var id = new URLSearchParams(window.location.search).get('id');
    var listado = panel.getAttribute('data-listado') || '';
    var tipoPedido = panel.getAttribute('data-tipo') || '';
    var compatibilidadLista = false;
    var solicitudActual = null;

    function normalizarTipo(valor) {
        return texto(valor).toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    }

    var compatibilidadManual = panel.hasAttribute('data-compatibilidad-manual');

    function estiloBotonResolucion(btn, bloqueado) {
        btn.disabled = !!bloqueado;
        btn.classList.toggle('btn-resolucion-bloqueada', !!bloqueado);

        if (bloqueado) {
            btn.classList.remove('btn-outline-primary', 'btn-primary');
            btn.classList.add('btn-secondary', 'btn-outline');
            return;
        }

        btn.classList.remove('btn-secondary', 'btn-outline-primary', 'btn-primary');
        btn.classList.add('btn-outline');
    }

    function evaluacionCompatibilidadHecha() {
        return !!(fichaEvaluacion && fichaEvaluacion.getAttribute('data-evaluacion-lista') === '1');
    }

    function actualizarBotonCompatibilidad() {
        var botonCompat = document.getElementById('boton-compatibilidad');

        if (!botonCompat || !compatibilidadManual) {
            return;
        }

        var hecha = evaluacionCompatibilidadHecha();

        botonCompat.disabled = hecha;
        botonCompat.hidden = hecha;
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

    function mostrarAviso(origen, ok) {
        avisoDeApi(mensaje, origen, ok);
    }

    function marcarCompatibilidadListo(ok) {
        var avisoResolucion = document.getElementById('resolucion-espera-compatibilidad');

        compatibilidadLista = !!ok;

        if (fichaResolucion) {
            fichaResolucion.setAttribute('data-compatibilidad-lista', compatibilidadLista ? '1' : '0');
        }

        if (botones) {
            botones.setAttribute('data-compatibilidad-lista', compatibilidadLista ? '1' : '0');
        }

        if (botones && botones.hasAttribute('data-resolver')) {
            actualizarVistaResolucion();
            return;
        }

        if (botones) {
            botones.hidden = !compatibilidadLista;
        }

        if (avisoResolucion) {
            avisoResolucion.hidden = compatibilidadLista;
        }
    }

    function mostrarEvaluacionPendiente() {
        var espera = document.getElementById('evaluacion-espera');
        var resultado = document.getElementById('evaluacion-resultado');
        var avisoEval = document.getElementById('aviso-evaluacion');

        if (espera) {
            espera.hidden = false;
        }

        if (resultado) {
            resultado.hidden = true;
        }

        if (fichaEvaluacion) {
            fichaEvaluacion.setAttribute('data-cargado', '0');
            fichaEvaluacion.setAttribute('data-evaluacion-lista', '0');
        }

        if (avisoEval) {
            avisoEval.textContent = '';
        }

        marcarCompatibilidadListo(false);
        actualizarBotonCompatibilidad();
    }

    function pintarAvataresCompatibilidad(datosRespuesta, solicitud) {
        var mascotaApi = datosRespuesta && datosRespuesta.mascota;
        var foto = document.getElementById('compatibilidad-foto-mascota');
        var fotoVacia = document.getElementById('compatibilidad-foto-mascota-vacia');
        var etiquetaMascota = document.querySelector('#compatibilidad-avatar-mascota [data-campo="nombre_mascota"]');
        var etiquetaPostulante = document.querySelector('#compatibilidad-avatar-postulante [data-campo="nombre_postulante"]');
        var nombreMascota = texto(mascotaApi && mascotaApi.nombre);
        var nombrePostulante = [
            texto(solicitud && solicitud.nombre_usuario),
            texto(solicitud && solicitud.apellido_usuario)
        ].join(' ').trim();
        var urlFoto = texto(mascotaApi && mascotaApi.foto_url);

        if (etiquetaMascota) {
            if (nombreMascota !== '') {
                etiquetaMascota.setAttribute('data-texto', nombreMascota);
            } else {
                etiquetaMascota.removeAttribute('data-texto');
            }
        }

        if (etiquetaPostulante) {
            if (nombrePostulante !== '') {
                etiquetaPostulante.setAttribute('data-texto', nombrePostulante);
            } else {
                etiquetaPostulante.removeAttribute('data-texto');
            }
        }

        if (!foto) {
            return;
        }

        if (urlFoto !== '') {
            foto.src = urlFoto;
            foto.alt = nombreMascota;
            foto.hidden = false;

            if (fotoVacia) {
                fotoVacia.hidden = true;
            }

            return;
        }

        foto.removeAttribute('src');
        foto.hidden = true;

        if (fotoVacia) {
            fotoVacia.hidden = false;
        }
    }

    function pintarRazonesEvaluacion(razones) {
        var listaRazones = document.getElementById('lista-razones');
        var modelo = document.getElementById('modelo-razon-evaluacion');

        if (!listaRazones) {
            return;
        }

        while (listaRazones.firstChild) {
            listaRazones.removeChild(listaRazones.firstChild);
        }

        if (!Array.isArray(razones)) {
            razones = textoDeItem(razones) ? [razones] : [];
        }

        razones.forEach(function (razon) {
            var detalle = textoDeItem(razon);
            var item;

            if (detalle === '') {
                return;
            }

            if (modelo) {
                item = modelo.cloneNode(true);
                item.removeAttribute('id');
                item.textContent = detalle;
            } else {
                item = document.createElement('li');
                item.textContent = detalle;
            }

            listaRazones.appendChild(item);
        });
    }

    function mostrarEvaluacionConDatos(evaluacion, datosRespuesta) {
        var espera = document.getElementById('evaluacion-espera');
        var resultado = document.getElementById('evaluacion-resultado');
        var pct = puntajeCompatibilidadPct(evaluacion);
        var puntajeApi = evaluacion && evaluacion.puntaje;
        var corazon = document.getElementById('corazon-compatibilidad');

        if (espera) {
            espera.hidden = true;
        }

        if (resultado) {
            resultado.hidden = false;
        }

        if (fichaEvaluacion) {
            pintarCaja(fichaEvaluacion, {
                puntaje: puntajeApi !== undefined && puntajeApi !== null ? puntajeApi : '',
                clasificacion: evaluacion.clasificacion
            });
            fichaEvaluacion.setAttribute('data-cargado', '1');
            fichaEvaluacion.setAttribute('data-evaluacion-lista', '1');
        }

        pintarCorazonCompatibilidad(corazon, pct);
        pintarAvataresCompatibilidad(
            datosRespuesta,
            solicitudActual || (datosRespuesta && datosRespuesta.solicitud)
        );
        pintarRazonesEvaluacion(evaluacion.razones);
        marcarCompatibilidadListo(true);
        actualizarBotonCompatibilidad();
    }

    function texto(valor) {
        if (valor === undefined || valor === null) {
            return '';
        }

        return String(valor);
    }

    function esVerdadero(valor) {
        return valor === true || valor === 1 || valor === '1' || valor === 't' || valor === 'true';
    }

    function esBooleano(valor) {
        return valor === true || valor === false || valor === 't' || valor === 'f' || valor === 'true' || valor === 'false';
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

    function siNo(valor) {
        if (valor === true || valor === 1 || valor === '1' || valor === 't' || valor === 'true') {
            return 'Sí';
        }

        if (valor === false || valor === 0 || valor === '0' || valor === 'f' || valor === 'false') {
            return 'No';
        }

        return texto(valor);
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
        });
    }

    function pintarFicha(solicitud) {
        if (ficha) {
            pintarCaja(ficha, solicitud);
        }

        if (fichaSolicitudMeta) {
            pintarCaja(fichaSolicitudMeta, {
                fecha_solicitud: solicitud.fecha_solicitud
            });
        }

        if (fichaResolucion) {
            fichaResolucion.setAttribute('data-estado-actual', texto(solicitud.estado_solicitud));
            fichaResolucion.setAttribute('data-id-estado-actual', texto(solicitud.id_estado_solicitud));
            pintarCaja(fichaResolucion, {
                estado_solicitud: solicitud.estado_solicitud
            });
        }

        if (botones) {
            botones.setAttribute('data-id-estado-actual', texto(solicitud.id_estado_solicitud));
        }
    }

    function datosDeRespuesta(respuesta) {
        var datos = respuesta && respuesta.data !== undefined ? respuesta.data : respuesta;

        if (Array.isArray(datos)) {
            return datos[0] || {};
        }

        return datos || {};
    }

    function traerFormulario(idSolicitud) {
        var pedido = normalizarTipo(tipoPedido);

        function vacio(error) {
            pintarFormulario({});

            if (error) {
                mostrarAviso(error);
            }
        }

        if (pedido.indexOf('transito') !== -1) {
            return apiRequest('formularios_transito', {
                parametros: { id_solicitud: idSolicitud }
            }).then(function (respuesta) {
                pintarFormulario(datosDeRespuesta(respuesta));
            }).catch(vacio);
        }

        return apiRequest('formularios_adopcion', {
            parametros: { id_solicitud: idSolicitud }
        }).then(function (respuesta) {
            pintarFormulario(datosDeRespuesta(respuesta));
        }).catch(function (errorAdopcion) {
            if (pedido.indexOf('adopcion') !== -1) {
                vacio(errorAdopcion);
                return;
            }

            return apiRequest('formularios_transito', {
                parametros: { id_solicitud: idSolicitud }
            }).then(function (respuesta) {
                pintarFormulario(datosDeRespuesta(respuesta));
            }).catch(function (errorTransito) {
                vacio(errorAdopcion || errorTransito);
            });
        });
    }

    function pintarFormulario(datos) {
        var caja = document.getElementById('campos-formulario');
        var grupoMetros = document.getElementById('grupo-metros-patio');
        var grupoMascotas = document.getElementById('grupo-mascotas-hogar');
        var listaMascotas = document.getElementById('lista-mascotas-hogar');
        var modeloMascota = document.getElementById('modelo-mascota-hogar');

        if (!caja) {
            return;
        }

        caja.querySelectorAll('[data-campo]').forEach(function (nodo) {
            var valor = datos[nodo.getAttribute('data-campo')];

            if (nodo.type === 'checkbox') {
                nodo.checked = esVerdadero(valor);
                return;
            }

            nodo.value = esBooleano(valor) ? siNo(valor) : texto(valor);
        });

        if (grupoMetros) {
            grupoMetros.hidden = !esVerdadero(datos.tiene_patio);
        }

        pintarLista('compromisos', datos.compromisos);
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

                    if (nodo.type === 'checkbox') {
                        nodo.checked = esVerdadero(valor);
                        return;
                    }

                    nodo.value = texto(valor);
                });
                listaMascotas.appendChild(fila);
            });
        }
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

    function mostrar(idSolicitud) {
        return apiRequest('solicitudes', {
            parametros: { id: idSolicitud }
        }).then(function (respuesta) {
            var solicitud = (respuesta && respuesta.data) || {};

            if (Array.isArray(solicitud)) {
                solicitud = solicitud[0] || {};
            }

            if (!solicitud.id) {
                mostrarAviso(respuesta, false);
                return null;
            }

            if (tipoPedido && normalizarTipo(solicitud.tipo_solicitud).indexOf(normalizarTipo(tipoPedido)) === -1) {
                if (listado) {
                    window.location.href = listado;
                }

                return null;
            }

            solicitudActual = solicitud;
            pintarFicha(solicitud);

            var tareas = [
                traerFormulario(idSolicitud),
                traerMascota(solicitud.id_mascota),
                traerPostulante(solicitud, solicitud.id_usuario)
            ];

            if (!compatibilidadManual) {
                tareas.push(traerEvaluacion(idSolicitud));
            }

            return Promise.all(tareas).then(function () {
                return solicitud;
            });
        });
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

    function activarSeccionPostulante(seccionId) {
        var nav = document.getElementById('nav-postulante');

        if (!nav || !seccionId) {
            return;
        }

        nav.querySelectorAll('[data-postulante-seccion]').forEach(function (boton) {
            boton.classList.toggle('active', boton.getAttribute('data-postulante-seccion') === seccionId);
        });

        document.querySelectorAll('[data-panel-postulante]').forEach(function (panelSeccion) {
            var activo = panelSeccion.getAttribute('data-panel-postulante') === seccionId;
            panelSeccion.classList.toggle('d-none', !activo);
        });
    }

    function pintarContactosPostulante(contactos) {
        var lista = document.getElementById('lista-contacto-postulante');
        var seccion = document.getElementById('seccion-contacto-postulante');

        if (!lista) {
            return;
        }

        lista.innerHTML = '';

        (contactos || []).forEach(function (item) {
            var tipo = texto(item.tipo_contacto) || 'Contacto';
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

        if (seccion) {
            seccion.classList.toggle('postulante-seccion-vacia', lista.children.length === 0);
        }
    }

    function ocultarCamposVaciosPostulante() {
        var seccionDomicilio = document.getElementById('seccion-domicilio-postulante');

        if (!seccionDomicilio) {
            return;
        }

        seccionDomicilio.querySelectorAll('.pet-detail-meta li').forEach(function (item) {
            var valor = item.querySelector('[data-campo]');

            if (!valor) {
                return;
            }

            item.hidden = !valor.hasAttribute('data-texto');
        });
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
        ocultarCamposVaciosPostulante();

        if (!idUsuario) {
            return Promise.resolve();
        }

        return Promise.all([
            apiRequest('usuarios', { parametros: { id: idUsuario } }).catch(function () {
                return null;
            }),
            apiRequest('contactos', { parametros: { id_usuario: idUsuario } }).catch(function () {
                return null;
            }),
            apiRequest('domicilios', { parametros: { id_usuario: idUsuario } }).catch(function () {
                return null;
            })
        ]).then(function (respuestas) {
            var usuario = datosDeRespuesta(respuestas[0] || {});
            var contactos = listaDe(respuestas[1]);
            var domicilio = datosDeRespuesta(respuestas[2] || {});

            pintarCaja(fichaPostulante, Object.assign({}, domicilio, {
                nombre: texto(usuario.nombre) || base.nombre,
                apellido: texto(usuario.apellido) || base.apellido,
                fecha_nacimiento: usuario.fecha_nacimiento
            }));
            pintarContactosPostulante(contactos);
            ocultarCamposVaciosPostulante();
        });
    }

    function traerMascota(idMascota) {
        if (!fichaMascota || !idMascota) {
            return Promise.resolve();
        }

        return apiRequest('mascotas', { parametros: { id: idMascota } }).then(function (respuesta) {
            var mascota = datosDeRespuesta(respuesta);
            var foto = document.getElementById('detalle-foto');
            var vacia = document.getElementById('detalle-foto-vacia');

            pintarCaja(fichaMascota, mascota);
            pintarIconoEspecieMascota(fichaMascota, mascota);

            if (foto && texto(mascota.foto_url) !== '') {
                foto.src = mascota.foto_url;
                foto.alt = texto(mascota.nombre);
                foto.hidden = false;

                if (vacia) {
                    vacia.hidden = true;
                }
            }
        }).catch(function () {
            return null;
        });
    }

    function traerEvaluacion(idSolicitud) {
        var avisoEval = document.getElementById('aviso-evaluacion');
        var botonCompat = document.getElementById('boton-compatibilidad');

        if (!fichaEvaluacion) {
            return Promise.resolve();
        }

        if (compatibilidadManual && evaluacionCompatibilidadHecha()) {
            return Promise.resolve();
        }

        if (compatibilidadManual) {
            mostrarEvaluacionPendiente();
        }

        if (botonCompat) {
            botonCompat.disabled = true;
        }

        if (compatibilidadManual) {
            avisoEsperandoApi(mensaje);
        }

        return apiRequest('compatibilidad', {
            parametros: { id_solicitud: idSolicitud }
        }).then(function (respuesta) {
            var datos = datosCompatibilidadDeRespuesta(respuesta);
            var evaluacion = datos.compatibilidad || datos;

            if (!evaluacionCompatibilidadValida(evaluacion)) {
                mostrarEvaluacionPendiente();

                if (avisoEval) {
                    avisoEval.textContent = mensajeDeApi(respuesta);
                }

                mostrarAviso(respuesta, false);
                return null;
            }

            mostrarEvaluacionConDatos(evaluacion, datos);

            if (avisoEval) {
                avisoEval.textContent = '';
            }

            mostrarAviso(null);
            return evaluacion;
        }).catch(function (error) {
            mostrarEvaluacionPendiente();

            if (avisoEval) {
                avisoEval.textContent = mensajeDeApi(error);
            }

            mostrarAviso(error, false);
            return null;
        }).then(function (resultado) {
            if (botonCompat) {
                botonCompat.disabled = false;
            }

            return resultado;
        });
    }

    function cargarEstados(idActual) {
        if (!botones) {
            return Promise.resolve();
        }

        return apiRequest('estados_solicitudes').then(function (respuesta) {
            var datos = (respuesta && respuesta.data) || [];
            var modelo = document.getElementById('boton-modelo-estado');

            if (!Array.isArray(datos)) {
                datos = [datos];
            }

            while (botones.firstChild) {
                botones.removeChild(botones.firstChild);
            }

            if (!modelo) {
                return;
            }

            datos.forEach(function (estado) {
                var boton;
                var nombre = texto(estado && estado.nombre_estado);
                var clave = normalizarTipo(nombre);

                if (!estado || texto(estado.id) === texto(idActual)) {
                    return;
                }

                if (botones.hasAttribute('data-resolver') && clave.indexOf('aprob') === -1 && clave.indexOf('rechaz') === -1) {
                    return;
                }

                boton = modelo.cloneNode(false);
                boton.removeAttribute('id');
                boton.hidden = false;
                boton.setAttribute('data-id-estado', texto(estado.id));
                boton.setAttribute('data-nombre-estado', nombre);
                boton.textContent = nombre;

                if (botones.hasAttribute('data-resolver')) {
                    boton.classList.add('btn-estado-solicitud');

                    if (modelo.hasAttribute('data-confirmar-estado')) {
                        boton.setAttribute('data-confirmar-estado', '1');
                    }

                    estiloBotonResolucion(boton, !compatibilidadLista);
                } else if (clave.indexOf('rechaz') !== -1) {
                    boton.className = 'btn btn-outline';
                } else if (clave.indexOf('aprob') !== -1) {
                    boton.className = 'btn btn-primary';
                }

                botones.appendChild(boton);
            });

            if (botones.hasAttribute('data-resolver')) {
                actualizarVistaResolucion();
            }
        });
    }

    if (!id) {
        if (listado) {
            window.location.href = listado;
        }

        return;
    }

    var botonCompatibilidad = document.getElementById('boton-compatibilidad');

    if (botonCompatibilidad) {
        botonCompatibilidad.addEventListener('click', function () {
            if (!id || evaluacionCompatibilidadHecha()) {
                return;
            }

            traerEvaluacion(id);
        });
    }

    function enviarCambioEstado(idEstado) {
        if (!idEstado || (compatibilidadManual && !compatibilidadLista)) {
            return;
        }

        avisoEsperandoApi(mensaje);

        apiRequest('solicitudes', {
            metodo: 'PUT',
            parametros: { id: id },
            cuerpo: { id_estado_solicitud: idEstado }
        }).then(function (respuesta) {
            return mostrar(id).then(function (solicitud) {
                mostrarAviso(respuesta, true);

                if (solicitud) {
                    return cargarEstados(solicitud.id_estado_solicitud);
                }

                return null;
            });
        }).catch(function (error) {
            mostrarAviso(error);
        });
    }

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

            if (!boton || boton.disabled || (compatibilidadManual && !compatibilidadLista)) {
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

    if (compatibilidadManual) {
        mostrarEvaluacionPendiente();
    }

    mostrar(id).then(function (solicitud) {
        if (solicitud) {
            return cargarEstados(solicitud.id_estado_solicitud);
        }

        if (mensaje && texto(mensaje.textContent).trim() !== '') {
            return null;
        }

        if (listado) {
            window.location.href = listado;
        }

        return null;
    }).catch(function (error) {
        mostrarAviso(error, false);

        if (listado && error && error.status === 404 && (!mensaje || texto(mensaje.textContent).trim() === '')) {
            window.location.href = listado;
        }
    });
});
