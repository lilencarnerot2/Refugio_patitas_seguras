document.addEventListener('DOMContentLoaded', function () {

    var mensaje = document.getElementById('mensaje-formulario');

    var inputDireccion = document.getElementById('input-direccion');
    var inputTelefono = document.getElementById('input-telefono');
    var inputEmail = document.getElementById('input-email');

    var inputAlias = document.getElementById('input-alias');
    var inputCbu = document.getElementById('input-cbu');

    var btnContacto = document.getElementById('btn-guardar-contacto');
    var btnDonacion = document.getElementById('btn-guardar-donacion');

    var catalogos = {
        tipos_vivienda: {
            titulo: 'Tipos de Vivienda',
            campo: 'nombre_tipo',
            etiqueta: 'Tipo de vivienda'
        },

        especies: {
            titulo: 'Especies',
            campo: 'nombre_especie',
            etiqueta: 'Especie'
        },

        motivos_adopcion: {
            titulo: 'Motivos de Adopción',
            campo: 'descripcion',
            etiqueta: 'Motivo'
        }
    };


    function mostrarMensaje(texto, esError) {
        if (!mensaje) {
            return;
        }

        mensaje.textContent = texto || '';

        if (!texto) {
            mensaje.removeAttribute('data-estado');
            return;
        }

        mensaje.setAttribute(
            'data-estado',
            esError ? 'error' : 'ok'
        );
    }


    function mensajeError(error) {
        return mensajeDeApi(error)
            || (error && error.message)
            || 'Ocurrió un error. Intentá nuevamente.';
    }


    // CONFIGURACIÓN GENERAL
    function cargarConfiguracion() {
        apiRequest('configuracion')
            .then(function (respuesta) {
                var datos = respuesta && respuesta.data
                    ? respuesta.data
                    : {};

                inputDireccion.value =
                    datos.direccion || '';

                inputTelefono.value =
                    datos.telefono || datos.whatsapp || '';

                inputEmail.value =
                    datos.email_contacto || '';

                inputAlias.value =
                    datos.alias_mercado_pago || '';

                inputCbu.value =
                    datos.cbu || '';
            })
            .catch(function (error) {
                mostrarMensaje(
                    mensajeError(error),
                    true
                );
            });
    }


    function guardarContacto() {
        var cuerpo = {
            direccion: inputDireccion.value.trim(),
            telefono: inputTelefono.value.trim(),
            email_contacto: inputEmail.value.trim()
        };

        btnContacto.disabled = true;
        btnContacto.innerHTML = 'Guardando...';

        apiRequest('configuracion', {
            metodo: 'PUT',
            cuerpo: cuerpo
        })
            .then(function () {
                mostrarMensaje(
                    'Los datos de contacto se guardaron correctamente.',
                    false
                );
            })
            .catch(function (error) {
                mostrarMensaje(
                    mensajeError(error),
                    true
                );
            })
            .finally(function () {
                btnContacto.disabled = false;
                btnContacto.innerHTML =
                    '<i class="bi bi-save me-2"></i>Guardar Cambios';
            });
    }


    function guardarDonacion() {
        var cuerpo = {
            alias_mercado_pago: inputAlias.value.trim(),
            cbu: inputCbu.value.trim()
        };

        btnDonacion.disabled = true;
        btnDonacion.innerHTML = 'Guardando...';

        apiRequest('configuracion', {
            metodo: 'PUT',
            cuerpo: cuerpo
        })
            .then(function () {
                mostrarMensaje(
                    'Los datos de donación se guardaron correctamente.',
                    false
                );
            })
            .catch(function (error) {
                mostrarMensaje(
                    mensajeError(error),
                    true
                );
            })
            .finally(function () {
                btnDonacion.disabled = false;
                btnDonacion.innerHTML =
                    '<i class="bi bi-save me-2"></i>Guardar Cambios';
            });
    }


    btnContacto.addEventListener(
        'click',
        guardarContacto
    );

    btnDonacion.addEventListener(
        'click',
        guardarDonacion
    );


    // MODAL DE CATÁLOGOS
    function crearModalCatalogo() {
        var existente =
            document.getElementById('modal-catalogo-config');

        if (existente) {
            return existente;
        }

        var contenedor = document.createElement('div');

        contenedor.innerHTML = `
            <div
                class="modal fade"
                id="modal-catalogo-config"
                tabindex="-1"
                aria-hidden="true">

                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5
                                class="modal-title"
                                id="titulo-modal-catalogo">
                                Catálogo
                            </h5>

                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal">
                            </button>
                        </div>

                        <div class="modal-body">

                            <p
                                id="mensaje-catalogo"
                                class="mensaje-formulario">
                            </p>

                            <div class="input-group mb-4">

                                <input
                                    type="text"
                                    class="form-control"
                                    id="input-nuevo-catalogo"
                                    placeholder="Nuevo valor">

                                <button
                                    type="button"
                                    class="btn btn-success"
                                    id="btn-agregar-catalogo">
                                    Agregar
                                </button>

                            </div>

                            <div
                                class="list-group"
                                id="lista-catalogo">
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(
            contenedor.firstElementChild
        );

        return document.getElementById(
            'modal-catalogo-config'
        );
    }


    var modalElemento = crearModalCatalogo();
    var modalCatalogo =
        new bootstrap.Modal(modalElemento);
    var tituloModal =
        document.getElementById('titulo-modal-catalogo');
    var inputNuevo =
        document.getElementById('input-nuevo-catalogo');
    var btnAgregar =
        document.getElementById('btn-agregar-catalogo');
    var listaCatalogo =
        document.getElementById('lista-catalogo');
    var mensajeCatalogo =
        document.getElementById('mensaje-catalogo');
    var catalogoActual = '';


    function mostrarMensajeCatalogo(texto, esError) {
        mensajeCatalogo.textContent = texto || '';

        if (!texto) {
            mensajeCatalogo.removeAttribute(
                'data-estado'
            );
            return;
        }

        mensajeCatalogo.setAttribute(
            'data-estado',
            esError ? 'error' : 'ok'
        );
    }


    function datosCatalogo(respuesta) {
        if (
            respuesta
            && Array.isArray(respuesta.data)
        ) {
            return respuesta.data;
        }

        return [];
    }


    function cargarCatalogo(nombre) {
        var config = catalogos[nombre];

        if (!config) {
            return;
        }

        catalogoActual = nombre;

        tituloModal.textContent = config.titulo;

        listaCatalogo.innerHTML =
            '<div class="text-muted">Cargando...</div>';

        inputNuevo.value = '';

        mostrarMensajeCatalogo('', false);

        apiRequest(nombre)
            .then(function (respuesta) {
                dibujarCatalogo(
                    datosCatalogo(respuesta)
                );
            })
            .catch(function (error) {
                listaCatalogo.innerHTML = '';

                mostrarMensajeCatalogo(
                    mensajeError(error),
                    true
                );
            });
    }


    function dibujarCatalogo(items) {
        var config = catalogos[catalogoActual];

        listaCatalogo.innerHTML = '';

        if (!items.length) {
            listaCatalogo.innerHTML =
                '<div class="text-muted">No hay registros cargados.</div>';

            return;
        }

        items.forEach(function (item) {
            var fila = document.createElement('div');

            fila.className =
                'list-group-item d-flex justify-content-between align-items-center gap-3';

            var valor =
                item[config.campo] || '';

            var texto = document.createElement('span');

            texto.textContent = valor;
            texto.className = 'flex-grow-1';

            var acciones =
                document.createElement('div');

            acciones.className =
                'd-flex gap-2';

            var btnEditar =
                document.createElement('button');

            btnEditar.type = 'button';
            btnEditar.className =
                'btn btn-sm btn-outline-success';

            btnEditar.textContent = 'Editar';

            btnEditar.addEventListener(
                'click',
                function () {
                    editarItemCatalogo(
                        item.id,
                        valor
                    );
                }
            );

            var btnEliminar =
                document.createElement('button');

            btnEliminar.type = 'button';
            btnEliminar.className =
                'btn btn-sm btn-outline-danger';

            btnEliminar.textContent = 'Eliminar';

            btnEliminar.addEventListener(
                'click',
                function () {
                    eliminarItemCatalogo(
                        item.id,
                        valor
                    );
                }
            );

            acciones.appendChild(btnEditar);
            acciones.appendChild(btnEliminar);

            fila.appendChild(texto);
            fila.appendChild(acciones);

            listaCatalogo.appendChild(fila);
        });
    }


    function agregarItemCatalogo() {
        var config = catalogos[catalogoActual];

        var valor = inputNuevo.value.trim();

        if (!valor) {
            mostrarMensajeCatalogo(
                'Ingresá un valor.',
                true
            );

            return;
        }

        var cuerpo = {};
        cuerpo[config.campo] = valor;

        btnAgregar.disabled = true;

        apiRequest(catalogoActual, {
            metodo: 'POST',
            cuerpo: cuerpo
        })
            .then(function () {
                inputNuevo.value = '';

                mostrarMensajeCatalogo(
                    'Registro agregado correctamente.',
                    false
                );

                cargarCatalogo(catalogoActual);
            })
            .catch(function (error) {
                mostrarMensajeCatalogo(
                    mensajeError(error),
                    true
                );
            })
            .finally(function () {
                btnAgregar.disabled = false;
            });
    }


    function editarItemCatalogo(id, valorActual) {
        var config = catalogos[catalogoActual];

        var nuevoValor = window.prompt(
            'Editar ' + config.etiqueta,
            valorActual
        );

        if (nuevoValor === null) {
            return;
        }

        nuevoValor = nuevoValor.trim();

        if (!nuevoValor) {
            mostrarMensajeCatalogo(
                'El valor no puede quedar vacío.',
                true
            );

            return;
        }

        var cuerpo = {};
        cuerpo[config.campo] = nuevoValor;

        apiRequest(catalogoActual, {
            metodo: 'PUT',
            parametros: {
                id: id
            },
            cuerpo: cuerpo
        })
            .then(function () {
                mostrarMensajeCatalogo(
                    'Registro actualizado correctamente.',
                    false
                );

                cargarCatalogo(catalogoActual);
            })
            .catch(function (error) {
                mostrarMensajeCatalogo(
                    mensajeError(error),
                    true
                );
            });
    }


    function eliminarItemCatalogo(id, valor) {
        var confirmar = window.confirm(
            '¿Querés eliminar "' + valor + '"?'
        );

        if (!confirmar) {
            return;
        }

        apiRequest(catalogoActual, {
            metodo: 'DELETE',
            parametros: {
                id: id
            }
        })
            .then(function () {
                mostrarMensajeCatalogo(
                    'Registro eliminado correctamente.',
                    false
                );

                cargarCatalogo(catalogoActual);
            })
            .catch(function (error) {
                mostrarMensajeCatalogo(
                    mensajeError(error),
                    true
                );
            });
    }


    btnAgregar.addEventListener(
        'click',
        agregarItemCatalogo
    );


    document.querySelectorAll(
        '.btn-editar-catalogo'
    ).forEach(function (boton) {

        boton.addEventListener(
            'click',
            function () {
                var nombre =
                    boton.getAttribute(
                        'data-catalogo'
                    );

                cargarCatalogo(nombre);

                modalCatalogo.show();
            }
        );

    });


    // INICIO
    cargarConfiguracion();

});