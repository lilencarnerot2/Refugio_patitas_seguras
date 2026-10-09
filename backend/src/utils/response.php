<?php

function responderJson(
    $success,
    $message = null,
    $data = null,
    $status = 200
) {
    http_response_code($status);

    $respuesta = [
        'success' => $success
    ];

    if ($message !== null) {
        $respuesta['message'] = $message;
    }

    if ($data !== null) {
        $respuesta['data'] = $data;
    }

    echo json_encode(
        $respuesta,
        JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
    );
}