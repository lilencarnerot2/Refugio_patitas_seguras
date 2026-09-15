<?php

require_once __DIR__ . '/../services/tipos_vacunas.php';
require_once __DIR__ . '/../utils/response.php';

function manejar_tipos_vacunas(): void
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

        $tipos = listar_tipos_vacunas();

        responderJson(
            true,
            null,
            $tipos,
            200
        );

    } catch (Throwable $e) {

        responderJson(
            false,
            'Error al obtener los tipos de vacunas.',
            null,
            500
        );
    }
}