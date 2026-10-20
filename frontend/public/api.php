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

if (isset($_GET['archivo'])) {
    $ruta_archivo = (string) $_GET['archivo'];

    if (
        $_SERVER['REQUEST_METHOD'] !== 'GET'
        || !preg_match('#^/uploads/mascotas/[A-Za-z0-9][A-Za-z0-9._-]*$#', $ruta_archivo)
        || strpos($ruta_archivo, '..') !== false
    ) {
        http_response_code(404);
        header('Content-Type: application/json; charset=utf-8');

        echo json_encode([
            'success' => false,
            'message' => 'Archivo no encontrado.'
        ]);

        exit;
    }

    $contexto_archivo = stream_context_create([
        'http' => [
            'method' => 'GET',
            'ignore_errors' => true,
            'timeout' => 30
        ]
    ]);

    $contenido_archivo = @file_get_contents(
        rtrim($backend_url, '/') . $ruta_archivo,
        false,
        $contexto_archivo
    );

    $codigo_archivo = 0;
    $tipo_archivo = '';

    foreach ($http_response_header ?? [] as $cabecera) {
        if (preg_match('#HTTP/\S+\s+(\d{3})#', $cabecera, $coincidencia)) {
            $codigo_archivo = (int) $coincidencia[1];
        }

        if (stripos($cabecera, 'Content-Type:') === 0) {
            $tipo_archivo = strtolower(trim(substr($cabecera, 13)));
        }
    }

    if (
        $contenido_archivo === false
        || $codigo_archivo !== 200
        || strpos($tipo_archivo, 'image/') !== 0
    ) {
        http_response_code(404);
        header('Content-Type: application/json; charset=utf-8');

        echo json_encode([
            'success' => false,
            'message' => 'Archivo no encontrado.'
        ]);

        exit;
    }

    header('Content-Type: ' . $tipo_archivo);
    header('X-Content-Type-Options: nosniff');
    header('Cache-Control: public, max-age=86400');

    echo $contenido_archivo;

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


$tipo_contenido = $_SERVER['CONTENT_TYPE'] ?? 'application/json';
$es_multipart = stripos($tipo_contenido, 'multipart/form-data') === 0;
$contenido_multipart = '';

if ($es_multipart) {
    $mensaje_archivo = '';

    if (
        empty($_POST)
        && empty($_FILES)
        && (int) ($_SERVER['CONTENT_LENGTH'] ?? 0) > 0
    ) {
        $mensaje_archivo = 'El archivo supera el tamaño máximo que acepta el servidor.';
    }

    foreach ($_FILES as $archivo) {
        if (
            $archivo['error'] === UPLOAD_ERR_INI_SIZE
            || $archivo['error'] === UPLOAD_ERR_FORM_SIZE
        ) {
            $mensaje_archivo = 'El archivo supera el tamaño máximo que acepta el servidor.';
        } elseif (
            $archivo['error'] !== UPLOAD_ERR_OK
            && $archivo['error'] !== UPLOAD_ERR_NO_FILE
        ) {
            $mensaje_archivo = 'No se pudo recibir el archivo.';
        }
    }

    if ($mensaje_archivo !== '') {
        http_response_code(413);
        header('Content-Type: application/json; charset=utf-8');

        echo json_encode([
            'success' => false,
            'message' => $mensaje_archivo
        ]);

        exit;
    }

    $limite = '----PatitasSeguras' . bin2hex(random_bytes(12));

    foreach ($_POST as $nombre => $valor) {
        $contenido_multipart .= '--' . $limite . "\r\n"
            . 'Content-Disposition: form-data; name="' . $nombre . '"' . "\r\n\r\n"
            . $valor . "\r\n";
    }

    foreach ($_FILES as $nombre => $archivo) {
        if ($archivo['error'] !== UPLOAD_ERR_OK) {
            continue;
        }

        $contenido_multipart .= '--' . $limite . "\r\n"
            . 'Content-Disposition: form-data; name="' . $nombre . '"; filename="'
            . str_replace('"', '', basename($archivo['name'])) . '"' . "\r\n"
            . 'Content-Type: ' . ($archivo['type'] ?: 'application/octet-stream') . "\r\n\r\n"
            . file_get_contents($archivo['tmp_name']) . "\r\n";
    }

    $contenido_multipart .= '--' . $limite . "--\r\n";
    $tipo_contenido = 'multipart/form-data; boundary=' . $limite;
}

$headers = [
    'Content-Type: ' . $tipo_contenido
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


$contenido = $es_multipart
    ? $contenido_multipart
    : file_get_contents('php://input');

$opciones = [
    'http' => [
        'method' => $metodo,
        'header' => implode("\r\n", $headers),
        'ignore_errors' => true,
        'timeout' => 60
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