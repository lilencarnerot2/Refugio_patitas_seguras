<?php

require_once __DIR__ . '/../services/tipos_seguimientos.php';

function manejar_tipos_seguimientos(): void {
    $metodo = $_SERVER['REQUEST_METHOD'];

    try {
        if ($metodo !== 'GET') {
            responderJson(
                false,
                'Método no permitido.',
                null,
                405
            );
            return;
        }

        $tipos = listar_tipos_seguimientos();

        responderJson(
            true,
            'Tipos de seguimiento obtenidos correctamente.',
            $tipos,
            200
        );

    } catch (Throwable $e) {
        responderJson(
            false,
            $e->getMessage(),
            null,
            500
        );
    }
}