document.addEventListener('DOMContentLoaded', function () {
    const formulario = document.getElementById('form-registro');

    if (!formulario) {
        return;
    }

    const boton = formulario.querySelector('button[type="button"]');

    if (!boton) {
        return;
    }

    boton.addEventListener('click', function () {
        const nombre = document.getElementById('nombre').value.trim();
        const apellido = document.getElementById('apellido').value.trim();
        const correo = document.getElementById('correo').value.trim();
        const clave = document.getElementById('clave').value;

        // Validar campos vacíos
        if (!nombre || !apellido || !correo || !clave) {
            alert('Completá todos los campos.');
            return;
        }

        // Validar largo de contraseña (según el contrato del Padrón: mínimo 8 caracteres)
        if (clave.length < 8) {
            alert('La contraseña debe tener al menos 8 caracteres.');
            return;
        }

        boton.disabled = true;

        apiRequest('registro', {
            metodo: 'POST',
            cuerpo: {
                nombre: nombre,
                apellido: apellido,
                email: correo,
                password: clave
            }
        })
        .then(function (respuesta) {
            alert(respuesta.message || 'Usuario registrado correctamente.');
            window.location.href = '/index.php';
        })
        .catch(function (error) {
            alert(error.message || 'No se pudo registrar el usuario.');
        })
        .finally(function () {
            boton.disabled = false;
        });
    });
});