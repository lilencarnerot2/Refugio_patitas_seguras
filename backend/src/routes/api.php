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

        case 'tipos_solicitudes':
            require_once __DIR__ . '/../controllers/tipos_solicitudes.php';
                manejarTiposSolicitudes();
            break;

        case 'estados_solicitudes':
            require_once __DIR__ . '/../controllers/estados_solicitudes.php';
                manejar_estados_solicitudes();
            break;

        case 'solicitudes':
            require_once __DIR__ . '/../controllers/solicitudes.php';
                manejar_solicitudes();
            break;

        case 'tipos_movimientos_mascotas':
            require_once __DIR__ . '/../controllers/tipos_movimientos_mascotas.php';
                manejar_tipos_movimientos_mascotas();
            break;

        case 'movimientos_mascotas':
            require_once __DIR__ . '/../controllers/movimientos_mascotas.php';
                manejar_movimientos_mascotas();
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
