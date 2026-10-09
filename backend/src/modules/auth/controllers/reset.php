<?php

require_once __DIR__ . '/../services/integracion_registro.php';
require_once __DIR__ . '/../../../utils/response.php';

function confirmar_reset_controller($datos)
{
    $token = trim($datos['token'] ?? '');
    $password = $datos['password'] ?? '';

    if ($token === '' || $password === '') {
        return responderJson(
            false,
            'Token y contraseña son obligatorios',
            null,
            400
        );
    }

    $resultado = confirmarResetEnPadron($token, $password);

    if (!$resultado['ok']) {
        $statusPadron = (int) ($resultado['status'] ?? 500);

        $status = ($statusPadron >= 400 && $statusPadron < 500)
            ? $statusPadron
            : 500;

        return responderJson(
            false,
            $resultado['error'] ?? 'Error al confirmar el reset',
            null,
            $status
        );
    }

    return responderJson(
        true,
        $resultado['data']['mensaje'] ?? 'Contraseña actualizada correctamente',
        null,
        200
    );
}

function manejar_confirmar_reset()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return responderJson(false, 'Método no permitido', null, 405);
    }

    $body = file_get_contents('php://input');
    $datos = json_decode($body, true);

    if (!is_array($datos)) {
        return responderJson(false, 'JSON inválido', null, 400);
    }

    return confirmar_reset_controller($datos);
}