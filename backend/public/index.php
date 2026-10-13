<?php

$frontend_url = getenv('FRONTEND_URL');

if ($frontend_url) {
    header("Access-Control-Allow-Origin: $frontend_url");
}
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Credentials: true');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once __DIR__ . '/../src/routes/api.php';

manejarRutas();