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
    var turnos = [];
    var diasHabilitados = [];
    var turnosListos = false;

    if (!formulario) {
        return;
    }

    boton = formulario.querySelector('.btn-primary');
    pegarDatosSesion();
    hoy = new Date();
    hoy.setHours(0, 0, 0, 0);
    mesVisible = new Date(hoy.getFullYear(), hoy.getMonth(), 1);

    actualizarEnvio();

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

    cargarTurnosVoluntariado().then(function (respuesta) {
        aplicarTurnos(respuesta);
    }).catch(function (error) {
        aplicarTurnos(null);
        avisoDeApi(aviso, error, false);
    }).finally(function () {
        turnosListos = true;
        actualizarEnvio();
    });

    function cargarTurnosVoluntariado() {
        return apiRequest('voluntariado_turnos');
    }

    function numeroDiaSemanaDeTurno(item) {
        var numero;

        if (!item || item.dia_semana === undefined || item.dia_semana === null || item.dia_semana === '') {
            return NaN;
        }

        numero = Number(item.dia_semana);

        return isNaN(numero) ? NaN : numero;
    }

    function diasHabilitadosDesdeTurnos(lista) {
        var resultado = [];
        var i;
        var numero;

        for (i = 0; i < lista.length; i++) {
            numero = numeroDiaSemanaDeTurno(lista[i]);

            if (!isNaN(numero) && resultado.indexOf(numero) === -1) {
                resultado.push(numero);
            }
        }

        return resultado;
    }

    function aplicarTurnos(respuesta) {
        turnos = respuesta ? turnosVoluntariadoDe(respuesta) : [];
        diasHabilitados = diasHabilitadosDesdeTurnos(turnos);

        if (calendario && fecha) {
            pintarCalendario();
        }

        if (turnos.length && aviso && aviso.getAttribute('data-estado') !== 'error') {
            avisoDeApi(aviso, '', true);
        }
    }

    function actualizarEnvio() {
        if (!boton) {
            return;
        }

        boton.disabled = !turnosListos || !turnos.length;
    }

    formulario.addEventListener('submit', function (evento) {
        evento.preventDefault();

        if (boton && boton.disabled) {
            return;
        }

        if (boton) {
            boton.disabled = true;
        }

        enviarFormularioApi(formulario, aviso, 'voluntariado', {
            metodo: 'POST',
            cuerpo: armarCuerpo()
        }).then(function (respuesta) {
            if (voluntariadoSinMailUsuario(respuesta)) {
                completarAvisoSinMailUsuario(aviso, respuesta);
            }

            formulario.reset();
            limpiarTurno();
        }).catch(function () {
            return null;
        }).finally(function () {
            actualizarEnvio();
        });
    });

    function voluntariadoSinMailUsuario(respuesta) {
        var datos;

        if (!respuesta || !respuesta.data) {
            return false;
        }

        datos = respuesta.data;

        return datos.mail_usuario_enviado === false;
    }

    function completarAvisoSinMailUsuario(caja, respuesta) {
        var partes;
        var extra;
        var turno;
        var texto;

        if (!caja) {
            return;
        }

        partes = [mensajeDeApi(respuesta).trim()];
        turno = respuesta && respuesta.data && respuesta.data.turno;

        if (turno) {
            if (turno.fecha) {
                partes.push(String(turno.fecha).trim());
            }

            if (turno.dia) {
                partes.push(String(turno.dia).trim());
            }

            if (turno.rango) {
                partes.push(String(turno.rango).trim());
            }
        }

        extra = caja.getAttribute('data-aviso-exito');

        if (extra) {
            partes.push(extra.trim());
        }

        texto = partes.filter(Boolean).join(' ');

        if (!texto) {
            return;
        }

        caja.textContent = texto;
        caja.setAttribute('data-estado', 'ok');
        caja.removeAttribute('aria-busy');
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

    function esDiaCalendarioHabilitado(fechaLocal) {
        var n = fechaLocal.getDay();
        var i;

        if (!diasHabilitados.length) {
            return false;
        }

        for (i = 0; i < diasHabilitados.length; i++) {
            if (diasHabilitados[i] === n) {
                return true;
            }
        }

        return false;
    }

    function esDiaHabilitado(fechaLocal) {
        return esDiaCalendarioHabilitado(fechaLocal) && aYmd(fechaLocal) >= aYmd(hoy);
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

        if (isNaN(d.getTime())) {
            return [];
        }

        n = d.getDay();

        return turnos.filter(function (item) {
            var diaSemana = numeroDiaSemanaDeTurno(item);

            return !isNaN(diaSemana) && diaSemana === n;
        });
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

    function etiquetaTurno(item) {
        var partes = [];

        if (item.dia) {
            partes.push(String(item.dia).trim());
        }

        if (item.rango) {
            partes.push(String(item.rango).trim());
        } else if (item.horario) {
            partes.push(String(item.horario).trim());
        }

        return partes.join(' ');
    }

    function pintarHorarios(items) {
        var i;
        var opcion;
        var lista = Array.isArray(items) ? items : [];
        var clave;

        if (!turno) {
            return;
        }

        turno.innerHTML = '<option value="" selected hidden></option>';

        for (i = 0; i < lista.length; i++) {
            clave = claveTurnoVoluntariado(lista[i]);

            if (!clave) {
                continue;
            }

            opcion = document.createElement('option');
            opcion.value = clave;
            opcion.textContent = etiquetaTurno(lista[i]) || clave;
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

            if (!esDiaCalendarioHabilitado(fechaLocal) || !esDiaHabilitado(fechaLocal)) {
                col.innerHTML = '<span class="calendario-dia-inactivo d-block py-1">' + i + '</span>';
                grilla.appendChild(col);
                continue;
            }

            btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'btn btn-sm w-100 calendario-dia-fin-semana border-0';
            btn.textContent = String(i);
            btn.setAttribute('aria-pressed', seleccion === valor ? 'true' : 'false');
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

});
