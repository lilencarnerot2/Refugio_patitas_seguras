<?php

/**
 * Cliente HTTP para el servicio externo "Padrón PP2".
 * Este service se encarga de la comunicación con el servicio de registro/login
 * que provee la cátedra.
 */

function loginEnPadron($email, $password)
{
    $url = getenv('PP2_API_URL');
    $token = getenv('PP2_API_KEY');

    if (!$url || !$token) {
        return [
            'ok' => false,
            'status' => 500,
            'error' => 'Faltan PP2_API_URL o PP2_API_KEY en el .env'
        ];
    }

    $payload = json_encode([
        'email' => $email,
        'password' => $password
    ]);

    $ch = curl_init($url . '/login');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token
    ]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    $respuesta = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $errorCurl = curl_error($ch);
    curl_close($ch);

    if ($errorCurl) {
        return [
            'ok' => false,
            'status' => 500,
            'error' => 'Error de conexión con el Padrón: ' . $errorCurl
        ];
    }

    $datos = json_decode($respuesta, true);

    if ($httpCode >= 200 && $httpCode < 300) {
        return [
            'ok' => true,
            'status' => $httpCode,
            'data' => $datos
        ];
    }

    return [
        'ok' => false,
        'status' => $httpCode,
        'error' => $datos['detail'] ?? 'Error desconocido del Padrón'
    ];
}

function registrarEnPadron($datos)
{
    $url = getenv('PP2_API_URL');
    $token = getenv('PP2_API_KEY');

    if (!$url || !$token) {
        return [
            'ok' => false,
            'status' => 500,
            'error' => 'Faltan PP2_API_URL o PP2_API_KEY en el .env'
        ];
    }

    $payload = json_encode([
        'nombre' => $datos['nombre'],
        'apellido' => $datos['apellido'],
        'email' => $datos['email'],
        'password' => $datos['password'],
        'proyecto_id' => 4
    ]);

    $ch = curl_init($url . '/registrar');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token
    ]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    $respuesta = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $errorCurl = curl_error($ch);
    curl_close($ch);

    if ($errorCurl) {
        return [
            'ok' => false,
            'status' => 500,
            'error' => 'Error de conexión con el Padrón: ' . $errorCurl
        ];
    }

    $datosRespuesta = json_decode($respuesta, true);

    if ($httpCode >= 200 && $httpCode < 300) {
        return [
            'ok' => true,
            'status' => $httpCode,
            'data' => $datosRespuesta
        ];
    }

    return [
        'ok' => false,
        'status' => $httpCode,
        'error' => $datosRespuesta['detail'] ?? 'Error desconocido del Padrón'
    ];
}