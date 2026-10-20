<?php $refugio = require __DIR__ . '/../../public/includes/datos_refugio.php'; ?>
    <footer class="perfil-footer">
        <div class="d-flex flex-column flex-lg-row align-items-center justify-content-between gap-2 gap-lg-3 text-center text-lg-start">
            <div class="d-flex align-items-center gap-2">
                <span class="perfil-footer-logo rounded-circle d-inline-flex align-items-center justify-content-center"><i class="fa-solid fa-paw"></i></span>
                <div>
                    <strong class="d-block">PATITAS SEGURAS</strong>
                    <small>Refugio de animales</small>
                </div>
            </div>

            <ul class="list-unstyled d-flex flex-wrap justify-content-center gap-2 gap-md-3 mb-0 small">
                <li><i class="bi bi-telephone"></i> <?php echo $refugio['telefono']; ?></li>
                <li><i class="bi bi-envelope"></i> <?php echo $refugio['email']; ?></li>
                <li><i class="bi bi-geo-alt"></i> <?php echo $refugio['direccion']; ?></li>
            </ul>

        </div>
    </footer>
<script src="/assets/js/loader.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
