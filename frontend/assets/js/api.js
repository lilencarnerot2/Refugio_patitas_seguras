// Todo sale del backend (APP_CONFIG lo arma el servidor con variables de entorno).
var API_URL = (window.APP_CONFIG && window.APP_CONFIG.backendUrl) || '';
var INICIO_URL = (window.APP_CONFIG && window.APP_CONFIG.inicioUrl) || '';
var PANEL_URL = (window.APP_CONFIG && window.APP_CONFIG.panelUrl) || '';

function apiBaseUrl() {
  var base = (window.APP_CONFIG && window.APP_CONFIG.backendUrl) || API_URL || '';

  return String(base).trim();
}

function nombreRecursoApi(recurso) {
  var nombre = String(recurso === undefined || recurso === null ? '' : recurso).trim();

  if (!nombre || !/^[a-z0-9_-]+$/i.test(nombre)) {
    return '';
  }

  return nombre;
}

function urlSolicitudApi(recurso, parametros) {
  var base = apiBaseUrl();
  var nombre = nombreRecursoApi(recurso);
  var url;
  var separador;
  var clave;
  var val;

  if (!base || !nombre) {
    return '';
  }

  base = base.split('#')[0].split('?')[0];

  if (/^https?:\/\//i.test(base)) {
    url = base + '?recurso=' + encodeURIComponent(nombre);
  } else {
    url = base.replace(/\/?$/, '') + '?recurso=' + encodeURIComponent(nombre);

    if (url.charAt(0) !== '/') {
      url = '/' + url.replace(/^\/+/, '');
    }

    try {
      url = new URL(url, window.location.origin).toString().split('#')[0];
    } catch (e) {
      return '';
    }
  }

  Object.keys(parametros || {}).forEach(function (key) {
    clave = String(key || '').trim();

    if (!clave) {
      return;
    }

    val = parametros[key];

    if (val !== undefined && val !== null && val !== '') {
      separador = url.indexOf('?') >= 0 ? '&' : '?';
      url += separador + encodeURIComponent(clave) + '=' + encodeURIComponent(val);
    }
  });

  return url;
}

function urlFotoMascota(valor) {
  var ruta = String(valor === undefined || valor === null ? '' : valor).trim();
  var base = apiBaseUrl().split('#')[0].split('?')[0];

  if (ruta === '' || !base || ruta.charAt(0) !== '/' || ruta.charAt(1) === '/') {
    return ruta;
  }

  return base + '?archivo=' + encodeURIComponent(ruta);
}

function apiRequest(recurso, opciones) {
  var url;
  var opts = opciones || {};
  var config;

  if (!apiBaseUrl()) {
    return Promise.reject(new Error(''));
  }

  url = urlSolicitudApi(recurso, opts.parametros || {});

  if (!url) {
    return Promise.reject(new Error(''));
  }

  config = {
    method: opts.metodo || 'GET',
    headers: {},
    credentials: opts.credentials || 'include'
  };

  if (opts.cuerpo !== undefined && typeof FormData !== 'undefined' && opts.cuerpo instanceof FormData) {
    config.body = opts.cuerpo;
  } else if (opts.cuerpo !== undefined) {
    config.headers['Content-Type'] = 'application/json';
    config.body = JSON.stringify(opts.cuerpo);
  }

  return fetch(url, config)
    .then(function (response) {
      return response.json().catch(function () {
        return null;
      }).then(function (data) {
        if (!response.ok || (data && data.success === false)) {
          var textoError = mensajeDeApi(data);

          var error = new Error(textoError);
          error.status = response.status;
          error.data = data;
          throw error;
        }

        return data;
      });
    });
}

function guardarCookie(nombre, valor) {
  document.cookie = nombre + '=' + encodeURIComponent(valor || '') + '; path=/; SameSite=Lax';
}

function borrarCookie(nombre) {
  document.cookie = nombre + '=; path=/; SameSite=Lax; max-age=0';
}

function valorCookie(nombre) {
  var partes = document.cookie.split(';');
  var clave = nombre + '=';
  var i;
  var parte;

  for (i = 0; i < partes.length; i++) {
    parte = partes[i].trim();

    if (parte.indexOf(clave) === 0) {
      return decodeURIComponent(parte.slice(clave.length));
    }
  }

  return '';
}

function guardarSesion(usuario) {
  usuario = usuario || {};
  guardarCookie('usuario_id', usuario.id);
  guardarCookie('usuario_nombre', usuario.nombre);
  guardarCookie('usuario_apellido', usuario.apellido);
  guardarCookie('usuario_email', usuario.email);
  guardarCookie('usuario_rol', usuario.id_rol);
}

