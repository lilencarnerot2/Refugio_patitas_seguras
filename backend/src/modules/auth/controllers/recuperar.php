<?php

require_once __DIR__ . '/../services/integracion_registro.php';
require_once __DIR__ . '/../../../utils/response.php';

function solicitar_reset_controller($datos)
{
    $email = trim($datos['email'] ?? '');

    if ($email === '') {
        return responderJson(
            false,
            'El email es obligatorio',
            null,
            400
        );
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return responderJson(
            false,
            'El email no tiene un formato válido',
            null,
            400
        );
    }

    $email = strtolower($email);

    $resultado = solicitarResetEnPadron($email);

    if (!$resultado['ok']) {
        $statusPadron = (int) ($resultado['status'] ?? 500);

        $status = ($statusPadron >= 400 && $statusPadron < 500)
            ? $statusPadron
            : 500;

        return responderJson(
            false,
            $resultado['error'] ?? 'Error al solicitar el reset',
            null,
            $status
        );
    }

    return responderJson(
        true,
        'Si el email está registrado, te enviamos un mail con instrucciones.',
        null,
        200
    );
}

function manejar_solicitar_reset()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return responderJson(false, 'Método no permitido', null, 405);
    }

    $body = file_get_contents('php://input');
    $datos = json_decode($body, true);

    if (!is_array($datos)) {
        return responderJson(false, 'JSON inválido', null, 400);
    }

    return solicitar_reset_controller($datos);
}
function manejar_solicitar_cambio_password()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return responderJson(false, 'Método no permitido', null, 405);
    }

    if (!requerir_autenticacion()) {
        return;
    }

    $body = file_get_contents('php://input');
    $datos = json_decode($body, true);

    if (!is_array($datos)) {
        return responderJson(false, 'JSON inválido', null, 400);
    }

    $email = trim($datos['email'] ?? '');
    $email_sesion = $_SESSION['usuario_email'] ?? ''; 

    
    if (empty($email_sesion)) {
        require_once __DIR__ . '/../../usuarios/services/usuarios.php';
        $usuario = buscar_usuario_por_id((int)$_SESSION['usuario_id']);
       
    }

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return responderJson(false, 'El email no es válido', null, 400);
    }

    
    $resultado = solicitarResetEnPadron($email);

    if (!$resultado['ok']) {
        $statusPadron = (int) ($resultado['status'] ?? 500);
        $status = ($statusPadron >= 400 && $statusPadron < 500) ? $statusPadron : 500;
        return responderJson(false, $resultado['error'] ?? 'Error al solicitar el cambio', null, $status);
    }

    return responderJson(
        true,
        'Te enviamos un mail para que puedas cambiar tu contraseña.',
        null,
        200
    );
}
 
