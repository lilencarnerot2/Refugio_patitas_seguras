<?php

require_once __DIR__ . '/../services/tipos_solicitudes.php';
require_once __DIR__ . '/../../../utils/response.php';

function manejarTiposSolicitudes(): void
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
        $tipos = listarTiposSolicitudes();

        responderJson(
            true,
            null,
            $tipos,
            200
        );

    } catch (Throwable $e) {
        responderJson(
            false,
            'Error al obtener los tipos de solicitudes.',
            null,
            500
        );
    }
}