function borrarSesion() {
  borrarCookie('usuario_id');
  borrarCookie('usuario_nombre');
  borrarCookie('usuario_apellido');
  borrarCookie('usuario_email');
  borrarCookie('usuario_rol');
  borrarCookie('usuario_acceso_admin');
  borrarCookie('usuario_acceso_superadmin');
}


function accesoDeApi(recurso, parametros) {
  return apiRequest(recurso, { parametros: parametros || {} }).then(function () {
    return true;
  }, function () {
    return false;
  });
}

function irTrasSesion(origen) {
  var data = (origen && origen.data) || {};
  var usuario = data.usuario || {};
  var parametros = usuario.id ? { id: usuario.id } : {};

  guardarSesion(usuario);

  return accesoDeApi('usuarios', parametros).then(function (esAdmin) {
    if (esAdmin) {
      guardarCookie('usuario_acceso_admin', '1');
    }

    return accesoDeApi('roles').then(function (esSuper) {
      if (esSuper) {
        guardarCookie('usuario_acceso_superadmin', '1');
        guardarCookie('usuario_acceso_admin', '1');
      }

      if (INICIO_URL) {
        window.location.href = INICIO_URL;
      }
    });
  });
}

function formatear_fecha(fecha) {
  var texto;
  var partes;

  if (fecha === undefined || fecha === null || fecha === '') {
    return '';
  }

  texto = String(fecha).trim().replace('T', ' ').split(/\s+/)[0];

  if (texto.indexOf('/') !== -1) {
    return texto;
  }

  partes = texto.split('-');

  if (partes.length !== 3) {
    return texto;
  }

  return partes[2] + '/' + partes[1] + '/' + partes[0];
}

function fechaCorta(valor) {
  return formatear_fecha(valor);
}

function fechaHoraLista(valor) {
  var bruto = (valor === undefined || valor === null) ? '' : String(valor).trim();
  var partes;
  var fechaParte;
  var horaParte;
  var d;
  var h;

  if (!bruto) {
    return '';
  }

  bruto = bruto.replace('T', ' ');
  partes = bruto.split(/\s+/);
  fechaParte = partes[0] || '';
  horaParte = partes[1] || '';

  if (fechaParte.indexOf('-') !== -1) {
    d = fechaParte.split('-');

    if (d.length === 3) {
      fechaParte = d[2] + '/' + d[1] + '/' + d[0];
    }
  }

  if (horaParte.indexOf(':') !== -1) {
    h = horaParte.split(':');

    if (h.length >= 2) {
      horaParte = h[0] + ':' + h[1];
    }
  }

  if (fechaParte && horaParte) {
    return fechaParte + ' ' + horaParte;
  }

  return fechaParte || horaParte;
}

var barrioSinDato = 'Sin barrio';

function fechaDeHoyIso() {
  var hoy = new Date();

  hoy.setMinutes(hoy.getMinutes() - hoy.getTimezoneOffset());

  return hoy.toISOString().slice(0, 10);
}

function reservarEspacioCalendario(campo, altoMinimo) {
  var rect;
  var falta;

  if (!campo) {
    return;
  }

  altoMinimo = altoMinimo || 300;
  rect = campo.getBoundingClientRect();
  falta = altoMinimo - (window.innerHeight - rect.bottom);

  if (falta > 0) {
    window.scrollBy({ top: falta, behavior: 'smooth' });
  }
}

function textoDeItem(item) {
  if (item === undefined || item === null) {
    return '';
  }

  if (typeof item !== 'object') {
    return String(item);
  }

  var claves = Object.keys(item);
  var i;
  var clave;
  var valor;

  for (i = 0; i < claves.length; i++) {
    clave = claves[i];

    if (clave === 'id' || clave === 'estado' || clave.indexOf('id_') === 0) {
      continue;
    }

    valor = item[clave];

    if (valor !== undefined && valor !== null && valor !== '' && typeof valor !== 'object') {
      return String(valor);
    }
  }

  if (item.id !== undefined && item.id !== null) {
    return String(item.id);
  }

  return '';
}

function esMensajeApiTecnico(texto) {
  var limpio = String(texto || '').trim();

  if (!limpio) {
    return false;
  }

  return (
    /SQLSTATE\[[\w]+\]/i.test(limpio)
    || /\bERROR:\s+/i.test(limpio)
    || /\bLINE\s+\d+:/i.test(limpio)
    || /\bUndefined column:/i.test(limpio)
  );
}

function textoMensajeApiPublico(texto) {
  var limpio = String(texto || '').trim();

  if (!limpio || esMensajeApiTecnico(limpio)) {
    return '';
  }

  return limpio;
}

