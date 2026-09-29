document.addEventListener('DOMContentLoaded', function () {
    const formulario = document.getElementById('form-login');

    if (!formulario) {
        return;
    }

    const boton = formulario.querySelector('button[type="button"]');

    if (!boton) {
        return;
    }

    boton.addEventListener('click', function () {
        const correo = document.getElementById('correo').value.trim();
        const clave = document.getElementById('clave').value;

        if (!correo || !clave) {
            alert('Completá el correo y la contraseña.');
            return;
        }

        boton.disabled = true;

        apiRequest('login', {
            metodo: 'POST',
            cuerpo: {
                email: correo,
                password: clave
            }
        })
        .then(function (respuesta) {
            alert(respuesta.message || 'Inicio de sesión exitoso.');

            window.location.href = '../index.php';
        })
        .catch(function (error) {
            alert(error.message || 'No se pudo iniciar sesión.');
        })
        .finally(function () {
            boton.disabled = false;
        });
    });
});