    <footer>
        <div class="footer-inner">
            <div>
                <h3>PATITAS SEGURAS</h3>
                <p class="footer-tagline">Refugio de animales</p>
            </div>
            <div class="footer-contact">
                <p><i class="bi bi-envelope"></i> hola@patitaseguras.org</p>
                <p><i class="bi bi-geo-alt"></i> Merlo, Buenos Aires, Argentina</p>
            </div>
        </div>
    </footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function () {
    if (window.bootstrap && window.bootstrap.Collapse) {
        return;
    }

    var boton = document.querySelector('[data-bs-target="#menu-admin"]');
    var menu = document.getElementById('menu-admin');

    if (!boton || !menu) {
        return;
    }

    boton.addEventListener('click', function () {
        var abierto = menu.classList.toggle('show');

        boton.setAttribute('aria-expanded', abierto ? 'true' : 'false');
    });

    if (window.innerWidth >= 993 && !menu.classList.contains('show')) {
        if (window.bootstrap && window.bootstrap.Collapse) {
            window.bootstrap.Collapse.getOrCreateInstance(menu).show();
        } else {
            menu.classList.add('show');
            boton.setAttribute('aria-expanded', 'true');
        }
    }
})();
</script>
</body>
</html>