function textoMensajeDeCuerpo(cuerpo) {
  var valor;

  if (!cuerpo || typeof cuerpo !== 'object') {
    return '';
  }

  if (cuerpo.message !== undefined && cuerpo.message !== null && cuerpo.message !== '') {
    return textoMensajeApiPublico(cuerpo.message);
  }

  if (cuerpo.mensaje !== undefined && cuerpo.mensaje !== null && cuerpo.mensaje !== '') {
    return textoMensajeApiPublico(cuerpo.mensaje);
  }

  if (typeof cuerpo.error === 'string' && cuerpo.error !== '') {
    return textoMensajeApiPublico(cuerpo.error);
  }

  if (cuerpo.error && typeof cuerpo.error === 'object') {
    valor = textoMensajeDeCuerpo(cuerpo.error);

    if (valor) {
      return valor;
    }
  }

  if (Array.isArray(cuerpo.errors) && cuerpo.errors.length) {
    valor = cuerpo.errors.map(function (item) {
      return typeof item === 'string' ? item : textoMensajeDeCuerpo(item);
    }).filter(Boolean).join(' ');

    if (valor) {
      return valor;
    }
  }

  return '';
}

function mensajeDeApi(origen) {
  var cuerpo;
  var texto;

  if (!origen) {
    return '';
  }

  if (typeof origen === 'string') {
    return textoMensajeApiPublico(origen);
  }

  if (origen instanceof Error) {
    if (origen.data) {
      texto = mensajeDeApi(origen.data);

      if (texto) {
        return texto;
      }
    }

    if (origen.message) {
      return textoMensajeApiPublico(origen.message);
    }

    return '';
  }

  texto = textoMensajeDeCuerpo(origen);

  if (texto) {
    return texto;
  }

  if (origen.success === false) {
    return '';
  }

  if (origen.data !== undefined && origen.data !== null) {
    cuerpo = origen.data;

    if (typeof cuerpo === 'string' && cuerpo !== '') {
      return textoMensajeApiPublico(cuerpo);
    }

    if (cuerpo && typeof cuerpo === 'object' && (cuerpo.message || cuerpo.mensaje || cuerpo.error || cuerpo.errors)) {
      texto = textoMensajeDeCuerpo(cuerpo);

      if (texto) {
        return texto;
      }
    }
  }

  return '';
}

function despuesDePintarAviso(tarea) {
  if (typeof tarea !== 'function') {
    return;
  }

  setTimeout(tarea, 0);
}

function avisoEsperandoApi(caja, textoEspera) {
  if (!caja) {
    return;
  }

  caja.setAttribute('data-estado', 'pendiente');
  caja.setAttribute('aria-busy', 'true');

  if (textoEspera) {
    caja.textContent = textoEspera;
    return;
  }

  caja.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
}

function marcarIntentoEnvio(formulario) {
  var intento;

  if (!formulario) {
    return;
  }

  intento = formulario.querySelector('.form-intento');

  if (intento) {
    intento.checked = true;
  }

  formulario.setAttribute('data-intento-envio', '1');
}

function formatosValidos(formulario, caja) {
  var invalidos = [];
  var avisos = [];

  avisoDeApi(caja, '', true, formulario);

  formulario.querySelectorAll('input[pattern]').forEach(function (campo) {
    if (campo.disabled || campo.value === '') {
      return;
    }

    if (campo.validity.patternMismatch) {
      invalidos.push(campo);

      if (campo.title && avisos.indexOf(campo.title) === -1) {
        avisos.push(campo.title);
      }
    }
  });

  if (!invalidos.length) {
    return true;
  }

  marcarIntentoEnvio(formulario);

  invalidos.forEach(function (campo) {
    campo.setAttribute('data-campo-estado', 'error');
  });

  avisoDeApi(caja, avisos.join(' '), false);

  return false;
}

function limpiarMarcasCampos(formulario) {
  if (!formulario) {
    return;
  }

  formulario.querySelectorAll('[data-campo-estado]').forEach(function (nodo) {
    nodo.removeAttribute('data-campo-estado');
  });
}

function cuerpoDeErrorApi(origen) {
  if (origen instanceof Error && origen.data) {
    return origen.data;
  }

  if (origen && origen.data !== undefined) {
    return origen.data;
  }

  return origen;
}

function agregarNombreCampo(lista, nombre) {
  if (nombre === undefined || nombre === null || nombre === '') {
    return;
  }

  if (lista.indexOf(String(nombre)) === -1) {
    lista.push(String(nombre));
  }
}

