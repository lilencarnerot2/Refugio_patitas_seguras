<?php $refugio = require __DIR__ . '/../../public/includes/datos_refugio.php'; ?>
    <footer>
        <div class="footer-inner footer-inner-solo-frase">
            <p class="footer-note mb-0"><?php echo htmlspecialchars($refugio['lema'], ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
    </footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
