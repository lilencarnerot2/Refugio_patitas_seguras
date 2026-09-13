    <footer id="contacto">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
            <div>
                <h3>PATITAS SEGURAS</h3>
                <p style="font-size: 12px; color: #ccc;">Refugio de animales</p>
                <div class="social-icons">
                    <a href="https://facebook.com" target="_blank" title="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="https://instagram.com" target="_blank" title="Instagram"><i class="fa-brands fa-instagram"></i></a>
                    <a href="https://tiktok.com" target="_blank" title="TikTok"><i class="fa-brands fa-tiktok"></i></a>
                </div>
            </div>
            <div>
                <p style="font-size: 13px;"><i class="fa-solid fa-phone"></i> 11 2345 6789</p>
                <p style="font-size: 13px; margin-top: 5px;"><i class="fa-solid fa-envelope"></i> hola@patitaseguras.org</p>
                <p style="font-size: 13px; margin-top: 5px;"><i class="fa-solid fa-location-dot"></i> Merlo, Buenos Aires, Argentina</p>
                <p style="font-size: 12px; margin-top: 10px; color: #ccc;">Amor que se nota, huellas que quedan. 🐾</p>
            </div>
        </div>
    </footer>

<?php if (!empty($scriptsPagina) && is_array($scriptsPagina)): ?>
    <script>
        window.APP_CONFIG = {
            backendUrl: <?php echo json_encode(getenv('BACKEND_SERVICE_URL') ?: ''); ?>
        };
    </script>
    <script src="../assets/js/api.js?t=<?php echo filemtime(__DIR__ . '/../../assets/js/api.js'); ?>"></script>
    <script src="../assets/js/app.js?t=<?php echo filemtime(__DIR__ . '/../../assets/js/app.js'); ?>"></script>
<?php foreach ($scriptsPagina as $script): ?>
<?php $rutaScript = __DIR__ . '/../../assets/js/' . $script; ?>
    <script src="../assets/js/<?php echo htmlspecialchars($script, ENT_QUOTES, 'UTF-8'); ?>?t=<?php echo is_file($rutaScript) ? filemtime($rutaScript) : time(); ?>"></script>
<?php endforeach; ?>
<?php endif; ?>
</body>
</html>
