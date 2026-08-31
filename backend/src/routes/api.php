<?php

require_once __DIR__ . '/../controllers/mascotas.php';
require_once __DIR__ . '/../utils/response.php';
require_once __DIR__ . '/../controllers/especies.php';
require_once __DIR__ . '/../controllers/razas.php';

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

        case 'especies':
            manejarEspecies();
            break;

        case 'razas':
            manejarRazas();
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