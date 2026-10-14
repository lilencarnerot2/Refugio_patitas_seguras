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
          var error = new Error(mensajeDeApi(data));
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

function mensajeDeApi(origen) {
  var cuerpo;

  if (!origen) {
    return '';
  }

  if (typeof origen === 'string') {
    return origen;
  }

  cuerpo = origen.data && origen.data.message !== undefined ? origen.data : origen;

  if (cuerpo.message === undefined || cuerpo.message === null) {
    return '';
  }

  return String(cuerpo.message);
}

function avisoDeApi(caja, origen) {
  var codigo;

  if (!caja) {
    return;
  }

  caja.textContent = mensajeDeApi(origen);
  codigo = origen && origen.status;

  if (codigo) {
    caja.setAttribute('data-codigo', codigo);
  } else {
    caja.removeAttribute('data-codigo');
  }
}
