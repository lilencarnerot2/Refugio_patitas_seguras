const API_URL = (window.APP_CONFIG && window.APP_CONFIG.backendUrl) || '';

function apiRequest(recurso, opciones) {
    opciones = opciones || {};

    if (!API_URL) {
        return Promise.reject(new Error('No está configurada la URL del backend.'));
    }

     const url = new URL(API_URL.replace(/\/$/, '') + '/');
    url.searchParams.set('recurso', recurso);

    const parametros = opciones.parametros || {};
    Object.keys(parametros).forEach(function (clave) {
        if (parametros[clave] !== undefined && parametros[clave] !== null && parametros[clave] !== '') {
            url.searchParams.set(clave, parametros[clave]);
        }
    });

    const config = {
        method: opciones.metodo || 'GET',
        headers: {},
        credentials: 'include'
    };

    if (opciones.cuerpo !== undefined) {
        config.headers['Content-Type'] = 'application/json';
        config.body = JSON.stringify(opciones.cuerpo);
    }

    return fetch(url.toString(), config).then(function (response) {
        return response.json().catch(function () {
            throw new Error('El servidor no devolvió una respuesta válida.');
        }).then(function (data) {
            if (!response.ok || data.success === false) {
                const error = new Error(data.message || ('Error HTTP: ' + response.status));
                error.status = response.status;
                error.data = data;
                throw error;
            }

            return data;
        });
    });
}
