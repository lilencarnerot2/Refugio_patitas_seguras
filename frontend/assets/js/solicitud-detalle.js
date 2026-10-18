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
    var vistaHref = panel.getAttribute('data-vista-href') || '';
    var compatibilidadLista = false;
    var solicitudActual = null;

    function normalizarTipo(valor) {
        return texto(valor).toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    }

    var evaluacionManual = panel.hasAttribute('data-evaluacion-manual');
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

        if (!botonCompat || !evaluacionManual) {
            return;
        }

        var hecha = evaluacionCompatibilidadHecha();

        botonCompat.disabled = hecha;
        botonCompat.classList.toggle('evaluacion-compatibilidad-hecha', hecha);

        if (hecha) {
            botonCompat.setAttribute('aria-disabled', 'true');
        } else {
            botonCompat.removeAttribute('aria-disabled');
        }
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
        var etiquetaSi = panel.getAttribute('data-etiqueta-si') || panel.getAttribute('data-si');
        var etiquetaNo = panel.getAttribute('data-etiqueta-no') || panel.getAttribute('data-no');

        if (valor === true || valor === 1 || valor === '1' || valor === 't' || valor === 'true') {
            return etiquetaSi || '';
        }

        if (valor === false || valor === 0 || valor === '0' || valor === 'f' || valor === 'false') {
            return etiquetaNo || '';
        }

        return texto(valor);
    }

    function tipoDetallePagina() {
        return normalizarTipo(panel.getAttribute('data-tipo') || '');
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

        if (fichaPostulante) {
            pintarCaja(
                fichaPostulante.querySelector('[data-panel-postulante="personal"]'),
                datosPersonalesPostulante({}, solicitud)
            );
        }

        if (fichaMascota) {
            pintarMascotaDesdeSolicitud(solicitud);
        }

        actualizarLinkSeguimientos();
    }

    function datosMascotaDesdeSolicitud(solicitud) {
        if (!solicitud) {
            return null;
        }

        return {
            id: solicitud.id_mascota,
            nombre: solicitud.nombre_mascota || ''
        };
    }

    function pintarMascotaDesdeSolicitud(solicitud) {
        var datos = datosMascotaDesdeSolicitud(solicitud);

        if (!fichaMascota || !datos || texto(datos.nombre) === '') {
            return;
        }

        pintarCaja(fichaMascota, datos);
    }

    function estadoPermiteSeguimiento(solicitud) {
        if (typeof estadoSolicitudPermiteSeguimiento === 'function') {
            return estadoSolicitudPermiteSeguimiento(solicitud && solicitud.estado_solicitud);
        }

        return normalizarTipo(solicitud && solicitud.estado_solicitud).indexOf('aprob') !== -1;
    }

    function actualizarLinkSeguimientos() {
        var linkSeg = document.getElementById('link-seguimientos-solicitud');
        var hrefSeg = typeof hrefSeguimientosPorTipoDetalle === 'function'
            ? hrefSeguimientosPorTipoDetalle(tipoDetallePagina())
            : '';
        var solicitud = solicitudActual;

        if (!linkSeg) {
            return;
        }

        linkSeg.hidden = true;
        linkSeg.removeAttribute('href');

        if (!hrefSeg || !solicitud || texto(solicitud.id) === '') {
            return;
        }

        if (!estadoPermiteSeguimiento(solicitud) || !solicitud.id_mascota) {
            return;
        }

        linkSeg.href = hrefSeg + '?id_solicitud=' + encodeURIComponent(String(solicitud.id));
        linkSeg.hidden = false;
    }

    function limpiarAvisoSeccion(idCaja) {
        var caja = document.getElementById(idCaja);

        if (caja) {
            caja.textContent = '';
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
        var avisoFormulario = document.getElementById('aviso-formulario');

        function vacio(origen, ok) {
            var fichaForm = document.getElementById('ficha-formulario');

            pintarFormulario({});

            if (fichaForm) {
                if (ok === false) {
                    fichaForm.setAttribute('data-formulario-vacio', '1');
                } else {
                    fichaForm.removeAttribute('data-formulario-vacio');
                }
            }

            if (!avisoFormulario) {
                return;
            }

            if (typeof avisoDeApi === 'function') {
                avisoDeApi(avisoFormulario, origen === undefined ? '' : origen, ok !== false);
            } else {
                limpiarAvisoSeccion('aviso-formulario');
            }
        }

        if (typeof apiFormularioSolicitudDetalleAdmin !== 'function') {
            vacio('', true);
            return Promise.resolve(null);
        }

        return apiFormularioSolicitudDetalleAdmin(tipoDetallePagina(), idSolicitud).then(function (respuesta) {
            var idPostulante = solicitudActual && solicitudActual.id_usuario;
            var datos = datosFormularioDeRespuesta(respuesta, {
                id_solicitud: idSolicitud,
                id_usuario: idPostulante,
                omitirValidacionUsuario: true
            });

            if (!datos) {
                vacio(respuesta && respuesta.success === false ? respuesta : {
                    success: false,
                    message: (respuesta && respuesta.message) || 'No se pudo cargar el formulario de la solicitud.'
                }, false);
                return;
            }

            vacio('', true);
            pintarFormulario(datos);
        }).catch(function (error) {
            var datosError = error && error.data;
            var tieneMsg = datosError && (datosError.message || datosError.mensaje);
            var aviso = error;

            if (error && error.status === 404 && !tieneMsg) {
                aviso = { success: false, message: 'Esta solicitud no tiene formulario guardado todavía.', status: 404 };
            } else if (error && (error.status === 500 || error.status >= 502) && !tieneMsg) {
                aviso = {
                    success: false,
                    message: 'No se pudo cargar el formulario desde el servidor. Probá recargar en unos minutos.',
                    status: error.status
                };
            }

            vacio(aviso, false);
        });
    }

    function pintarFormulario(datos) {
        var caja = document.getElementById('campos-formulario');
        var fichaForm = document.getElementById('ficha-formulario');
        var grupoMetros = document.getElementById('grupo-metros-patio');
        var grupoMascotas = document.getElementById('grupo-mascotas-hogar');
        var listaMascotas = document.getElementById('lista-mascotas-hogar');
        var modeloMascota = document.getElementById('modelo-mascota-hogar');

        if (!caja) {
            return;
        }

        if (fichaForm) {
            fichaForm.removeAttribute('data-formulario-vacio');
        }

        pintarCaja(caja, datos);

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
                pintarCaja(fila, mascota);
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
        if (typeof apiSolicitudDetalleAdmin !== 'function') {
            mostrarAviso({ success: false, message: 'No se pudo cargar la solicitud.' }, false);
            return Promise.resolve(null);
        }

        return apiSolicitudDetalleAdmin(idSolicitud).then(function (respuesta) {
            var solicitud = (respuesta && respuesta.data) || {};

            if (Array.isArray(solicitud)) {
                solicitud = solicitud[0] || {};
            }

            if (!solicitud.id) {
                mostrarAviso(respuesta, false);
                return null;
            }

            if (vistaHref) {
                var destino = enlaceDetalleSolicitud(solicitud);

                if (destino && nombreArchivoRutaSolicitud(destino) !== nombreArchivoRutaSolicitud(vistaHref)) {
                    window.location.href = destino + '?id=' + encodeURIComponent(String(idSolicitud));
                    return null;
                }
            }

            solicitudActual = solicitud;
            pintarFicha(solicitud);

            var tareas = [
                traerFormulario(String(solicitud.id)),
                traerMascota(solicitud.id_mascota),
                traerPostulante(solicitud, solicitud.id_usuario)
            ];

            if (fichaEvaluacion && !evaluacionManual) {
                tareas.push(traerEvaluacion(String(solicitud.id)));
            } else if (fichaEvaluacion && evaluacionManual) {
                actualizarBotonCompatibilidad();
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

    function datosPersonalesPostulante(usuario, solicitud) {
        return {
            nombre: texto(usuario && usuario.nombre) || texto(solicitud && solicitud.nombre_usuario),
            apellido: texto(usuario && usuario.apellido) || texto(solicitud && solicitud.apellido_usuario),
            fecha_nacimiento: usuario && usuario.fecha_nacimiento
        };
    }

    function marcarSeccionPostulanteVacia(seccion, vacia) {
        if (seccion) {
            seccion.classList.toggle('postulante-seccion-vacia', !!vacia);
        }
    }

    function traerPostulante(solicitud, idUsuario) {
        if (!fichaPostulante) {
            return Promise.resolve();
        }

        var panelPersonal = fichaPostulante.querySelector('[data-panel-postulante="personal"]');
        var panelDomicilio = document.getElementById('seccion-domicilio-postulante');
        var seccionContacto = document.getElementById('seccion-contacto-postulante');
        var consultaPerfilRemoto = fichaPostulante.hasAttribute('data-postulante-perfil-remoto');
        var idConsulta = idUsuario !== undefined && idUsuario !== null && String(idUsuario).trim() !== ''
            ? String(idUsuario).trim()
            : '';
        pintarCaja(panelPersonal, datosPersonalesPostulante({}, solicitud));
        pintarContactosPostulante([]);
        ocultarCamposVaciosPostulante();
        marcarSeccionPostulanteVacia(seccionContacto, true);
        marcarSeccionPostulanteVacia(panelDomicilio, true);

        if (!idConsulta) {
            return Promise.resolve();
        }

        return cargarPerfilUsuario(idConsulta, {
            incluirContactosDomicilio: consultaPerfilRemoto
        }).then(function (perfil) {
            var usuario = perfil.usuario || {};
            var contactos = perfil.contactos || [];
            var domicilio = perfil.domicilio || {};

            pintarCaja(panelPersonal, datosPersonalesPostulante(usuario, solicitud));

            if (panelDomicilio) {
                pintarCaja(panelDomicilio, domicilio);
            }

            pintarContactosPostulante(contactos);
            ocultarCamposVaciosPostulante();
            marcarSeccionPostulanteVacia(seccionContacto, contactos.length === 0);
            marcarSeccionPostulanteVacia(panelDomicilio, !domicilio || !Object.keys(domicilio).some(function (clave) {
                return texto(domicilio[clave]) !== '';
            }));
        }).catch(function (error) {
            if (error) {
                mostrarAviso(error, false);
            }
        });
    }

    function traerMascota(idMascota) {
        actualizarLinkSeguimientos();

        if (!fichaMascota || !idMascota) {
            return Promise.resolve();
        }

        if (typeof apiMascotaDetalleAdmin !== 'function') {
            return Promise.resolve();
        }

        return apiMascotaDetalleAdmin(idMascota).then(function (respuesta) {
            var mascota = datosDeRespuesta(respuesta);
            var foto = document.getElementById('detalle-foto');
            var vacia = document.getElementById('detalle-foto-vacia');
            actualizarLinkSeguimientos();

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
            pintarMascotaDesdeSolicitud(solicitudActual);
            actualizarLinkSeguimientos();
            return null;
        });
    }

    function traerEvaluacion(idSolicitud) {
        var avisoEval = document.getElementById('aviso-evaluacion');
        var botonCompat = document.getElementById('boton-compatibilidad');

        if (!fichaEvaluacion) {
            return Promise.resolve();
        }

        if (evaluacionCompatibilidadHecha()) {
            actualizarBotonCompatibilidad();
            return Promise.resolve();
        }

        if (botonCompat) {
            botonCompat.disabled = true;
        }

        if (avisoEval) {
            avisoEsperandoApi(avisoEval);
        }

        if (typeof apiCompatibilidadDetalleAdmin !== 'function') {
            mostrarEvaluacionPendiente();

            if (avisoEval) {
                avisoDeApi(avisoEval, '', true);
            }

            return Promise.resolve(null);
        }

        var tipoCompat = solicitudActual && solicitudActual.tipo_solicitud
            ? (typeof esTipoSolicitudTransito === 'function' && esTipoSolicitudTransito(solicitudActual.tipo_solicitud) ? 'transito' : 'adopcion')
            : tipoDetallePagina();

        return apiCompatibilidadDetalleAdmin(tipoCompat, idSolicitud).then(function (respuesta) {
            var datos = datosCompatibilidadDeRespuesta(respuesta);
            var evaluacion = datos.compatibilidad || datos;

            if (!evaluacionCompatibilidadValida(evaluacion)) {
                mostrarEvaluacionPendiente();

                if (avisoEval) {
                    avisoDeApi(avisoEval, respuesta, false);
                }

                return null;
            }

            mostrarEvaluacionConDatos(evaluacion, datos);

            if (avisoEval) {
                avisoDeApi(avisoEval, '', true);
            }

            return evaluacion;
        }).catch(function (error) {
            mostrarEvaluacionPendiente();

            if (avisoEval) {
                if (evaluacionManual && error && error.status === 404) {
                    avisoDeApi(avisoEval, '', true);
                } else {
                    avisoDeApi(avisoEval, error, false);
                }
            }

            return null;
        }).then(function (resultado) {
            if (avisoEval) {
                avisoEval.removeAttribute('aria-busy');
                avisoEval.removeAttribute('data-estado');
            }

            actualizarBotonCompatibilidad();

            if (botonCompat && !evaluacionCompatibilidadHecha()) {
                botonCompat.disabled = false;
            }

            return resultado;
        });
    }

    function plantillaBotonEstado(clave) {
        if (clave.indexOf('rechaz') !== -1) {
            return document.getElementById('boton-modelo-estado-rechaz') || document.getElementById('boton-modelo-estado');
        }

        if (clave.indexOf('aprob') !== -1) {
            return document.getElementById('boton-modelo-estado-aprob') || document.getElementById('boton-modelo-estado');
        }

        return document.getElementById('boton-modelo-estado');
    }

    function cargarEstados(idActual) {
        if (!botones) {
            return Promise.resolve();
        }

        if (typeof apiEstadosSolicitudDetalleAdmin !== 'function') {
            return Promise.resolve();
        }

        return apiEstadosSolicitudDetalleAdmin().then(function (respuesta) {
            var datos = (respuesta && respuesta.data) || [];
            var plantilla;

            if (!Array.isArray(datos)) {
                datos = [datos];
            }

            while (botones.firstChild) {
                botones.removeChild(botones.firstChild);
            }

            datos.forEach(function (estado) {
                var boton;
                var nombre = texto(estado && estado.nombre_estado);
                var clave = normalizarTipo(nombre);

                if (!estado || texto(estado.id) === texto(idActual)) {
                    return;
                }

                if (botones.hasAttribute('data-resolver')
                    && clave.indexOf('aprob') === -1
                    && clave.indexOf('rechaz') === -1
                    && clave.indexOf('finaliz') === -1) {
                    return;
                }

                plantilla = plantillaBotonEstado(clave);

                if (!plantilla) {
                    return;
                }

                boton = plantilla.cloneNode(false);
                boton.removeAttribute('id');
                boton.hidden = false;
                boton.removeAttribute('disabled');
                boton.setAttribute('data-id-estado', texto(estado.id));
                boton.setAttribute('data-nombre-estado', nombre);
                boton.textContent = nombre;

                if (botones.hasAttribute('data-resolver')) {
                    boton.classList.add('btn-estado-solicitud');

                    if (plantilla.hasAttribute('data-confirmar-estado')) {
                        boton.setAttribute('data-confirmar-estado', '1');
                    }

                    estiloBotonResolucion(boton, !compatibilidadLista);
                }

                botones.appendChild(boton);
            });

            if (botones.hasAttribute('data-resolver')) {
                actualizarVistaResolucion();
            }
        }).catch(function (error) {
            mostrarAviso(error, false);
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

        if (typeof apiActualizarEstadoSolicitudDetalleAdmin !== 'function') {
            mostrarAviso({ success: false, message: 'No se pudo actualizar el estado.' }, false);
            return;
        }

        apiActualizarEstadoSolicitudDetalleAdmin(id, idEstado).then(function (respuesta) {
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

    function tiposSolicitudDe(respuesta) {
        var datos = respuesta && respuesta.data;

        if (Array.isArray(datos)) {
            return datos;
        }

        return datos ? [datos] : [];
    }

    var cadenaInicio = typeof apiTiposSolicitudDetalleAdmin === 'function'
        ? apiTiposSolicitudDetalleAdmin().then(function (respuestaTipos) {
            enlazarModelosDetalleSolicitud(tiposSolicitudDe(respuestaTipos));
        }).catch(function () {
            return null;
        })
        : Promise.resolve();

    cadenaInicio.then(function () {
        return mostrar(id);
    }).then(function (solicitud) {
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
