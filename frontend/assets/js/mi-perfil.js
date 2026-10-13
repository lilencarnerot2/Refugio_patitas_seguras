document.addEventListener('DOMContentLoaded', function () {
    var panel = document.getElementById('panel-mi-perfil');
    if (!panel) {
        return;
    }

    var ficha = document.getElementById('datos-perfil');
    var listaDomicilio = document.getElementById('lista-domicilio');
    var cabeceraMov = document.getElementById('cabecera-movimientos');
    var cuerpoMov = document.getElementById('cuerpo-movimientos');
    var cabeceraSol = document.getElementById('cabecera-solicitudes');
    var cuerpoSol = document.getElementById('cuerpo-solicitudes');
    var caja = document.getElementById('mensaje-perfil') || document.getElementById('mensaje-aportes');
    var idUsuario = valorCookie('usuario_id');
    var linkPanel = document.querySelector('#menu-perfil .nav-panel');
    var menu = document.getElementById('menu-perfil');

    function cargarFicha(desdeApi) {
        if (!ficha) {
            return Promise.resolve();
        }

        if (desdeApi) {
            return pedirSiPuede('usuarios', {
                parametros: idUsuario ? { id: idUsuario } : {}
            }).then(function (res) {
                var u;

                if (res) {
                    u = datoDeRespuesta(res);
                    if (u.id) {
                        idUsuario = asTexto(u.id);
                    }
                    llenarLista(ficha, u);
                }

                return cargarContactos();
            });
        }

        return cargarContactos();
    }

    accesoDeApi('usuarios').then(function (ok) {
        if (linkPanel) {
            linkPanel.hidden = !ok;
        }

        return cargarFicha(ok);
    });

    if (menu) {
        menu.querySelectorAll('[data-bs-toggle="tab"]').forEach(function (tab) {
            tab.addEventListener('shown.bs.tab', function (ev) {
                menu.querySelectorAll('[data-bs-toggle="tab"]').forEach(function (otro) {
                    if (otro === ev.target) {
                        otro.classList.add('active');
                    } else {
                        otro.classList.remove('active');
                    }
                });
            });
        });
    }

    function valorCookie(nombre) {
        var lista = document.cookie.split(';');
        var i;
        var parte;
        var clave = nombre + '=';

        for (i = 0; i < lista.length; i++) {
            parte = lista[i].trim();
            if (parte.indexOf(clave) === 0) {
                return decodeURIComponent(parte.slice(clave.length));
            }
        }

        return '';
    }

    function datosDeSesion() {
        var datos = {};
        var lista = document.cookie.split(';');
        var i;
        var parte;
        var corte;
        var clave;
        var valor;
        var prefijo = 'usuario_';

        for (i = 0; i < lista.length; i++) {
            parte = lista[i].trim();
            corte = parte.indexOf('=');

            if (corte < 1 || parte.indexOf(prefijo) !== 0) {
                continue;
            }

            clave = parte.slice(prefijo.length, corte);
            valor = decodeURIComponent(parte.slice(corte + 1));

            if (!clave || !valor || esId(clave) || clave.indexOf('acceso') === 0 || esSoloNumero(valor)) {
                continue;
            }

            datos[clave] = valor;
        }

        return datos;
    }

    function asTexto(v) {
        if (v === undefined || v === null) {
            return '';
        }
        return String(v);
    }

    function avisar(err) {
        var texto;

        if (!caja) {
            return;
        }

        texto = mensajeDeApi(err);

        if (!texto) {
            return;
        }

        if (caja.textContent && caja.textContent.indexOf(texto) !== -1) {
            return;
        }

        caja.textContent = caja.textContent ? caja.textContent + ' ' + texto : texto;
        caja.setAttribute('data-estado', 'error');

        if (err && err.status) {
            caja.setAttribute('data-codigo', String(err.status));
        }
    }

    function esId(clave) {
        return clave === 'id' || clave.indexOf('id_') === 0;
    }

    function esSoloNumero(valor) {
        return /^\d+$/.test(asTexto(valor));
    }

    function datoDeRespuesta(res) {
        var datos = (res && res.data) || {};

        if (Array.isArray(datos)) {
            return datos[0] || {};
        }

        return datos;
    }

    function pedirSiPuede(recurso, opciones) {
        return apiRequest(recurso, opciones).catch(function (error) {
            avisar(error);
            return null;
        });
    }

    function ponerDato(ul, etiqueta, valor) {
        if (!ul || asTexto(valor) === '') {
            return;
        }

        var li = document.createElement('li');
        var nom = document.createElement('span');
        var val = document.createElement('span');

        nom.className = 'detalle-label';
        nom.textContent = etiqueta.replace(/_/g, ' ');
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
            if (esId(k)) {
                continue;
            }
            if (k.indexOf('fecha') === 0) {
                ponerDato(ul, k, fechaCorta(obj[k]));
            } else {
                ponerDato(ul, k, obj[k]);
            }
        }
    }

    function delUsuario(item) {
        if (!item) {
            return false;
        }
        if (item.id_usuario === undefined || item.id_usuario === null) {
            return true;
        }
        return asTexto(item.id_usuario) === asTexto(idUsuario);
    }

    function cargarContactos() {
        return pedirSiPuede('contactos').then(function (r2) {
            var arr;
            var i;
            var c;

            if (!r2 || !ficha) {
                return;
            }

            arr = listaDe(r2);
            for (i = 0; i < arr.length; i++) {
                c = arr[i];
                if (!c) {
                    continue;
                }
                ponerDato(ficha, textoDeItem(c), c.valor);
            }
        });
    }

    if (ficha) {
        llenarLista(ficha, datosDeSesion());
    }

    if (listaDomicilio) {
        pedirSiPuede('domicilios').then(function (res) {
            if (!res) {
                return;
            }

            llenarLista(listaDomicilio, datoDeRespuesta(res));
        });
    }

    function vistaActiva(id) {
        var vista = document.getElementById(id);

        return !!(vista && vista.classList.contains('active'));
    }

    function cargarMovimientos() {
        if (!cuerpoMov || cuerpoMov.getAttribute('data-pedido')) {
            return;
        }

        cuerpoMov.setAttribute('data-pedido', '1');

        pedirSiPuede('movimientos').then(function (res) {
            if (!res) {
                return;
            }

            pintarTabla(cabeceraMov, cuerpoMov, listaDe(res).filter(delUsuario));
        });
    }

    function cargarSolicitudes() {
        if (!cuerpoSol || cuerpoSol.getAttribute('data-pedido')) {
            return;
        }

        cuerpoSol.setAttribute('data-pedido', '1');

        pedirSiPuede('solicitudes').then(function (res) {
            if (!res) {
                return;
            }

            pintarTabla(cabeceraSol, cuerpoSol, listaDe(res).filter(delUsuario));
        });
    }

    if (vistaActiva('vista-movimientos')) {
        cargarMovimientos();
    }

    if (vistaActiva('vista-solicitudes')) {
        cargarSolicitudes();
    }

    if (menu) {
        menu.querySelectorAll('[data-bs-target="#vista-movimientos"]').forEach(function (tab) {
            tab.addEventListener('shown.bs.tab', cargarMovimientos);
        });

        menu.querySelectorAll('[data-bs-target="#vista-solicitudes"]').forEach(function (tab) {
            tab.addEventListener('shown.bs.tab', cargarSolicitudes);
        });
    }
});
