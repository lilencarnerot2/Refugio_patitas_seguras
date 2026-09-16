<?php

require_once __DIR__ . '/../modules/mascotas/controllers/mascotas.php';
require_once __DIR__ . '/../modules/mascotas/controllers/especies.php';
require_once __DIR__ . '/../modules/mascotas/controllers/razas.php';
require_once __DIR__ . '/../modules/auth/controllers/login.php';
require_once __DIR__ . '/../modules/auth/controllers/registro.php';
require_once __DIR__ . '/../utils/response.php';

function manejarRutas()
{
    $recurso = $_GET['recurso'] ?? '';

    switch ($recurso) {

        case 'mascotas':
            manejarMascotas();
            break;

        case 'login':
            manejarLogin();
            break;

        case 'registro':
            manejarRegistro();
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

        case 'fichas_medicas':
            require_once __DIR__ . '/../modules/salud/controllers/fichas_medicas.php';
            manejar_fichas_medicas();
            break;

        case 'controles_medicos':
            require_once __DIR__ . '/../modules/salud/controllers/controles_medicos.php';
            manejar_controles_medicos();
            break;

        case 'tipos_vacunas':
            require_once __DIR__ . '/../modules/salud/controllers/tipos_vacunas.php';
            manejar_tipos_vacunas();
            break;

        case 'vacunas':
            require_once __DIR__ . '/../modules/salud/controllers/vacunas.php';
            manejar_vacunas();
            break;

        case 'castraciones':
            require_once __DIR__ . '/../modules/salud/controllers/castraciones.php';
            manejar_castraciones();
            break;

        case 'tipos_productos':
            require_once __DIR__ . '/../modules/salud/controllers/tipos_productos.php';
            manejar_tipos_productos();
            break;

        case 'desparasitaciones':
            require_once __DIR__ . '/../modules/salud/controllers/desparasitaciones.php';
            manejar_desparasitaciones();
            break;

        case 'tipos_solicitudes':
            require_once __DIR__ . '/../modules/solicitudes/controllers/tipos_solicitudes.php';
            manejarTiposSolicitudes();
            break;

        case 'estados_solicitudes':
            require_once __DIR__ . '/../modules/solicitudes/controllers/estados_solicitudes.php';
            manejar_estados_solicitudes();
            break;

        case 'solicitudes':
            require_once __DIR__ . '/../modules/solicitudes/controllers/solicitudes.php';
            manejar_solicitudes();
            break;

        case 'tipos_movimientos_mascotas':
            require_once __DIR__ . '/../modules/movimientos/controllers/tipos_movimientos_mascotas.php';
            manejar_tipos_movimientos_mascotas();
            break;

        case 'movimientos_mascotas':
            require_once __DIR__ . '/../modules/movimientos/controllers/movimientos_mascotas.php';
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
