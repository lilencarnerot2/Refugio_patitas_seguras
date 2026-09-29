<?php

require_once __DIR__ . '/../utils/response.php';

function requerir_autenticacion(): bool
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (
        !isset($_SESSION['usuario_id']) ||
        (int) $_SESSION['usuario_id'] <= 0
    ) {
        responderJson(
            false,
            'Debés iniciar sesión.',
            null,
            401
        );

        return false;
    }

    return true;
}