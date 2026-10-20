<?php

require_once __DIR__ . '/../services/enfermedades_condiciones.php';
require_once __DIR__ . '/../../../utils/response.php';

function manejar_enfermedades_condiciones(): void {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        responderJson(
            false,
            'Método no permitido.',
            null,
            405
        );
        return;
    }

    try {
        $datos = listar_enfermedades_condiciones();
        responderJson(
            true,
            'Enfermedades y condiciones obtenidas correctamente.',
            $datos,
            200
        );

    } catch (Throwable $e) {
        error_log(
            'ERROR ENFERMEDADES CONDICIONES: ' .
            $e->getMessage()
        );
        responderJson(
            false,
            'No se pudieron obtener las enfermedades y condiciones.',
            null,
            500
        );
    }
}