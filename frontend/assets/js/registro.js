document.addEventListener('DOMContentLoaded', function () {
    const formulario = document.getElementById('form-registro');

    if (!formulario) {
        return;
    }

    const boton = formulario.querySelector('button[type="submit"]');

    if (!boton) {
        return;
    }

    formulario.addEventListener('submit', function (evento) {
        var mensaje = formulario.querySelector('.campo-obligatorio');
        var correo = document.getElementById('correo');

        evento.preventDefault();
        correo.value = correo.value.trim();

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
        .catch(function () {
            return null;
        })
        .finally(function () {
            boton.disabled = false;
        });
    });
});
