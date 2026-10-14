<?php

$backend_url = getenv('BACKEND_INTERNAL_URL');

if (!$backend_url) {
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');

    echo json_encode([
        'success' => false,
        'message' => 'Backend interno no configurado.'
    ]);

    exit;
}

$metodo = $_SERVER['REQUEST_METHOD'];

$query_string = $_SERVER['QUERY_STRING'] ?? '';

$url = rtrim($backend_url, '/');

if ($query_string !== '') {
    $url .= '/?' . $query_string;
} else {
    $url .= '/';
}


$headers = [
    'Content-Type: ' .
        ($_SERVER['CONTENT_TYPE'] ?? 'application/json')
];

if (!empty($_SERVER['HTTP_AUTHORIZATION'])) {
    $headers[] =
        'Authorization: ' .
        $_SERVER['HTTP_AUTHORIZATION'];
}

if (!empty($_SERVER['HTTP_COOKIE'])) {
    $headers[] =
        'Cookie: ' .
        $_SERVER['HTTP_COOKIE'];
}


$contenido = file_get_contents('php://input');

$opciones = [
    'http' => [
        'method' => $metodo,
        'header' => implode("\r\n", $headers),
        'ignore_errors' => true,
        'timeout' => 15
    ]
];

if (
    in_array(
        $metodo,
        ['POST', 'PUT', 'PATCH', 'DELETE'],
        true
    )
) {
    $opciones['http']['content'] = $contenido;
}


$contexto = stream_context_create($opciones);

$respuesta = @file_get_contents(
    $url,
    false,
    $contexto
);


if ($respuesta === false && empty($http_response_header)) {

    http_response_code(502);
    header('Content-Type: application/json; charset=utf-8');

    echo json_encode([
        'success' => false,
        'message' => 'No se pudo conectar con el backend.'
    ]);

    exit;
}


$codigo_http = 200;

foreach ($http_response_header ?? [] as $header) {

    if (
        preg_match(
            '#HTTP/\S+\s+(\d{3})#',
            $header,
            $coincidencia
        )
    ) {
        $codigo_http = (int) $coincidencia[1];
    }

    if (
        stripos($header, 'Content-Type:') === 0
    ) {
        header($header);
    }

    if (
        stripos($header, 'Set-Cookie:') === 0
    ) {
        header($header, false);
    }
}


http_response_code($codigo_http);

echo $respuesta;