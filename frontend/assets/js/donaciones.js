document.addEventListener('DOMContentLoaded', function () {
    var panel = document.getElementById('panel-aportes-sitio');

    if (!panel) {
        return;
    }

    cargarCatalogos(panel).then(function () {
        enlazarFormularioMovimiento(document.getElementById('form-movimiento'));
    });
});
