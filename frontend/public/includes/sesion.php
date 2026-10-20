<?php

$GLOBALS['error_validacion_rol_backend'] = false;
$GLOBALS['sesion_backend_expirada'] = false;

function usuarioSesion(): ?array
{
    if (empty($_COOKIE['usuario_id'])) {
        return null;
    }

    return [
        'id' => $_COOKIE['usuario_id'],
        'nombre' => $_COOKIE['usuario_nombre'] ?? '',
        'apellido' => $_COOKIE['usuario_apellido'] ?? '',
        'email' => $_COOKIE['usuario_email'] ?? '',
        'id_rol' => $_COOKIE['usuario_rol'] ?? ''
    ];
}

function usuarioAutenticado(): bool
{
    if (usuarioSesion() !== null) {
        return true;
    }

    if (empty($_COOKIE[session_name()])) {
        return false;
    }

    return validarRolEnBackend('contactos') === true;
}

function esPersonalAdmin(): bool
{
    if (
        !usuarioAutenticado()
        || (empty($_COOKIE['usuario_acceso_admin']) && empty($_COOKIE['usuario_acceso_superadmin']))
    ) {
        return false;
    }

    return validarRolEnBackend(
        'usuarios',
        isset($_COOKIE['usuario_id']) ? (string) $_COOKIE['usuario_id'] : null
    ) === true;
}

function esSuperadministrador(): bool
{
    if (!usuarioAutenticado() || empty($_COOKIE['usuario_acceso_superadmin'])) {
        return false;
    }

    return validarRolEnBackend('roles') === true;
}

function validarRolEnBackend(string $recurso, ?string $id = null): ?bool
{
    static $resultados = [];
    $clave = $recurso . ':' . ($id ?? '');

    if (array_key_exists($clave, $resultados)) {
        return $resultados[$clave];
    }

    $backendUrl = rtrim((string) getenv('BACKEND_INTERNAL_URL'), '/');
    $nombreCookieSesion = session_name();
    $idSesion = $_COOKIE[$nombreCookieSesion] ?? '';

    if ($backendUrl === '' || !preg_match('/^[A-Za-z0-9,-]+$/', (string) $idSesion)) {
        $GLOBALS['error_validacion_rol_backend'] = $backendUrl === '';

        if ($backendUrl !== '' && !empty($_COOKIE['usuario_id'])) {
            $GLOBALS['sesion_backend_expirada'] = true;
        }

        $resultados[$clave] = false;
        return false;
    }

    $url = $backendUrl . '/?recurso=' . rawurlencode($recurso);

    if ($id !== null && $id !== '') {
        $url .= '&id=' . rawurlencode($id);
    }

    $contexto = stream_context_create([
        'http' => [
            'method' => 'GET',
            'header' => 'Cookie: ' . $nombreCookieSesion . '=' . $idSesion,
            'ignore_errors' => true,
            'timeout' => 5
        ]
    ]);

    $respuesta = @file_get_contents($url, false, $contexto);
    $codigo = 0;

    foreach ($http_response_header ?? [] as $cabecera) {
        if (preg_match('#HTTP/\S+\s+(\d{3})#', $cabecera, $coincidencia)) {
            $codigo = (int) $coincidencia[1];
        }
    }

    if ($codigo === 401 || $codigo === 403) {
        if ($codigo === 401) {
            $GLOBALS['sesion_backend_expirada'] = true;
        }

        $resultados[$clave] = false;
        return false;
    }

    if ($codigo !== 200 || $respuesta === false) {
        $GLOBALS['error_validacion_rol_backend'] = true;
        $resultados[$clave] = null;
        return null;
    }

    $datos = json_decode($respuesta, true);

    if (!is_array($datos) || !array_key_exists('success', $datos)) {
        $GLOBALS['error_validacion_rol_backend'] = true;
        $resultados[$clave] = null;
        return null;
    }

    $resultados[$clave] = $datos['success'] === true;
    return $resultados[$clave];
}

function responderErrorValidacionRol(): void
{
    http_response_code(503);
    header('Content-Type: text/html; charset=utf-8');
    echo '<!DOCTYPE html><html lang="es"><meta charset="utf-8"><title>Servicio no disponible</title>'
        . '<h1>No se pudo verificar tu acceso</h1>'
        . '<p>El panel administrativo no está disponible en este momento. Intentá nuevamente más tarde.</p>'
        . '</html>';
    exit;
}

function limpiarCookiesSesion(): void
{
    $nombres = [
        'usuario_id',
        'usuario_nombre',
        'usuario_apellido',
        'usuario_email',
        'usuario_rol',
        'usuario_acceso_admin',
        'usuario_acceso_superadmin',
        session_name()
    ];
    $rutas = ['/', '/public', '/admin', '/user'];

    foreach ($nombres as $nombre) {
        foreach ($rutas as $ruta) {
            setcookie($nombre, '', [
                'expires' => time() - 3600,
                'path' => $ruta,
                'samesite' => 'Lax'
            ]);
        }
    }
}

function exigirPersonalAdmin(): void
{
    if (!usuarioAutenticado()) {
        header('Location: /public/login.php');
        exit;
    }

    $permiso = validarRolEnBackend(
        'usuarios',
        isset($_COOKIE['usuario_id']) ? (string) $_COOKIE['usuario_id'] : null
    );

    if ($permiso === true) {
        return;
    }

    if (!empty($GLOBALS['sesion_backend_expirada'])) {
        limpiarCookiesSesion();
        header('Location: /public/login.php');
        exit;
    }

    if ($permiso === null || !empty($GLOBALS['error_validacion_rol_backend'])) {
        responderErrorValidacionRol();
    }

    header('Location: /index.php');
    exit;
}

function exigirSuperadministrador(): void
{
    if (!usuarioAutenticado()) {
        header('Location: /public/login.php');
        exit;
    }

    $permiso = validarRolEnBackend('roles');

    if ($permiso === true) {
        return;
    }

    if (!empty($GLOBALS['sesion_backend_expirada'])) {
        limpiarCookiesSesion();
        header('Location: /public/login.php');
        exit;
    }

    if ($permiso === null || !empty($GLOBALS['error_validacion_rol_backend'])) {
        responderErrorValidacionRol();
    }

    if (validarRolEnBackend(
        'usuarios',
        isset($_COOKIE['usuario_id']) ? (string) $_COOKIE['usuario_id'] : null
    ) === true) {
        header('Location: /admin/dashboard.php');
        exit;
    }

    header('Location: /index.php');
    exit;
}
