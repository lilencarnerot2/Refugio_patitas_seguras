<?php

function enviar_notificacion_whatsapp(
    string $telefono,
    string $tipo,
    string $nombre,
    ?string $mascota = null
): array {

    $url_base = getenv('NOTIFICATION_SERVICE_URL');

    if (!$url_base) {
        throw new RuntimeException(
            'El servicio de notificaciones no está configurado.'
        );
    }

    $url = rtrim(
        $url_base,
        '/'
    ) . '/notifications/whatsapp';


    $datos = [
        'telefono' => $telefono,
        'tipo' => $tipo,
        'nombre' => $nombre,
        'mascota' => $mascota ?? ''
    ];


    $opciones = [
        'http' => [
            'method' => 'POST',
            'header' =>
                "Content-Type: application/json\r\n",
            'content' => json_encode($datos),
            'ignore_errors' => true,
            'timeout' => 10
        ]
    ];


    $contexto = stream_context_create(
        $opciones
    );


    $respuesta = @file_get_contents(
        $url,
        false,
        $contexto
    );


    if ($respuesta === false) {
        throw new RuntimeException(
            'No se pudo conectar con el servicio de notificaciones.'
        );
    }


    $respuesta_json = json_decode(
        $respuesta,
        true
    );


    if (!is_array($respuesta_json)) {
        throw new RuntimeException(
            'La respuesta del servicio de notificaciones no es válida.'
        );
    }


    if (
        isset($respuesta_json['success']) &&
        $respuesta_json['success'] === false
    ) {
        throw new RuntimeException(
            $respuesta_json['message']
                ?? 'Error al enviar la notificación.'
        );
    }


    return $respuesta_json;
}