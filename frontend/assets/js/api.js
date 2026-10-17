// Todo sale del backend (APP_CONFIG lo arma el servidor con variables de entorno).
var API_URL = (window.APP_CONFIG && window.APP_CONFIG.backendUrl) || '';
var INICIO_URL = (window.APP_CONFIG && window.APP_CONFIG.inicioUrl) || '';
var PANEL_URL = (window.APP_CONFIG && window.APP_CONFIG.panelUrl) || '';

function apiRequest(recurso, opciones) {
  if (!API_URL) {
    return Promise.reject(new Error(''));
  }

  var url = API_URL;

  var params = new URLSearchParams();
  params.set('recurso', recurso);

  var opts = opciones || {};
  var extra = opts.parametros || {};
  Object.keys(extra).forEach(function (key) {
    var val = extra[key];
    if (val !== undefined && val !== null && val !== '') {
      params.set(key, val);
    }
  });

  var config = {
    method: opts.metodo || 'GET',
    headers: {},
    credentials: opts.credentials || 'include'
  };

  if (opts.cuerpo !== undefined) {
    config.headers['Content-Type'] = 'application/json';
    config.body = JSON.stringify(opts.cuerpo);
  }

  return fetch(url + '?' + params.toString(), config)
    .then(function (response) {
      return response.json().catch(function () {
        return null;
      }).then(function (data) {
        if (!response.ok || (data && data.success === false)) {
          var textoError = mensajeDeApi(data);

          if (!textoError && data && typeof data === 'object') {
            textoError = mensajeDeApi({ success: false, data: data });
          }

          if (!textoError && !response.ok && response.statusText) {
            textoError = mensajeDeApi({
              success: false,
              message: response.statusText
            });
          }

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

function fechaCorta(valor) {
  var texto = (valor === undefined || valor === null) ? '' : String(valor);
  return texto.split(' ')[0];
}

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

function textoMensajeDeCuerpo(cuerpo) {
  var valor;

  if (!cuerpo || typeof cuerpo !== 'object') {
    return '';
  }

  if (cuerpo.message !== undefined && cuerpo.message !== null && cuerpo.message !== '') {
    return String(cuerpo.message);
  }

  if (cuerpo.mensaje !== undefined && cuerpo.mensaje !== null && cuerpo.mensaje !== '') {
    return String(cuerpo.mensaje);
  }

  if (typeof cuerpo.error === 'string' && cuerpo.error !== '') {
    return cuerpo.error;
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
    return origen;
  }

  if (origen instanceof Error) {
    if (origen.message) {
      return String(origen.message);
    }

    if (origen.data) {
      texto = mensajeDeApi(origen.data);

      if (texto) {
        return texto;
      }
    }

    return '';
  }

  texto = textoMensajeDeCuerpo(origen);

  if (texto) {
    return texto;
  }

  if (origen.data !== undefined && origen.data !== null) {
    cuerpo = origen.data;

    if (typeof cuerpo === 'string' && cuerpo !== '') {
      return cuerpo;
    }

    texto = textoMensajeDeCuerpo(cuerpo);

    if (texto) {
      return texto;
    }

    if (typeof cuerpo === 'object') {
      texto = mensajeDeApi(cuerpo);

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

function avisoDeApi(caja, origen, ok) {
  var codigo;
  var textoMsg;
  var esError;

  if (!caja) {
    return;
  }

  if (origen === '') {
    caja.textContent = '';
    caja.removeAttribute('data-estado');
    caja.removeAttribute('data-codigo');
    caja.removeAttribute('aria-busy');
    return;
  }

  caja.removeAttribute('aria-busy');

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

function enlazarModelosDetalleSolicitud(tipos) {
  var contenedor = document.getElementById('modelos-solicitud');
  var slots;
  var lista;
  var i;

  if (!contenedor) {
    return;
  }

  slots = contenedor.querySelectorAll('[data-enlace-detalle-tipo]');
  lista = Array.isArray(tipos) ? tipos.slice() : [];
  lista.sort(function (a, b) {
    return Number(a && a.id) - Number(b && b.id);
  });

  for (i = 0; i < slots.length && i < lista.length; i++) {
    if (lista[i].id !== undefined && lista[i].id !== null) {
      slots[i].setAttribute('data-id-tipo-solicitud', String(lista[i].id));
    }
  }
}

function enlaceDetalleSolicitud(solicitud) {
  var panel = document.getElementById('panel-solicitudes');
  var fijo = panel && panel.getAttribute('data-detalle');
  var contenedor = document.getElementById('modelos-solicitud');
  var idTipo = solicitud && solicitud.id_tipo_solicitud;
  var enlace;
  var defecto;
  var filaModelo;

  if (fijo) {
    return String(fijo).replace(/\?.*$/, '');
  }

  if (contenedor && idTipo !== undefined && idTipo !== null && String(idTipo) !== '') {
    enlace = contenedor.querySelector('[data-id-tipo-solicitud="' + String(idTipo) + '"]');

    if (enlace && enlace.getAttribute('href')) {
      return enlace.getAttribute('href').replace(/\?.*$/, '');
    }
  }

  defecto = contenedor && contenedor.querySelector('[data-enlace-detalle-default]');

  if (defecto && defecto.getAttribute('href')) {
    return defecto.getAttribute('href').replace(/\?.*$/, '');
  }

  filaModelo = document.getElementById('fila-modelo-solicitud');
  enlace = filaModelo && filaModelo.querySelector('a[href]');

  if (enlace) {
    return enlace.getAttribute('href').replace(/\?.*$/, '');
  }

  return '';
}
