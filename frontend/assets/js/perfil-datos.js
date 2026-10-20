document.addEventListener('DOMContentLoaded', function () {
    var tarjetaContacto = document.getElementById('perfil-personales');
    var tarjetaDomicilio = document.getElementById('perfil-domicilio');
    var cajaPagina = document.getElementById('mensaje-perfil');
    var tiposContacto = [];
    var contactos = [];
    var domicilioActual = null;
    var catalogos = { tiposVivienda: [], provincias: [] };
    var filasDomicilio = [
        ['Tipo de vivienda', 'tipo_vivienda'],
        ['Provincia', 'provincia'],
        ['Municipio', 'municipio'],
        ['Localidad', 'localidad'],
        ['Barrio o zona', 'barrio_zona'],
        ['Calle', 'calle'],
        ['Altura', 'altura'],
        ['Piso', 'piso'],
        ['Departamento', 'departamento'],
        ['Ruta', 'ruta_nombre'],
        ['Kilómetro', 'kilometro'],
        ['Entre calle 1', 'entre_calle_1'],
        ['Entre calle 2', 'entre_calle_2'],
        ['Indicadores de referencia', 'indicadores_referencia'],
        ['Código postal', 'codigo_postal']
    ];
    var camposTextoDomicilio = [
        'codigo_postal',
        'calle',
        'altura',
        'piso',
        'departamento',
        'ruta_nombre',
        'kilometro',
        'entre_calle_1',
        'entre_calle_2',
        'indicadores_referencia'
    ];
    var contacto;
    var domicilio;
    var campos;

    if (!tarjetaContacto || !tarjetaDomicilio) {
        return;
    }

    contacto = seccion(tarjetaContacto);
    domicilio = seccion(tarjetaDomicilio);
    campos = {
        tipoVivienda: domicilio.formulario.elements.id_tipo_vivienda,
        provincia: domicilio.formulario.elements.id_provincia,
        comuna: domicilio.formulario.elements.id_comuna_municipio,
        localidad: domicilio.formulario.elements.id_localidad,
        barrio: domicilio.formulario.elements.nombre_barrio
    };

    contacto.botonEditar.addEventListener('click', function () {
        armarFormularioContacto();
        avisoDeApi(contacto.aviso, '', true);
        abrirFormulario(contacto);
    });

    domicilio.botonEditar.addEventListener('click', function () {
        avisoDeApi(domicilio.aviso, '', true);
        abrirFormulario(domicilio);

        llenarFormularioDomicilio().catch(function (error) {
            avisoDeApi(domicilio.aviso, error, false);
        });
    });

    [contacto, domicilio].forEach(function (parte) {
        parte.formulario.querySelector('[data-accion="cancelar"]').addEventListener('click', function () {
            cerrarFormulario(parte);
            avisoDeApi(parte.aviso, '', true);
        });

        parte.formulario.addEventListener('input', quitarMarcaDeCampo);
        parte.formulario.addEventListener('change', quitarMarcaDeCampo);
    });

    contacto.formulario.addEventListener('submit', function (evento) {
        evento.preventDefault();
        guardarContactos();
    });

    domicilio.formulario.addEventListener('submit', function (evento) {
        evento.preventDefault();
        guardarDomicilio();
    });

    campos.provincia.addEventListener('change', function () {
        llenarOpciones(campos.localidad, []);

        cargarHijo(campos.comuna, 'comunas_municipios', 'id_provincia', campos.provincia.value).catch(function (error) {
            avisoDeApi(domicilio.aviso, error, false);
        });
    });

    campos.comuna.addEventListener('change', function () {
        cargarHijo(campos.localidad, 'localidades', 'id_comuna_municipio', campos.comuna.value).catch(function (error) {
            avisoDeApi(domicilio.aviso, error, false);
        });
    });

    cargarPerfil();

    function seccion(tarjeta) {
        return {
            tarjeta: tarjeta,
            aviso: tarjeta.querySelector('[data-mensaje]'),
            vista: tarjeta.querySelector('[data-vista]'),
            formulario: tarjeta.querySelector('form'),
            botonEditar: tarjeta.querySelector('[data-accion="editar"]')
        };
    }

    function texto(valor) {
        if (valor === undefined || valor === null) {
            return '';
        }

        return String(valor).trim();
    }

    function normalizar(valor) {
        return texto(valor).toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    }

    function listaDe(respuesta) {
        var datos = respuesta && respuesta.data;

        if (!datos) {
            return [];
        }

        return Array.isArray(datos) ? datos : [datos];
    }

    function objetoDe(respuesta) {
        var datos = respuesta && respuesta.data;

        return datos && typeof datos === 'object' && !Array.isArray(datos) ? datos : null;
    }

    function marcarEstado(parte, estado) {
        parte.tarjeta.setAttribute('data-perfil-estado', estado);
    }

    function agregarFila(lista, etiqueta, valor) {
        var fila = document.createElement('li');
        var nombre = document.createElement('span');
        var dato = document.createElement('span');
        var contenido = texto(valor);

        nombre.className = 'detalle-label';
        nombre.textContent = etiqueta;
        dato.setAttribute('data-texto', contenido !== '' ? contenido : 'Sin completar');

        if (contenido === '') {
            fila.setAttribute('data-vacio', '');
        }

        fila.appendChild(nombre);
        fila.appendChild(dato);
        lista.appendChild(fila);
    }

    function abrirFormulario(parte) {
        parte.vista.hidden = true;
        parte.botonEditar.hidden = true;
        parte.formulario.hidden = false;
    }

    function cerrarFormulario(parte) {
        parte.formulario.hidden = true;
        parte.formulario.removeAttribute('data-intento-envio');
        limpiarMarcasCampos(parte.formulario);
        parte.vista.hidden = false;
        parte.botonEditar.hidden = false;
    }

    function quitarMarcaDeCampo(evento) {
        if (evento.target && evento.target.removeAttribute) {
            evento.target.removeAttribute('data-campo-estado');
        }
    }

    function nombreDeCampo(formulario, nodo) {
        var etiqueta = nodo.id ? formulario.querySelector('label[for="' + nodo.id + '"]') : null;

        return etiqueta ? etiqueta.textContent.replace(/\s+/g, ' ').trim() : nodo.name;
    }

    function camposObligatoriosVacios(formulario) {
        var vacios = [];

        Array.prototype.forEach.call(formulario.elements, function (nodo) {
            if (!nodo.name || !nodo.required || nodo.disabled) {
                return;
            }

            if (texto(nodo.value) === '') {
                vacios.push(nodo);
            }
        });

        return vacios;
    }

    function avisarCamposVacios(parte, vacios) {
        var nombres = [];
        var aviso = 'Completá los campos obligatorios';

        avisoDeApi(parte.aviso, '', true);
        marcarIntentoEnvio(parte.formulario);

        vacios.forEach(function (nodo) {
            var nombre = nombreDeCampo(parte.formulario, nodo);

            nodo.setAttribute('data-campo-estado', 'error');

            if (nombres.indexOf(nombre) === -1) {
                nombres.push(nombre);
            }
        });

        if (nombres.length) {
            aviso += ': ' + nombres.join(', ');
        }

        avisoDeApi(parte.aviso, aviso + '.', false);
    }

    function esMovil(tipo) {
        return normalizar(tipo.descripcion).indexOf('movil') !== -1;
    }

    function esFijo(tipo) {
        return normalizar(tipo.descripcion).indexOf('fijo') !== -1;
    }

    function esWhatsapp(tipo) {
        return normalizar(tipo.descripcion).indexOf('whatsapp') !== -1;
    }

    function tipoDe(comprobar) {
        var i;

        for (i = 0; i < tiposContacto.length; i++) {
            if (comprobar(tiposContacto[i])) {
                return tiposContacto[i];
            }
        }

        return null;
    }

    function valorContacto(tipo) {
        var actual = tipo ? contactoPrincipal(tipo.id) : null;

        return actual ? texto(actual.valor) : '';
    }

    function contactoPrincipal(idTipo) {
        var i;

        for (i = 0; i < contactos.length; i++) {
            if (Number(contactos[i].id_tipo_contacto) === Number(idTipo)) {
                return contactos[i];
            }
        }

        return null;
    }

    function pintarContacto() {
        var tipoMovil = tipoDe(esMovil);
        var tipoFijo = tipoDe(esFijo);
        var valorMovil = valorContacto(tipoMovil);

        contacto.vista.innerHTML = '';

        if (tipoMovil) {
            agregarFila(contacto.vista, tipoMovil.descripcion, valorMovil);
        }

        if (tipoFijo) {
            agregarFila(contacto.vista, tipoFijo.descripcion, valorContacto(tipoFijo));
        }

        marcarEstado(contacto, valorMovil !== '' ? 'completo' : 'incompleto');
    }

    function columnaDeContacto(tipo) {
        var columna = document.createElement('div');
        var etiqueta = document.createElement('label');
        var entrada = document.createElement('input');

        columna.className = 'col-md-6';
        etiqueta.className = 'form-label';
        etiqueta.setAttribute('for', 'perfil-contacto-' + tipo.id);
        etiqueta.textContent = tipo.descripcion;

        entrada.className = 'form-control';
        entrada.type = 'tel';
        entrada.id = 'perfil-contacto-' + tipo.id;
        entrada.name = 'contacto_' + tipo.id;
        entrada.required = esMovil(tipo);
        entrada.setAttribute('data-id-tipo', tipo.id);
        entrada.value = valorContacto(tipo);

        columna.appendChild(etiqueta);
        columna.appendChild(entrada);

        return columna;
    }

    function armarFormularioContacto() {
        var caja = contacto.formulario.querySelector('[data-campos]');
        var tipoMovil = tipoDe(esMovil);
        var tipoFijo = tipoDe(esFijo);

        caja.innerHTML = '';

        if (tipoMovil) {
            caja.appendChild(columnaDeContacto(tipoMovil));
        }

        if (tipoFijo) {
            caja.appendChild(columnaDeContacto(tipoFijo));
        }
    }

    function guardarContactos() {
        var formulario = contacto.formulario;
        var boton = formulario.querySelector('[type="submit"]');
        var vacios = camposObligatoriosVacios(formulario);
        var pedido = Promise.resolve();
        var fallo = null;
        var cambios = 0;
        var tipoMovil = tipoDe(esMovil);

        if (formulario.getAttribute('data-enviando') === '1') {
            return;
        }

        if (vacios.length) {
            avisarCamposVacios(contacto, vacios);
            return;
        }

        formulario.setAttribute('data-enviando', '1');
        boton.disabled = true;
        avisoDeApi(contacto.aviso, '', true, formulario);

        tiposContacto.forEach(function (tipo) {
            var entrada = formulario.querySelector('[data-id-tipo="' + tipo.id + '"]');
            var valor = entrada ? entrada.value.trim() : '';
            var actual = contactoPrincipal(tipo.id);
            var entradaMovil = tipoMovil ? formulario.querySelector('[data-id-tipo="' + tipoMovil.id + '"]') : null;
            var manejado = !!entrada;
            var opciones;

            if (entradaMovil && esWhatsapp(tipo)) {
                manejado = true;
                valor = entradaMovil.value.trim();
            }

            if (!manejado || (!actual && valor === '') || (actual && valor === texto(actual.valor))) {
                return;
            }

            if (!actual) {
                opciones = {
                    metodo: 'POST',
                    cuerpo: { id_tipo_contacto: Number(tipo.id), valor: valor, es_principal: true }
                };
            } else if (valor === '') {
                opciones = { metodo: 'DELETE', parametros: { id: actual.id } };
            } else {
                opciones = {
                    metodo: 'PUT',
                    parametros: { id: actual.id },
                    cuerpo: {
                        id_tipo_contacto: Number(tipo.id),
                        valor: valor,
                        es_principal: true,
                        observaciones: actual.observaciones || null
                    }
                };
            }

            cambios++;

            pedido = pedido.then(function () {
                return apiRequest('contactos', opciones);
            }).catch(function (error) {
                fallo = fallo || error;
            });
        });

        pedido.then(function () {
            return apiRequest('contactos').then(function (respuesta) {
                contactos = listaDe(respuesta);
            }).catch(function (error) {
                fallo = fallo || error;
            });
        }).then(function () {
            pintarContacto();

            if (fallo) {
                avisoDeApi(contacto.aviso, fallo, false, formulario);
                return;
            }

            cerrarFormulario(contacto);
            avisoDeApi(contacto.aviso, cambios ? 'Tus datos personales se guardaron correctamente.' : 'No hiciste ningún cambio.', true);
        }).finally(function () {
            formulario.removeAttribute('data-enviando');
            boton.disabled = false;
        });
    }

    function domicilioCompleto(datos) {
        return texto(datos.tipo_vivienda) !== ''
            && texto(datos.provincia) !== ''
            && texto(datos.municipio) !== ''
            && texto(datos.localidad) !== ''
            && texto(datos.calle) !== ''
            && texto(datos.altura) !== ''
            && texto(datos.codigo_postal) !== '';
    }

    function pintarDomicilio() {
        var datos = domicilioActual || {};

        domicilio.vista.innerHTML = '';

        filasDomicilio.forEach(function (fila) {
            agregarFila(domicilio.vista, fila[0], datos[fila[1]]);
        });

        if (!domicilioActual) {
            marcarEstado(domicilio, 'vacio');
            return;
        }

        marcarEstado(domicilio, domicilioCompleto(datos) ? 'completo' : 'incompleto');
    }

    function llenarOpciones(select, lista) {
        while (select.options.length > 1) {
            select.remove(1);
        }

        lista.forEach(function (item) {
            var opcion;

            if (!item || item.id === undefined || item.id === null) {
                return;
            }

            opcion = document.createElement('option');
            opcion.value = String(item.id);
            opcion.textContent = textoDeItem(item);
            select.appendChild(opcion);
        });

        select.selectedIndex = 0;
    }

    function cargarHijo(select, recurso, parametro, idPadre) {
        var parametros = {};

        llenarOpciones(select, []);

        if (!idPadre) {
            return Promise.resolve();
        }

        parametros[parametro] = idPadre;

        return apiRequest(recurso, { parametros: parametros }).then(function (respuesta) {
            llenarOpciones(select, listaDe(respuesta));
        });
    }

    function llenarFormularioDomicilio() {
        var formulario = domicilio.formulario;
        var datos = domicilioActual;

        formulario.reset();
        llenarOpciones(campos.tipoVivienda, catalogos.tiposVivienda);
        llenarOpciones(campos.provincia, catalogos.provincias);
        llenarOpciones(campos.comuna, []);
        llenarOpciones(campos.localidad, []);

        if (!datos) {
            return Promise.resolve();
        }

        campos.tipoVivienda.value = texto(datos.id_tipo_vivienda);
        campos.provincia.value = texto(datos.id_provincia);
        campos.barrio.value = texto(datos.barrio_zona) === barrioSinDato ? '' : texto(datos.barrio_zona);

        camposTextoDomicilio.forEach(function (nombre) {
            formulario.elements[nombre].value = texto(datos[nombre]);
        });

        return cargarHijo(campos.comuna, 'comunas_municipios', 'id_provincia', datos.id_provincia).then(function () {
            campos.comuna.value = texto(datos.id_comuna_municipio);

            return cargarHijo(campos.localidad, 'localidades', 'id_comuna_municipio', datos.id_comuna_municipio);
        }).then(function () {
            campos.localidad.value = texto(datos.id_localidad);
        });
    }

    function cuerpoDomicilio() {
        var formulario = domicilio.formulario;
        var cuerpo = {
            id_tipo_vivienda: Number(campos.tipoVivienda.value),
            id_localidad: Number(campos.localidad.value),
            nombre_barrio: campos.barrio.value.trim() || barrioSinDato
        };

        camposTextoDomicilio.forEach(function (nombre) {
            var valor = formulario.elements[nombre].value.trim();

            cuerpo[nombre] = valor === '' ? null : valor;
        });

        return cuerpo;
    }

    function guardarDomicilio() {
        var formulario = domicilio.formulario;
        var boton = formulario.querySelector('[type="submit"]');
        var vacios = camposObligatoriosVacios(formulario);
        var metodo = domicilioActual ? 'PUT' : 'POST';
        var cuerpo;

        if (formulario.getAttribute('data-enviando') === '1') {
            return;
        }

        if (vacios.length) {
            avisarCamposVacios(domicilio, vacios);
            return;
        }

        cuerpo = cuerpoDomicilio();
        formulario.setAttribute('data-enviando', '1');
        boton.disabled = true;
        avisoDeApi(domicilio.aviso, '', true, formulario);

        apiRequest('domicilios', { metodo: metodo, cuerpo: cuerpo }).catch(function (error) {
            if (metodo === 'PUT' && error && error.status === 404) {
                return apiRequest('domicilios', { metodo: 'POST', cuerpo: cuerpo });
            }

            throw error;
        }).then(function () {
            return apiRequest('domicilios');
        }).then(function (respuesta) {
            domicilioActual = objetoDe(respuesta);
            pintarDomicilio();
            cerrarFormulario(domicilio);
            avisoDeApi(domicilio.aviso, 'Tu domicilio se guardó correctamente.', true);
        }).catch(function (error) {
            avisoDeApi(domicilio.aviso, error, false, formulario);
        }).finally(function () {
            formulario.removeAttribute('data-enviando');
            boton.disabled = false;
        });
    }

    function cargarPerfil() {
        return Promise.all([
            apiRequest('tipos_contacto'),
            apiRequest('contactos'),
            apiRequest('domicilios').catch(function (error) {
                if (error && error.status === 404) {
                    return null;
                }

                throw error;
            }),
            apiRequest('tipos_vivienda'),
            apiRequest('provincias')
        ]).then(function (respuestas) {
            tiposContacto = listaDe(respuestas[0]).filter(function (tipo) {
                return tipo && tipo.estado !== false;
            });
            contactos = listaDe(respuestas[1]);
            domicilioActual = objetoDe(respuestas[2]);
            catalogos.tiposVivienda = listaDe(respuestas[3]);
            catalogos.provincias = listaDe(respuestas[4]);

            pintarContacto();
            pintarDomicilio();
            contacto.botonEditar.hidden = false;
            domicilio.botonEditar.hidden = false;
        }).catch(function (error) {
            avisoDeApi(cajaPagina, error, false);
        });
    }
});
