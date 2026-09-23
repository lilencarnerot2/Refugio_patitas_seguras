<?php

require_once __DIR__ . '/../services/tipos_contacto.php';
require_once __DIR__ . '/../../../utils/response.php';

function manejar_tipos_contacto()
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

        return responderJson(
            true,
            null,
            listar_tipos_contacto(),
            200
        );

    } catch (Throwable $e) {

        error_log(
            'ERROR TIPOS CONTACTO: ' .
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