<?php
header('Content-Type: application/javascript');

echo 'window.APP_CONFIG = ' . json_encode([
    'backendUrl' => '/public/api.php',
    'inicioUrl' => '/index.php',
    'panelUrl' => '/admin/dashboard.php',
    
   
    'configuracion' => 'configuracion',
    
    'detalleSolicitud' => [
        'comunes' => [
            'solicitud' => 'solicitudes',
            'mascotas' => 'mascotas',
            'estadosSolicitud' => 'estados_solicitudes',
            'tiposSolicitud' => 'tipos_solicitudes',
            'compatibilidad' => 'compatibilidad',
        ],
        'adopcion' => [
            'formulario' => 'formularios_adopcion',
            'compatibilidad' => 'compatibilidad',
        ],
        'transito' => [
            'formulario' => 'formularios_transito',
        ],
        'vistas' => [
            'adopcion' => 'adopcion_detalle.php',
            'transito' => 'transito_detalle.php',
            'seguimientosAdopcion' => 'seguimientos_adopcion.php',
            'seguimientosTransito' => 'seguimientos_transito.php',
        ],
    ],
    'seguimientos' => [
        'tipos' => 'tipos_seguimientos',
    ],
], JSON_UNESCAPED_UNICODE) . ';';