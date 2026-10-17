document.addEventListener('DOMContentLoaded', function () {
    var panel = document.getElementById('panel-mi-perfil');
    if (!panel) {
        return;
    }

    var caja = document.getElementById('mensaje-perfil') || document.getElementById('mensaje-aportes');
    var linkPanel = document.querySelector('.perfil-topbar .nav-panel');
    var menu = document.getElementById('menu-perfil');
    var avisos = {};
    var toggleMenu = document.getElementById('menu-perfil-toggle');

    if (toggleMenu && window.matchMedia('(min-width: 993px)').matches) {
        toggleMenu.checked = true;
    }

    if (linkPanel) {
        linkPanel.hidden = !valorCookie('usuario_acceso_admin');
    }

    pedirNodos('[data-recurso]');

    function cerrarMenuMovil() {
        if (toggleMenu && window.matchMedia('(max-width: 992px)').matches) {
            toggleMenu.checked = false;
        }
    }

    if (menu) {
        menu.querySelectorAll('a[href]').forEach(function (link) {
            link.addEventListener('click', function () {
                cerrarMenuMovil();
            });
        });
    }

    function asTexto(v) {
        if (v === undefined || v === null) {
            return '';
        }
        return String(v);
    }

    function vistaDe(nodo) {
        var vista = nodo && nodo.closest('.tab-pane');

        return (vista && vista.id) || vistaActual();
    }

    function vistaActual() {
        var vista = panel.querySelector('.tab-pane.active');

        return (vista && vista.id) || 'datos';
    }

    function pintarAviso() {
        var err;

        if (!caja) {
            return;
        }

        err = avisos[vistaActual()];

        if (!err) {
            avisoDeApi(caja, '', true);
            return;
        }

        avisoDeApi(caja, err, false);
    }

    function avisar(vista, err) {
        avisos[vista] = err || null;
        pintarAviso();
    }

    function datoDeRespuesta(res) {
        var datos = (res && res.data) || {};

        if (Array.isArray(datos)) {
            return datos[0] || {};
        }

        return datos;
    }

    function esId(clave) {
        return clave === 'id' || clave.indexOf('id_') === 0;
    }

    function ponerDato(ul, etiqueta, valor) {
        if (!ul || asTexto(valor) === '' || valor === false) {
            return;
        }

        var li = document.createElement('li');
        var nom = document.createElement('span');
        var val = document.createElement('span');
        var nombre = asTexto(etiqueta).replace(/_/g, ' ');

        if (valor === true) {
            val.setAttribute('data-texto', nombre.charAt(0).toUpperCase() + nombre.slice(1));
            li.appendChild(val);
            ul.appendChild(li);
            return;
        }

        nom.className = 'detalle-label';
        nom.textContent = nombre;
        val.setAttribute('data-texto', asTexto(valor));

        li.appendChild(nom);
        li.appendChild(val);
        ul.appendChild(li);
    }

    function llenarLista(ul, obj) {
        var claves;
        var i;
        var k;

        if (!ul) {
            return;
        }

        ul.innerHTML = '';
        if (!obj) {
            return;
        }

        claves = clavesVisibles(obj);
        for (i = 0; i < claves.length; i++) {
            k = claves[i];
            if (esId(k) || (k === 'estado' && typeof obj[k] === 'boolean')) {
                continue;
            }
            if (k.indexOf('fecha') === 0) {
                ponerDato(ul, k, fechaCorta(obj[k]));
            } else {
                ponerDato(ul, k, obj[k]);
            }
        }
    }

    function pintarTablaPaginada(cuerpo, cabecera, items) {
        var cajaLista = cuerpo.closest('[data-por-pagina]') || cuerpo;
        var claves = clavesDeItems(items);

        function dibujar(nuevaPagina) {
            var corte = armarPaginador(cajaLista, items.length, nuevaPagina, dibujar);

            pintarTabla(cabecera, cuerpo, items.slice(corte.desde, corte.hasta), claves);
        }

        dibujar(1);
    }

    function pintarNodo(nodo, res) {
        var items;
        var i;
        var item;
        var cabecera;

        if (!nodo) {
            return;
        }

        items = listaDe(res);

        if (nodo.tagName === 'TBODY') {
            cabecera = document.getElementById(nodo.getAttribute('data-cabecera'));
            if (!cabecera) {
                cabecera = nodo.parentNode && nodo.parentNode.querySelector('thead tr');
            }
            pintarTablaPaginada(nodo, cabecera, items);
            return;
        }

        if (items.length > 1) {
            nodo.innerHTML = '';
            for (i = 0; i < items.length; i++) {
                item = items[i];
                if (!item) {
                    continue;
                }
                if (item.valor !== undefined) {
                    ponerDato(nodo, textoDeItem(item), item.valor);
                } else {
                    llenarLista(nodo, item);
                }
            }
            return;
        }

        llenarLista(nodo, datoDeRespuesta(res));
    }

    function perfilSinDatos(error, recurso) {
        if (!error || error.status !== 404) {
            return false;
        }

        return recurso === 'domicilios' || recurso === 'contactos';
    }

    function avisoVacioDeLista(nodo, origen) {
        var vacio = nodo && nodo.nextElementSibling;
        var texto = mensajeDeApi(origen);

        if (!vacio || !vacio.classList.contains('perfil-vacio') || !texto) {
            return;
        }

        vacio.textContent = texto;
    }

    function listaVaciaEnNodo(nodo, res) {
        var items = listaDe(res);

        if (items.length > 1) {
            return false;
        }

        if (items.length === 1) {
            return !nodo.querySelector('li');
        }

        return !nodo.querySelector('li');
    }

    function pedirNodo(nodo) {
        var recurso;
        var vista;

        if (!nodo || nodo.getAttribute('data-pedido')) {
            return Promise.resolve();
        }

        recurso = nodo.getAttribute('data-recurso');
        vista = vistaDe(nodo);

        if (!recurso) {
            return Promise.resolve();
        }

        nodo.setAttribute('data-pedido', '1');

        return apiRequest(recurso, {
            parametros: parametrosPerfilUsuario(idUsuarioEnSesion())
        }).then(function (res) {
            var resPintar = res;

            if (recurso === 'contactos') {
                resPintar = {
                    success: true,
                    data: listaContactosDePerfil(datosPerfilDeRespuesta(res, idUsuarioEnSesion()))
                };
            } else if (recurso === 'domicilios') {
                resPintar = {
                    success: true,
                    data: objetoDomicilioDePerfil(datosPerfilDeRespuesta(res, idUsuarioEnSesion()))
                };
            }

            pintarNodo(nodo, resPintar);
            nodo.setAttribute('data-cargado', '1');

            if (listaVaciaEnNodo(nodo, res)) {
                avisoVacioDeLista(nodo, res);
            }

            avisar(vista, null);
            return res;
        }).catch(function (error) {
            nodo.setAttribute('data-cargado', '1');

            if (perfilSinDatos(error, recurso)) {
                nodo.innerHTML = '';
                avisoVacioDeLista(nodo, error);
                avisar(vista, null);
                return null;
            }

            avisar(vista, error);
            return null;
        });
    }

    function pedirNodos(selector) {
        var nodos = panel.querySelectorAll(selector);
        var i;
        var pedidos = [];

        for (i = 0; i < nodos.length; i++) {
            pedidos.push(pedirNodo(nodos[i]));
        }

        return Promise.all(pedidos);
    }
});