function nombresCamposEnError(origen) {
  var data = cuerpoDeErrorApi(origen);
  var lista = [];
  var clave;
  var i;
  var item;
  var nombre;

  if (!data || typeof data !== 'object') {
    return lista;
  }

  if (Array.isArray(data.campos)) {
    for (i = 0; i < data.campos.length; i++) {
      agregarNombreCampo(lista, data.campos[i]);
    }
  }

  if (data.campos && typeof data.campos === 'object' && !Array.isArray(data.campos)) {
    for (clave in data.campos) {
      if (Object.prototype.hasOwnProperty.call(data.campos, clave)) {
        agregarNombreCampo(lista, clave);
      }
    }
  }

  if (Array.isArray(data.errors)) {
    for (i = 0; i < data.errors.length; i++) {
      item = data.errors[i];

      if (typeof item === 'string') {
        continue;
      }

      if (item && typeof item === 'object') {
        nombre = item.campo || item.field || item.name || item.clave;

        agregarNombreCampo(lista, nombre);
      }
    }
  }

  return lista;
}

function marcarCamposDeError(formulario, origen) {
  var nombres = nombresCamposEnError(origen);
  var i;
  var nombre;
  var nodo;

  if (!formulario || !nombres.length) {
    return;
  }

  for (i = 0; i < nombres.length; i++) {
    nombre = nombres[i];
    nodo = formulario.elements[nombre];

    if (!nodo) {
      nodo = formulario.querySelector('[name="' + nombre + '"]');
    }

    if (nodo) {
      if (nodo.length && nodo[0]) {
        nodo[0].setAttribute('data-campo-estado', 'error');
      } else {
        nodo.setAttribute('data-campo-estado', 'error');
      }
    }
  }
}

function avisoDeApi(caja, origen, ok, formulario) {
  var codigo;
  var textoMsg;
  var esError;

  if (!caja && !formulario) {
    return;
  }

  if (origen === '') {
    if (caja) {
      caja.textContent = '';
      caja.removeAttribute('data-estado');
      caja.removeAttribute('data-codigo');
      caja.removeAttribute('aria-busy');
    }

    limpiarMarcasCampos(formulario);
    return;
  }

  if (caja) {
    caja.removeAttribute('aria-busy');
  }

  textoMsg = mensajeDeApi(origen);

  if (!textoMsg && origen instanceof Error && origen.data) {
    textoMsg = mensajeDeApi(origen.data);
  }

  if (!textoMsg && origen && origen.data && !(origen instanceof Error)) {
    textoMsg = mensajeDeApi(origen.data);
  }

  esError = (
    origen instanceof Error
    || ok === false
    || (origen && origen.success === false)
  );

  if (formulario) {
    if (esError) {
      marcarIntentoEnvio(formulario);
      marcarCamposDeError(formulario, origen);
    } else if (ok === true) {
      limpiarMarcasCampos(formulario);
    }
  }

  if (!caja) {
    return;
  }

  if (!textoMsg && ok === true) {
    caja.textContent = '';
    caja.removeAttribute('data-estado');
    caja.removeAttribute('data-codigo');
    caja.removeAttribute('aria-busy');
    return;
  }

  if (ok === true) {
    caja.setAttribute('data-estado', 'ok');
  } else if (esError) {
    caja.setAttribute('data-estado', 'error');
  } else if (!origen) {
    caja.removeAttribute('data-estado');
  } else {
    caja.removeAttribute('data-estado');
  }

  caja.textContent = textoMsg;
  codigo = origen && origen.status;

  if (codigo) {
    caja.setAttribute('data-codigo', codigo);
  } else {
    caja.removeAttribute('data-codigo');
  }

  if (textoMsg && esError && typeof caja.scrollIntoView === 'function') {
    despuesDePintarAviso(function () {
      caja.scrollIntoView({ block: 'nearest', behavior: 'auto' });
    });
  }
}

function enviarFormularioApi(formulario, caja, recurso, opciones) {
  if (formulario && formulario.getAttribute('data-enviando') === '1') {
    return Promise.reject(new Error(''));
  }

  if (formulario) {
    formulario.setAttribute('data-enviando', '1');
  }

  marcarIntentoEnvio(formulario);
  avisoDeApi(caja, '', true, formulario);

  return apiRequest(recurso, opciones || {}).then(function (respuesta) {
    avisoDeApi(caja, respuesta, true, formulario);
    return respuesta;
  }).catch(function (error) {
    avisoDeApi(caja, error, false, formulario);
    throw error;
  }).finally(function () {
    if (formulario) {
      formulario.removeAttribute('data-enviando');
    }
  });
}

function iconoEspecieMascota(especie) {
  var nombre = especie === undefined || especie === null ? '' : String(especie).trim().toLowerCase();

  if (nombre.indexOf('gato') !== -1) {
    return 'gato';
  }

  if (nombre.indexOf('perro') !== -1) {
    return 'perro';
  }

  return 'otro';
}

