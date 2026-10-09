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

/**
 * Busca el email de un usuario en el Padrón PP2 por su uid_pp2.
 * Devuelve el email (string) o null si no lo encuentra.
 */
function buscarEmailEnPadron(string $uid_pp2): ?string
{
    $url = getenv('PP2_API_URL');
    $token = getenv('PP2_API_KEY');

    if (!$url || !$token) {
        return null;
    }

    $ch = curl_init($url . '/usuarios');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token
    ]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    $respuesta = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200) {
        return null;
    }

    $usuarios = json_decode($respuesta, true);

    if (!is_array($usuarios)) {
        return null;
    }

    foreach ($usuarios as $usuario) {
        if (isset($usuario['id']) && (string) $usuario['id'] === $uid_pp2) {
            return $usuario['email'] ?? null;
        }
    }

    return null;
}
/**
 * Canjea el token de reset por una contraseña nueva en el Padrón.
 * El Padrón responde 200 si la contraseña se actualizó,
 * 400 si el token no sirve o venció, 422 si la contraseña está fuera de largo.
 */
function confirmarResetEnPadron(string $token, string $password): array
{
    $url = getenv('PP2_API_URL');
    $apiKey = getenv('PP2_API_KEY');

    if (!$url || !$apiKey) {
        return [
            'ok' => false,
            'status' => 500,
            'error' => 'Faltan PP2_API_URL o PP2_API_KEY en el .env'
        ];
    }

    $payload = json_encode([
        'token' => $token,
        'password' => $password
    ]);

    $ch = curl_init($url . '/password');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey
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
