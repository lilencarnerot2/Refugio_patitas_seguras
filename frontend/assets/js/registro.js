document.addEventListener('DOMContentLoaded', function () {
    const formulario = document.getElementById('form-registro');

    if (!formulario) {
        return;
    }

    const boton = formulario.querySelector('.btn-primary');

    if (!boton) {
        return;
    }

    boton.addEventListener('click', function () {
        var mensaje = formulario.querySelector('.campo-obligatorio');
        var correo = document.getElementById('correo');

        correo.value = correo.value.trim();

        if (!formatosValidos(formulario, mensaje)) {
            return;
        }

        boton.disabled = true;

        enviarFormularioApi(formulario, mensaje, 'registro', {
            metodo: 'POST',
            cuerpo: {
                nombre: document.getElementById('nombre').value.trim(),
                apellido: document.getElementById('apellido').value.trim(),
                email: document.getElementById('correo').value.trim(),
                password: document.getElementById('clave').value,
                fecha_nacimiento: document.getElementById('fecha_nacimiento').value,
                telefono: document.getElementById('telefono').value.trim()
            }
        })
        .then(function (respuesta) {
            return irTrasSesion(respuesta);
        })
        .catch(function (error) {
            if (error && error.status === 401) {
                avisoDeApi(mensaje, 'Ese correo ya tiene una cuenta. Si es tuya, ingresá la misma contraseña con la que la creaste o recuperala desde "Ingresar" > "Recuperar contraseña".', false, formulario);
                formulario.querySelector('#clave').setAttribute('data-campo-estado', 'error');
            }

            return null;
        })
        .finally(function () {
            boton.disabled = false;
        });
    });
});