function pintarIconoEspecieMascota(contenedor, mascota) {
  var raiz = contenedor;

  if (!raiz || typeof raiz.querySelector !== 'function') {
    raiz = typeof contenedor === 'string' ? document.getElementById(contenedor) : null;
  }

  if (!raiz) {
    return;
  }

  var icono = raiz.querySelector('.ficha-icono-especie');

  if (!icono) {
    return;
  }

  var tipo = iconoEspecieMascota(mascota && mascota.especie);
  icono.setAttribute('data-especie-icono', tipo === 'otro' ? '' : tipo);
}

function datosCompatibilidadDeRespuesta(respuesta) {
  var datos = respuesta && respuesta.data !== undefined ? respuesta.data : respuesta;

  if (Array.isArray(datos)) {
    datos = datos[0] || {};
  }

  return datos || {};
}

function evaluacionCompatibilidadValida(evaluacion) {
  var puntaje;

  if (!evaluacion || typeof evaluacion !== 'object') {
    return false;
  }

  if (String(evaluacion.clasificacion || '').trim() !== '') {
    return true;
  }

  puntaje = evaluacion.puntaje;

  return puntaje !== undefined && puntaje !== null && String(puntaje).trim() !== '';
}

function puntajeCompatibilidadPct(evaluacion) {
  var raw = evaluacion && evaluacion.puntaje;
  var numero;

  if (raw === undefined || raw === null || String(raw).trim() === '') {
    return null;
  }

  numero = Number(raw);

  if (isNaN(numero)) {
    return null;
  }

  return Math.max(0, Math.min(100, numero));
}

function pintarCorazonCompatibilidad(nodo, puntaje) {
  var pct = puntaje === null || puntaje === undefined ? 0 : puntaje;

  if (!nodo) {
    return;
  }

  nodo.style.setProperty('--compat-fill', pct + '%');
  nodo.setAttribute('data-puntaje', String(pct));
  nodo.classList.remove('compatibilidad-corazon-animando');
  void nodo.offsetWidth;
  nodo.classList.add('compatibilidad-corazon-animando');
}

var mapaHrefDetallePorIdTipoSolicitud = {};

function normalizarClaveTipoSolicitud(valor) {
  return String(valor || '').trim().toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
}

function esTipoSolicitudTransito(valor) {
  var tipo = normalizarClaveTipoSolicitud(valor);

  return tipo.indexOf('transito') !== -1 || (tipo.indexOf('hogar') !== -1 && tipo.indexOf('trans') !== -1);
}

function panelConfigSolicitudAdmin() {
  return document.getElementById('panel-solicitudes')
    || document.getElementById('panel-solicitud-detalle')
    || document.getElementById('panel-seguimientos-admin')
    || null;
}

function configDetalleSolicitudAdmin() {
  var cfg = window.APP_CONFIG && window.APP_CONFIG.detalleSolicitud;

  return cfg && typeof cfg === 'object' ? cfg : null;
}

function recursoDetalleSolicitudAdmin(tipoDetalle, clave) {
  var cfg = configDetalleSolicitudAdmin();
  var comunes;
  var tipo;
  var bloque;

  if (!cfg || !clave) {
    return '';
  }

  comunes = cfg.comunes || {};

  if (clave === 'solicitud' || clave === 'mascotas' || clave === 'estadosSolicitud' || clave === 'tiposSolicitud' || clave === 'compatibilidad') {
    return nombreRecursoApi(comunes[clave] || '');
  }

  tipo = normalizarClaveTipoSolicitud(tipoDetalle);

  if (!tipo) {
    var panelTipo = panelConfigSolicitudAdmin();
    tipo = normalizarClaveTipoSolicitud(panelTipo && panelTipo.getAttribute('data-tipo'));
  }

  bloque = cfg[tipo] || {};
  var nombre = nombreRecursoApi(bloque[clave] || '');

  if (!nombre && clave === 'formulario') {
    var panelForm = panelConfigSolicitudAdmin();
    nombre = nombreRecursoApi(panelForm && panelForm.getAttribute('data-recurso-formulario') || '');
  }

  return nombre;
}

function apiSolicitudDetalleAdmin(idSolicitud) {
  var recurso = recursoDetalleSolicitudAdmin('', 'solicitud');

  if (!recurso) {
    return Promise.reject(new Error(''));
  }

  return apiRequest(recurso, {
    parametros: { id: idSolicitud }
  });
}

