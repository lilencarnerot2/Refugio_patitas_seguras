document.addEventListener('DOMContentLoaded', function () {
    var panel = document.getElementById('panel-mi-perfil');
    if (!panel) {
        return;
    }

    var linkPanel = document.querySelector('.perfil-topbar .nav-panel');
    var menu = document.getElementById('menu-perfil');
    var toggleMenu = document.getElementById('menu-perfil-toggle');

    if (toggleMenu && window.matchMedia('(min-width: 993px)').matches) {
        toggleMenu.checked = true;
    }

    if (linkPanel) {
        linkPanel.hidden = !valorCookie('usuario_acceso_admin');
    }

    function cerrarMenuMovil() {
        if (toggleMenu && window.matchMedia('(max-width: 992px)').matches) {
            toggleMenu.checked = false;
        }
    }

    if (menu) {
        menu.querySelectorAll('a[href]').forEach(function (link) {
            link.addEventListener('click', function () {
                cerrarMenuMovil();
            });
        });
    }
});
