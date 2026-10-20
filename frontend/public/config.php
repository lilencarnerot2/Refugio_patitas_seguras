<?php
header('Content-Type: application/javascript');

echo 'window.APP_CONFIG = ' . json_encode([
    'backendUrl' => '/public/api.php',
    'inicioUrl' => '/index.php',
    'panelUrl' => '/admin/dashboard.php',
    'loginUrl' => '/public/login.php'
]) . ';';