function apiFormularioSolicitudDetalleAdmin(tipoDetalle, idSolicitud) {
  var recurso = recursoDetalleSolicitudAdmin(tipoDetalle, 'formulario');

  if (!recurso) {
    return Promise.reject(new Error(''));
  }

  return apiRequest(recurso, {
    parametros: { id_solicitud: idSolicitud }
  });
}

function apiMascotaDetalleAdmin(idMascota) {
  var recurso = recursoDetalleSolicitudAdmin('', 'mascotas');

  if (!recurso) {
    return Promise.reject(new Error(''));
  }

  return apiRequest(recurso, {
    parametros: { id: idMascota }
  });
}

function apiCompatibilidadDetalleAdmin(tipoDetalle, idSolicitud) {
  var recurso = recursoDetalleSolicitudAdmin(tipoDetalle, 'compatibilidad');

  if (!recurso) {
    return Promise.reject(new Error(''));
  }

  return apiRequest(recurso, {
    parametros: { id_solicitud: idSolicitud }
  });
}

function apiEstadosSolicitudDetalleAdmin() {
  var recurso = recursoDetalleSolicitudAdmin('', 'estadosSolicitud');

  if (!recurso) {
    return Promise.reject(new Error(''));
  }

  return apiRequest(recurso);
}

function apiTiposSolicitudDetalleAdmin() {
  var recurso = recursoDetalleSolicitudAdmin('', 'tiposSolicitud');

  if (!recurso) {
    return Promise.reject(new Error(''));
  }

  return apiRequest(recurso);
}

function apiActualizarEstadoSolicitudDetalleAdmin(idSolicitud, idEstado) {
  var recurso = recursoDetalleSolicitudAdmin('', 'solicitud');

  if (!recurso) {
    return Promise.reject(new Error(''));
  }

  return apiRequest(recurso, {
    metodo: 'PUT',
    parametros: { id: idSolicitud },
    cuerpo: { id_estado_solicitud: idEstado }
  });
}

function vistasDetalleAdmin() {
  var cfg = configDetalleSolicitudAdmin();

  return cfg && cfg.vistas && typeof cfg.vistas === 'object' ? cfg.vistas : null;
}

function hrefDetallePorTransito(esTransito) {
  var vistas = vistasDetalleAdmin();
  var href = vistas ? (esTransito ? vistas.transito : vistas.adopcion) : '';

  return href ? String(href).replace(/\?.*$/, '') : '';
}

function hrefSeguimientosPorTransito(esTransito) {
  var vistas = vistasDetalleAdmin();
  var href = vistas ? (esTransito ? vistas.seguimientosTransito : vistas.seguimientosAdopcion) : '';

  return href ? String(href).replace(/\?.*$/, '') : '';
}

function hrefSeguimientosPorTipoDetalle(tipoDetalle) {
  var clave = normalizarClaveTipoSolicitud(tipoDetalle);

  if (clave === 'transito') {
    return hrefSeguimientosPorTransito(true);
  }

  if (clave === 'adopcion') {
    return hrefSeguimientosPorTransito(false);
  }

  return '';
}

function hrefDetallePorNombreTipo(nombre) {
  return hrefDetallePorTransito(esTipoSolicitudTransito(nombre));
}

function enlazarModelosDetalleSolicitud(tipos) {
  var lista = (Array.isArray(tipos) ? tipos : []).filter(function (item) {
    return !!item;
  });

  mapaHrefDetallePorIdTipoSolicitud = {};

  lista.forEach(function (tipo) {
    if (tipo.id !== undefined && tipo.id !== null) {
      mapaHrefDetallePorIdTipoSolicitud[String(tipo.id)] = hrefDetallePorNombreTipo(tipo.nombre_tipo);
    }
  });
}

function enlaceDetalleSolicitud(solicitud) {
  var panel = document.getElementById('panel-solicitudes');
  var fijo = panel && panel.getAttribute('data-detalle');
  var idTipo = solicitud && solicitud.id_tipo_solicitud;
  var href;
  var filaModelo;
  var enlace;

  if (fijo) {
    return String(fijo).replace(/\?.*$/, '');
  }

  if (idTipo !== undefined && idTipo !== null && String(idTipo) !== '') {
    href = mapaHrefDetallePorIdTipoSolicitud[String(idTipo)];

    if (href) {
      return href;
    }
  }

  if (solicitud && solicitud.tipo_solicitud) {
    return hrefDetallePorNombreTipo(solicitud.tipo_solicitud);
  }

  filaModelo = document.getElementById('fila-modelo-solicitud');
  enlace = filaModelo && filaModelo.querySelector('a[href]');

  if (enlace) {
    return enlace.getAttribute('href').replace(/\?.*$/, '');
  }

  return hrefDetallePorTransito(false);
}

