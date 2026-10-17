<?php

require_once __DIR__ . '/../services/motivos_adopcion.php';
require_once __DIR__ . '/../../../utils/response.php';

function manejar_motivos_adopcion(): void {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        responderJson(
            false,
            'Método no permitido.',
            null,
            405
        );

        return;
    }
    $motivos = listar_motivos_adopcion();
    responderJson(
        true,
        'Motivos de adopción obtenidos correctamente.',
        $motivos,
        200
    );
}