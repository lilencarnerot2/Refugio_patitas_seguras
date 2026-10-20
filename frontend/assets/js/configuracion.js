document.addEventListener('DOMContentLoaded', function() {
    
    var rutaConfig = (window.APP_CONFIG && window.APP_CONFIG.configuracion) ? window.APP_CONFIG.configuracion : 'configuracion';

    // Función para cargar los datos (cuando el backend esté listo)
    function cargarConfiguracion() {
        
    }

    // Botón Guardar Contacto
    document.getElementById('btn-guardar-contacto').addEventListener('click', function() {
        const boton = this;
        boton.disabled = true;
        boton.innerHTML = 'Guardando...';

        // Simulación por ahora
        setTimeout(() => {
            alert('¡Datos de contacto guardados con éxito! (Simulación frontend)');
            boton.disabled = false;
            boton.innerHTML = '<i class="fa-solid fa-floppy-disk me-2"></i>Guardar Cambios';
        }, 800);
    });

    // Botón Guardar Donación
    document.getElementById('btn-guardar-donacion').addEventListener('click', function() {
        const boton = this;
        boton.disabled = true;
        boton.innerHTML = 'Guardando...';

        // Simulación por ahora
        setTimeout(() => {
            alert('¡Datos de donación guardados con éxito! (Simulación frontend)');
            boton.disabled = false;
            boton.innerHTML = '<i class="fa-solid fa-floppy-disk me-2"></i>Guardar Cambios';
        }, 800);
    });
});