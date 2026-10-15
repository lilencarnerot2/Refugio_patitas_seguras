document.addEventListener('DOMContentLoaded', function () {
    const params = new URLSearchParams(window.location.search);
    const token = params.get('token');

    const formEmail = document.getElementById('form-recuperar-email');
    const formPassword = document.getElementById('form-recuperar-password');
    const titulo = document.getElementById('titulo-recuperar');
    const subtitulo = document.getElementById('subtitulo-recuperar');

    if (!formEmail || !formPassword) {
        return;
    }

    // Decidir qué formulario mostrar según si hay token en la URL
    if (token) {
        formEmail.hidden = true;
        formPassword.hidden = false;
        document.getElementById('token').value = token;
        titulo.textContent = 'Nueva contraseña';
        subtitulo.textContent = 'Ingresá tu nueva contraseña.';
        inicializarFormularioPassword();
    } else {
        formEmail.hidden = false;
        formPassword.hidden = true;
        inicializarFormularioEmail();
    }

    // Flujo A: pedir email
    function inicializarFormularioEmail() {
        const boton = formEmail.querySelector('.btn-primary');
        if (!boton) {
            return;
        }

        boton.addEventListener('click', function () {
            const email = document.getElementById('email').value.trim();

            if (!email) {
                alert('Ingresá tu correo electrónico.');
                return;
            }

            boton.disabled = true;
            const textoOriginal = boton.textContent;
            boton.textContent = 'Enviando...';

            apiRequest('password-reset-solicitar', {
                metodo: 'POST',
                cuerpo: { email: email }
            })
            .then(function (respuesta) {
                alert(respuesta.message || 'Si el email está registrado, te enviamos un mail.');
            })
            .catch(function (error) {
                const status = (error && error.status) ? error.status : 0;
                const mensaje = (error && error.message) ? error.message : '';

                if (status === 409) {
                    alert('El proyecto todavía no tiene configurada la URL de reset. Contactá al administrador.');
                } else if (status === 400) {
                    alert(mensaje || 'El email no es válido.');
                } else if (status === 404) {
                    // no revelamos si existe el mail por seguridad
                    alert('Si el email está registrado, te enviamos un mail.');
                } else {
                    alert(mensaje || 'No se pudo procesar la solicitud. Intentá de nuevo más tarde.');
                }
            })
            .finally(function () {
                boton.disabled = false;
                boton.textContent = textoOriginal;
            });
        });
    }

    // Flujo B: nueva contraseña
    function inicializarFormularioPassword() {
        const boton = formPassword.querySelector('.btn-primary');
        if (!boton) {
            return;
        }

        boton.addEventListener('click', function () {
            const password = document.getElementById('password').value;
            const tokenActual = document.getElementById('token').value;

            if (!password) {
                alert('Ingresá tu nueva contraseña.');
                return;
            }

            if (password.length < 8) {
                alert('La contraseña debe tener al menos 8 caracteres.');
                return;
            }

            boton.disabled = true;
            const textoOriginal = boton.textContent;
            boton.textContent = 'Guardando...';

            apiRequest('password-reset-confirmar', {
                metodo: 'POST',
                cuerpo: {
                    token: tokenActual,
                    password: password
                }
            })
            .then(function (respuesta) {
                alert(respuesta.message || 'Contraseña actualizada correctamente.');
                window.location.href = 'login.php?reset=ok';
            })
            .catch(function (error) {
                const status = (error && error.status) ? error.status : 0;
                const mensaje = (error && error.message) ? error.message : '';

                if (status === 400) {
                    alert('El token no sirve o ya venció. Pedí uno nuevo.');
                } else if (status === 422) {
                    alert('La contraseña debe tener entre 8 y 72 caracteres.');
                } else {
                    alert(mensaje || 'No se pudo cambiar la contraseña. Intentá de nuevo más tarde.');
                }
            })
            .finally(function () {
                boton.disabled = false;
                boton.textContent = textoOriginal;
            });
        });
    }
});