<?php $refugio = require __DIR__ . '/datos_refugio.php'; ?>
    <footer id="contacto">
        <div class="footer-inner">
            <div>
                <h3>PATITAS SEGURAS</h3>
                <p class="footer-tagline">Refugio de animales</p>
            </div>
            <div class="footer-contact">
                <p><i class="bi bi-telephone"></i> <?php echo $refugio['telefono']; ?></p>
                <p><i class="bi bi-envelope"></i> <?php echo $refugio['email']; ?></p>
                <p><i class="bi bi-geo-alt"></i> <?php echo $refugio['direccion']; ?></p>
                <p class="footer-note"><?php echo $refugio['lema']; ?></p>
            </div>
        </div>
    </footer>
<script src="/assets/js/loader.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
