<?php $refugio = require __DIR__ . '/datos_refugio.php'; ?>
    <footer id="contacto">
        <div class="footer-inner">
            <div>
                <h3>PATITAS SEGURAS</h3>
                <p class="footer-tagline">Refugio de animales</p>
                <div class="social-icons">
                    <a href="https://facebook.com" target="_blank" title="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="https://instagram.com" target="_blank" title="Instagram"><i class="bi bi-instagram"></i></a>
                    <a href="https://tiktok.com" target="_blank" title="TikTok"><i class="bi bi-tiktok"></i></a>
                </div>
            </div>
            <div class="footer-contact">
                <p><i class="bi bi-telephone"></i> <?php echo htmlspecialchars($refugio['telefono'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p><i class="bi bi-envelope"></i> <?php echo htmlspecialchars($refugio['email'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p><i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($refugio['direccion'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p class="footer-note"><?php echo htmlspecialchars($refugio['lema'], ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
        </div>
    </footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
