var panel = document.getElementById('panel-solicitud-detalle');
var ficha = document.getElementById('ficha-solicitud');
var mensaje = document.getElementById('mensaje-formulario');

if (panel && ficha) {
    var clave = claveDeApi(ficha, '.pet-detail-meta [data-campo]');
    var id = clave ? new URLSearchParams(window.location.search).get(clave) : '';
    var listado = panel.getAttribute('data-listado') || '';

    if (id) {
        apiRequest('solicitudes', {
            parametros: { id: id }
        })
            .then(function (respuesta) {
                var solicitud = respuesta.data;

                if (!solicitud) {
                    if (mensaje) {
                        mensaje.textContent = mensajeDeApi(respuesta);
                    }
                    return;
                }

                asignarCamposDeApi(ficha, solicitud);
            })
            .catch(function (error) {
                if (mensaje) {
                    mensaje.textContent = mensajeDeApi(error);
                }

                if (listado && error && error.status === 404) {
                    window.location.href = listado;
                }
            });
    }
}
