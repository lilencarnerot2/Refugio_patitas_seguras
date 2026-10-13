<?php

require_once __DIR__ . '/../services/voluntariado.php';
require_once __DIR__ . '/../../../utils/response.php';


function manejar_voluntariado(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        responderJson(false, 'Método no permitido.', null, 405);
        return;
    }

    $body = file_get_contents('php://input');
    $datos = json_decode($body, true);

    if (!is_array($datos)) {
        responderJson(false, 'JSON inválido.', null, 400);
        return;
    }

    try {
        $resultado = registrar_voluntariado($datos);

        responderJson(
            true,
            'Tu postulación fue enviada correctamente. Te enviamos un mail con los datos del turno.',
            $resultado,
            201
        );

    } catch (InvalidArgumentException $e) {
        responderJson(false, $e->getMessage(), null, 400);
    } catch (Throwable $e) {
        error_log('ERROR VOLUNTARIADO: ' . $e->getMessage());
        responderJson(false, 'Error al procesar la postulación.', null, 500);
    }
}