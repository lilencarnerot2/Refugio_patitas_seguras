<?php
header('Content-Type: application/javascript');

echo 'window.APP_CONFIG = ' . json_encode([
    'backendUrl' => getenv('BACKEND_SERVICE_URL'),
    'inicioUrl' => '/index.php',
    'panelUrl' => '/admin/dashboard.php'
]) . ';';
