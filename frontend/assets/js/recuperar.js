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

    function inicializarFormularioEmail() {
        const boton = formEmail.querySelector('.btn-primary');
        const mensaje = formEmail.querySelector('.campo-obligatorio');

        if (!boton) {
            return;
        }

        boton.addEventListener('click', function () {
            const campoEmail = document.getElementById('email');

            campoEmail.value = campoEmail.value.trim();

            boton.disabled = true;

            enviarFormularioApi(formEmail, mensaje, 'password-reset-solicitar', {
                metodo: 'POST',
                cuerpo: { email: document.getElementById('email').value.trim() }
            }).catch(function () {
                return null;
            }).finally(function () {
                boton.disabled = false;
            });
        });
    }

    function inicializarFormularioPassword() {
        const boton = formPassword.querySelector('.btn-primary');
        const mensaje = formPassword.querySelector('.campo-obligatorio');

        if (!boton) {
            return;
        }

        boton.addEventListener('click', function () {
            boton.disabled = true;

            enviarFormularioApi(formPassword, mensaje, 'password-reset-confirmar', {
                metodo: 'POST',
                cuerpo: {
                    token: document.getElementById('token').value,
                    password: document.getElementById('password').value
                }
            }).then(function () {
                window.location.href = 'login.php?reset=ok';
            }).catch(function () {
                return null;
            }).finally(function () {
                boton.disabled = false;
            });
        });
    }
});
