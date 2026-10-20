<?php

require_once __DIR__ . '/../repositories/compatibilidad.php';


function calcular_compatibilidad_solicitud(int $id_solicitud): array
{
    if ($id_solicitud <= 0) {
        throw new InvalidArgumentException('El id de la solicitud no es válido.');
    }

    // 1. Obtener la solicitud
    $solicitud = obtener_solicitud_compatibilidad($id_solicitud);

    if (!$solicitud) {
        throw new RuntimeException('Solicitud no encontrada.');
    }

    // 2. Determinar tipo (adopcion o transito)
    $tipo_solicitud = strtolower(trim($solicitud['tipo_solicitud']));
    $tipo_microservicio = null;

    if ($tipo_solicitud === 'adopción' || $tipo_solicitud === 'adopcion') {
        $tipo_microservicio = 'adopcion';
    } elseif ($tipo_solicitud === 'hogar de tránsito' || $tipo_solicitud === 'hogar de transito') {
        $tipo_microservicio = 'transito';
    } else {
        throw new InvalidArgumentException('Tipo de solicitud no soportado para compatibilidad.');
    }

    // 3. Obtener el formulario correspondiente
    if ($tipo_microservicio === 'adopcion') {
        $formulario = obtener_formulario_adopcion_por_solicitud($id_solicitud);
    } else {
        $formulario = obtener_formulario_transito_por_solicitud($id_solicitud);
    }

    if (!$formulario) {
        throw new RuntimeException('La solicitud todavía no tiene formulario completo.');
    }

    // 4. Obtener la mascota
    $mascota = obtener_mascota_compatibilidad((int) $solicitud['id_mascota']);

    if (!$mascota) {
        throw new RuntimeException('Mascota no encontrada o no disponible.');
    }

    // 5. Armar body para el microservicio
    $body = [
        'tipo' => $tipo_microservicio,
        'formulario' => $formulario,
        'mascota' => [
            'id' => (int) $mascota['id'],
            'nombre' => $mascota['nombre'],
            'id_especie' => (int) $mascota['id_especie'],
            'especie' => $mascota['especie'],
            'id_raza' => $mascota['id_raza'] !== null ? (int) $mascota['id_raza'] : null,
            'raza' => $mascota['raza'],
            'tamaño' => $mascota['tamano'],
            'sexo' => $mascota['sexo'],
            'fecha_nacimiento_estimada' => $mascota['fecha_nacimiento_estimada'],
            'historial' => $mascota['historial'],
            'foto_url' => $mascota['foto_url'],
        ],
    ];

    // 6. Llamar al microservicio
    $resultado = llamar_microservicio_compatibilidad($body);

    // 7. Devolver respuesta combinada
    return [
        'solicitud' => [
            'id' => (int) $solicitud['id'],
            'tipo_solicitud' => $solicitud['tipo_solicitud'],
            'estado_solicitud' => $solicitud['estado_solicitud'],
        ],
        'mascota' => [
            'id' => (int) $mascota['id'],
            'nombre' => $mascota['nombre'],
            'especie' => $mascota['especie'],
            'raza' => $mascota['raza'],
            'tamaño' => $mascota['tamano'],
            'sexo' => $mascota['sexo'],
            'fecha_nacimiento_estimada' => $mascota['fecha_nacimiento_estimada'],
            'historial' => $mascota['historial'],
            'foto_url' => $mascota['foto_url'],
        ],
        'compatibilidad' => $resultado,
    ];
}


function llamar_microservicio_compatibilidad(array $body): array
{
    $url = getenv('COMPATIBILITY_SERVICE_URL');

    if (!$url) {
        throw new RuntimeException('Falta COMPATIBILITY_SERVICE_URL en el .env');
    }

    $payload = json_encode($body);

    $ch = curl_init(rtrim($url, '/') . '/compatibility');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    $respuesta = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $errorCurl = curl_error($ch);
    curl_close($ch);

    if ($errorCurl) {
        throw new RuntimeException('Error de conexión con el microservicio: ' . $errorCurl);
    }

    $datos = json_decode($respuesta, true);

    if ($httpCode < 200 || $httpCode >= 300) {
        $mensaje = $datos['message'] ?? 'Error desconocido del microservicio.';
        throw new RuntimeException('Microservicio: ' . $mensaje);
    }

    if (!is_array($datos) || !isset($datos['data'])) {
        throw new RuntimeException('Respuesta inválida del microservicio.');
    }

    return $datos['data'];
}