function esValorVerdaderoApi(valor) {
  return valor === true || valor === 1 || valor === '1' || valor === 't' || valor === 'true';
}

function estadoSolicitudPermiteSeguimiento(nombreEstado) {
  var clave = normalizarClaveTipoSolicitud(nombreEstado);

  return clave.indexOf('aprob') !== -1;
}

function nombreArchivoRutaSolicitud(ruta) {
  var limpia = String(ruta || '').replace(/\?.*$/, '');

  return limpia.split('/').pop() || limpia;
}

function turnosVoluntariadoDe(respuesta) {
  var datos;
  var lista = [];
  var clave;
  var item;
  var turno;

  if (!respuesta || respuesta.data === undefined || respuesta.data === null) {
    return [];
  }

  datos = respuesta.data;

  if (Array.isArray(datos)) {
    return datos.filter(function (entry) {
      return !!entry;
    });
  }

  if (typeof datos === 'object') {
    if (Array.isArray(datos.turnos)) {
      return datos.turnos.filter(function (entry) {
        return !!entry;
      });
    }

    if (Array.isArray(datos.items)) {
      return datos.items.filter(function (entry) {
        return !!entry;
      });
    }

    for (clave in datos) {
      if (!Object.prototype.hasOwnProperty.call(datos, clave)) {
        continue;
      }

      if (clave === 'contacto' || clave === 'email' || clave === 'telefono' || clave === 'whatsapp' || clave === 'direccion' || clave === 'correo') {
        continue;
      }

      item = datos[clave];

      if (!item || typeof item !== 'object' || Array.isArray(item)) {
        continue;
      }

      turno = Object.assign({}, item);

      if (!turno.rango_horario) {
        turno.rango_horario = clave;
      }

      lista.push(turno);
    }

    if (lista.length) {
      return lista;
    }
  }

  return datos ? [datos] : [];
}

function claveTurnoVoluntariado(item) {
  if (!item || typeof item !== 'object') {
    return '';
  }

  if (item.rango_horario) {
    return String(item.rango_horario).trim();
  }

  if (item.id) {
    return String(item.id).trim();
  }

  return '';
}

function idUsuarioEnSesion() {
  return String(valorCookie('usuario_id') || '').trim();
}

function parametrosPerfilUsuario(idUsuario) {
  var id = String(
    idUsuario !== undefined && idUsuario !== null ? idUsuario : idUsuarioEnSesion()
  ).trim();

  if (!id) {
    return {};
  }

  return { id_usuario: id };
}

function idUsuarioEnDatosPerfil(datos) {
  var muestra;
  var i;

  if (!datos || typeof datos !== 'object') {
    return '';
  }

  if (!Array.isArray(datos) && datos.id_usuario !== undefined && datos.id_usuario !== null) {
    return String(datos.id_usuario);
  }

  if (Array.isArray(datos)) {
    for (i = 0; i < datos.length; i++) {
      muestra = datos[i];

      if (muestra && muestra.id_usuario !== undefined && muestra.id_usuario !== null) {
        return String(muestra.id_usuario);
      }
    }
  }

  return '';
}

function datosPerfilDeRespuesta(respuesta, idEsperado) {
  var datos;
  var idSesion;
  var idDueño;

  if (!respuesta || respuesta.success === false) {
    return null;
  }

  idEsperado = String(idEsperado || '').trim();

  if (idEsperado === '') {
    return null;
  }

  datos = respuesta.data;

  if (datos === undefined || datos === null) {
    return null;
  }

  idDueño = idUsuarioEnDatosPerfil(datos);

  if (idDueño !== '') {
    return String(idDueño) === idEsperado ? datos : null;
  }

  idSesion = idUsuarioEnSesion();

  if (idSesion !== '' && idEsperado === idSesion) {
    return datos;
  }

  return null;
}

function usuarioDeRespuestaPerfil(respuesta, idEsperado) {
  var datos = respuesta && respuesta.data !== undefined ? respuesta.data : respuesta;
  var usuario;

  if (Array.isArray(datos)) {
    usuario = datos[0] || {};
  } else {
    usuario = datos && typeof datos === 'object' ? datos : {};
  }

  idEsperado = String(idEsperado || '').trim();

  if (!usuario || String(usuario.id || '') === '') {
    return {};
  }

  if (idEsperado !== '' && String(usuario.id) !== idEsperado) {
    return {};
  }

  return usuario;
}

function listaContactosDePerfil(datos) {
  if (!datos) {
    return [];
  }

  if (Array.isArray(datos)) {
    return datos;
  }

  if (Array.isArray(datos.contactos)) {
    return datos.contactos;
  }

  if (Array.isArray(datos.items)) {
    return datos.items;
  }

  return [];
}

