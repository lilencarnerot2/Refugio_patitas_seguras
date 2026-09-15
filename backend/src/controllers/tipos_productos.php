<?php

require_once __DIR__ . '/../services/tipos_productos.php';
require_once __DIR__ . '/../utils/response.php';

function manejar_tipos_productos(): void
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

        responderJson(
            true,
            null,
            listar_tipos_productos(),
            200
        );

    } catch (Throwable $e) {

        responderJson(
            false,
            'Error al obtener los tipos de productos.',
            null,
            500
        );
    }
}