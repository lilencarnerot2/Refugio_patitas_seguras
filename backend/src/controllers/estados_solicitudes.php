<?php

require_once __DIR__ . '/../services/estados_solicitudes.php';
require_once __DIR__ . '/../utils/response.php';

function manejar_estados_solicitudes(): void
{
    $metodo = $_SERVER['REQUEST_METHOD'];

    if ($metodo !== 'GET') {
        responderJson(
            false,
            'Método no permitido.',
            null,
            405
        );
        return;
    }

    try {
        $estados = listar_estados_solicitudes();

        responderJson(
            true,
            null,
            $estados,
            200
        );

    } catch (Throwable $e) {
        responderJson(
            false,
            'Error al obtener los estados de las solicitudes.',
            null,
            500
        );
    }
}