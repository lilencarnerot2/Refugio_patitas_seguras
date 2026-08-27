<?php

require_once __DIR__ . '/../controllers/mascotas.php';
require_once __DIR__ . '/../utils/response.php';

function manejarRutas()
{
    $recurso = $_GET['recurso'] ?? '';

    switch ($recurso) {

        case 'mascotas':
            manejarMascotas();
            break;

        case '':
            responderJson(
                true,
                'API Patitas Seguras funcionando.'
            );
            break;

        default:
            responderJson(
                false,
                'Recurso no encontrado.',
                null,
                404
            );
            break;
    }
}