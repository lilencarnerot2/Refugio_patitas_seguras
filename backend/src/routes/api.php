<?php

require_once __DIR__ . '/../controllers/mascotas.php';
require_once __DIR__ . '/../utils/response.php';
require_once __DIR__ . '/../controllers/especies.php';
require_once __DIR__ . '/../controllers/razas.php';
require_once __DIR__ . '/../controllers/login.php';  
require_once __DIR__ . '/../controllers/registro.php';


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
            require_once __DIR__ . '/../controllers/fichas_medicas.php';
                manejar_fichas_medicas();
            break;

        case 'controles_medicos':
            require_once __DIR__ . '/../controllers/controles_medicos.php';
                manejar_controles_medicos();
            break;

        case 'tipos_vacunas':
            require_once __DIR__ . '/../controllers/tipos_vacunas.php';
                manejar_tipos_vacunas();
            break;

        case 'vacunas':
            require_once __DIR__ . '/../controllers/vacunas.php';
                manejar_vacunas();
            break;

        case 'castraciones':
            require_once __DIR__ . '/../controllers/castraciones.php';
                manejar_castraciones();
            break;

        case 'tipos_productos':
            require_once __DIR__ . '/../controllers/tipos_productos.php';
                manejar_tipos_productos();
            break;

        case 'desparasitaciones':
            require_once __DIR__ . '/../controllers/desparasitaciones.php';
                manejar_desparasitaciones();
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
