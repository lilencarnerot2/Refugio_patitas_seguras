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
        .catch(function () {
            return null;
        })
        .finally(function () {
            boton.disabled = false;
        });
    });
});