function objetoDomicilioDePerfil(datos) {
  if (!datos || Array.isArray(datos)) {
    return {};
  }

  if (datos.domicilio && typeof datos.domicilio === 'object') {
    return datos.domicilio;
  }

  return datos;
}

function perfilUsuarioEsRemoto(idUsuario) {
  var id = String(idUsuario || '').trim();
  var sesion = idUsuarioEnSesion();

  return id !== '' && sesion !== '' && id !== sesion;
}

function cargarPerfilUsuario(idUsuario, opciones) {
  var opts = opciones || {};
  var id = String(idUsuario || '').trim();
  var paramsPerfil = parametrosPerfilUsuario(id);
  var tareas;

  if (!id) {
    return Promise.resolve({
      usuario: {},
      contactos: [],
      domicilio: {}
    });
  }

  tareas = [
    apiRequest('usuarios', { parametros: { id: id } }).catch(function () {
      return null;
    })
  ];

  if (opts.incluirContactosDomicilio) {
    tareas.push(
      apiRequest('contactos', { parametros: paramsPerfil }).catch(function () {
        return null;
      }),
      apiRequest('domicilios', { parametros: paramsPerfil }).catch(function () {
        return null;
      })
    );
  }

  return Promise.all(tareas).then(function (respuestas) {
    var perfil = {
      usuario: usuarioDeRespuestaPerfil(respuestas[0], id),
      contactos: [],
      domicilio: {}
    };

    if (opts.incluirContactosDomicilio) {
      perfil.contactos = listaContactosDePerfil(datosPerfilDeRespuesta(respuestas[1], id));
      perfil.domicilio = objetoDomicilioDePerfil(datosPerfilDeRespuesta(respuestas[2], id));
    }

    return perfil;
  });
}

function datosFormularioDeRespuesta(respuesta, opciones) {
  var opts = opciones || {};
  var idSolicitud = String(opts.id_solicitud || '').trim();
  var idUsuario = String(opts.id_usuario || '').trim();
  var idSesion;
  var datos;
  var objeto;

  if (!respuesta || respuesta.success === false) {
    return null;
  }

  datos = respuesta.data;

  if (datos === undefined || datos === null) {
    return null;
  }

  objeto = Array.isArray(datos) ? datos[0] || null : datos;

  if (!objeto || typeof objeto !== 'object') {
    return null;
  }

  if (idSolicitud !== '' && !opts.omitirValidacionUsuario) {
    if (objeto.id_solicitud !== undefined && objeto.id_solicitud !== null) {
      if (String(objeto.id_solicitud) !== idSolicitud) {
        return null;
      }
    } else {
      idSesion = idUsuarioEnSesion();

      if (idUsuario === '' || idSesion === '' || idUsuario !== idSesion) {
        return null;
      }
    }
  }

  if (!opts.omitirValidacionUsuario && idUsuario !== '' && objeto.id_usuario !== undefined && objeto.id_usuario !== null) {
    if (String(objeto.id_usuario) !== idUsuario) {
      return null;
    }
  }

  return objeto;
}

if (typeof window !== 'undefined') {
  window.esTipoSolicitudTransito = esTipoSolicitudTransito;
  window.esValorVerdaderoApi = esValorVerdaderoApi;
  window.estadoSolicitudPermiteSeguimiento = estadoSolicitudPermiteSeguimiento;
  window.enlazarModelosDetalleSolicitud = enlazarModelosDetalleSolicitud;
  window.enlaceDetalleSolicitud = enlaceDetalleSolicitud;
  window.nombreArchivoRutaSolicitud = nombreArchivoRutaSolicitud;
  window.datosFormularioDeRespuesta = datosFormularioDeRespuesta;
  window.hrefSeguimientosPorTransito = hrefSeguimientosPorTransito;
  window.hrefSeguimientosPorTipoDetalle = hrefSeguimientosPorTipoDetalle;
  window.apiSolicitudDetalleAdmin = apiSolicitudDetalleAdmin;
  window.apiFormularioSolicitudDetalleAdmin = apiFormularioSolicitudDetalleAdmin;
  window.apiMascotaDetalleAdmin = apiMascotaDetalleAdmin;
  window.apiCompatibilidadDetalleAdmin = apiCompatibilidadDetalleAdmin;
  window.apiEstadosSolicitudDetalleAdmin = apiEstadosSolicitudDetalleAdmin;
  window.apiTiposSolicitudDetalleAdmin = apiTiposSolicitudDetalleAdmin;
  window.apiActualizarEstadoSolicitudDetalleAdmin = apiActualizarEstadoSolicitudDetalleAdmin;
}
