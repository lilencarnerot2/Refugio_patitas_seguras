document.addEventListener('DOMContentLoaded', function () {
    var formulario = document.getElementById('form-voluntariado');
    var aviso = document.getElementById('mensaje-voluntariado');
    var fecha = document.getElementById('fecha');
    var turno = document.getElementById('rango_horario');
    var calendario = document.getElementById('calendario-voluntariado');
    var boton;
    var hoy;
    var mesVisible;
    var diasSemana = ['L', 'M', 'X', 'J', 'V', 'S', 'D'];
    var turnos = [
        { rango_horario: 'sabado-09-11', dia: 'Sábado', rango: '09:00 - 11:00' },
        { rango_horario: 'sabado-14-16', dia: 'Sábado', rango: '14:00 - 16:00' },
        { rango_horario: 'domingo-10-12', dia: 'Domingo', rango: '10:00 - 12:00' }
    ];

    if (!formulario) {
        return;
    }

    boton = formulario.querySelector('.btn-primary');
    pegarDatosSesion();
    hoy = new Date();
    hoy.setHours(0, 0, 0, 0);
    mesVisible = new Date(hoy.getFullYear(), hoy.getMonth(), 1);

    if (calendario && fecha) {
        calendario.querySelector('[data-mes-anterior]').addEventListener('click', function () {
            mesVisible.setMonth(mesVisible.getMonth() - 1);
            pintarCalendario();
        });

        calendario.querySelector('[data-mes-siguiente]').addEventListener('click', function () {
            mesVisible.setMonth(mesVisible.getMonth() + 1);
            pintarCalendario();
        });

        pintarCabeceraDias();
        pintarCalendario();
    }

    pintarHorarios([]);

    formulario.addEventListener('submit', function (evento) {
        evento.preventDefault();

        if (aviso) {
            aviso.textContent = '';
            aviso.removeAttribute('data-estado');
            aviso.classList.remove('alert-success', 'alert-danger');
        }

        if (boton) {
            boton.disabled = true;
        }

        apiRequest('voluntariado', {
            metodo: 'POST',
            cuerpo: armarCuerpo()
        }).then(function (respuesta) {
            pintarAviso(respuesta, true);
            formulario.reset();
            limpiarTurno();
        }).catch(function (error) {
            pintarAviso(error, false);
        }).finally(function () {
            if (boton) {
                boton.disabled = false;
            }
        });
    });

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

    function pegarDatosSesion() {
        var campos = {
            nombre: valorCookie('usuario_nombre'),
            apellido: valorCookie('usuario_apellido'),
            email: valorCookie('usuario_email')
        };
        var id;
        var campo;

        for (id in campos) {
            if (!Object.prototype.hasOwnProperty.call(campos, id)) {
                continue;
            }

            campo = formulario.elements[id];

            if (campo && !campo.value && campos[id]) {
                campo.value = campos[id];
            }
        }
    }

    function aYmd(d) {
        var mes = String(d.getMonth() + 1);
        var dia = String(d.getDate());

        if (mes.length < 2) {
            mes = '0' + mes;
        }

        if (dia.length < 2) {
            dia = '0' + dia;
        }

        return d.getFullYear() + '-' + mes + '-' + dia;
    }

    function esFinDeSemana(fechaLocal) {
        var n = fechaLocal.getDay();

        return n === 0 || n === 6;
    }

    function esDiaHabilitado(fechaLocal) {
        return esFinDeSemana(fechaLocal) && aYmd(fechaLocal) >= aYmd(hoy);
    }

    function sincronizarFechaAttr() {
        if (!fecha) {
            return;
        }

        if (fecha.value) {
            fecha.setAttribute('value', fecha.value);
        } else {
            fecha.removeAttribute('value');
        }
    }

    function limpiarTurno() {
        if (fecha) {
            fecha.value = '';
            sincronizarFechaAttr();
        }

        mesVisible = new Date(hoy.getFullYear(), hoy.getMonth(), 1);
        pintarCalendario();
        pintarHorarios([]);
    }

    function turnosParaFecha(valorFecha) {
        var d = new Date(valorFecha + 'T12:00:00');
        var n;
        var diaEsperado;
        var i;
        var lista = [];

        if (isNaN(d.getTime())) {
            return lista;
        }

        n = d.getDay();
        diaEsperado = n === 6 ? 'Sábado' : (n === 0 ? 'Domingo' : '');

        if (!diaEsperado) {
            return lista;
        }

        for (i = 0; i < turnos.length; i++) {
            if (turnos[i].dia === diaEsperado) {
                lista.push(turnos[i]);
            }
        }

        return lista;
    }

    function pintarCabeceraDias() {
        var fila = calendario.querySelector('[data-calendario-cabecera]');
        var i;
        var col;

        if (!fila) {
            return;
        }

        fila.innerHTML = '';

        for (i = 0; i < diasSemana.length; i++) {
            col = document.createElement('div');
            col.className = 'calendario-celda';
            col.textContent = diasSemana[i];
            fila.appendChild(col);
        }
    }

    function pintarHorarios(items) {
        var i;
        var opcion;
        var lista = Array.isArray(items) ? items : [];

        if (!turno) {
            return;
        }

        turno.innerHTML = '<option value="" selected hidden></option>';

        for (i = 0; i < lista.length; i++) {
            if (!lista[i] || !lista[i].rango_horario) {
                continue;
            }

            opcion = document.createElement('option');
            opcion.value = lista[i].rango_horario;
            opcion.textContent = lista[i].dia + ' ' + lista[i].rango;
            turno.appendChild(opcion);
        }

        turno.value = '';
    }

    function pintarCalendario() {
        var grilla = calendario.querySelector('[data-calendario-grid]');
        var titulo = calendario.querySelector('[data-mes-titulo]');
        var btnAnterior = calendario.querySelector('[data-mes-anterior]');
        var anio = mesVisible.getFullYear();
        var mes = mesVisible.getMonth();
        var primerDia = new Date(anio, mes, 1);
        var ultimoDia = new Date(anio, mes + 1, 0).getDate();
        var huecos = (primerDia.getDay() + 6) % 7;
        var seleccion = fecha ? fecha.value : '';
        var i;
        var col;
        var btn;
        var fechaLocal;
        var valor;
        var n;

        if (!grilla || !titulo) {
            return;
        }

        titulo.textContent = primerDia.toLocaleDateString('es-AR', { month: 'long', year: 'numeric' });
        grilla.innerHTML = '';

        if (btnAnterior) {
            btnAnterior.disabled = mesVisible.getFullYear() === hoy.getFullYear() && mesVisible.getMonth() === hoy.getMonth();
        }

        for (i = 0; i < huecos; i++) {
            col = document.createElement('div');
            col.className = 'calendario-celda';
            col.setAttribute('aria-hidden', 'true');
            col.innerHTML = '&nbsp;';
            grilla.appendChild(col);
        }

        for (i = 1; i <= ultimoDia; i++) {
            fechaLocal = new Date(anio, mes, i);
            valor = aYmd(fechaLocal);
            col = document.createElement('div');
            col.className = 'calendario-celda';

            if (!esFinDeSemana(fechaLocal) || !esDiaHabilitado(fechaLocal)) {
                col.innerHTML = '<span class="calendario-dia-inactivo d-block py-1">' + i + '</span>';
                grilla.appendChild(col);
                continue;
            }

            btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'btn btn-sm w-100 calendario-dia-fin-semana border-0';
            btn.textContent = String(i);

            if (seleccion === valor) {
                btn.classList.add('calendario-dia-elegido');
            }

            btn.setAttribute('aria-label', 'Elegir ' + valor);
            btn.addEventListener('click', function (v) {
                return function () {
                    fecha.value = v;
                    sincronizarFechaAttr();
                    pintarHorarios(turnosParaFecha(v));
                    pintarCalendario();
                };
            }(valor));

            col.appendChild(btn);
            grilla.appendChild(col);
        }

        i = (huecos + ultimoDia) % 7;
        if (i !== 0) {
            for (n = i; n < 7; n++) {
                col = document.createElement('div');
                col.className = 'calendario-celda';
                col.setAttribute('aria-hidden', 'true');
                col.innerHTML = '&nbsp;';
                grilla.appendChild(col);
            }
        }
    }

    function armarCuerpo() {
        var datos = {};
        var i;
        var campo;

        for (i = 0; i < formulario.elements.length; i++) {
            campo = formulario.elements[i];

            if (!campo.name || campo.disabled || campo.type === 'submit' || campo.type === 'button') {
                continue;
            }

            if (campo.type === 'checkbox') {
                datos[campo.name] = campo.checked;
            } else {
                datos[campo.name] = String(campo.value || '').trim();
            }
        }

        return datos;
    }

    function pintarAviso(origen, ok) {
        var texto;
        var infoTurno;

        if (!aviso) {
            return;
        }

        texto = mensajeDeApi(origen);

        if (ok) {
            infoTurno = origen && origen.data && origen.data.turno;

            if (infoTurno && infoTurno.fecha) {
                texto = texto + ' ' + infoTurno.fecha;
            }

            if (infoTurno && infoTurno.dia) {
                texto = texto + ' ' + infoTurno.dia;
            }

            if (infoTurno && infoTurno.rango) {
                texto = texto + ' ' + infoTurno.rango;
            }

            texto = texto + ' Listo! Si no te llega el mail en unos minutos, fijate en spam.';
        }

        aviso.hidden = false;
        aviso.textContent = texto || '';
        aviso.setAttribute('data-estado', ok ? 'ok' : 'error');
        aviso.classList.toggle('alert-success', ok);
        aviso.classList.toggle('alert-danger', !ok);
    }
});
