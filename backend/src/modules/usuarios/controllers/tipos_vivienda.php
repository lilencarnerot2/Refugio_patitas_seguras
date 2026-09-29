<?php

require_once __DIR__ . '/../services/tipos_vivienda.php';
require_once __DIR__ . '/../../../utils/response.php';

function manejar_tipos_vivienda()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        return responderJson(
            false,
            'Método no permitido',
            null,
            405
        );
    }

    try {
        $tipos_vivienda = listar_tipos_vivienda();

        return responderJson(
            true,
            null,
            $tipos_vivienda,
            200
        );

    } catch (Throwable $e) {

        error_log(
            'ERROR TIPOS VIVIENDA: ' .
            $e->getMessage()
        );

        return responderJson(
            false,
            'Error interno',
            null,
            500
        );
    }
}